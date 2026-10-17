<?php
/*
 * Ninja Forms GS Dashboard Widget
 * @since 1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit();
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
?>
<div class="dashboard-content">
    <?php
    $gs_connector_service = new njforms_Googlesheet_Services();

    $forms_list = $gs_connector_service->get_forms_connected_to_sheet();


    ?>
    <div class="main-content">
        <div>
            <h3><?php echo esc_html__("Ninja Forms connected with Google Sheets.", "gsheetconnector-ninja-forms"); ?>
            </h3>


            <style>
                .widget-table {
                    border: 1px solid #eee;
                    width: 100%;
                }

                .widget-table th {
                    text-align: left;
                    background: #eee;
                    padding: 2px 3px;
                    border-bottom: 1px solid #eee;
                }

                .widget-table td {
                    text-align: left;
                    background: #fff;
                    padding: 2px 3px;
                    word-wrap: break-word;
                }

                .widget-table td:nth-child(1) {
                    width: 50%;
                }
            </style>

            <?php

            $forms = Ninja_Forms()->form()->get_forms();

            ?>



            <table class="widget-table" id="gsnfp-feed-table" data-page="1">
                <thead>
                    <tr>
                        <th><?php echo esc_html__('Form Name', 'gsheetconnector-ninja-forms'); ?></th>
                        <th><?php echo esc_html__('Action Name', 'gsheetconnector-ninja-forms'); ?></th>
                        <th><?php echo esc_html__('Sheet Name', 'gsheetconnector-ninja-forms'); ?></th>
                    </tr>
                </thead>
                <tbody id="gsnfp-feed-table-body">
                    <?php if (!empty($forms)) {

                        foreach ($forms as $form) {
                            $form_id    = $form->get_id();
                            $form_name  = $form->get_setting('title');
                            $actions = Ninja_Forms()->form($form_id)->get_actions();
                            foreach ($actions as  $action) {
                                $action_type = $action->get_setting('type');
                                if ($action_type == 'google_sheet') {
                                    if ($action->get_setting('integration-mode-type') == 'automatic') {
                                        $sheetId_data = get_option('gsc_njf_pro_sheetId');
                                        $sheet_id = $action->get_setting('auto_sheet_names');
                                        $sheet_name = isset($sheetId_data[$sheet_id]) ? $sheetId_data[$sheet_id] : "";
                                        $tab_id = $action->get_setting('auto_sheet_tab_id');
                                        $tab_name = isset($action_data['auto_sheet_tab_names']) ? $action_data['auto_sheet_tab_names'] : "";
                                    } else {
                                        $sheet_id = $action->get_setting('field_map_ninja_gsid');
                                        $sheet_name = $action->get_setting('field_map_ninja_gsname');
                                        $tab_id = $action->get_setting('field_map_ninja_gstabid');
                                        $tab_name = $action->get_setting('field_map_ninja_gstabname');
                                    }
                                    $action_id = $action->get_id();
                                    $action_label = $action->get_setting('label');


                    ?><tr>
                                        <td> <a class="form-titl" target="_blank" href="<?php echo esc_url(admin_url('admin.php?page=ninja-forms&form_id=' . intval($form_id))); ?>">
                                                <?php echo esc_html($form_name); ?>
                                            </a>
                                        </td>
                                        <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                        ?>
                                        <td><?php echo $action_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  
                                            ?></td>
                                        <td>

                                            <?php
                                            if ($sheet_id != '' && $tab_id != '') {
                                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped     
                                            ?>
                                                <a href="<?php echo esc_url('https://docs.google.com/spreadsheets/d/' . rawurlencode($sheet_id) . '/edit#gid=' . rawurlencode($tab_id)); ?>" target="_blank" rel="noopener noreferrer">
                                                    <?php echo $sheet_name . '-' . $tab_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  
                                                    ?>
                                                </a>
                                            <?php } else { ?>

                                                <span class=""><?php echo esc_html__('Not connected', 'gsheetconnector-ninja-forms'); ?></span>
                                            <?php }

                                            ?>

                                        </td>

                                    </tr>
                        <?php }
                            }
                        } ?>
                    <?php } else { ?>
                        <tr class="gsnfp-feed-loading-row">
                            <td colspan="3" class="gsnfp-colspan-three">

                                <span class="gsnfp-loader-text"><?php echo esc_html__('Ninja Forms is not connected with Google Sheets.', 'gsheetconnector-ninja-forms'); ?></span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div> <!-- main-content end -->
</div> <!-- dashboard-content end -->
<style type="text/css">
    .postbox-header .hndle {
        justify-content: flex-start !important;
    }
</style>