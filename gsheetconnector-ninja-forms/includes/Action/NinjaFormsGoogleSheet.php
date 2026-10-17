<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

if (! class_exists('NF_Abstracts_Action')) {
    return;
}


// I know this says NF_Notification_Base_Type, but the name will eventually be changed to reflect the action nomenclature.
final class NF_Action_NJGheetAction extends NF_Abstracts_Action
{

    /**
     * Get things rolling
     */
    protected $_name = 'google_sheet';
    protected $_tags = array();
    protected $_timing = 'normal';
    protected $_priority = '10';
    function __construct()
    {
        parent::__construct();

        $this->_nicename = esc_html__('GSheetConnector for Ninja Forms', 'gsheetconnector-ninja-forms');

        $njauthenticated = get_option('njforms_gs_token');

        $per = get_option('njforms_gs_verify');

        $this->_settings['action_name_unique'] = array(
            'name' => 'action_name_unique',
            'type' => 'textbox',
            'label' => '<strong>' . __('The above Action Name must always be unique and not duplicated.', 'gsheetconnector-ninja-forms') . '</strong>',
            'width' => 'full',
            'group' => 'primary',
        );



        if ((!empty($njauthenticated) && $per == "valid")) {

            $this->_settings['field_map_ninja_gsname'] = array(
                'name' => 'field_map_ninja_gsname',
                'type' => 'textbox',
                'label' => __('Google SpreadSheet Name', 'gsheetconnector-ninja-forms'),
                'width' => 'full',
                'group' => 'primary',
                'tmpl_row' => 'nf-tmpl-ninjags-custom-field-map-row',
                'help' => esc_html__('Enter the exact name of your Google Spreadsheet (as shown in Google Sheets).', 'gsheetconnector-ninja-forms'),
                'columns'           => array(
                    'mautic_ninjags_alias1'          => array(
                        'header' => 'Ninja Field Alias',
                        'column2'    => __('Ninja Field Alias', 'gsheetconnector-ninja-forms'),
                        'default'   => '',
                    ),
                    'value'          => array(
                        'header' => 'Value',
                        'column1'    => __('Ninja Forms Field Key', 'gsheetconnector-ninja-forms'),
                        'default'   => '',
                    ),
                ),
            );

            $this->_settings['field_map_ninja_gsid'] = array(
                'name' => 'field_map_ninja_gsid',
                'type' => 'textbox',
                'label' => __('Google SpreadSheet ID', 'gsheetconnector-ninja-forms'),
                'width' => 'full',
                'group' => 'primary',
                'tmpl_row' => 'nf-tmpl-ninjags-custom-field-map-row',
                'help' => esc_html__('Paste the Spreadsheet ID from your Google Sheet URL.', 'gsheetconnector-ninja-forms'),
                'columns'           => array(
                    'mautic_ninjags_alias'          => array(
                        'header' => 'Ninja Field Alias',
                        'column2'    => __('Ninja Field Alias', 'gsheetconnector-ninja-forms'),
                        'default'   => '',
                    ),
                    'value'          => array(
                        'header' => 'Value',
                        'column1'    => __('Ninja Forms Field Key', 'gsheetconnector-ninja-forms'),
                        'default'   => '',
                    ),
                ),
            );

            $this->_settings['field_map_ninja_gstabname'] = array(
                'name' => 'field_map_ninja_gstabname',
                'type' => 'textbox',
                'label' => __('Google SpreadSheet Tab Name', 'gsheetconnector-ninja-forms'),
                'width' => 'full',
                'group' => 'primary',
                'tmpl_row' => 'nf-tmpl-ninjags-custom-field-map-row',
                'help' => esc_html__('nter the exact sheet tab name (e.g., Sheet1) from the bottom of your Google Spreadsheet.', 'gsheetconnector-ninja-forms'),
                'columns'           => array(
                    'mautic_ninjags_alias'          => array(
                        'header' => 'Ninja Field Alias',
                        'column2'    => __('Ninja Field Alias', 'gsheetconnector-ninja-forms'),
                        'default'   => '',
                    ),
                    'value'          => array(
                        'header' => 'Value',
                        'column1'    => __('Ninja Forms Field Key', 'gsheetconnector-ninja-forms'),
                        'default'   => '',
                    ),
                ),
            );

            $this->_settings['field_map_ninja_gstabid'] = array(
                'name' => 'field_map_ninja_gstabid',
                'type' => 'textbox',
                'label' => __('Google SpreadSheet Tab ID', 'gsheetconnector-ninja-forms'),
                'width' => 'full',
                'group' => 'primary',
                'tmpl_row' => 'nf-tmpl-ninjags-custom-field-map-row',
                'help' => esc_html__('Get the Tab ID from your sheet URL after gid=.', 'gsheetconnector-ninja-forms'),
                'columns'           => array(
                    'mautic_ninjags_alias'          => array(
                        'header' => 'Ninja Field Alias',
                        'column2'    => __('Ninja Field Alias', 'gsheetconnector-ninja-forms'),
                        'default'   => '',
                    ),
                    'value'          => array(
                        'header' => 'Value',
                        'column1'    => __('Ninja Forms Field Key', 'gsheetconnector-ninja-forms'),
                        'default'   => '',
                    ),
                ),
            );

            //$google_sheet_set = $action->get_settings();
            if (isset($_GET['form_id'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $form_id = $_GET['form_id']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                $actions = Ninja_Forms()->form($form_id)->get_actions();

                foreach ($actions as $action) {
                    $type = $action->get_setting('type');
                    if ($type == 'google_sheet') {
                        $google_sheet_set = $action->get_settings();
                    }
                }




                $tab_id = isset($google_sheet_set['field_map_ninja_gstabid']) ? $google_sheet_set['field_map_ninja_gstabid'] : "";

                $sheet_id = isset($google_sheet_set['field_map_ninja_gsid']) ? $google_sheet_set['field_map_ninja_gsid'] : "";




                $link = "https://docs.google.com/spreadsheets/d/" . $sheet_id . "/edit#gid=" . $tab_id;
                if (!empty($sheet_id)) {



                    $this->_settings['google_sheet_link'] = array(
                        'name'    => 'google_sheet_link',
                        'type'    => 'option-repeater',
                        'label'   => '<a href="#" onClick="getSheetLink(); return false;" class="gscnjf-tip" aria-label="' . esc_attr__('View Spreadsheet', 'gsheetconnector-ninja-forms') . '" data-tooltip="' . esc_attr__('View Spreadsheet', 'gsheetconnector-ninja-forms') . '">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 5C6.5 5 2 12 2 12C2 12 6.5 19 12 19C17.5 19 22 12 22 12C22 12 17.5 5 12 5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" />
                                </svg>
                            </a>',
                        'width'   => 'half',
                        'group'   => 'primary',
                        'columns' => array(),


                    );


                    $this->_settings['google_sheet_download'] = array(
                        'name'    => 'google_sheet_download',
                        'type'    => 'option-repeater',
                        'label'   => '<a href="#" onClick="downloadSheetLink(); return false;" class="gscnjf-tip" aria-label="' . esc_attr__('Download Spreadsheet', 'gsheetconnector-ninja-forms') . '" data-tooltip="' . esc_attr__('Download Spreadsheet (PRO)', 'gsheetconnector-ninja-forms') . '">
                               <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 4V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                                <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M5 20H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                            </svg>
                            </a>',
                        'width'   => 'half',
                        'group'   => 'primary',
                        'columns' => array(),

                    );
                }
            }

            $this->_settings['gscnjf_pro_features_box'] = array(
                'name'    => 'gscnjf_pro_features_box',
                'type'    => 'option-repeater',          // same trick as your download setting
                'label'   => $this->gscnjf_get_pro_box_html(),
                'width'   => 'full',
                'group'   => 'primary',
                'columns' => array(),
            );


            $this->_settings['gscnjf_auto_config_title'] = array(
                'name'  => 'gscnjf_auto_config_title',
                'type'  => 'textbox',
                'label' => '<p><span class="gscnjf-feature-title">' . esc_html__('Automatic Google Sheets Configuration', 'gsheetconnector-ninja-forms') . ' <span class="gscnjf-pro-badge">' . esc_html__('PRO', 'gsheetconnector-ninja-forms') . '</span></span>'
                    . '</p>',
                'width' => 'full',
                'group' => 'primary',
            );

            $this->_settings['gscnjf_auto_sheet_names'] = array(
                'name'    => 'gscnjf_auto_sheet_names',
                'type'    => 'select',
                'label'   => esc_html__('Google SpreadSheet Name', 'gsheetconnector-ninja-forms'),
                'width'   => 'full',
                'group'   => 'primary',
                'disabled' => true,
                'options' => array(
                    array('label' => esc_html__('- Select -', 'gsheetconnector-ninja-forms'), 'value' => 0),
                ),
            );

            $this->_settings['gscnjf_auto_sheet_tab_names_drop'] = array(
                'name'    => 'gscnjf_auto_sheet_tab_names_drop',
                'type'    => 'select',
                'label'   => esc_html__('Google Tab Name', 'gsheetconnector-ninja-forms'),
                'width'   => 'full',
                'group'   => 'primary',
                'disabled' => true,
                'options' => array(
                    array('label' => esc_html__('- Select -', 'gsheetconnector-ninja-forms'), 'value' => 0),
                ),
            );



            // ===================== NEW: form fields list + sort settings =====================


            $gscnjf_builder_form_id = isset($_GET['form_id']) ? absint($_GET['form_id']) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

            $nf_field_array = array();
            foreach (self::gscnjf_free_form_fields_list($gscnjf_builder_form_id) as $field_key => $field_label) {
                $nf_field_array[] = array('label' => $field_label, 'value' => $field_key);
            }

            $this->_settings['gscnjf_fields_sync_ui'] = array(
                'name'  => 'gscnjf_fields_sync_ui',
                'type'  => 'html',
                'label' => '',
                'value' => $this->gscnjf_free_fields_sync_html($gscnjf_builder_form_id),
                'width' => 'full',
                'group' => 'primary',
            );

            $this->_settings['gscnjf_sheet_sorting_enable'] = array(
                'name' => 'gscnjf_sheet_sorting_enable',
                'type' => 'toggle',
                'label' => esc_html__('Spreadsheet Sorting', 'gsheetconnector-ninja-forms') . ' <span class="gscnjf-pro-badge">' . esc_html__('PRO', 'gsheetconnector-ninja-forms') . '</span>',
                'width' => 'full',
                'group' => 'primary',
                'value' => 1,
            );

            $this->_settings['gscnjf_sheet_sorting_column'] = array(
                'name' => 'gscnjf_sheet_sorting_column',
                'type' => 'select',
                'label' => esc_html__('Sort Column', 'gsheetconnector-ninja-forms'),
                'width' => 'one-half',
                'group' => 'primary',
                'value' => 'sequence',
                'options' => $nf_field_array,

            );

            $this->_settings['gscnjf_sheet_sorting_order'] = array(
                'name' => 'gscnjf_sheet_sorting_order',
                'type' => 'select',
                'label' => esc_html__('Sort Order', 'gsheetconnector-ninja-forms'),
                'width' => 'one-half',
                'group' => 'primary',
                'value' => 'asc',
                'options' => array(
                    array('label' => esc_html__('Ascending', 'gsheetconnector-ninja-forms'), 'value' => 'asc'),
                    array('label' => esc_html__('Descending', 'gsheetconnector-ninja-forms'), 'value' => 'desc'),
                ),

            );

            $this->_settings['gscnjf_conditional_logic_enable'] = array(
                'name'  => 'gscnjf_conditional_logic_enable',
                'type'  => 'toggle',
                'label' => esc_html__('Conditional Logic', 'gsheetconnector-ninja-forms') . ' <span class="gscnjf-pro-badge">' . esc_html__('PRO', 'gsheetconnector-ninja-forms') . '</span>',
                'width' => 'full',
                'group' => 'primary',
                'value' => 1,
            );

            $this->_settings['gscnjf_conditional_logic_desc'] = array(
                'name'  => 'gscnjf_conditional_logic_desc',
                'type'  => 'html',
                'label' => '',
                'value' => '<p class="gscnjf-cl-desc">' . esc_html__('The Enable Conditional Logic option allows you to create rules to dynamically send (or skip) the submission to Google Sheet based on field values.', 'gsheetconnector-ninja-forms') . '</p>',
                'width' => 'full',
                'group' => 'primary',
            );

            $this->_settings['gscnjf_conditional_logic_match_type'] = array(
                'name'    => 'gscnjf_conditional_logic_match_type',
                'type'    => 'select',
                'label'   => esc_html__('Process This Feed If', 'gsheetconnector-ninja-forms'),
                'width'   => 'full',
                'group'   => 'primary',
                'value'   => 'all',
                'options' => array(
                    array('label' => esc_html__('All', 'gsheetconnector-ninja-forms'), 'value' => 'all'),
                    array('label' => esc_html__('Any', 'gsheetconnector-ninja-forms'), 'value' => 'any'),
                ),
            );

            $this->_settings['gscnjf_conditional_logic_rules'] = array(
                'name'  => 'gscnjf_conditional_logic_rules',
                'type'  => 'html',
                'label' => '',
                'value' => self::gscnjf_free_condition_row_html($gscnjf_builder_form_id), // must be this method's return, a string
                'width' => 'full',
                'group' => 'primary',
            );


            // ===================== END NEW =====================


            $this->_settings['gscnjf_header_title_enable'] = array(
                'name'  => 'gscnjf_header_title_enable',
                'type'  => 'toggle',
                'label' => esc_html__('Header Settings', 'gsheetconnector-ninja-forms') . ' <span class="gscnjf-pro-badge">' . esc_html__('PRO', 'gsheetconnector-ninja-forms') . '</span>',
                'width' => 'full',
                'group' => 'primary',
                'value' => 1,
            );




            $this->_settings['gscnjf_header_setting_desc'] = array(
                'name' => 'gscnjf_header_setting_desc',
                'type' => 'html',
                'label' => '',
                'value' => '<p class="nf-gscnjf-sh-desc">' . esc_html__('Customize the appearance and behavior of your Spreadsheet headers and rows for better readability and organization.', 'gsheetconnector-ninja-forms') . '</p>',
                'width' => 'full',
                'group' => 'primary',


            );

            $this->_settings['gscnjf_freeze_header'] = array(
                'name' => 'gscnjf_freeze_header',
                'type' => 'toggle',
                'label' => esc_html__('Freeze Header Row', 'gsheetconnector-ninja-forms'),
                'width' => 'full',
                'group' => 'primary',
                'value' => 1,
                'help' => esc_html__('Freeze header if you want to freeze first row considered as header.', 'gsheetconnector-ninja-forms'),

            );

            $this->_settings['gscnjf_color_settings'] = array(
                'name' => 'gscnjf_color_settings',
                'type' => 'toggle',
                'label' => esc_html__('Color Appearance', 'gsheetconnector-ninja-forms'),
                'width' => 'full',
                'group' => 'primary',
                'value' => 1,
                'help' => esc_html__('Please Enable If you want to color rows of header/ odd / even rows', 'gsheetconnector-ninja-forms'),

            );

            $this->_settings['gscnjf_header_color_njf'] = array(
                'name' => 'gscnjf_header_color_njf',
                'type' => 'color',
                'label' => esc_html__('Header Color', 'gsheetconnector-ninja-forms'),
                'width' => 'one-half',
                'group' => 'primary',
                'value' => '#ffffff',

            );

            $this->_settings['gscnjf_odd_color_njf'] = array(
                'name' => 'gscnjf_odd_color_njf',
                'type' => 'color',
                'label' => esc_html__('Odd Rows Color', 'gsheetconnector-ninja-forms'),
                'width' => 'one-half',
                'group' => 'primary',
                'value' => '#ffffff',

            );

            $this->_settings['gscnjf_even_color_njf'] = array(
                'name' => 'gscnjf_even_color_njf',
                'type' => 'color',
                'label' => esc_html__('Even Rows Color', 'gsheetconnector-ninja-forms'),
                'width' => 'one-half',
                'group' => 'primary',
                'value' => '#ffffff',

            );

            $this->_settings['gscnjf_header_appearance_settings'] = array(
                'name' => 'gscnjf_header_appearance_settings',
                'type' => 'toggle',
                'label' => esc_html__('Header Appearance', 'gsheetconnector-ninja-forms'),
                'width' => 'full',
                'group' => 'primary',
                'value' => 1,
                'help' => esc_html__('Please Enable if you want to style the header row text (font style, size and color)', 'gsheetconnector-ninja-forms'),

            );

            $header_font_size_options = array();
            foreach (range(10, 16) as $gscnjf_font_size) {
                $header_font_size_options[] = array(
                    'label' => (string) $gscnjf_font_size,
                    'value' => (string) $gscnjf_font_size,
                );
            }

            $this->_settings['gscnjf_header_font_style'] = array(
                'name' => 'gscnjf_header_font_style',
                'type' => 'select',
                'label' => esc_html__('Font Style', 'gsheetconnector-ninja-forms'),
                'width' => 'one-half',
                'group' => 'primary',
                'value' => 'normal',
                'options' => array(
                    array('label' => esc_html__('Normal', 'gsheetconnector-ninja-forms'), 'value' => 'normal'),
                    array('label' => esc_html__('Italic', 'gsheetconnector-ninja-forms'), 'value' => 'italic'),
                    array('label' => esc_html__('Bold', 'gsheetconnector-ninja-forms'), 'value' => 'bold'),
                ),

            );

            $this->_settings['gscnjf_header_font_size'] = array(
                'name' => 'gscnjf_header_font_size',
                'type' => 'select',
                'label' => esc_html__('Font Size', 'gsheetconnector-ninja-forms'),
                'width' => 'one-half',
                'group' => 'primary',
                'value' => '12',
                'options' => $header_font_size_options,

            );

            $this->_settings['gscnjf_header_font_color'] = array(
                'name' => 'gscnjf_header_font_color',
                'type' => 'color',
                'label' => esc_html__('Font Color', 'gsheetconnector-ninja-forms'),
                'width' => 'one-half',
                'group' => 'primary',
                'value' => '#000000',


            );

            $this->_settings['gscnjf_row_appearance_settings'] = array(
                'name' => 'gscnjf_row_appearance_settings',
                'type' => 'toggle',
                'label' => esc_html__('Row Appearance', 'gsheetconnector-ninja-forms'),
                'width' => 'full',
                'group' => 'primary',
                'value' => 1,
                'help' => esc_html__('Please Enable if you want to style the data row text (font style, size and color)', 'gsheetconnector-ninja-forms'),

            );

            $this->_settings['gscnjf_row_font_style'] = array(
                'name' => 'gscnjf_row_font_style',
                'type' => 'select',
                'label' => esc_html__('Font Style', 'gsheetconnector-ninja-forms'),
                'width' => 'one-half',
                'group' => 'primary',
                'value' => 'normal',
                'options' => array(
                    array('label' => esc_html__('Normal', 'gsheetconnector-ninja-forms'), 'value' => 'normal'),
                    array('label' => esc_html__('Italic', 'gsheetconnector-ninja-forms'), 'value' => 'italic'),
                    array('label' => esc_html__('Bold', 'gsheetconnector-ninja-forms'), 'value' => 'bold'),
                ),

            );

            $this->_settings['gscnjf_row_font_size'] = array(
                'name' => 'gscnjf_row_font_size',
                'type' => 'select',
                'label' => esc_html__('Font Size', 'gsheetconnector-ninja-forms'),
                'width' => 'one-half',
                'group' => 'primary',
                'value' => '12',
                'options' => $header_font_size_options,

            );

            $this->_settings['gscnjf_row_font_color'] = array(
                'name' => 'gscnjf_row_font_color',
                'type' => 'color',
                'label' => esc_html__('Font Color', 'gsheetconnector-ninja-forms'),
                'width' => 'one-half',
                'group' => 'primary',
                'value' => '#000000',


            );

            $this->_settings['gscnjf_sync_setting_title'] = array(
                'name'  => 'gscnjf_sync_setting_title',
                'type'  => 'textbox',
                'label' => '<p><span class="gscnjf-feature-title">' . esc_html__('Google Speadsheets Data Sync', 'gsheetconnector-ninja-forms') . ' <span class="gscnjf-pro-badge">' . esc_html__('PRO', 'gsheetconnector-ninja-forms') . '</span></span>'
                    . '</p>',
                'width' => 'full',
                'group' => 'primary',
            );

            $this->_settings['gscnjf_sync_setting_desc'] = array(
                'name' => 'gscnjf_sync_setting_desc',
                'type' => 'html',
                'label' => '',
                'value' => '<p class="nf-gscnjf-sh-desc">' . esc_html__('Easily sync past form submissions to your connected Google Spreadsheet. Choose a date range and ensure your spreadsheet stays complete and up to date.', 'gsheetconnector-ninja-forms') . '</p>',
                'width' => 'full',
                'group' => 'primary',


            );


            $this->_settings['gscnjf_field_map_ninja_gs_upgrade_to_pro_link'] =  array(
                'name'      => 'gscnjf_field_map_ninja_gs_upgrade_to_pro_link',
                'type'      => 'textbox',
                'label'     => '<a  class="nf-button primary nf-close-drawer gscnf-sync-entries">' .
                    esc_html__('Sync Entries', 'gsheetconnector-ninja-forms') . '</a>',
                'width'     => 'full',
                'group'     => 'primary',
                'tmpl_row'  => 'nf-tmpl-save-field-repeater-row',
                'value' => FALSE,
            );
        } else {
            // if user is not authenticated then show display error note


            $gscnjf_auth_html = '<div class="gscnjf-setup-alert">
                                <div class="gscnjf-alert-icon">
                                    <svg width="30px" height="30px" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18.2202 21.25H5.78015C5.14217 21.2775 4.50834 21.1347 3.94373 20.8364C3.37911 20.5381 2.90402 20.095 2.56714 19.5526C2.23026 19.0101 2.04372 18.3877 2.02667 17.7494C2.00963 17.111 2.1627 16.4797 2.47015 15.92L8.69013 5.10999C9.03495 4.54078 9.52077 4.07013 10.1006 3.74347C10.6804 3.41681 11.3346 3.24518 12.0001 3.24518C12.6656 3.24518 13.3199 3.41681 13.8997 3.74347C14.4795 4.07013 14.9654 4.54078 15.3102 5.10999L21.5302 15.92C21.8376 16.4797 21.9907 17.111 21.9736 17.7494C21.9566 18.3877 21.7701 19.0101 21.4332 19.5526C21.0963 20.095 20.6211 20.5381 20.0565 20.8364C19.4919 21.1347 18.8581 21.2775 18.2202 21.25V21.25Z" stroke="#9a3412" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M10.8809 17.15C10.8809 17.0021 10.9102 16.8556 10.9671 16.7191C11.024 16.5825 11.1074 16.4586 11.2125 16.3545C11.3175 16.2504 11.4422 16.1681 11.5792 16.1124C11.7163 16.0567 11.8629 16.0287 12.0109 16.03C12.2291 16.034 12.4413 16.1021 12.621 16.226C12.8006 16.3499 12.9398 16.5241 13.0211 16.7266C13.1023 16.9292 13.122 17.1512 13.0778 17.3649C13.0335 17.5786 12.9272 17.7745 12.7722 17.9282C12.6172 18.0818 12.4203 18.1863 12.2062 18.2287C11.9921 18.2711 11.7703 18.2494 11.5685 18.1663C11.3666 18.0833 11.1938 17.9426 11.0715 17.7618C10.9492 17.5811 10.8829 17.3683 10.8809 17.15ZM11.2409 14.42L11.1009 9.20001C11.0876 9.07453 11.1008 8.94766 11.1398 8.82764C11.1787 8.70761 11.2424 8.5971 11.3268 8.5033C11.4112 8.40949 11.5144 8.33449 11.6296 8.28314C11.7449 8.2318 11.8697 8.20526 11.9959 8.20526C12.1221 8.20526 12.2469 8.2318 12.3621 8.28314C12.4774 8.33449 12.5805 8.40949 12.6649 8.5033C12.7493 8.5971 12.8131 8.70761 12.852 8.82764C12.8909 8.94766 12.9042 9.07453 12.8909 9.20001L12.7609 14.42C12.7609 14.6215 12.6808 14.8149 12.5383 14.9574C12.3957 15.0999 12.2024 15.18 12.0009 15.18C11.7993 15.18 11.606 15.0999 11.4635 14.9574C11.321 14.8149 11.2409 14.6215 11.2409 14.42Z" fill="#9a3412" />
                                    </svg>
                                </div>
                                <div class="gscnjf-alert-content">
                                    <div class="feed-alert-header">' . esc_html__('Google Sheets Setup Required', 'gsheetconnector-ninja-forms') . '</div>
                                    <p>' . esc_html__('To start sending form entries to Google Sheets, please connect your Google account first.', 'gsheetconnector-ninja-forms') . '</p>
                                    <p>' . esc_html__('your selected Method is : Existing Client / Secret Key (Auto Setup).', 'gsheetconnector-ninja-forms') . '</p>
                                    
                                    
                                   
                                    <ul>
                                        <li>✔ ' . esc_html__('Click on the Sign in with Google button', 'gsheetconnector-ninja-forms') . '</li>
                                        <li>✔ ' . esc_html__('Log in using your Google account', 'gsheetconnector-ninja-forms') . '</li>
                                        <li>✔ ' . esc_html__('Select the Google account where your Sheets are stored', 'gsheetconnector-ninja-forms') . '</li>
                                        <li>✔ ' . esc_html__('Grant access to: Google Drive & Google Sheets', 'gsheetconnector-ninja-forms') . '</li>
                                        <li>✔ ' . esc_html__('Save the authentication code if prompted', 'gsheetconnector-ninja-forms') . '</li>
                                    </ul>
                                    <a href="' . esc_url(admin_url('admin.php?page=njform-google-sheet-config&tab=integration')) . '" target="_blank" class="gscnjf-alert-btn link-hover-white">
                                        ' . esc_html__('Go to Integration Setup', 'gsheetconnector-ninja-forms') . '
                                    </a>
                                </div>
                            </div>';


            $this->_settings['field_map_ninja_gs_nj_error'] = array(


                'name' => 'field_map_ninja_gs_nj_errors',
                'type' => 'html',
                'label' => '',
                'value' => $gscnjf_auth_html,
                'width' => 'full',
                'group' => 'primary',

            );
        }
    }

    // ===================== NEW: form fields list (key => label) =====================
    public static function gscnjf_free_form_fields_list($form_id)
    {
        $list = array();
        $form_id = absint($form_id);


        if (!$form_id || !function_exists('Ninja_Forms')) {
            return $list;
        }
        $skip_types = array('submit', 'html', 'hr', 'recaptcha', 'spam');
        try {
            $form = Ninja_Forms()->form($form_id);
            if (!$form || !method_exists($form, 'get_fields')) {
                return $list;
            }
            foreach ((array) $form->get_fields() as $field) {
                $key  = $field->get_setting('key');
                $type = $field->get_setting('type');

                if (empty($key) || in_array($type, $skip_types, true)) {
                    continue;
                }

                $label = $field->get_setting('label');
                $list[$key] = ($label !== '' && $label !== null) ? sanitize_text_field($label) : $key;
            }
        } catch (Exception $e) {
            error_log('[GSC] form fields list: ' . $e->getMessage()); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
        }

        return $list;
    }


    public static function gscnjf_free_fields_sync_html($form_id)
    {
        // Submission ID and Date first, then the real form fields.
        $rows = array();
        $skip = ['turnstile_'];
        foreach (self::gscnjf_free_form_fields_list($form_id) as $key => $label) {
            // skip hidden fields
            foreach ($skip as $prefix) {
                if (strpos($key, $prefix) === 0) {
                    continue 2; // skip to the next field
                }
            }
            $rows[$key] = $label;
        }


        $html  = '';
        $html  .= '<div class="gscnjf-sync-card">';
        $html .= '<div class="gscnjf-sync-title">';
        $html .= '<span>' . esc_html__('Select Fields To Sync', 'gsheetconnector-ninja-forms') . '</span>';
        $html .= '<span class="gscnjf-pro-badge">' . esc_html__('PRO', 'gsheetconnector-ninja-forms') . '</span>';
        $html .= '</div>';
        $html .= '<div class="gscnjf-select-all">';
        $html .= '<span class="gscnjf-select-all-label" id="gscnjf-select-all-label">' . esc_html__('Select All Fields', 'gsheetconnector-ninja-forms') . '</span>';
        $html .= '<label class="gscnjf-switch">';
        $html .= '<input type="checkbox" id="gscnjf-check-all" checked disabled>';
        $html .= '<span class="gscnjf-slider"></span>';
        $html .= '</label>';
        $html .= '</div>';

        foreach ($rows as $key => $label) {
            $html .= '<div class="gscnjf-row" data-key="' . esc_attr($key) . '">';
            $html .= '<span class="gscnjf-drag">&#9776;</span>';
            $html .= '<label class="gscnjf-switch">';
            $html .= '<input type="checkbox" class="gscnjf-row-toggle" checked disabled>';
            $html .= '<span class="gscnjf-slider"></span>';
            $html .= '</label>';
            $html .= '<span class="gscnjf-field-label">' . esc_html($label) . '</span>';
            $html .= '<div class="gscnjf-input-wrap">';
            $html .= '<input type="text" class="gscnjf-col-input" value="' . esc_attr($label) . '" disabled>';
            $html .= '</div>';
            $html .= '<span class="gscnjf-remove">&#10005;</span>';
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }
    // ===================== END NEW =====================


    public function builder_templates()
    {
        //exit;
        NF_Mautic::template('custom-field-map-row.html');
    }

    public static function gscnjf_free_condition_row_html($form_id)
    {
        $fields = array('submission_id' => esc_html__('Submission ID', 'gsheetconnector-ninja-forms'));
        foreach (self::gscnjf_free_form_fields_list($form_id) as $key => $label) {
            $fields[$key] = $label;
        }

        $operators = array(
            'is'               => __('Is', 'gsheetconnector-ninja-forms'),
            'is_not'           => __('Is Not', 'gsheetconnector-ninja-forms'),
            'contains'         => __('Contains', 'gsheetconnector-ninja-forms'),
            'does_not_contain' => __('Does Not Contain', 'gsheetconnector-ninja-forms'),
            'starts_with'      => __('Starts With', 'gsheetconnector-ninja-forms'),
            'ends_with'        => __('Ends With', 'gsheetconnector-ninja-forms'),
            'greater_than'     => __('Greater Than', 'gsheetconnector-ninja-forms'),
            'less_than'        => __('Less Than', 'gsheetconnector-ninja-forms'),
            'is_empty'         => __('Is Empty', 'gsheetconnector-ninja-forms'),
            'is_not_empty'     => __('Is Not Empty', 'gsheetconnector-ninja-forms'),
        );

        $field_options = '';
        foreach ($fields as $key => $label) {
            $field_options .= '<option value="' . esc_attr($key) . '">' . esc_html($label) . '</option>';
        }

        $operator_options = '';
        foreach ($operators as $key => $label) {
            $operator_options .= '<option value="' . esc_attr($key) . '">' . esc_html($label) . '</option>';
        }

        $html  = '<div class="gscnjf-cl-card"><div class="gscnjf-cl-row">';
        $html .= '<select class="gscnjf-cl-select" disabled>' . $field_options . '</select>';
        $html .= '<select class="gscnjf-cl-select gscnjf-cl-operator" disabled>' . $operator_options . '</select>';
        $html .= '<input type="text" class="gscnjf-cl-value" placeholder="' . esc_attr__('Enter a value', 'gsheetconnector-ninja-forms') . '" disabled>';
        $html .= '<span class="gscnjf-cl-add">+</span><span class="gscnjf-cl-remove">&#10005;</span>';
        $html .= '</div></div>';

        return $html;
    }

    public function process($action_settings, $form_id, $data)
    {
        $action_settings['id'];

        $sheet_name = isset($action_settings['field_map_ninja_gsname']) ? $action_settings['field_map_ninja_gsname'] : "";
        $sheet_id = isset($action_settings['field_map_ninja_gsid']) ? $action_settings['field_map_ninja_gsid'] : "";
        $sheet_tab_name = isset($action_settings['field_map_ninja_gstabname']) ? $action_settings['field_map_ninja_gstabname'] : "";
        $tab_id = isset($action_settings['field_map_ninja_gstabid']) ? $action_settings['field_map_ninja_gstabid'] : "";

        if (has_filter('ninja_forms_get_fields_sorted')) {
            $fields_by_key = array();
            foreach ($data['fields'] as $field) {
                if (is_null($field)) continue;
                if (is_array($field)) {
                    if (! isset($field['key'])) continue;
                    $key = $field['key'];
                } else {
                    $key = $field->get_setting('key');
                }
                $fields_by_key[$key] = $field;
            }
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
            $data['fields'] = apply_filters('ninja_forms_get_fields_sorted', array(), $data['fields'], $fields_by_key, $form_id); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
        }
        foreach ($data['fields'] as $f => $form) {
            $field_label = array();
            if (is_array($form['value'])) {

                if ($form['settings']['type'] == 'repeater') {
                    $f_label = [];
                    $field_label = [];

                    foreach ($form['fields'] as $key => $value) {
                        $f_label[$value['id']] = $value['label'];
                    }

                    foreach ($form['settings']['value'] as $key => $value) {
                        $remove = explode('_', $value['id']);
                        $label  = isset($f_label[$remove[0]]) ? $f_label[$remove[0]] : '';
                        $number = $remove[1] + 1;

                        $raw = $value['value'];

                        if (is_object($raw)) {
                            $raw = (array) $raw;
                        }

                        if (is_array($raw)) {
                            $vals = array_values($raw);
                            $last = !empty($vals) ? end($vals) : '';

                            // Date/Time field: [date, hour, minute, am/pm]
                            if (count($vals) >= 3 && is_scalar($last) && in_array(strtolower((string) $last), ['am', 'pm'], true)) {
                                $date = $vals[0];

                                if (
                                    !is_string($date)
                                    || trim($date) === ''
                                    || strpos($date, '[object Object]') !== false
                                ) {
                                    $val = ''; // no date selected -> show nothing
                                } else {
                                    $val = $date . ' ' . $vals[1] . ':' . $vals[2] . ' ' . $vals[3];
                                }
                            } else {
                                // Checkbox List / Multi-Select -> [1,2,3]
                                $flat = [];
                                array_walk_recursive($raw, function ($v) use (&$flat) {
                                    if (is_scalar($v) && trim((string) $v) !== '' && strpos((string) $v, '[object Object]') === false) {
                                        $flat[] = $v;
                                    }
                                });
                                $val = empty($flat) ? '' : '[' . implode(',', $flat) . ']';
                            }
                        } else {
                            $val = (string) $raw;
                            if (strpos($val, '[object Object]') !== false) {
                                $val = ''; // date came as plain text "[object Object]..."
                            }
                        }

                        $field_label[] = $number . "." . $label . "-" . $val;
                    }

                    $data1[$form['label']] = implode(',', $field_label);
                } elseif ($form['settings']['type'] == 'html') {
                    $html_val = strip_tags($form['value']); // phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags
                    $data1[$form['label']] = implode(',', $html_val);
                } else {
                    $data1[$form['label']] = implode(',', $form['value']);
                }
            } elseif ($form['settings']['type'] == 'signature') {

                // 1. find the raw value (check both places)
                $raw = !empty($form['settings']['value']) ? $form['settings']['value'] : (isset($form['value']) ? $form['value'] : '');

                // 2. decode safely
                if (is_array($raw)) {
                    $sig = $raw;
                } else {
                    $sig = json_decode($raw, true);
                    if (!is_array($sig)) {
                        $sig = json_decode(stripslashes($raw), true);
                    }
                    if (!is_array($sig)) {
                        $sig = json_decode(html_entity_decode($raw), true);
                    }
                }

                $signature_type = isset($sig['signature_type']) ? $sig['signature_type'] : '';
                $val = '';

                if ($signature_type == 'typed') {
                    $val = isset($sig['typed_name']) ? $sig['typed_name'] : '';
                } elseif ($signature_type == 'drawn' && !empty($sig['signature_data'])) {
                    $val = isset($sig['signature_data']) ? $sig['signature_data'] : '';
                }



                $data1[$form['label']] = $val;
            } else {
                if ($form['settings']['type'] == 'html') {
                    $data1[$form['label']] = strip_tags($form['value']); // phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags
                } else {
                    $data1[$form['label']] = $form['value'];
                }
            }
        }



        if ((!empty($sheet_name)) && (!empty($sheet_tab_name))) {
            try {
                include_once(NINJAFORMS_GOOGLESHEET_ROOT . "/lib/google-sheets.php");
                $doc = new njfgsc_googlesheet();
                $doc->auth();
                $doc->setSpreadsheetId($sheet_id);
                $doc->setWorkTabId($tab_id);
                //$timestamp = strtotime(date("Y-m-d H:i:s"));
                // Fetched local date and time instaed of unix date and time
                $data1['date'] = date_i18n(get_option('date_format'));
                $data1['time'] = date_i18n(get_option('time_format'));

                $doc->add_row($data1);
            } catch (Exception $e) {
                $data1['ERROR_MSG'] = $e->getMessage();
                $data1['TRACE_STK'] = $e->getTraceAsString();
                NJForm_gs_Connector_Utility::gs_debug_log($data1);
            }
        }
        return $data;
    }

    public function gscnjf_get_pro_box_html()
    {

        $sections = array(
            __('Automatic Google Sheets Configuration', 'gsheetconnector-ninja-forms') => array(
                __('Auto fetch Google Sheets list', 'gsheetconnector-ninja-forms'),
                __('Auto detect sheet tabs', 'gsheetconnector-ninja-forms'),
                __('Real-time entry sync', 'gsheetconnector-ninja-forms'),
            ),
            __('Select Fields to Sync', 'gsheetconnector-ninja-forms') => array(
                __('Drag & drop field reordering', 'gsheetconnector-ninja-forms'),
                __('Rename column headers', 'gsheetconnector-ninja-forms'),
                __('Select specific fields to sync', 'gsheetconnector-ninja-forms'),
            ),
            __('Conditional Logic', 'gsheetconnector-ninja-forms') => array(
                __('Apply rules based on form values', 'gsheetconnector-ninja-forms'),
                __('Sync data only when conditions match', 'gsheetconnector-ninja-forms'),

            ),
            __('Header Settings', 'gsheetconnector-ninja-forms') => array(
                __('Freeze header row', 'gsheetconnector-ninja-forms'),
                __('Custom font styling', 'gsheetconnector-ninja-forms'),
                __('Header & row color control', 'gsheetconnector-ninja-forms'),
                __('Download spreadsheet as file', 'gsheetconnector-ninja-forms'),
            ),
        );

        $upgrade_url = 'https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro';

        ob_start();
?>
        <div class="gscnjf-pro">
            <div class="gscnjf-pro-head">
                <div class="gscnjf-pro-lock">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="5" y="11" width="14" height="9" rx="2" fill="currentColor" />
                        <path d="M8 11V8a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <div>
                    <p class="gscnjf-pro-title"><?php echo esc_html__('Unlock Advanced Features with Form Actions', 'gsheetconnector-ninja-forms'); ?></p>
                    <div class="gscnjf-pro-row">
                        <span class="gscnjf-pro-badge"><?php echo esc_html__('Advanced options are available in PRO', 'gsheetconnector-ninja-forms'); ?></span>
                        <a class="gscnjf-pro-btn" href="<?php echo esc_url($upgrade_url); ?>" target="_blank" rel="noopener">
                            <?php echo esc_html__('Get Advanced Features', 'gsheetconnector-ninja-forms'); ?> &#8599;
                        </a>
                    </div>
                </div>
            </div>

            <div class="gscnjf-pro-cols" id="gscnjf-pro-cols">
                <?php foreach ($sections as $heading => $items) : ?>
                    <div>
                        <h4><?php echo esc_html($heading); ?></h4>
                        <ul>
                            <?php foreach ($items as $item) : ?>
                                <li><?php echo esc_html($item); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>

            <a href="#" class="gscnjf-pro-toggle"
                data-hide="<?php echo esc_attr__('Hide Features', 'gsheetconnector-ninja-forms'); ?> &#9650;"
                data-show="<?php echo esc_attr__('Show Features', 'gsheetconnector-ninja-forms'); ?> &#9660;"
                onclick="var c=document.getElementById('gscnjf-pro-cols'),o=c.style.display==='none';c.style.display=o?'':'none';this.innerHTML=o?this.getAttribute('data-hide'):this.getAttribute('data-show');return false;">
                <?php echo esc_html__('Hide Features', 'gsheetconnector-ninja-forms'); ?> &#9650;
            </a>
        </div>
<?php
        // Collapse whitespace so the string stays clean inside 'label'.
        return trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }
}

return new NF_Action_NJGheetAction();
