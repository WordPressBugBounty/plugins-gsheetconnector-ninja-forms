<?php

if (!defined('ABSPATH'))
   exit;

class njfgsc_googlesheet
{

   private $token;
   private $spreadsheet;
   private $worksheet;

   public function __construct() {}



   /**
    * Thin wrapper around wp_remote_request() for Google REST calls.
    *
    * @param string $method HTTP method.
    * @param string $url    Full REST endpoint URL.
    * @param string $token  Bearer access token.
    * @param array  $args   Extra wp_remote_request() args (body/headers).
    * @return array|WP_Error Decoded JSON body, or WP_Error on failure.
    */
   private static function request($method, $url, $token, $args = array())
   {
      $args['method']  = $method;
      $args['headers'] = array_merge(
         array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
         ),
         isset($args['headers']) ? $args['headers'] : array()
      );

      if (isset($args['body']) && is_array($args['body'])) {
         $args['body'] = wp_json_encode($args['body']);
      }

      $response = wp_remote_request($url, $args);

      if (is_wp_error($response)) {
         return $response;
      }

      $code = wp_remote_retrieve_response_code($response);
      $body = json_decode(wp_remote_retrieve_body($response), true);

      if ($code < 200 || $code >= 300) {
         $message = isset($body['error']['message']) ? $body['error']['message'] : 'Unknown Google API error.';
         return new WP_Error('gsc_api_error', $message, array('status' => $code, 'body' => $body));
      }

