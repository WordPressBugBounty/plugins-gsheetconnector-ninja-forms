<?php
/*
 * Utilities class for njform google sheet connector
 * @since 1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
   exit;
}

/**
 * Utilities class - singleton class
 * @since 1.0
 */
class NJForm_gs_Connector_Utility {

   /**
    * Add Sub Menu in Ninja Form
    *
    * @return singleton instance of NJForm_gs_Connector_Utility
    */
    public function admin_page() {
    try{
      $current_role = $this->get_current_user_role();
      add_submenu_page('ninja-forms', __('Google Sheet', 'gsheetconnector-ninja-forms'), __('Google Sheet', 'gsheetconnector-ninja-forms'), $current_role, 'njform-google-sheet-config', array($this, 'njforms_google_sheet_config'));
    } catch (Exception $e) {
         $this->gs_debug_log("Something Wrong : - " . $e->getMessage());
      } 
    }

   /**
    * Setting Page 
    *
    * @return singleton instance of NJForm_gs_Connector_Utility
    */
   public function njforms_google_sheet_config() {
       include( NINJAFORMS_GOOGLESHEET_PATH . "includes/pages/njforms-gs-settings.php" );
   }
   
   /**
    * Get the singleton instance of the NJForm_gs_Connector_Utility class
    *
    * @return singleton instance of NJForm_gs_Connector_Utility
    */
   public static function instance() {

      static $instance = NULL;
      if (is_null($instance)) {
         $instance = new NJForm_gs_Connector_Utility();
      }
      return $instance;
   }

   /**
    * Prints message (string or array) in the debug.log file
    *
    * @param mixed $message
    */
   public function logger($message) {
      if (WP_DEBUG === true) {
         if (is_array($message) || is_object($message)) {
            error_log(print_r($message, true));// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log, WordPress.PHP.DevelopmentFunctions.error_log_print_r
         } else {
            error_log($message);// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
         }
      }
   }

   /**
    * Display error or success message in the admin section
    *
    * @param array $data containing type and message
    * @return string with html containing the error message
    * 
    * @since 1.0 initial version
    */
   public function admin_notice($data = array())
   {
      $message = isset($data['message']) ? $data['message'] : '';
      $message_type = isset($data['type']) ? $data['type'] : '';
        
      switch ($message_type) {
         case 'error':
             $admin_notice = '<div id="message" class="error notice is-dismissible">';
             break;
         case 'update':
             $admin_notice = '<div id="message" class="updated notice is-dismissible">';
             break;
         case 'update-nag':
             $admin_notice = '<div id="message" class="update-nag">';
             break;
         case 'upgrade':
             $admin_notice = '<div id="message" class="error notice wpforms-gs-upgrade is-dismissible">';
             break;
         default:
             $message = __('There\'s something wrong with your code...', 'gsheetconnector-ninja-forms');
             $admin_notice = "<div id=\"message\" class=\"error\">";
             break;
      }

      $admin_notice .= '<p>' . esc_html( $message ) . '</p>';
      $admin_notice .= "</div>\n";

      return $admin_notice;
   }

   /**
    * Utility function to get the current user's role
    *
    * @since 1.0
    */
   public function get_current_user_role() {
      global $wp_roles;
      foreach ($wp_roles->role_names as $role => $name) :
         if (current_user_can($role))
            return $role;
      endforeach;
   }

   /**
    * Fetch and save Auto Integration API credentials
    *
    * @since 1.2.22
    */
    public function save_api_credentials()
    {
       // Create a nonce
       $nonce = wp_create_nonce('ninjagsc_api_free_creds');
 
       // Prepare parameters for the API call
       $params = array(
          'action' => 'get_data',
          'nonce' => $nonce,
          'plugin' => 'NINJAGSC',
          'method' => 'get',
       );
 
       // Add nonce and any other security parameters to the API request
       $api_url = add_query_arg($params, NINJAFORMS_GOOGLESHEET_API_URL);
 
       // Make the API call using wp_remote_get
       $response = wp_remote_get($api_url);
 
       // Check for errors
       if (is_wp_error($response)) {
          // Handle error
          self::gs_debug_log(__METHOD__ . ' Error: ' . $response->get_error_message());
       } else {
          // API call was successful, process the data
          $response = wp_remote_retrieve_body($response);
 
          $decoded_response = json_decode($response);
 
         if (isset($decoded_response->api_creds) && (!empty($decoded_response->api_creds))) {
             $api_creds = wp_parse_args($decoded_response->api_creds);
             if (is_multisite()) {
                // If it's a multisite, update the site option (network-wide)
                update_site_option('ninjagsc_api_free_creds', $api_creds);
             } else {
                // If it's not a multisite, update the regular option
                update_option('ninjagsc_api_free_creds', $api_creds);
             }
         }
      }
   }
 
