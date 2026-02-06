<?php
/*
 * service class for njform Google Sheet Connector
 * @since 1.0
 */
if (!defined('ABSPATH')) {
   exit; // Exit if accessed directly
}
/**
 * NJforms_Googlesheet_Services Class
 *
 * @since 1.0
 */
class NJforms_Googlesheet_Services {

   public function __construct() {
      // activation n deactivation ajax call
      add_action('wp_ajax_deactivate_nj_integation', array($this, 'deactivate_nj_integation'));
      
      // display for upgrade notice
      add_action( 'admin_notices', array( $this, 'display_upgrade_notice' ) );
      
      add_action( 'wp_ajax_set_upgrade_notification_interval', array( $this, 'set_upgrade_notification_interval' ) );
      add_action( 'wp_ajax_close_upgrade_notification_interval', array( $this, 'close_upgrade_notification_interval' ) );

   }

   /**
    * Function - fetch njform list that is connected with google sheet
    * @since 1.0
    */
   public function get_forms_connected_to_sheet() {
      global $wpdb;
      //$query = $wpdb->get_results("SELECT ID,post_title,meta_value from " . $wpdb->prefix . "posts as p JOIN " . $wpdb->prefix . "postmeta as pm on p.ID = pm.post_id where pm.meta_key='njforms_gs_settings' AND p.post_type='njforms' ORDER BY p.ID");
      $query = $wpdb->get_results("SELECT DISTINCT(naction.parent_id) AS ID, nform.title AS title  FROM ".$wpdb->prefix."nf3_actions AS naction JOIN ". $wpdb->prefix ."nf3_forms AS nform ON nform.id = naction.parent_id
WHERE type='google_sheet'");
      return $query;
   }

   /**
    * function to save the setting data of google sheet
    *
    * @since 1.0
    */
   public function add_integration() {
    
    $Code = "";
    $header = "";
    if (isset($_GET['code'])) {
        update_option('is_new_client_secret_njfgsc', 1);
        if (is_string($_GET['code'])) {
            $Code = sanitize_text_field($_GET["code"]);
        }
        $header = esc_url_raw(admin_url('admin.php?page=njform-google-sheet-config'));
        //  $service = new NJforms_Gsheet_Connector_Init();
        //  $service->verify_njforms_gs_integation($Code);
        //  header("Location: " . admin_url('admin.php?page=njform-google-sheet-config'));
    }
      ?>

<div class="main-promotion-box"> <a href="#" class="close-link"></a>
  <div class="promotion-inner">
    <h2>A way to connect WordPress <br />
      and <span>Google Sheets Pro</span></h2>
    <p class="ratings">Ratings : <span></span></p>
    <p>The Most Powerful Bridge Between WordPress  and <strong>Google Sheets</strong>, <br />
      Now available for popular <strong>Contact Forms</strong>, <strong>Page Builder Forms</strong>,<br />
      and <strong>E-commerce</strong> Platforms like <strong>WooCommerce</strong> <br />
      and <strong>Easy Digital Downloads</strong> (EDD).</p>
    <div class="button-bar"> <a href="https://www.gsheetconnector.com/" target="_blank">Buy Now</a> <a href="https://demo.gsheetconnector.com/" target="_blank">Check Demo</a> </div>
  </div>
  <div class="gsheet-plugins"></div>
</div> <!-- main-promotion-box #end -->

<script>
document.addEventListener("DOMContentLoaded", function () {
  var closeButton = document.querySelector(".close-link");
  var promotionBox = document.querySelector(".main-promotion-box");

  // Check if the promotion box should be hidden
  if (localStorage.getItem("promotionBoxHidden") === "true") {
    promotionBox.style.display = "none";
  }

  closeButton.addEventListener("click", function (event) {
    event.preventDefault();

    // Hide the promotion box
    promotionBox.style.display = "none";

    // Store hidden state
    localStorage.setItem("promotionBoxHidden", "true");
  });
});
</script>


<div class="card-ninjaforms dropdownoption-ninjaforms">
	<h2><?php echo esc_html__('Ninja Forms - Google Sheet Integration', 'gsheetconnector-ninja-forms'); ?></h2>
	<p class="sub-desc"><?php echo esc_html__('Choose your Google API Setting from the dropdown. You can select Use Existing Client/Secret Key (Auto Google API Configuration) or Use Manual Client/Secret Key (Use Your Google API Configuration - Pro Version) or Use Service Account (Recommended- Pro Version) . After saving, the related integration settings will appear, and you can complete the setup.', "gsheetconnector-ninja-forms"); ?></p>
	
	<div class="row">
     
        <label for="gs_ninjaforms_dro_option"><?php echo esc_html__('Choose Google API Setting ', 'gsheetconnector-ninja-forms'); ?></label>
     
    <div class="drop-down-select-btn">
        <select id="gs_ninjaforms_dro_option" name="gs_ninjaforms_dro_option">
            <option value="ninjaforms_existing" selected><?php echo esc_html__('Use Existing Client/Secret Key (Auto Google API Configuration)', 'gsheetconnector-ninja-forms'); ?>
            </option>
            <option value="ninjaforms_manual" disabled=""><?php echo esc_html__('Use Manual Client/Secret Key (Use Your Google API Configuration) (Upgrade To PRO)', 'gsheetconnector-ninja-forms'); ?></option>
             <option value="ninjaforms_service" disabled=""><?php echo esc_html__('Service Account (Recommended) (Upgrade To PRO)', 'gsheetconnector-ninja-forms'); ?></option>
        </select>
        <div class="int-meth-btn-ninjaforms">
    <a href="<?php echo esc_url('https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro'); ?>" 
       target="_blank" 
       rel="noopener noreferrer">
        <input 
            type="button"
            name="save-method-api-ninjaforms"
            value="<?php echo esc_attr__('Upgrade To PRO', 'gsheetconnector-ninja-forms'); ?>"
            class="upgrade-btn"
        />
    </a>
</div>

    </div>
		</div> <!-- row #end -->
		
</div>

<div class="card-wp">
    <span class="njforms-setting-field log-setting">
        <input type="hidden" name="redirect_auth_ninjaforms" id="redirect_auth_ninjaforms"
            value="<?php echo (isset($header)) ? esc_attr($header) : ''; ?>">
        <!-- Changed by ahmed 17-6-23  -->
        <h2><?php echo esc_html__('Google Sheet Integration - Use Existing Client/Secret Key (Auto Google API Configuration)', 'gsheetconnector-ninja-forms'); ?></h2>
		 <p  class="sub-desc"><?php echo esc_html__('Automatic integration allows you to connect Ninja Forms with Google Sheets using built-in Google API configuration. By authorizing your Google account, the plugin will handle API setup and authentication automatically, enabling seamless form data sync. Learn more in the documentation', 'gsheetconnector-ninja-forms'); ?> <a href="https://www.gsheetconnector.com/docs/ninja-forms-gsheetconnector/integration-with-google-existing-method" target="_blank">
		 <?php echo esc_html__('click here', 'gsheetconnector-ninja-forms'); ?></a>.</p>
         

        <?php if (empty($Code)) { ?>
            <div class="njform-gs-alert-kk" id="google-drive-msg">
                <p class="njform-gs-alert-heading">
                    <?php echo esc_html__('Authenticate with your Google account, follow these steps:', 'gsheetconnector-ninja-forms'); ?>
                </p>
                <ol class="njform-gs-alert-steps">
                    <li><?php echo esc_html__('Click on the "Sign In With Google" button.', 'gsheetconnector-ninja-forms'); ?></li>
                    <li><?php echo esc_html__('Grant permissions for the following:', 'gsheetconnector-ninja-forms'); ?></li> 
					<li><?php echo esc_html__('Google Drive', 'gsheetconnector-ninja-forms'); ?></li>
					<li><?php echo esc_html__('Google Sheets', 'gsheetconnector-ninja-forms'); ?>
					<span><?php echo esc_html__('* Ensure that you enable the checkbox for each of these services.', 'gsheetconnector-ninja-forms'); ?></span></li> 
                    <li><?php echo esc_html__('This will allow the integration to access your Google Drive and Google Sheets.', 'gsheetconnector-ninja-forms'); ?>
                    </li>
                </ol>
            </div>
        <?php } ?>

        <!-- changed end 17-6-23  -->
        <div class="row">
            <label><?php esc_html_e( 'Google Access Code', 'gsheetconnector-ninja-forms' ); ?></label>

            <?php if (!empty(get_option('njforms_gs_token')) && get_option('njforms_gs_token') !== "") { ?>
              <input 
    type="text" 
    name="google-access-code" 
    id="njforms-setting-google-access-code" 
    value=""
    disabled 
    placeholder="<?php echo esc_attr__( 'Currently Active', 'gsheetconnector-ninja-forms' ); ?>" 
/>
                <input type="button" name="nj-deactivate-log" id="nj-deactivate-log"
                    value="<?php echo esc_attr__( 'Deactivate', 'gsheetconnector-ninja-forms' ); ?>"
                    class="button button-primary" />
 

        <span class="loading-sign-deactive">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <?php } else { 
                    $redirct_uri = admin_url('admin.php?page=njform-google-sheet-config');
                    ?>
                    <input type="text" name="google-access-code" id="njforms-setting-google-access-code" value="<?php echo esc_attr($Code); ?>" readonly placeholder="<?php echo esc_html__('Click Sign In With Google Button', 'gsheetconnector-ninja-forms'); ?>" oncopy="return false;" onpaste="return false;" oncut="return false;" />

                    <?php if (empty($Code)) { ?>
                        <a href="<?php echo esc_url( 'https://oauth.gsheetconnector.com/index.php?clien_admin_url=' . urlencode( $redirct_uri ) . '&plugin=woocommercegsheetconnector' ); ?>">
                            <img class="custom-image button_njformgsc"
                                 src="<?php echo esc_url( NINJAFORMS_GOOGLESHEET_URL . '/assets/img/btn_google_signin_dark_pressed_web.gif' ); ?>"
                                 alt="<?php esc_attr_e( 'Connect Now', 'gsheetconnector-ninja-forms' ); ?>">
                        </a>
                    <?php } ?>
 
            <?php } ?>
            <!-- set nonce -->
           <input 
    type="hidden" 
    name="gs-ajax-nonce" 
    id="gs-ajax-nonce"
    value="<?php echo esc_attr( wp_create_nonce( 'gs-ajax-nonce' ) ); ?>" 
/>

            <?php if (is_user_logged_in() && !empty($Code)) { ?>
               <input 
    type="submit" 
    name="save-gs"
    class="njforms-btn njforms-btn-md njforms-btn-orange blinking-button-wc"
    id="save-njform-gs-code"
    value="<?php echo esc_attr( 'Click here to Save Authentication Code' ); ?>"
>

            <?php } ?>
            <span class="loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
           </div>
            <?php 
            //resolved - google sheet permission issues - START
            if (!empty(get_option('njforms_gs_verify')) && (get_option('njforms_gs_verify') == "invalid-auth")) {
                ?>
                <p style="color:red"> 
                    <?php echo esc_html(__('Something went wrong! It looks you have not given the permission of Google Drive and Google Sheets from your google account.Please Deactivate Auth and Re-Authenticate again with the permissions.', 'gsheetconnector-ninja-forms'));
                  ?>
                </p>
                <p style="color:#c80d0d; border: 1px solid; padding: 8px;">
                    <img width="350"
                         src="<?php echo esc_url( NINJAFORMS_GOOGLESHEET_URL . 'assets/img/permission_screen.png' ); ?>"
                         alt="<?php esc_attr_e( 'Permission Screen', 'gsheetconnector-ninja-forms' ); ?>">
                </p>

                <p style="color:#c80d0d; font-size: 14px; border: 1px solid; padding: 8px;">
                    <?php echo esc_html__( 'Also,', 'gsheetconnector-ninja-forms' ); ?>
                    <a href="https://myaccount.google.com/permissions" target="_blank" rel="noopener noreferrer">
                        <?php echo esc_html__( 'Click Here', 'gsheetconnector-ninja-forms' ); ?>
                    </a>
                    <?php echo esc_html__(
                        ' and if it displays "GSheetConnector for WP Contact Forms" under Third-party apps with account access then remove it.',
                        'gsheetconnector-ninja-forms'
                    ); ?>
                </p>

            <?php
            }
            //resolved - google sheet permission issues - END
            else{
                // connected-email-account
                $token = get_option('njforms_gs_token');
                if (!empty($token) && $token !== "") {
                $google_sheet = new njfgsc_googlesheet();
                $email_account = $google_sheet->gsheet_print_google_account_email();

                if ($email_account) {
					         update_option( 'njform_gs_auth_expired_free', 'false' );
                                ?>
                <div class="connected-account row">
                    <label><?php printf(__('Connected Email Account', 'gsheetconnector-ninja-forms'), $email_account); ?></label>
					<?php printf(__('%s', 'gsheetconnector-ninja-forms'), $email_account); ?>
                </div>
               <?php } else { 
					          update_option( 'njform_gs_auth_expired_free', 'true' ); ?>
                <p style="color:red">
                    <?php echo esc_html(__('Something wrong! Your Auth code may be wrong or expired. Please Deactivate and Do Re-Auth Code ', 'gsheetconnector-ninja-forms')); ?>
                </p>
                <?php
                            }
                        }
                    }
                ?> 
	
				<div class="msg success-msg">
				<i class="fa-solid fa-lock"></i>
					<p>
    <?php echo esc_html__(
        'We do not store any of the data from your Google account on our servers, everything is processed & stored on your server. We take your privacy extremely seriously and ensure it is never misused.',
        'gsheetconnector-ninja-forms'
    ); ?>

    <a href="<?php echo esc_url( 'https://gsheetconnector.com/usage-tracking/' ); ?>"
       target="_blank"
       rel="noopener noreferrer">
       <?php echo esc_html__( 'Learn more.', 'gsheetconnector-ninja-forms' ); ?>
    </a>
</p>
			</div>
	
	
	
    <span class="njforms-setting-field">
        <label><?php esc_html_e( 'Debug Log →', 'gsheetconnector-ninja-forms' ); ?></label>

        <button class="njgsc-logs">
            <?php esc_html_e( 'View', 'gsheetconnector-ninja-forms' ); ?>
        </button>
        <label>
            <a class="debug-clear-kk">
                <?php esc_html_e( 'Clear', 'gsheetconnector-ninja-forms' ); ?>
            </a>
        </label>

        <span class="clear-loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <p id="njgs-validation-message"></p>
        <div class="nj-system-Error-logs">
    <button id="copy-logs-btn" onclick="copyLogs()"><?php echo esc_html__( 'Copy Logs', 'gsheetconnector-ninja-forms' ); ?>
</button>

    <div class="njdisplayLogs" id="njdisplayLogs">
        <?php
        $nfexistDebugFile = get_option('nfgs_debug_log_file');
        // Check if debug unique log file exists
        if (!empty($nfexistDebugFile) && file_exists($nfexistDebugFile)) {
            $displaynjfreeLogs = file_get_contents($nfexistDebugFile);
            if (!empty($displaynjfreeLogs)) {
            // Escape the content safely for HTML, preserving line breaks.
            echo wp_kses(nl2br(esc_html($displaynjfreeLogs)), array('br' => array()));
            } else {
                echo esc_html(__('No errors found.', 'gsheetconnector-ninja-forms'));
            }
        } else {
            // If debug log file does not exist
            echo esc_html(__('No log file exists as no errors are generated.', 'gsheetconnector-ninja-forms'));
        }
        ?>
    </div>
</div>

<script>
function copyLogs() {
    // Get the content from the log (text within the <pre> tag)
    const logContent = document.getElementById('logContent');

    if (logContent) {
        // Create a range and select the log content
        const range = document.createRange();
        range.selectNode(logContent);

        // Clear any existing selections and select the new range
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);

        try {
            // Attempt to copy the selected text to clipboard
            document.execCommand('copy');
            alert('Logs copied to clipboard!');
        } catch (err) {
            console.error('Failed to copy logs: ', err);
            alert('Failed to copy logs.');
        }

        // Clear the selection after copying
        selection.removeAllRanges();
    } else {
        alert('No logs available to copy.');
    }
}
</script>
         <span id="deactivate-message"></span>
        </span>
      </p>
</div>
<script>
// JavaScript/jQuery code
document.addEventListener('DOMContentLoaded', function() {
    var googleDriveMsg = document.getElementById('google-drive-msg');
    if (googleDriveMsg) {
        // Check if the 'gfgs_token' option is not empty
        if ('<?php echo get_option('njforms_gs_token'); ?>' !== '') {
            googleDriveMsg.style.display = 'none';
        }
    }
});
</script>
<div class="two-col njgsc-box-help12">
    <div class="col njgsc-box12">
        <header>
            <h3><?php echo esc_html__( 'Next steps…', 'gsheetconnector-ninja-forms' ); ?></h3>
        </header>
        <div class="njgsc-box-content12">
            <ul class="njgsc-list-icon12">
                <li>
                    <a href="https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro" target="_blank" rel="noopener noreferrer">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-star-filled"></span>
                            </button>
                            <strong><?php echo esc_html__( 'Upgrade to PRO', 'gsheetconnector-ninja-forms' ); ?></strong>
                            <p><?php echo esc_html__( 'Multiple Forms to Sheets, Merge Tags and much more...', 'gsheetconnector-ninja-forms' ); ?></p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/docs/ninja-forms-gsheetconnector/requirements" target="_blank" rel="noopener noreferrer">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-download"></span>
                            </button>
                            <strong><?php echo esc_html__( 'Compatibility', 'gsheetconnector-ninja-forms' ); ?></strong>
                            <p><?php echo esc_html__( 'Compatibility with Ninja-Forms Third-Party Plugins', 'gsheetconnector-ninja-forms' ); ?></p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/docs/ninja-forms-gsheetconnector/plugin-settings-pro-version" target="_blank" rel="noopener noreferrer">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-chart-bar"></span>
                            </button>
                            <strong><?php echo esc_html__( 'Multi Languages', 'gsheetconnector-ninja-forms' ); ?></strong>
                            <p><?php echo esc_html__( 'This plugin supports multi-languages as well!', 'gsheetconnector-ninja-forms' ); ?></p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/docs/ninja-forms-gsheetconnector/plugin-settings-free-version" target="_blank" rel="noopener noreferrer">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-download"></span>
                            </button>
                            <strong><?php echo esc_html__( 'Support WordPress Multisites', 'gsheetconnector-ninja-forms' ); ?></strong>
                            <p><?php echo esc_html__( 'With the use of a Multisite, you’ll also have a new level of user-available: the Super Admin.', 'gsheetconnector-ninja-forms' ); ?></p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- 2nd column -->
    <div class="col njgsc-box13">
        <header>
            <h3><?php echo esc_html__( 'Product Support', 'gsheetconnector-ninja-forms' ); ?></h3>
        </header>
        <div class="njgsc-box-content13">
            <ul class="njgsc-list-icon13">
                <li>
                    <a href="https://www.gsheetconnector.com/docs/ninja-forms-gsheetconnector" target="_blank" rel="noopener noreferrer">
                        <span class="dashicons dashicons-book"></span>
                        <div>
                            <strong><?php echo esc_html__( 'Online Documentation', 'gsheetconnector-ninja-forms' ); ?></strong>
                            <p><?php echo esc_html__( 'Understand all the capabilities of Ninja-Forms GsheetConnector', 'gsheetconnector-ninja-forms' ); ?></p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/support" target="_blank" rel="noopener noreferrer">
                        <span class="dashicons dashicons-sos"></span>
                        <div>
                            <strong><?php echo esc_html__( 'Ticket Support', 'gsheetconnector-ninja-forms' ); ?></strong>
                            <p><?php echo esc_html__( 'Direct help from our qualified support team', 'gsheetconnector-ninja-forms' ); ?></p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/affiliate-area" target="_blank" rel="noopener noreferrer">
                        <span class="dashicons dashicons-admin-links"></span>
                        <div>
                            <strong><?php echo esc_html__( 'Affiliate Program', 'gsheetconnector-ninja-forms' ); ?></strong>
                            <p><?php echo esc_html__( 'Earn flat 30% on every sale!', 'gsheetconnector-ninja-forms' ); ?></p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php
   }