      return $body;
   }


   private static function creds()
   {
      return is_multisite()
         ? get_site_option('ninjagsc_api_free_creds')
         : get_option('ninjagsc_api_free_creds');
   }


   /**
    * Retrieve the client ID/secret pair to use for OAuth requests.
    *
    * @return array{0: string, 1: string} [client_id, client_secret]
    */
   private static function client_credentials()
   {
      $creds           = self::creds();
      $newClientSecret = get_option('is_new_client_secret_njfgsc');

      $clientId     = ($newClientSecret == 1) ? $creds['client_id_web'] : $creds['client_id_desk'];
      $clientSecret = ($newClientSecret == 1) ? $creds['client_secret_web'] : $creds['client_secret_desk'];

      return array($clientId, $clientSecret);
   }


   /**
    * Fetch a spreadsheet's sheet/tab metadata via the Sheets REST API.
    *
    * @param string $spreadsheet_id Google Spreadsheet ID.
    * @return array|WP_Error List of sheet entries (each with a `properties` array), or WP_Error on failure.
    */
   private function get_spreadsheet_meta($spreadsheet_id)
   {
      $url = 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode($spreadsheet_id) . '?fields=' . rawurlencode('sheets.properties');

      $body = self::request('GET', $url, $this->token);

      if (is_wp_error($body)) {
         return $body;
      }

      return isset($body['sheets']) ? $body['sheets'] : array();
   }


   //constructed on call
   public static function preauth($access_code)
   {

      try {
         $creds = self::creds();
         if (!$creds) return;

         $response = wp_remote_post(
            'https://oauth2.googleapis.com/token',
            [
               'body' => [
                  'code'          => $access_code,
                  'client_id'     => $creds['client_id_web'],
                  'client_secret' => $creds['client_secret_web'],
                  'redirect_uri'  => 'https://oauth.gsheetconnector.com',
                  'grant_type'    => 'authorization_code'
               ]
            ]
         );
         if (is_wp_error($response)) {
            return false;
         }

         $body = json_decode(wp_remote_retrieve_body($response), true);
         if (!is_array($body)) {
            $body = [];
         }

         self::updateToken($body);

         return !empty($body['access_token']);
      } catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log('[Auth Exception]. ' . $e->getMessage());
         throw new LogicException('Auth error: ' . esc_html($e->getMessage()));
      }
   }


   /**
    * Authenticates the user with Google Sheets API.
    * 
    * @throws LogicException If the OAuth2 access token is invalid or missing.
    * @since 1.0.15
    * */

   public function auth()
   {
      $tokenData = json_decode(get_option('njforms_gs_token'), true);
      if (!is_array($tokenData)) {
         $tokenData = array();
      }

      if (empty($tokenData['refresh_token'])) {
         if (class_exists('GSCNINJA_Free_Error_Logs')) {

            GSCNINJA_Free_Error_Logs::log_to_db(
               'Google_Access_Token_Invalid_Existing',
               403,
               'Google access token is invalid or expired (Existing Method)',
               [
                  'error_type'      => 'invalid_token',
                  'authentication_method' => 'Existing',
                  'message' => 'Authentication failed. The stored Google access token is invalid, expired, or refresh token is no longer valid. Please re-authenticate your Google account.',

               ]
            );
         }

         // Nothing to refresh with; stop here instead of reading a missing key below.
         throw new LogicException("Auth, Error fetching OAuth2 access token, message: refresh token not found. Please re-authenticate your Google account.");
      }

      // Reuse the current access token if it hasn't expired yet (60s safety buffer).
      if (!empty($tokenData['access_token']) && !empty($tokenData['expire']) && time() < ($tokenData['expire'] - 60)) {
         $this->token = $tokenData['access_token'];
         return;
      }

      try {
         list($clientId, $clientSecret) = self::client_credentials();

         $response = wp_remote_post(
            'https://oauth2.googleapis.com/token',
            array(
               'body' => array(
                  'refresh_token' => $tokenData['refresh_token'],
                  'client_id'     => $clientId,
                  'client_secret' => $clientSecret,
                  'grant_type'    => 'refresh_token',
               ),
            )
         );

         if (is_wp_error($response)) {
            throw new Exception($response->get_error_message());
         }

         $refreshed = json_decode(wp_remote_retrieve_body($response), true);

         if (empty($refreshed['access_token'])) {
            throw new Exception(isset($refreshed['error_description']) ? $refreshed['error_description'] : 'Unknown error refreshing token.');
         }

         // Google does not resend the refresh token on a refresh grant; keep the existing one.
         $refreshed['refresh_token'] = $tokenData['refresh_token'];

         njfgsc_googlesheet::updateToken($refreshed);

         $this->token = $refreshed['access_token'];
      } catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log($e->getMessage());
         throw new LogicException("Auth, Error fetching OAuth2 access token, message: " . esc_html($e->getMessage()));
      }
   }


   public static function updateToken($tokenData)
   {
      $expires_in = isset($tokenData['expires_in']) ? intval($tokenData['expires_in']) : 0;
      $tokenData['expire'] = time() + $expires_in;

      try {

         if (isset($tokenData['scope'])) {
            $permission = explode(" ", $tokenData['scope']);
            if ((in_array("https://www.googleapis.com/auth/drive.metadata.readonly", $permission)) && (in_array("https://www.googleapis.com/auth/spreadsheets", $permission))) {
               update_option('njforms_gs_verify', 'valid');
            } else {
               update_option('njforms_gs_verify', 'invalid-auth');

               // Log permission error to error logs
               if (class_exists('GSCNINJA_Free_Error_Logs')) {
                  GSCNINJA_Free_Error_Logs::log_to_db(
                     'Google_Auth_Permission_Error',
                     403,
                     'Google Drive and Google Sheets permissions not granted',
                     [
                        'error_type' => 'Missing Permissions',
                        'message' => 'User did not grant Google Drive and/or Google Sheets permissions during OAuth authentication',
                        'granted_scopes' => $tokenData['scope'] ?? '',
                        'required_drive_scope' => 'https://www.googleapis.com/auth/drive.file OR https://www.googleapis.com/auth/drive.metadata.readonly',
                        'required_sheets_scope' => 'https://www.googleapis.com/auth/spreadsheets',
                     ]
                  );
               }
            }
         }
         $tokenJson = json_encode($tokenData);
         update_option('njforms_gs_token', $tokenJson);
      } catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log("Token write fail! - " . $e->getMessage());
      }
   }

   /**
    * Generates a token for the user and refreshes it if expired.
    *
    * @since 1.0.15
    */

   public static function get_auth_url($nfgsc_clientId = '', $nfgsc_clientSecert = '')
   {
      $params = array(
         'client_id'       => $nfgsc_clientId,
         'redirect_uri'    => admin_url('admin.php?page=njform-google-sheet-config'),
         'response_type'   => 'code',
         'access_type'     => 'offline',
         'approval_prompt' => 'force',
         'scope'           => implode(' ', array(
            'https://www.googleapis.com/auth/drive.metadata.readonly',
            'https://www.googleapis.com/auth/spreadsheets',
            'https://www.googleapis.com/auth/userinfo.email',
         )),
      );

      return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
   }

   /* Sets the Google Spreadsheet ID.
     *
     * @param string $id Spreadsheet ID.
     * @since 1.0.15
     */

   public function setSpreadsheetId($id)
   {
      $this->spreadsheet = $id;
   }


   /**
    * Retrieves the Google Spreadsheet ID.
    *
    * @return string Spreadsheet ID.
    * @since 1.0.15
    */

   public function getSpreadsheetId()
   {

      return $this->spreadsheet;
   }


   /**
    * Sets the Google Sheet tab (worksheet) ID.
    *
    * @param string $id Worksheet ID.
    * @since 1.0.15
    */

   public function setWorkTabId($id)
   {
      $this->worksheet = $id;
   }

   /**
    * Retrieves the Google Sheet tab (worksheet) ID.
    *
    * @return string Worksheet ID.
    * @since 1.0.15
    */

   public function get_worktabs($spreadsheet_id)
   {
      $work_tabs_list = array();

      try {
         $sheets = $this->get_spreadsheet_meta($spreadsheet_id);

         if (is_wp_error($sheets)) {
            NJForm_gs_Connector_Utility::gs_debug_log($sheets->get_error_message());
            return null;
         }

         foreach ($sheets as $sheet) {
            if (isset($sheet['properties'])) {
               $work_tabs_list[] = array(
                  'id'    => $sheet['properties']['sheetId'],
                  'title' => $sheet['properties']['title'],
               );
            }
         }
      } catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log($e->getMessage());
         return null;
      }

      return $work_tabs_list;
   }


   public function add_row($data)
   {
      try {
         $spreadsheetId = $this->getSpreadsheetId();
         $worksheetId   = $this->getWorkTabId();

         $sheets = $this->get_spreadsheet_meta($spreadsheetId);

         if (is_wp_error($sheets)) {
            NJForm_gs_Connector_Utility::gs_debug_log($sheets->get_error_message());
            return null;
         }

         if (empty($sheets) || empty($data)) {
            return null;
         }

         foreach ($sheets as $sheet) {
            $properties = isset($sheet['properties']) ? $sheet['properties'] : array();

            if (!isset($properties['sheetId']) || $properties['sheetId'] != $worksheetId) {
               continue;
            }

            $sheet_title = $properties['title'];

            // Fetch header row (row 1) to determine column order.
            $header_url = 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode($spreadsheetId)
               . '/values/' . rawurlencode($sheet_title . '!1:1');

            $header_response = self::request('GET', $header_url, $this->token);

            if (is_wp_error($header_response)) {
               NJForm_gs_Connector_Utility::gs_debug_log($header_response->get_error_message());
               continue;
            }

            $insert_data = array();


            if (!empty($header_response['values'][0])) {
               foreach ($header_response['values'][0] as $name) {
                  $insert_data[] = (isset($data[$name]) && $data[$name] !== '') ? $data[$name] : '';
               }
            }

            // Append the row.
            $append_url = 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode($spreadsheetId)
               . '/values/' . rawurlencode($sheet_title) . ':append?' . http_build_query(array(
                  'valueInputOption' => 'USER_ENTERED',
               ));

            $result = self::request('POST', $append_url, $this->token, array(
               'body' => array('values' => array($insert_data)),
            ));

            if (is_wp_error($result)) {
               NJForm_gs_Connector_Utility::gs_debug_log($result->get_error_message());
            }
         }
      } catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log($e->getMessage());
         return null;
      }
   }

   /**
    * Retrieves the Google Sheet tab (worksheet) ID.
    *
    * @return string Worksheet ID.
    * @since 1.0.15
    */
   public function getWorkTabId()
   {
      return $this->worksheet;
   }

   public function get_spreadsheets()
   {
      $all_sheets = array();

      try {
         $url = 'https://www.googleapis.com/drive/v3/files?' . http_build_query(array(
            'q'      => "mimeType='application/vnd.google-apps.spreadsheet' and trashed=false",
            'fields' => 'files(id,name)',
         ));

         $response = self::request('GET', $url, $this->token);

         if (is_wp_error($response)) {
            NJForm_gs_Connector_Utility::gs_debug_log($response->get_error_message());
            return null;
         }

         if (!empty($response['files'])) {
            foreach ($response['files'] as $spreadsheet) {
               $all_sheets[] = array(
                  'id'    => $spreadsheet['id'],
                  'title' => $spreadsheet['name'],
               );
            }
         }
      } catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log($e->getMessage());
         return null;
      }

      return $all_sheets;
   }



   /** 
    * GFGSC_googlesheet::gsheet_get_google_account
    * Get Google Account
    * @since 3.1 
    * @retun $user
    **/
   public function gsheet_get_google_account()
   {
      try {
         if (empty($this->token)) {
            return false;
         }

         $user = self::request('GET', 'https://www.googleapis.com/oauth2/v2/userinfo', $this->token);

         if (is_wp_error($user)) {
            NJForm_gs_Connector_Utility::gs_debug_log(__METHOD__ . ' Error in fetching user info: ' . $user->get_error_message());
            return false;
         }
      } catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log(__METHOD__ . " Error in fetching user info: \n " . $e->getMessage());
         return false;
      }

      return $user;
   }

   public function gsheet_get_google_account_email()
   {
      $google_account = $this->gsheet_get_google_account();

      if ($google_account && isset($google_account['email'])) {
         return $google_account['email'];
      }

      return "";
   }


   /** 
    * GFGSC_googlesheet::gsheet_get_google_account_email
    * Get Google Account Email
    * @since 3.1 
    * @retun string $email
    **/
   public function gsheet_print_google_account_email()
   {
      try {
         $google_sheet = new njfgsc_googlesheet();
         $google_sheet->auth();
         $email = $google_sheet->gsheet_get_google_account_email();
         update_option("njgs_email_account", $email);
         return $email;
      } catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log($e->getMessage());
         return false;
      }
   }

   public static function revokeToken_auto($access_code)
   {
      try {
         $tokendecode = json_decode($access_code, true);

         if (empty($tokendecode['access_token'])) {
            return false;
         }

         $response = wp_remote_post(
            'https://oauth2.googleapis.com/revoke',
            array(
               'body' => array(
                  'token' => $tokendecode['access_token'],
               ),
            )
         );

         if (is_wp_error($response)) {
            NJForm_gs_Connector_Utility::gs_debug_log($response->get_error_message());
            return false;
         }

         $code = wp_remote_retrieve_response_code($response);

         return ($code >= 200 && $code < 300);
      } catch (Exception $e) {
         NJForm_gs_Connector_Utility::gs_debug_log($e->getMessage());
         return false;
      }
   }
}
