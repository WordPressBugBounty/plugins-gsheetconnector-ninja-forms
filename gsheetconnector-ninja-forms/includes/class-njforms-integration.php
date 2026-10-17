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
class NJforms_Googlesheet_Services
{

    public function __construct()
    {
        // activation n deactivation ajax call
        add_action('wp_ajax_deactivate_nj_integation', array($this, 'deactivate_nj_integation'));

        // display for upgrade notice
        add_action('admin_notices', array($this, 'display_upgrade_notice'));

        add_action('wp_ajax_set_upgrade_notification_interval', array($this, 'set_upgrade_notification_interval'));
        add_action('wp_ajax_close_upgrade_notification_interval', array($this, 'close_upgrade_notification_interval'));

        /** Hide notification  */
        add_action('wp_ajax_dismiss_nf_pro_notice', array($this, 'dismiss_nf_pro_notice'));

        /** Disable Notication Bar */
        add_action('wp_ajax_nf_dismiss_notice', array($this, 'nf_dismiss_notice'));

        /* Snooze notitiacation Bar */
        add_action('wp_ajax_nf_snooze_notice', array($this, 'nf_snooze_notice'));

        /** Clear Error Log  */
        add_action('wp_ajax_nj_clear_error_logs', array($this, 'nj_clear_error_logs'));

        /** Update general setting */
        add_action('wp_ajax_nf_save_uninstall_settings', array($this, 'nf_save_uninstall_settings'));

        /** Clear debug log */
        add_action('wp_ajax_nf_clear_debug_logs', array($this, 'nf_clear_debug_logs'));

        /*  Activate Forms plugin */
        add_action('wp_ajax_nf_activate_plugin', array($this, 'nf_activate_plugin'));

        /*  Deactivate Forms plugin */
        add_action('wp_ajax_nf_deactivate_plugin', array($this, 'nf_deactivate_plugin'));

        /*  Install wp Forms plugin */
        add_action('wp_ajax_nf_install_plugin', array($this, 'nf_install_plugin'));
        
    }

    /**
     * Function - fetch njform list that is connected with google sheet
     * @since 1.0
     */
    public function get_forms_connected_to_sheet()
    {
        global $wpdb;
        //$query = $wpdb->get_results("SELECT ID,post_title,meta_value from " . $wpdb->prefix . "posts as p JOIN " . $wpdb->prefix . "postmeta as pm on p.ID = pm.post_id where pm.meta_key='njforms_gs_settings' AND p.post_type='njforms' ORDER BY p.ID");
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $query = $wpdb->get_results("SELECT DISTINCT(naction.parent_id) AS ID, nform.title AS title  FROM " . $wpdb->prefix . "nf3_actions AS naction JOIN " . $wpdb->prefix . "nf3_forms AS nform ON nform.id = naction.parent_id
WHERE type='google_sheet'");
        return $query;
    }



    /**
     * AJAX function - deactivate activation
     * @since 1.0
     */
    public function deactivate_nj_integation()
    {
        // nonce check
        check_ajax_referer('gs-ajax-nonce', 'security');

        if (get_option('njforms_gs_token') != '') {

            $accesstoken = get_option('njforms_gs_token');
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

    public function display_upgrade_notice()
    {
        $get_notification_display_interval = get_option('njforms_gs_upgrade_notice_interval');
        $close_notification_interval = get_option('njforms_gs_close_upgrade_notice');

        if ($close_notification_interval === "off") {
            return;
        }

        if (! empty($get_notification_display_interval)) {
            $adds_interval_date_object = DateTime::createFromFormat("Y-m-d", $get_notification_display_interval);
            $notice_interval_timestamp = $adds_interval_date_object->getTimestamp();
        }
    }

    public function set_upgrade_notification_interval()
    {
        // check nonce
        check_ajax_referer('njforms_gs_upgrade_ajax_nonce', 'security');
        $time_interval = gmdate('Y-m-d', strtotime('+10 day'));
        update_option('njforms_gs_upgrade_notice_interval', $time_interval);
        wp_send_json_success();
    }

    public function close_upgrade_notification_interval()
    {
        // check nonce
        check_ajax_referer('njforms_gs_upgrade_ajax_nonce', 'security');
        update_option('njforms_gs_close_upgrade_notice', 'off');
        wp_send_json_success();
    }

    public  function dismiss_nf_pro_notice()
    {

        $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

        if (! wp_verify_nonce($nonce, 'gs-ajax-nonce')) {
            wp_send_json_error('Invalid nonce');
        }

        setcookie(
            'gsheet_nf_pro_notice_dismissed',
            '1',
            time() + (7 * 24 * 60 * 60),
            COOKIEPATH,
            COOKIE_DOMAIN
        );

        wp_send_json_success();
    }

    
    /**
     * AJAX function - Permanently dismiss a notification bar 
     * @since 1.0.15
     */
    public function nf_dismiss_notice()
    {
        if (!isset($_POST['security']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['security'])), 'gs-ajax-nonce')) {
            wp_send_json_error('Invalid nonce');
        }

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
        if (!isset($_POST['key'])) {
            wp_send_json_error('Missing key');
        }

        $key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
        $key = sanitize_text_field($_POST['key']);
        update_option('nf_notice_' . $key, 'dismissed');
        wp_send_json_success();
    }


    /**
     * AJAX function - Snooze a notification bar by storing its dismissal timestamp
     * @since 1.0.15
     */

    public function nf_snooze_notice()
    {
        if (!isset($_POST['security']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['security'])), 'gs-ajax-nonce')) {
            wp_send_json_error('Invalid nonce');
        }
        if (!isset($_POST['key'])) {
            wp_send_json_error('Missing key');
        }

