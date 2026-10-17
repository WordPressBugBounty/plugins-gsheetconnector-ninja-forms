<?php

/**
 * Plugin Name:  GSheetConnector For Ninja Forms
 * Plugin URI:  https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro
 * Description:  Send your Ninja Forms data to your Google Sheets spreadsheet.
 * Author:       GSheetConnector
 * Author URI:   https://www.gsheetconnector.com/
 * Version:      3.0.0
 * Text Domain:  gsheetconnector-ninja-forms
 * License:      GPLv2
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path:  /languages
 * Requires Plugins: ninja-forms
 */
// Exit if accessed directly.

add_filter('doing_it_wrong_trigger_error', '__return_false');
if (!defined('ABSPATH')) {
    exit;
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

/*freemius*/
if (function_exists('is_plugin_active') && is_plugin_active('gsheetconnector-ninja-forms/gsheetconnector-ninjaforms.php')) {
    if (!function_exists('gs_ninjafree')) {
        // Create a helper function for easy SDK access.
        function gs_ninjafree() // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
        {
            global $gs_ninjafree;

            if (!isset($gs_ninjafree)) {
                // Activate multisite network integration.
                if (!defined('WP_FS__PRODUCT_9034_MULTISITE')) {
                    define('WP_FS__PRODUCT_9034_MULTISITE', true);
                }

                // Include Freemius SDK.
                require_once dirname(__FILE__) . '/lib/vendor/freemius/start.php';

                $gs_ninjafree = fs_dynamic_init(array(
                    'id'                  => '9034',
                    'slug'                => 'gsheetconnector-ninja-forms',
                    'type'                => 'plugin',
                    'public_key'          => 'pk_90fceba6082f2f976d4c7e2128455',
                    'is_premium'          => false,
                    'has_addons'          => false,
                    'has_paid_plans'      => false,
                    'is_org_compliant'    => true,
                    'menu'                => array(
                        'slug'           => 'njform-google-sheet-config',
                        'first-path'     => 'admin.php?page=njform-google-sheet-config',
                        'account'        => false,
                    ),
                ));
            }

            return $gs_ninjafree;
        }

        // Init Freemius.
        gs_ninjafree();
        // Signal that SDK was initiated.
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
        do_action('gs_ninjafree_loaded'); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound 
    }

    // As Per our wc-gsheetconnector commented.
    /*freemius */
    /* Customizing the Opt Message Freemius  */
    function gs_ninjafree_custom_connect_message_on_update( // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
        $message,
        $user_first_name,
        $plugin_title,
        $user_login,
        $site_link,
        $freemius_link
    ) {
        // translators: %1$s: User first name
        $greeting = sprintf(
            ('Hey %1$s'),
            esc_html($user_first_name)
        );

        // translators: %1$s: Plugin title, %2$s: Site link (Freemius), both HTML formatted
        $message_body = sprintf( // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
            __('Please help us improve %1$s! If you opt-in, some data about your usage of %1$s will be sent to %2$s. If you skip this, that\'s okay! %1$s will still work just fine.', 'gsheetconnector-ninja-forms'), // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
            '<b>' . esc_html($plugin_title) . '</b>',
            esc_url($freemius_link)
        );

        return $greeting . ',<br>' . $message_body;
    }
    gs_ninjafree()->add_filter('connect_message_on_update', 'gs_ninjafree_custom_connect_message_on_update', 10, 6);
}
/* End Customizing the Opt Message Freemius  */


if (NJforms_Gsheet_Connector_Init::ninja_gs_is_pugin_active('GSheet_Connector_NJForms_Pro_Init')) {
    return;
}
if ((function_exists('is_plugin_active') && is_plugin_active('gsheetconnector-ninja-forms-pro/gsheetconnector-ninja-forms-pro.php'))) {
    return;
}

define('NINJAFORMS_GOOGLESHEET_VERSION', '3.0.0');
define('NINJAFORMS_GOOGLESHEET_DB_VERSION', '3.0.0');
define('NINJAFORMS_GOOGLESHEET_ROOT', dirname(__FILE__));
define('NINJAFORMS_GOOGLESHEET_URL', plugins_url('/', __FILE__));
define('NINJAFORMS_GOOGLESHEET_BASE_FILE', basename(dirname(__FILE__)) . '/gsheetconnector-ninjaforms.php');
define('NINJAFORMS_GOOGLESHEET_BASE_NAME', plugin_basename(__FILE__));
define('NINJAFORMS_GOOGLESHEET_PATH', plugin_dir_path(__FILE__)); //use for include files to other files
define('NINJAFORMS_GOOGLESHEET_PRODUCT_NAME', 'Njforms Google Sheet Connector');
define('NINJAFORMS_GOOGLESHEET_REDIRECT_URI', admin_url('admin.php?page=njform-google-sheet-config&tab=integration'));
define('NINJAFORMS_GOOGLESHEET_API_URL', 'https://oauth.gsheetconnector.com/api-cred.php');
define('NINJAFORMS_GOOGLESHEET_CURRENT_THEME', get_stylesheet_directory());


/*
 * include utility classes
 */
if (!class_exists('NJForm_gs_Connector_Utility')) {
    include(NINJAFORMS_GOOGLESHEET_ROOT . '/includes/class-njform-utility.php');
    $GoogleSheetConnector = new NJForm_gs_Connector_Utility();
}


/*
 * Add Sub Menu 
 */
if (isset($GoogleSheetConnector)) {
    add_action('admin_menu', array(&$GoogleSheetConnector, 'admin_page'), 20);
}

/*
 * Setting Page
 */
add_action('ninja_forms_loaded', 'ninjaform_Googlesheet_integration');

function ninjaform_Googlesheet_integration() // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-njforms-integration.php';
}