   /**
    * AJAX function - deactivate activation
    * @since 1.0
    */
   public function deactivate_nj_integation() {
      // nonce check
      check_ajax_referer('gs-ajax-nonce', 'security');

      if (get_option('njforms_gs_token') != '') {

         $accesstoken = get_option( 'njforms_gs_token' );
         $client = new njfgsc_googlesheet();
         //$client->revokeToken_auto($accesstoken);

         delete_option('njforms_gs_token');
         delete_option('njforms_gs_access_code');
         delete_option('njforms_gs_verify');
         wp_send_json_success();
      } else {
         wp_send_json_error();
      }
   }

   /**
    * Function - Display Upgrade Notice
    * @since 1.0
    */
   
   public function display_upgrade_notice() {
      $get_notification_display_interval = get_option( 'njforms_gs_upgrade_notice_interval' );
      $close_notification_interval = get_option( 'njforms_gs_close_upgrade_notice' );
      
      if( $close_notification_interval === "off" ) {
         return;
      }
      
      if ( ! empty( $get_notification_display_interval ) ) {
         $adds_interval_date_object = DateTime::createFromFormat( "Y-m-d", $get_notification_display_interval );
         $notice_interval_timestamp = $adds_interval_date_object->getTimestamp();
      }
   }
   
   public function set_upgrade_notification_interval() {
      // check nonce
      check_ajax_referer( 'njforms_gs_upgrade_ajax_nonce', 'security' );
      $time_interval = date( 'Y-m-d', strtotime( '+10 day' ) );
      update_option( 'njforms_gs_upgrade_notice_interval', $time_interval );
      wp_send_json_success();
   }
   
   public function close_upgrade_notification_interval() {
      // check nonce
      check_ajax_referer( 'njforms_gs_upgrade_ajax_nonce', 'security' );
      update_option( 'njforms_gs_close_upgrade_notice', 'off' );
      wp_send_json_success();
   }

}


$njforms_service = new NJforms_Googlesheet_Services(); ?>