        $key = sanitize_text_field(wp_unslash($_POST['key']));
        update_option('nf_notice_' . $key . '_time', time());
        wp_send_json_success();
    }

    public function nj_clear_error_logs()
    {
        if (
            !isset($_POST['nonce']) ||
            !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'gs-ajax-nonce')
        ) {
            wp_send_json_error(array(
                'message' => 'Security check failed.'
            ));
        }

        global $wpdb;
        $table = $wpdb->prefix . 'gscnjf_error_logs';
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching -- table-existence check performed immediately before a truncate; must always be fresh, caching risks acting on a stale existence result.
        $table_exists = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $wpdb->prepare('SHOW TABLES LIKE %s', $table)
        );

        if ($table_exists !== $table) {
            wp_send_json_error(esc_html__('Error log table not found.', 'gsheetconnector-ninja-forms'), 404);
        }
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        // phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $table is built entirely from $wpdb->prefix + a hardcoded literal ('gsformntr_error_logs'), no user input; TRUNCATE TABLE cannot use prepare() placeholders for identifiers.
        $wpdb->query("TRUNCATE TABLE {$table}");
        wp_send_json_success(array('message' => esc_html__('Logs are cleared.', 'gsheetconnector-ninja-forms')));
    }

    /**
     * AJAX function - Save the "Remove Data on Uninstall" General Setting
     * @since 1.0.15
     */

    public function nf_save_uninstall_settings()
    {
        check_ajax_referer('gs-ajax-nonce', 'security');
        $value = isset($_POST['uninstall_setting']) ? intval($_POST['uninstall_setting']) : 0;
        update_option('gsc_njf_uninstall_plugin_settings', $value);
        wp_send_json_success();
    }


    public function nf_clear_debug_logs()
    {
        check_ajax_referer('gs-ajax-nonce', 'security');

        /*  Initialize the WP_Filesystem global */
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
        }


        /*  Define log path */
        $debug_file = WP_CONTENT_DIR . '/debug.log';

        /*  If file exists and is writable, clear it; otherwise create an empty file */
        if ($wp_filesystem->exists($debug_file)) {
            if ($wp_filesystem->is_writable($debug_file)) {
                $result = $wp_filesystem->put_contents($debug_file, '', FS_CHMOD_FILE);
            } else {
                wp_send_json_error('Debug log is not writable', 403);
            }
        } else {
            /*  Create the file if it doesn’t exist */
            $result = $wp_filesystem->put_contents($debug_file, '', FS_CHMOD_FILE);
        }

        if (false === $result) {
            wp_send_json_error('Failed to clear debug log', 500);
        }

        wp_send_json_success();
    }

    
    /**
     * Activates a plugin via AJAX using the provided plugin slug.
     *
     * @access public
     * @since 1.0.0
     */

    public function nf_activate_plugin()
    {

        /*  Verify nonce */
        if (! check_ajax_referer('gs-ajax-nonce', 'security', false)) {
            wp_send_json_error(array(
                'message' => __('Invalid security token', 'gsheetconnector-ninja-forms')
            ));
        }

        /*  Permission check */
        if (! current_user_can('activate_plugins')) {
            wp_send_json_error(array(
                'message' => __('You do not have permission to activate plugin', 'gsheetconnector-ninja-forms')
            ));
        }

        /*  Check plugin slug */
        if (empty($_POST['plugin_slug'])) {
            wp_send_json_error(array(
                'message' => __('Plugin slug is missing', 'gsheetconnector-ninja-forms')
            ));
        }

        $plugin_slug = sanitize_text_field(wp_unslash($_POST['plugin_slug']));

        /*  Load required file */
        if (! function_exists('activate_plugin')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        /* Check if already active */
        if (is_plugin_active($plugin_slug)) {
            wp_send_json_success(array(
                'message' => __('Plugin is already activated', 'gsheetconnector-ninja-forms')
            ));
        }

        /* Activate plugin */
        $result = activate_plugin($plugin_slug);

        if (is_wp_error($result)) {
            wp_send_json_error(array(
                'message' => $result->get_error_message()
            ));
        }

        wp_send_json_success(array(
            'message' => __('Plugin activated successfully', 'gsheetconnector-ninja-forms')
        ));
    }


     public function nf_deactivate_plugin()
    {

        check_ajax_referer('gs-ajax-nonce', 'security');


        if (!current_user_can('activate_plugins')) {
            wp_send_json_error('You do not have permission to deactivate plugins.');
        }

        if (!isset($_POST['plugin_slug'])) {
            wp_send_json_error('Plugin slug is missing.');
        }

        $plugin_slug = sanitize_text_field(wp_unslash($_POST['plugin_slug']));

        if (empty($plugin_slug)) {
            wp_send_json_error('Invalid plugin.');
        }

        /*  Ensure plugin exists before attempting to deactivate */
        if (!file_exists(WP_PLUGIN_DIR . '/' . $plugin_slug)) {
            wp_send_json_error('Plugin not found.');
        }

        deactivate_plugins($plugin_slug);

        if (is_plugin_active($plugin_slug)) {
            wp_send_json_error('Failed to deactivate plugin.');
        }

        wp_send_json_success('Plugin deactivated successfully.');
    }

    public function nf_install_plugin()
    {

        /* Nonce verify */
        if (! check_ajax_referer('gs-ajax-nonce', 'security', false)) {
            wp_send_json_error([
                'message' => __('Invalid security token', 'gsheetconnector-ninja-forms')
            ]);
        }

        /* Permission check */
        if (! current_user_can('install_plugins')) {
            wp_send_json_error([
                'message' => __('You do not have permission to install plugin', 'gsheetconnector-ninja-forms')
            ]);
        }

        if (empty($_POST['plugin_slug']) || empty($_POST['download_url'])) {
            wp_send_json_error([
                'message' => __('Missing required parameters', 'gsheetconnector-ninja-forms')
            ]);
        }

        $plugin_slug  = sanitize_text_field(wp_unslash($_POST['plugin_slug']));
        $download_url = esc_url_raw(wp_unslash($_POST['download_url']));

        if (empty($plugin_slug) || empty($download_url)) {
            wp_send_json_error([
                'message' => __('Invalid plugin data', 'gsheetconnector-ninja-forms')
            ]);
        }

        /*  Required files */
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/update.php';
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());

        $installed_plugins = get_plugins();
        $plugin_path = '';

        /* Find installed plugin */
        foreach ($installed_plugins as $path => $details) {
            if (strpos($path, $plugin_slug . '/') === 0) {
                $plugin_path = $path;
                break;
            }
        }

        /* ==============================
           If Installed → Upgrade
         ============================== */
        if ($plugin_path) {

            $update_plugins = get_site_transient('update_plugins');

            if (isset($update_plugins->response[$plugin_path])) {

                $result = $upgrader->upgrade($plugin_path);

                if (is_wp_error($result)) {
                    wp_send_json_error([
                        'message' => __('Upgrade failed: ', 'gsheetconnector-ninja-forms') . $result->get_error_message()
                    ]);
                }

                wp_send_json_success([
                    'message' => __('Plugin upgraded successfully', 'gsheetconnector-ninja-forms')
                ]);
            } else {

                wp_send_json_success([
                    'message' => __('Plugin already installed and up to date', 'gsheetconnector-ninja-forms')
                ]);
            }
        }

        /*
         ==============================
         Not Installed → Install
         ============================== */
        $result = $upgrader->install($download_url);

        if (is_wp_error($result)) {
            wp_send_json_error([
                'message' => __('Installation failed: ', 'gsheetconnector-ninja-forms') . $result->get_error_message()
            ]);
        }

        wp_send_json_success([
            'message' => __('Plugin installed successfully', 'gsheetconnector-ninja-forms')
        ]);
    }

}

$njforms_service = new NJforms_Googlesheet_Services();