//Include Library Files
include_once(NINJAFORMS_GOOGLESHEET_ROOT . '/lib/google-sheets.php');

class NJforms_Gsheet_Connector_Init
{

    public function __construct()
    {

        /* run on activation of plugin */
        register_activation_hook(__FILE__, array($this, 'njforms_gs_connector_activate'));

        /* run on deactivation of plugin */
        register_deactivation_hook(__FILE__, array($this, 'njforms_gs_connector_deactivate'));

        /* run on uninstall */
        register_uninstall_hook(__FILE__, array('NJforms_Gsheet_Connector_Init', 'njforms_gs_connector_uninstall'));

        /* validate is NinjaForms plugin exist */
        add_action('admin_init', array($this, 'validate_parent_plugin_exists'));

        /* Display widget to dashboard */
        add_action('wp_dashboard_setup', array($this, 'add_njform_gs_connector_summary_widget'));

        /* clear debug log data */
        add_action('wp_ajax_gs_clear_logs', array($this, 'gs_clear_logs'));

        /* load the js and css files */
        add_action('init', array($this, 'load_css_and_js_files'));
        /* run upgradation */
        add_action('admin_init', array($this, 'run_on_upgrade'));

        /* load the classes */
        add_action('init', array($this, 'load_all_classes'));

        /*  Load text domain */
        add_action('init', array($this, 'gsheetconnector_load_textdomain'));

        /* verify the spreadsheet connection */
        add_action('wp_ajax_verify_njforms_gs_integation', array($this, 'verify_njforms_gs_integation'));

        /* For register action in ninja form */
        add_filter('ninja_forms_register_actions', array($this, 'register_actions'));

        /* for calling file. */
        add_action('ninja_forms_builder_templates', array($this, 'builder_templates'));

        /* clear debug logs method using ajax for system status tab*/
        add_action('wp_ajax_nj_clear_debug_logs', array($this, 'nj_clear_debug_logs'));

        /** User for row meta */
        add_filter('plugin_row_meta', [$this, 'plugin_row_meta'], 10, 2);

        // Runs when the form is published in the builder. Priority 99 so it runs after the actions are saved.
        add_action('ninja_forms_save_form', [$this,'gscnjf_delete_saved_sync_ui'], 99);
    }