   /**
    * Utility function to get the current user's role
    *
    * @since 1.0
    */
   public static function gs_debug_log( $error ) {
          /** Insert error login in table */
      if (class_exists('GSCNINJA_Free_Error_Logs')) {
         GSCNINJA_Free_Error_Logs::njfree_log_from_debug($error);
      }
   }

   /**
    * Shared list of connector add-ons shown on the Extensions tab.
    *
    * Also used server-side (see NJforms_Gsheet_Connector_Init::get_allowed_addon_download_urls()
    * and get_allowed_addon_plugin_files()) to validate install/activate/deactivate AJAX
    * requests against a known allowlist instead of trusting client-supplied URLs/paths.
    *
    * @since 3.0.0
    */
   public static function get_addon_extensions() {
      return array(
         'contact-form-7/wp-contact-form-7.php' => array(
            'connector' => 'cf7-google-sheets-connector/google-sheet-connector.php',
            'connector-pro' => 'cf7-google-sheets-connector-pro/google-sheet-connector-pro.php',
            'name' => __('CF7 Google Sheet Connector', 'gsheetconnector-ninja-forms'),
            'link' => 'https://www.gsheetconnector.com/cf7-google-sheet-connector-pro',
            'img' => esc_url(NINJAFORMS_GOOGLESHEET_URL) . '/assets/img/pro-cf7-gsc.webp',
            'text' => __('Easily connect Contact Form 7 to Google Sheets and automatically sync form submissions in real-time. Every entry is instantly added to your selected spreadsheet, helping you manage leads, inquiries, and customer data efficiently without manual export.', 'gsheetconnector-ninja-forms'),
            'pro_plugin_active' => 'cf7-google-sheets-connector-pro/google-sheet-connector-pro.php',
            'url' => 'https://wordpress.org/plugins/cf7-google-sheets-connector/',
            'button' => __('Install', 'gsheetconnector-ninja-forms'),
            'badge'  => __('PRO', 'gsheetconnector-ninja-forms'),
            'freeLink' => 'https://wordpress.org/plugins/cf7-google-sheets-connector/',
            'downloadLink' => 'https://downloads.wordpress.org/plugin/cf7-google-sheets-connector.zip',
            'buyLink' => 'https://www.gsheetconnector.com/cf7-google-sheet-connector-pro',
            'mainPlugin' => 'contact-form-7/wp-contact-form-7.php',
            'theme' => 'contact-form-7',
            'docs' => 'https://www.gsheetconnector.com/docs/cf7-gsheetconnector',
            'demo' => 'https://demo.gsheetconnector.com/cf7-google-sheet-connector-pro',
         ),
         'wpforms/wpforms.php' => array(
            'connector' => 'gsheetconnector-wpforms/gsheetconnector-wpforms.php',
            'connector-pro' => 'gsheetconnector-wpforms-pro/gsheetconnector-wpforms-pro.php',
            'name' => __('WPForms Google Sheet Connector', 'gsheetconnector-ninja-forms'),
            'link' => 'https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro',
            'img' => esc_url(NINJAFORMS_GOOGLESHEET_URL) . '/assets/img/pro-wpf-gsc.webp',
            'text' => __('Integrate WPForms with Google Sheets and send form entries directly to your spreadsheet automatically. Track leads, manage customer data, and streamline reporting with seamless Google Sheets integration and real-time sync.', 'gsheetconnector-ninja-forms'),
            'pro_plugin_active' => 'gsheetconnector-wpforms-pro/gsheetconnector-wpforms-pro.php',
            'url' => 'https://wordpress.org/plugins/gsheetconnector-wpforms/',
            'button' => __('Install', 'gsheetconnector-ninja-forms'),
            'badge' => __('PRO', 'gsheetconnector-ninja-forms'),
            'freeLink' => 'https://wordpress.org/plugins/gsheetconnector-wpforms/',
            'downloadLink' => 'https://downloads.wordpress.org/plugin/gsheetconnector-wpforms.zip',
            'buyLink' => 'https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro',
            'mainPlugin' => array('wpforms/wpforms.php', 'wpforms-lite/wpforms.php'),
            'theme' => 'wpforms',
            'docs' => 'https://www.gsheetconnector.com/docs/gsheetconnnector-for-wpforms',
            'demo' => 'https://demo.gsheetconnector.com/wpforms-google-sheet-connector-pro',
         ),
         'gravityforms/gravityforms.php' => array(
            'connector' => 'gsheetconnector-gravity-forms/gsheetconnector-gravityforms.php',
            'connector-pro' => 'gsheetconnector-gravityforms-pro/gsheetconnector-gravityforms-pro.php',
            'name' => __('Gravity Forms Google Sheet Connector', 'gsheetconnector-ninja-forms'),
            'link' => 'https://www.gsheetconnector.com/gravity-forms-google-sheet-connector',
            'img' => esc_url(NINJAFORMS_GOOGLESHEET_URL) . '/assets/img/pro-gravity-gsc.webp',
            'text' => __('Connect Gravity Forms to Google Sheets and automatically push form submissions to your spreadsheet. Simplify data management, improve workflow automation, and keep all entries organized in one secure location.', 'gsheetconnector-ninja-forms'),
            'pro_plugin_active' => 'gsheetconnector-gravityforms-pro/gsheetconnector-gravityforms-pro.php',
            'url' => 'https://wordpress.org/plugins/gsheetconnector-gravity-forms/',
            'button' => __('Install', 'gsheetconnector-ninja-forms'),
            'badge' => __('PRO', 'gsheetconnector-ninja-forms'),
            'freeLink' => 'https://wordpress.org/plugins/gsheetconnector-gravity-forms/',
            'downloadLink' => 'https://downloads.wordpress.org/plugin/gsheetconnector-gravity-forms.zip',
            'buyLink' => 'https://www.gsheetconnector.com/gravity-forms-google-sheet-connector',
            'mainPlugin' => 'gravityforms/gravityforms.php',
            'theme' => 'gravityforms',
            'docs' => 'https://www.gsheetconnector.com/docs/gravity-forms-gsheetconnector',
            'demo' => 'https://demo.gsheetconnector.com/gravityforms-google-sheet-connector-pro',
         ),
         'ninja-forms/ninja-forms.php' => array(
            'connector' => 'gsheetconnector-ninja-forms/gsheetconnector-ninjaforms.php',
            'connector-pro' => 'gsheetconnector-ninja-forms-pro/gsheetconnector-ninjaform-pro.php',
            'name' => __('Ninja Forms Google Sheet Connector', 'gsheetconnector-ninja-forms'),
            'link' => 'https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro',
            'img' => esc_url(NINJAFORMS_GOOGLESHEET_URL) . '/assets/img/pro-ninjaf-gsc.webp',
            'text' => __('Automatically sync Ninja Forms submissions to Google Sheets with one-click integration. Capture leads, monitor responses, and manage data efficiently with instant spreadsheet updates and easy reporting.', 'gsheetconnector-ninja-forms'),
            'pro_plugin_active' => 'gsheetconnector-ninja-forms-pro/gsheetconnector-ninjaforms-pro.php',
            'url' => 'https://wordpress.org/plugins/gsheetconnector-ninjaforms/',
            'button' => __('Install', 'gsheetconnector-ninja-forms'),
            'badge' => __('PRO', 'gsheetconnector-ninja-forms'),
            'freeLink' => 'https://wordpress.org/plugins/gsheetconnector-ninja-forms/',
            'downloadLink' => 'https://downloads.wordpress.org/plugin/gsheetconnector-ninja-forms.zip',
            'buyLink' => 'https://www.gsheetconnector.com/ninja-forms-google-sheet-connector',
            'mainPlugin' => 'ninja-forms/ninja-forms.php',
            'theme' => 'ninja-forms',
            'docs' => 'https://gsheetconnector.com/docs/ninja-forms-gsheetconnector',
            'demo' => 'https://demo.gsheetconnector.com/ninjaforms-google-sheet-connector-pro',
         ),
         'forminator/forminator.php' => array(
            'connector' => 'gsheetconnector-forminator/gsheetconnector-forminator.php',
            'connector-pro' => 'gsheetconnector-forminator-pro/gsheetconnector-forminator-pro.php',
            'name' => __('Forminator Forms Google Sheet Connector', 'gsheetconnector-ninja-forms'),
            'link' => 'https://wordpress.org/plugins/gsheetconnector-forminator/',
            'img' => esc_url(NINJAFORMS_GOOGLESHEET_URL) . '/assets/img/forminator-addon.webp',
            'text' => __('Integrate Forminator Forms with Google Sheets to automatically store form entries in your spreadsheet. Eliminate manual data entry and ensure accurate, real-time syncing for better data tracking and analysis.', 'gsheetconnector-ninja-forms'),
            'pro_plugin_active' => 'gsheetconnector-forminator-pro/gsheetconnector-forminator-pro.php',
            'url' => 'https://wordpress.org/plugins/gsheetconnector-forminator/',
            'button' => __('Install', 'gsheetconnector-ninja-forms'),
            'badge' => __('Free', 'gsheetconnector-ninja-forms'),
            'freeLink' => 'https://wordpress.org/plugins/gsheetconnector-forminator/',
            'downloadLink' => 'https://downloads.wordpress.org/plugin/gsheetconnector-forminator.zip',
            'buyLink' => 'https://www.gsheetconnector.com/forminator-forms-google-sheet-connector-pro',
            'mainPlugin' => 'forminator/forminator.php',
            'theme' => 'forminator',
            'docs' => 'https://www.gsheetconnector.com/docs/forminator-forms-gsheetconnector',
            'demo' => 'https://demo.gsheetconnector.com/forminator-google-sheet-connector',
         ),
         'formidable/formidable.php' => array(
            'connector' => 'gsheetconnector-formidable-forms/gsheetconnector-formidable-forms.php',
            'connector-pro' => 'gsheetconnector-formidable-forms-pro/gsheetconnector-formidable-forms-pro.php',
            'name' => __('Formidable Forms Google Sheet Connector', 'gsheetconnector-ninja-forms'),
            'link' => 'https://wordpress.org/plugins/gsheetconnector-formidable-forms/',
            'img' => esc_url(NINJAFORMS_GOOGLESHEET_URL) . '/assets/img/formidable-addon.webp',
            'text' => __('Automatically sync Formidable Forms entries to Google Sheets. Manage leads, track submissions, and simplify data reporting with real-time integration.', 'gsheetconnector-ninja-forms'),
            'pro_plugin_active' => 'gsheetconnector-forminator-pro/gsheetconnector-forminator-pro.php',
            'url' => 'https://wordpress.org/plugins/gsheetconnector-formidable-forms/',
            'button' => __('Install', 'gsheetconnector-ninja-forms'),
            'badge' => __('Free', 'gsheetconnector-ninja-forms'),
            'freeLink' => 'https://wordpress.org/plugins/gsheetconnector-formidable-forms/',
            'downloadLink' => 'https://downloads.wordpress.org/plugin/gsheetconnector-formidable-forms.zip',
            'buyLink' => 'https://www.gsheetconnector.com/formidable-forms-google-sheet-connector-pro',
            'mainPlugin' => 'formidable/formidable.php',
            'theme' => 'formidable',
            'docs' => 'https://www.gsheetconnector.com/docs/formidable-forms-gsheetconnector',
            'demo' => 'https://demo.gsheetconnector.com/formidable-forms-google-sheet-connector',
         ),
         'fluentform/fluentform.php' => array(
            'connector' => 'gsheetconnector-for-fluentform/gsheetconnector-for-fluentform.php',
            'connector-pro' => 'gsheetconnector-for-fluentform-pro/gsheetconnector-for-fluentform-pro.php',
            'name' => __('Fluent Forms Google Sheet Connector', 'gsheetconnector-ninja-forms'),
            'link' => 'https://www.gsheetconnector.com/fluent-forms-google-sheet-connector-pro',
            'img' => esc_url(NINJAFORMS_GOOGLESHEET_URL) . '/assets/img/pro-fluent-forms-gsc.webp',
            'text' => __('Automatically sync Fluent Forms submissions to Google Sheets in real-time. Every form entry is instantly added to your selected spreadsheet, helping you manage leads, customer inquiries, and business data without manual export. Streamline your workflow, improve reporting accuracy, and keep all submissions organized with seamless Google Sheets integration.', 'gsheetconnector-ninja-forms'),
            'pro_plugin_active' => 'gsheetconnector-for-fluentform-pro/gsheetconnector-for-fluentform-pro.php',
            'url' => 'https://wordpress.org/plugins/gsheetconnector-for-fluentform/',
            'button' => __('Install', 'gsheetconnector-ninja-forms'),
            'badge' => __('Free', 'gsheetconnector-ninja-forms'),
            'freeLink' => 'https://wordpress.org/plugins/gsheetconnector-for-fluentform/',
            'downloadLink' => 'https://downloads.wordpress.org/plugin/gsheetconnector-for-fluentform.zip',
            'buyLink' => 'https://www.gsheetconnector.com/fluent-forms-google-sheet-connector-pro',
            'mainPlugin' => 'fluentform/fluentform.php',
            'theme' => '',
            'docs' => 'https://www.gsheetconnector.com/docs/fluent-forms-gsheetconnector',
            'demo' => 'https://demo.gsheetconnector.com/fluent-gsheet-connector',
         ),
         'pro-elements/pro-elements.php' => array(
            'connector' => 'gsheetconnector-for-elementor-forms/gsheetconnector-for-elementor-forms.php',
            'connector-pro' => 'gsheetconnector-for-elementor-forms-pro/gsheetconnector-for-elementor-forms-pro.php',
            'name' => __('Elementor Forms Google Sheet Connector', 'gsheetconnector-ninja-forms'),
            'link' => 'https://www.gsheetconnector.com/elementor-forms-google-sheet-connector-pro',
            'img' => esc_url(NINJAFORMS_GOOGLESHEET_URL) . '/assets/img/pro-elemntor-gsc.webp',
            'text' => __('Sync Elementor Forms with Google Sheets instantly. Automatically add new form submissions to your spreadsheet, manage leads efficiently, and simplify reporting with real-time data synchronization.', 'gsheetconnector-ninja-forms'),
            'pro_plugin_active' => 'gsheetconnector-for-elementor-forms-pro/gsheetconnector-for-elementor-forms-pro.php',
            'url' => 'https://wordpress.org/plugins/gsheetconnector-for-elementor-forms/',
            'button' => __('Install', 'gsheetconnector-ninja-forms'),
            'badge' => __('PRO', 'gsheetconnector-ninja-forms'),
            'freeLink' => 'https://wordpress.org/plugins/gsheetconnector-for-elementor-forms/',
            'downloadLink' => 'https://downloads.wordpress.org/plugin/gsheetconnector-for-elementor-forms.zip',
            'buyLink' => 'https://www.gsheetconnector.com/elementor-forms-google-sheet-connector-pro',
            'mainPlugin' => array('pro-elements/pro-elements.php', 'elementor-pro/elementor-pro.php'),
            'theme' => 'elements',
            'docs' => 'https://www.gsheetconnector.com/docs/elementor-google-sheet-connector',
            'demo' => 'https://demo.gsheetconnector.com/elementor-forms-gsheetconnector-pro',
         ),
         'avada/avada.php' => array(
            'connector' => 'avada-forms-google-sheet-connector-pro/avada-forms-google-sheet-connector-pro.php',
            'connector-pro' => 'avada-forms-google-sheet-connector-pro/avada-forms-google-sheet-connector-pro.php',
            'name' => __('Avada Forms Google Sheet Connector', 'gsheetconnector-ninja-forms'),
            'link' => 'https://www.gsheetconnector.com/avada-forms-google-sheet-connector-pro',
            'img' => esc_url(NINJAFORMS_GOOGLESHEET_URL) . '/assets/img/pro-avada-gsc.webp',
            'text' => __('Connect Avada Forms to Google Sheets and automatically transfer form entries to your spreadsheet. Streamline lead management, automate data collection, and improve business reporting with seamless integration.', 'gsheetconnector-ninja-forms'),
            'pro_plugin_active' => 'avada-forms-google-sheet-connector-pro/avada-forms-google-sheet-connector-pro.php',
            'url' => '',
            'button' => __('Install', 'gsheetconnector-ninja-forms'),
            'badge' => __('Available PRO Version', 'gsheetconnector-ninja-forms'),
            'freeLink' => '',
            'downloadLink' => '',
            'buyLink' => 'https://www.gsheetconnector.com/avada-forms-google-sheet-connector-pro',
            'mainPlugin' => 'avada',
            'theme' => 'Avada',
            'docs' => 'https://www.gsheetconnector.com/docs/avada-forms-gsheetconnector',
            'demo' => 'https://demo.gsheetconnector.com/avadaforms-google-sheet-connector-pro',
         ),
         'divi/divi.php' => array(
            'connector' => 'divi-forms-db-google-sheet-connector-pro/divi-forms-db-google-sheet-connector-pro.php',
            'connector-pro' => 'divi-forms-db-google-sheet-connector-pro/divi-forms-db-google-sheet-connector-pro.php',
            'name' => __('Divi Forms Google Sheet Connector', 'gsheetconnector-ninja-forms'),
            'link' => 'https://www.gsheetconnector.com/divi-forms-db-google-sheet-connector-pro',
            'img' => esc_url(NINJAFORMS_GOOGLESHEET_URL) . '/assets/img/pro-divi-gsc.webp',
            'text' => __('Integrate Divi Forms with Google Sheets and automatically sync submissions in real-time. Store customer data securely, track inquiries efficiently, and eliminate manual spreadsheet updates.', 'gsheetconnector-ninja-forms'),
            'pro_plugin_active' => 'divi-forms-db-google-sheet-connector-pro/divi-forms-db-google-sheet-connector-pro.php',
            'url' => '',
            'button' => __('Install', 'gsheetconnector-ninja-forms'),
            'badge' => __('Available PRO Version', 'gsheetconnector-ninja-forms'),
            'freeLink' => '',
            'downloadLink' => '',
            'buyLink' => 'https://www.gsheetconnector.com/divi-forms-db-google-sheet-connector-pro',
            'mainPlugin' => 'Divi',
            'theme' => 'Divi',
            'docs' => 'https://www.gsheetconnector.com/docs/divi-forms-google-sheet-connector',
            'demo' => 'https://demo.gsheetconnector.com/divi-forms-gsheetconnector-pro',
         ),
         'gsheetconnector-for-wp-core/gsheetconnector-for-wp-core.php' => array(
            'connector' => 'gsheetconnector-easy-digital-downloads/gsheetconnector-easy-digital-downloads.php',
            'connector-pro' => 'gsheetconnector-easy-digital-downloads-pro/gsheetconnector-easy-digital-downloads-pro.php',
            'name' => __('WP Core Google Sheet Connector', 'gsheetconnector-ninja-forms'),
            'link' => 'https://www.gsheetconnector.com/gsheetconnector-for-wp-core',
            'img' => esc_url(NINJAFORMS_GOOGLESHEET_URL) . '/assets/img/gsc-wp-core.webp',
            'text' => __('Automatically send WordPress core data (like comments or custom submissions) to Google Sheets. Simplify website data tracking, automate record keeping, and centralize important information in one organized spreadsheet.', 'gsheetconnector-ninja-forms'),
            'pro_plugin_active' => 'gsheetconnector-easy-digital-downloads-pro/gsheetconnector-easy-digital-downloads-pro.php',
            'url' => 'https://wordpress.org/plugins/gsheetconnector-easy-digital-downloads/',
            'button' => __('Install', 'gsheetconnector-ninja-forms'),
            'badge' => __('PRO', 'gsheetconnector-ninja-forms'),
            'freeLink' => '',
            'downloadLink' => 'https://downloads.wordpress.org/plugin/gsheetconnector-easy-digital-downloads.zip',
            'buyLink' => 'https://www.gsheetconnector.com/gsheetconnector-for-wp-core',
            'mainPlugin' => 'gsheetconnector-for-wp-core',
            'theme' => 'gsheetconnector-for-wp-core',
            'docs' => 'https://www.gsheetconnector.com/docs/gsheetconnector-for-wp-core',
            'demo' => '',
         ),
         'woocommerce/woocommerce.php' => array(
            'connector' => 'wc-gsheetconnector/wc-gsheetconnector.php',
            'connector-pro' => 'wc-gsheetconnector-pro/wc-gsheetconnector-pro.php',
            'name' => __('WooCommerce Google Sheet Connector', 'gsheetconnector-ninja-forms'),
            'link' => 'https://www.gsheetconnector.com/woocommerce-google-sheet-connector-pro',
            'img' => esc_url(NINJAFORMS_GOOGLESHEET_URL) . '/assets/img/pro-woo-gsc.webp',
            'text' => __('Sync WooCommerce orders with Google Sheets automatically. Store order details, customer information, and transaction data in real-time for easy reporting, accounting, and business analysis.', 'gsheetconnector-ninja-forms'),
            'pro_plugin_active' => 'wc-gsheetconnector-pro/wc-gsheetconnector-pro.php',
            'url' => 'https://wordpress.org/plugins/wc-gsheetconnector/',
            'button' => __('Install', 'gsheetconnector-ninja-forms'),
            'badge' => __('PRO', 'gsheetconnector-ninja-forms'),
            'freeLink' => 'https://wordpress.org/plugins/wc-gsheetconnector/',
            'downloadLink' => 'https://downloads.wordpress.org/plugin/wc-gsheetconnector.zip',
            'buyLink' => 'https://www.gsheetconnector.com/woocommerce-google-sheet-connector-pro',
            'mainPlugin' => 'woocommerce/woocommerce.php',
            'theme' => 'woocommerce',
            'docs' => 'https://www.gsheetconnector.com/docs/woocommerce-gsheetconnector',
            'demo' => 'https://woogsheets.gsheetconnector.com',
         ),
         'easy-digital-downloads/easy-digital-downloads.php' => array(
            'connector' => 'gsheetconnector-easy-digital-downloads/gsheetconnector-easy-digital-downloads.php',
            'connector-pro' => 'gsheetconnector-easy-digital-downloads-pro/gsheetconnector-easy-digital-downloads-pro.php',
            'name' => __('Easy Digital Downloads Google Sheet Connector', 'gsheetconnector-ninja-forms'),
            'link' => 'https://www.gsheetconnector.com/easy-digital-downloads-google-sheet-connector-pro',
            'img' => esc_url(NINJAFORMS_GOOGLESHEET_URL) . '/assets/img/pro-edd-gsc.webp',
            'text' => __('Automatically send Easy Digital Downloads (EDD) order details to Google Sheets. Track digital product sales, monitor transactions, and manage customer data effortlessly with real-time spreadsheet integration.', 'gsheetconnector-ninja-forms'),
            'pro_plugin_active' => 'gsheetconnector-easy-digital-downloads-pro/gsheetconnector-easy-digital-downloads-pro.php',
            'url' => 'https://wordpress.org/plugins/gsheetconnector-easy-digital-downloads/',
            'button' => __('Install', 'gsheetconnector-ninja-forms'),
            'badge' => __('PRO', 'gsheetconnector-ninja-forms'),
            'freeLink' => 'https://wordpress.org/plugins/gsheetconnector-easy-digital-downloads/',
            'downloadLink' => 'https://downloads.wordpress.org/plugin/gsheetconnector-easy-digital-downloads.zip',
            'buyLink' => 'https://www.gsheetconnector.com/edd-google-sheet-connector-pro',
            'mainPlugin' => 'easy-digital-downloads/easy-digital-downloads.php',
            'theme' => 'easy-digital-downloads',
            'docs' => 'https://www.gsheetconnector.com/docs/edd-gsheetconnector',
            'demo' => 'https://demo.gsheetconnector.com/easydigitaldownloads-google-sheet-connector-pro',
         ),
      );
   }

}
