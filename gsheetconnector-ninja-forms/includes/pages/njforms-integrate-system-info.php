<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
   exit();
}

// 🔒 Prevent Subscribers from seeing sensitive info
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( __( 'You do not have permission to access this page.', 'gsheetconnector-ninja-forms' ) );
}

$njforms_gs_tools_service = new NJforms_Gsheet_Connector_Init();
?>
<div class="system-statuswc">
   <div class="info-container">
    <h2 class="systemifo"><?php echo esc_html( __('System Info', 'gsheetconnector-ninja-forms' ) ); ?></h2>
      <button onclick="copySystemInfo()" class="copy"><?php echo esc_html( __('Copy System Info to Clipboard', 'gsheetconnector-ninja-forms' ) ); ?></button>
      <?php echo $njforms_gs_tools_service->get_njforms_system_info(); ?>
   </div>
</div>
<div class="system-Error">
    <div class="error-container">
        <h2 class="systemerror"><?php echo esc_html( __('Error Log', 'gsheetconnector-ninja-forms' ) ); ?></h2>
        <p><?php
   echo wp_kses_post( __(
    '<p>If you have <a href="https://www.gsheetconnector.com/how-to-enable-debugging-in-wordpress" target="_blank" rel="noopener noreferrer">WP_DEBUG_LOG</a> enabled, errors are stored in a log file. Here you can find the last 100 lines in reversed order so that you or the GSheetConnector support team can view it easily. The file cannot be edited here.</p>',
    'gsheetconnector-ninja-forms'
) );
?> </p>
        <button onclick="copyErrorLog()" class="copy"><?php echo esc_html( __('Copy Error Log to Clipboard', 'gsheetconnector-ninja-forms' ) ); ?></button>
         <button class="clear-content-logs-nj"><?php echo esc_html( __('Clear', 'gsheetconnector-ninja-forms' ) ); ?></button>
         <span class="clear-loading-sign-logs-nj">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <div class="clear-content-logs-msg-nj"></div>
        <input type="hidden" name="gs-ajax-nonce" id="gs-ajax-nonce"
       value="<?php echo esc_attr( wp_create_nonce( 'gs-ajax-nonce' ) ); ?>" />
       <div class="copy-message" style="display: none;"><?php echo esc_html( __('Copied', 'gsheetconnector-ninja-forms' ) ); ?></div> <!-- Add a hidden div for the copy message -->
        <?php echo $njforms_gs_tools_service->display_error_log(); ?>
    </div>
</div>