    /**
     * Plugin row meta.
     *
     * Adds row meta links to the plugin list table
     *
     * Fired by `plugin_row_meta` filter.
     *
     * @since 1.1.4
     * @access public
     *
     * @param array  $plugin_meta An array of the plugin's metadata, including
     *                            the version, author, author URI, and plugin URI.
     * @param string $plugin_file Path to the plugin file, relative to the plugins
     *                            directory.
     *
     * @return array An array of plugin row meta links.
     */
    public function plugin_row_meta($plugin_meta, $plugin_file)
    {
        if (NINJAFORMS_GOOGLESHEET_BASE_NAME === $plugin_file) {
            $row_meta = [
                'docs' => '<a href="https://support.gsheetconnector.com/kb-category/ninja-forms-gsheetconnector" aria-label="' . esc_attr(esc_html__('View Documentation', 'gsheetconnector-ninja-forms')) . '" target="_blank">' . esc_html__('Docs', 'gsheetconnector-ninja-forms') . '</a>',
                'ideo' => '<a href="https://www.gsheetconnector.com/support" aria-label="' . esc_attr(esc_html__('Get Support', 'gsheetconnector-ninja-forms')) . '" target="_blank">' . esc_html__('Support', 'gsheetconnector-ninja-forms') . '</a>',
            ];

            $plugin_meta = array_merge($plugin_meta, $row_meta);
        }

        return $plugin_meta;
    }


    public function gsheetconnector_load_textdomain()
    {
        load_plugin_textdomain('gsheetconnector-ninja-forms', false, dirname(plugin_basename(__FILE__)) . '/languages'); // phpcs:ignore luginCheck.CodeAnalysis.DiscouragedFunctions.load_plugin_textdomainFound
    }


    //called
    public function builder_templates()
    {
        include NINJAFORMS_GOOGLESHEET_PATH . 'includes/Templates/custom-field-map-row.html';
    }

    /**
     * AJAX function - verifies the token
     *
     * @since 1.0
     */
    public function verify_njforms_gs_integation($Code = "")
    {
        try {
            // 🚨 Block subscribers or any user without admin capability
            if (! current_user_can('manage_options')) {
                wp_send_json_error('Unauthorized', 403);
            }

            // nonce checksave_gs_settings
            check_ajax_referer('gs-ajax-nonce', 'security');
            /* sanitize incoming data */
            $Code = isset($_POST['code']) ? sanitize_text_field(wp_unslash($_POST['code'])) : '';

            if (!empty($Code)) {
                update_option('njforms_gs_access_code', $Code);
            } else {
                return;
            }
            if (get_option('njforms_gs_access_code') != '') {
                include_once(NINJAFORMS_GOOGLESHEET_ROOT . '/lib/google-sheets.php');
                njfgsc_googlesheet::preauth(get_option('njforms_gs_access_code'));

                wp_send_json_success();
            } else {
                update_option('njforms_gs_verify', 'invalid');
                wp_send_json_error();
            }
        } catch (Exception $e) {
            NJForm_gs_Connector_Utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
            wp_send_json_error();
        }
    }




    /**
     * AJAX function - Process for Google Sheet
     *
     * @since 1.0
     */
    public function process_googlesheets($fields, $entry, $form_data, $entry_id, $fixed_wpgs_feed_id = false)
    {
        try {
            if (
                empty($form_data['settings']['gsheetconnector-ninja-forms']) ||
                !isset($form_data['settings']['gsheetconnector-ninja-forms']) ||
                !wp_validate_boolean($form_data['settings']['gsheetconnector-ninja-forms'])
            ) {
                return false;
            }

            $is_processed = false;
            $form_id = $form_data['id'];

            $gsheetconnector_njforms = $form_data['settings']['gsheetconnector-ninja-forms'];
            if ($gsheetconnector_njforms != 1) {
                return false;
            }

            foreach ($form_data['settings']['wpgs_spreadsheets'] as $wpgs_feed_id => $googlesheet) {

                if ($fixed_wpgs_feed_id !== false && $fixed_wpgs_feed_id != $wpgs_feed_id) {
                    continue;
                }

                if (!$this->is_conditionals_passed($googlesheet, $fields, $entry, $form_data, $entry_id)) {
                    continue;
                }

                $this->wfgs_process_entry($fields, $entry, $entry_id, $wpgs_feed_id, $googlesheet, $form_id, $form_data, get_current_user_id());
                $is_processed = true;
            }

            return $is_processed;
        } catch (Exception $e) {
            NJForm_gs_Connector_Utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
        }
    }

    /**
     * Do things on plugin activation
     * @since 1.0
     */
    public function njforms_gs_connector_activate($network_wide)
    {
        try {
            global $wpdb;
            $this->run_on_activation();
            if (function_exists('is_multisite') && is_multisite()) {
                // check if it is a network activation - if so, run the activation function for each blog id
                if ($network_wide) {
                    // Get all blog ids
                    $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs"); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
                    foreach ($blogids as $blog_id) {
                        switch_to_blog($blog_id);
                        $this->run_for_site();
                        restore_current_blog();
                    }
                    return;
                }
            }
            // for non-network sites only
            $this->run_for_site();
        } catch (Exception $e) {
            NJForm_gs_Connector_Utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
        }
    }


    /**
     * AJAX function - clear log file
     * @since 1.0
     */
    public function gs_clear_logs()
    {

        // nonce check
        check_ajax_referer('gs-ajax-nonce', 'security');

        // Initialize WP_Filesystem
        if (! function_exists('WP_Filesystem')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
        WP_Filesystem();

        $existDebugFile = get_option('nfgs_debug_log_file');
        $clear_file_msg = '';

        // check if debug unique log file exists
        if (! empty($existDebugFile) && $wp_filesystem->exists($existDebugFile)) {
            $wp_filesystem->put_contents($existDebugFile, '', FS_CHMOD_FILE);
            $clear_file_msg = 'Logs are cleared.';
        } else {
            $clear_file_msg = 'No log file exists to clear logs.';
        }

        wp_send_json_success($clear_file_msg);
    }


    /**
     * deactivate the plugin
     * @since 1.0
     */
    public function njforms_gs_connector_deactivate() {}

    /**
     *  Runs on plugin uninstall.
     *  a static class method or function can be used in an uninstall hook
     *
     *  @since 1.0
     */
    public static function njforms_gs_connector_uninstall()
    {
        if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN'))
            exit();

        delete_site_option('njforms_GS_free_info');
    }

    /**
     * Validate parent Plugin Ninja Form exist and activated
     * @access public
     * @since 1.0
     */
    public function validate_parent_plugin_exists()
    {
        $plugin = plugin_basename(__FILE__);

        if (!is_plugin_active('ninja-forms/ninja-forms.php')) {
            add_action('admin_notices', array($this, 'ninjaform_missing_notice'));
            add_action('network_admin_notices', array($this, 'ninjaform_missing_notice'));
            // Deactivate your plugin
            deactivate_plugins($plugin);

            // Clear some variables from the URL if necessary
            if (isset($_GET['activate'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                // Do not sanitize it because we are destroying the variables from the URL
                unset($_GET['activate']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                unset($GLOBALS['gs_ninjafree']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended // Unset global variable after deactivating plugins
            }
        }
    }

    /**
     * Function to load all required classes
     * @since 2.8
     */
    public function load_all_classes()
    {
        if (!class_exists('njform_gs_Connector_Adds')) {
            include(NINJAFORMS_GOOGLESHEET_PATH . 'includes/class-njform-adds.php');
        }

        if (!class_exists('GSCNINJA_Free_Error_Logs')) {
            include(NINJAFORMS_GOOGLESHEET_ROOT . '/includes/class-njform-error-logs.php');
        }
    }

    /**
     * If Ninja Form plugin is not installed or activated then throw the error
     *
     * @access public
     * @return mixed error_message, an array containing the error message
     *
     * @since 1.0 initial version
     */
    public function ninjaform_missing_notice()
    {
        $plugin_error = NJForm_gs_Connector_Utility::instance()->admin_notice(array(
            'type' => 'error',
            'message' => esc_html__('Ninja Form Add-on requires Ninja-Forms plugin to be installed and activated.', 'gsheetconnector-ninja-forms')
        ));

        echo wp_kses_post($plugin_error);
    }

    public function load_css_and_js_files()
    {
        add_action('admin_print_styles', array($this, 'add_css_files'));
        add_action('admin_print_scripts', array($this, 'add_js_files'));
    }

    /**
     * enqueue CSS files
     * @since 1.0
     */
    public function add_css_files()
    {
        if (is_admin() && (isset($_GET['page']) && ($_GET['page'] == 'njform-google-sheet-config'))) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
            /** New CSS file start */
            wp_enqueue_style('njform-gs-header-css', NINJAFORMS_GOOGLESHEET_URL . 'assets/css/header.css', NINJAFORMS_GOOGLESHEET_VERSION, true);
            wp_enqueue_style('njform-gs-footer-css', NINJAFORMS_GOOGLESHEET_URL . 'assets/css/footer.css', NINJAFORMS_GOOGLESHEET_VERSION, true);
            wp_enqueue_style('njform-gs-extra-style-css', NINJAFORMS_GOOGLESHEET_URL . 'assets/css/extra-style.css', NINJAFORMS_GOOGLESHEET_VERSION, true);
            wp_enqueue_style('njform-gs-connector-font-css', NINJAFORMS_GOOGLESHEET_URL . 'assets/css/fontawesome.css', NINJAFORMS_GOOGLESHEET_VERSION, true);
            wp_enqueue_style('njform-gs-connector-font-min-css', NINJAFORMS_GOOGLESHEET_URL . 'assets/css/font-awesome.min.css', NINJAFORMS_GOOGLESHEET_VERSION, true);
            wp_enqueue_style('njform-gs-nf-connector-css', NINJAFORMS_GOOGLESHEET_URL . 'assets/css/gs-nf-connector.css', NINJAFORMS_GOOGLESHEET_VERSION, true);
            wp_enqueue_style('njform-gs-pro-feature-css', NINJAFORMS_GOOGLESHEET_URL . 'assets/css/pro-feature.css', NINJAFORMS_GOOGLESHEET_VERSION, true);
            wp_enqueue_style('njform-gs-global-css', NINJAFORMS_GOOGLESHEET_URL . 'assets/css/global.css', NINJAFORMS_GOOGLESHEET_VERSION, true);
            wp_enqueue_style('njform-gs-responsive-css', NINJAFORMS_GOOGLESHEET_URL . 'assets/css/responsive.css', NINJAFORMS_GOOGLESHEET_VERSION, true);
        }
    }

    /**
     * enqueue JS files
     * @since 1.0
     */
    public function add_js_files()
    {
        if (is_admin() && (isset($_GET['page']) && ($_GET['page'] == 'njform-google-sheet-config'))) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

            // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter	
            wp_enqueue_script('njform-gs-connector-js', NINJAFORMS_GOOGLESHEET_URL . 'assets/js/njform-gs-connector.js', NINJAFORMS_GOOGLESHEET_VERSION, true);


            wp_enqueue_script(
                'njform-gs-connector-js',
                NINJAFORMS_GOOGLESHEET_URL . 'assets/js/gsc-ninjaform-popup.js',
                array('jquery'),
                NINJAFORMS_GOOGLESHEET_VERSION,
                true // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
            );

            //wp_enqueue_script('system-debug-js', NINJAFORMS_GOOGLESHEET_URL . 'assets/js/system-debug.js', NINJAFORMS_GOOGLESHEET_VERSION, true); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter	
        }

        if (is_admin()) {
            wp_enqueue_script('njform-gs-connector-notice-css', NINJAFORMS_GOOGLESHEET_URL . 'assets/js/njforms-gs-connector-notice.js', NINJAFORMS_GOOGLESHEET_VERSION, true); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter	
            wp_enqueue_script('njforms-gs-connector-adds-js', NINJAFORMS_GOOGLESHEET_URL . 'assets/js/njforms-gs-connector-adds.js', NINJAFORMS_GOOGLESHEET_VERSION, true); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter	
        }
    }

    /**
     * Add custom link for the plugin beside activate/deactivate links
     * @param array $links Array of links to display below our plugin listing.
     * @return array Amended array of links.    * 
     * @since 1.0
     */
    public function njforms_gs_connector_plugin_action_links($links)
    {
        try {
            unset($links['edit']);

            $settings_link = sprintf(
                '<a href="%s">%s</a>',
                esc_url(admin_url('admin.php?page=njform-google-sheet-config&tab=integration')),
                esc_html__('Settings', 'gsheetconnector-ninja-forms')
            );

            $upgrade_link = sprintf(
                '<a href="%s" target="_blank"><span style="color: #008000;">%s</span></a>',
                esc_url('https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro'),
                esc_html__('Upgrade to PRO', 'gsheetconnector-ninja-forms')
            );

            return array_merge(array($settings_link, $upgrade_link), $links);
        } catch (Exception $e) {
            NJForm_gs_Connector_Utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
        }
    }

    /**
     * Called on activation.
     * Creates the site_options (required for all the sites in a multi-site setup)
     * If the current version doesn't match the new version, runs the upgrade
     * @since 1.0
     */
    private function run_on_activation()
    {
        try {
            $plugin_options = get_site_option('njforms_GS_free_info');
            if (false === $plugin_options) {
                $njforms_GS_free_info = array(
                    'version' => NINJAFORMS_GOOGLESHEET_VERSION,
                    'db_version' => NINJAFORMS_GOOGLESHEET_DB_VERSION
                );
                update_site_option('njforms_GS_free_info', $njforms_GS_free_info);
            } else if (NINJAFORMS_GOOGLESHEET_DB_VERSION != $plugin_options['version']) {
                $this->run_on_upgrade();
            }

            /** Create debug log table  */
            $this->create_njfree_debug_log_table();

            if (!get_option('ninja_plugin_activated_at')) {
                update_option('ninja_plugin_activated_at', time());
            }

            // Fetch and save the API credentails.
            NJForm_gs_Connector_Utility::instance()->save_api_credentials();

            // wp_redirect( admin_url( '/admin.php?page=njform-google-sheet-config&tab=integration' ));
        } catch (Exception $e) {
            NJForm_gs_Connector_Utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
        }
    }

    /**
     * called on upgrade. 
     * checks the current version and applies the necessary upgrades from that version onwards
     * @since 1.0
     */
    public function run_on_upgrade()
    {
        $plugin_options = get_site_option('njforms_GS_free_info');
        if ($plugin_options['version'] == '1.2.21') {
            $this->upgrade_database_18();
        }

        // update the version value
        $google_sheet_info = array(
            'version' => NINJAFORMS_GOOGLESHEET_VERSION,
            'db_version' => NINJAFORMS_GOOGLESHEET_DB_VERSION
        );

        // check if debug log file exists or not
        $nflogFilePathToDelete = NINJAFORMS_GOOGLESHEET_PATH . "logs/log.txt";
        // Check if the log file exists before attempting to delete
        if (file_exists($nflogFilePathToDelete)) {
            unlink($nflogFilePathToDelete); // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
        }
        update_site_option('njforms_GS_free_info', $google_sheet_info);

        /** Create debug log table  */
        $this->create_njfree_debug_log_table();

        if (!get_option('ninja_plugin_activated_at')) {
            update_option('ninja_plugin_activated_at', time());
        }
    }
    /*
     * @since 2.3.10
     */

    public function upgrade_database_18()
    {
        global $wpdb;

        // look through each of the blogs and upgrade the DB
        if (function_exists('is_multisite') && is_multisite()) {
            // Get all blog ids; foreach them and call the uninstall procedure on each of them
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs"); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

            // Get all blog ids; foreach them and call the install procedure on each of them if the plugin table is found
            foreach ($blog_ids as $blog_id) {
                switch_to_blog($blog_id);
                $this->upgrade_helper_18();
                restore_current_blog();
            }
        }
        $this->upgrade_helper_18();
    }

    public function upgrade_helper_18()
    {
        // Fetch and save the API credentails.
        NJForm_gs_Connector_Utility::instance()->save_api_credentials();
    }

    /**
     * Called on activation.
     * Creates the options and DB (required by per site)
     * @since 1.0
     */
    private function run_for_site()
    {
        try {
            if (!get_option('njforms_gs_access_code')) {
                update_option('njforms_gs_access_code', '');
            }
            if (!get_option('njforms_gs_verify')) {
                update_option('njforms_gs_verify', 'invalid');
            }
            if (!get_option('njforms_gs_verify')) {
                update_option('njforms_gs_verify', '');
            }
            if (!get_option('njforms_gs_verify')) {
                update_option('njforms_gs_verify', 'false');
            }
        } catch (Exception $e) {
            NJForm_gs_Connector_Utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
        }
    }


    /**
     * Called on uninstall - deletes site specific options
     *
     * @since 1.0
     */
    private static function delete_for_site()
    {
        try {

            $saved_value = get_option('gsc_njf_uninstall_plugin_settings', 'No'); // Default to 'No' if option is not set
            if ($saved_value === 'Yes') {
                delete_option('njforms_gs_access_code');
                delete_option('njforms_gs_verify');
                delete_option('njforms_gs_token');
                delete_post_meta_by_key('njforms_gs_settings');
            }
        } catch (Exception $e) {
            NJForm_gs_Connector_Utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
        }
    }

    /**
     * register action in ninja Email & Action tab 
     */
    public function register_actions($actions)
    {
        require_once plugin_dir_path(__FILE__) . 'includes/Action/NinjaFormsGoogleSheet.php';
        $actions['google_sheet'] = new NF_Action_NJGheetAction();
        return $actions;
    }

    /**
     * register action in ninja Email & Action tab 
     */
    public static function template($file_name = '', array $data = array())
    {

        if (!$file_name) {
            return;
        }
        extract($data);

        include NINJAFORMS_GOOGLESHEET_PATH . 'includes/Templates/' . $file_name;
    }

    /**
     * Add widget to the dashboard
     * @since 1.0
     */
    public function add_njform_gs_connector_summary_widget()
    {
        $title = sprintf(
            "<img style='width:30px;margin-right: 10px;' src='%sassets/img/ninja-gsc.svg'><span>%s</span>",
            esc_url(NINJAFORMS_GOOGLESHEET_URL),
            esc_html__('GSheetConnector For Ninja Forms', 'gsheetconnector-ninja-forms')
        );

        wp_add_dashboard_widget('njform_gs_dashboard', $title, array($this, 'njform_gs_connector_summary_dashboard'));
    }

    /**
     * Display widget conetents
     * @since 1.0
     */
    public function njform_gs_connector_summary_dashboard()
    {
        include_once(NINJAFORMS_GOOGLESHEET_ROOT . '/includes/pages/njform-dashboard-widget.php');
    }

    /**
     * AJAX function - clear log file for system status tab
     * @since 2.1
     */
    public function nj_clear_debug_logs()
    {

        // 🚨 Block subscribers or any user without admin capability
        if (! current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized', 403);
        }

        // nonce check
        check_ajax_referer('gs-ajax-nonce', 'security');

        // Initialize WP_Filesystem
        if (! function_exists('WP_Filesystem')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
        WP_Filesystem();

        $log_file = WP_CONTENT_DIR . '/debug.log';

        // Clear the log file using WP_Filesystem
        if ($wp_filesystem->exists($log_file) || $wp_filesystem->put_contents($log_file, '', FS_CHMOD_FILE)) {
            $wp_filesystem->put_contents($log_file, '', FS_CHMOD_FILE);
        }

        wp_send_json_success();
    }



    /**
     * Add function to check plugins is Activate or not
     * @param string $class of plugins main class .
     * @return true/false    
     * @since 2.0.2
     **/

    public static function ninja_gs_is_pugin_active($class)
    {
        if (class_exists($class)) {
            return true;
        }
        return false;
    }
    /** Create ninja form debug log table */
    public function create_njfree_debug_log_table()
    {

        global $wpdb;

        $table = $wpdb->prefix . 'gscnjf_error_logs';
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            error_id VARCHAR(191) NOT NULL,
            code INT NOT NULL,
            message TEXT NOT NULL,
            details LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY error_id (error_id),
            KEY code (code)
        ) {$charset};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    /**
     * Delete the stored gscnjf_fields_sync_ui value for every action of a form.
     */
    function gscnjf_delete_saved_sync_ui($form_id)
    {
        global $wpdb;

        $form_id = absint($form_id);

        // Fallback: the builder posts the form as JSON in $_POST['form'].
        if (! $form_id && ! empty($_POST['form'])) {
            $posted  = json_decode(wp_unslash($_POST['form']), true);
            $form_id = isset($posted['id']) ? absint($posted['id']) : 0;
        }

        if (! $form_id) {
            return;
        }

        $actions_table = $wpdb->prefix . 'nf3_actions';
        $meta_table    = $wpdb->prefix . 'nf3_action_meta';

        $action_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT DISTINCT m.parent_id
         FROM {$meta_table} m
         INNER JOIN {$actions_table} a ON a.id = m.parent_id
         WHERE a.parent_id = %d AND m.`key` = %s",
            $form_id,
            'gscnjf_fields_sync_ui'
        ));

        if (empty($action_ids)) {
            return;
        }

        $action_ids   = array_map('absint', $action_ids);
        $placeholders = implode(',', array_fill(0, count($action_ids), '%d'));

        $deleted = $wpdb->query($wpdb->prepare(
            "DELETE FROM {$meta_table}
         WHERE `key` = %s AND parent_id IN ($placeholders)",
            array_merge(array('gscnjf_fields_sync_ui'), $action_ids)
        ));
    }
}

// Initialize the njform google sheet connector class
$init = new NJforms_Gsheet_Connector_Init();

// Add custom link for our plugin
add_filter('plugin_action_links_' . NINJAFORMS_GOOGLESHEET_BASE_NAME, array($init, 'njforms_gs_connector_plugin_action_links'));
