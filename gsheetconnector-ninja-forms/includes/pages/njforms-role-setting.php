<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if (!current_user_can('administrator')) {
?>
    <span class="per_not_allo"><?php echo esc_html__("Permission Not Allowed", 'gsheetconnector-ninja-forms'); ?></span>
<?php
    return;
}
$gsheetconnector_allowed_accress_roles  = array('administrator', 'editor', 'author', 'contributor');
$participating_roles = array();
$editable_roles = get_editable_roles();

foreach ($editable_roles as $role => $details) {

    if (in_array($role, $gsheetconnector_allowed_accress_roles)) {
        $participating_roles[$role] = $details['name'];
    }
}
?>
<form id="gsc_nf_role_settings_form" method="post" action="options.php">
    <!--Start Pro Setting(Roll Permissions)-->
    <div class="gsc-pro-promo ml-15 mr-pro-15">

        <div class="gsc-pro-header">
            <div class="gsc-pro-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 19c-1 1-2 1-3 1 0-1 0-2 1-3l4-4"></path>
                    <path d="M14 3l7 7"></path>
                    <path d="M9 18l-4 4"></path>
                    <path d="M15 3c2 0 6 4 6 6-2 2-6 6-8 8l-6-6c2-2 6-8 8-8z"></path>
                    <circle cx="15" cy="9" r="1.5"></circle>
                </svg>

            </div>

            <div>
                <div class="unlock-header">
                    <?php echo esc_html(__('Unlock Role-Based Access Control', 'gsheetconnector-ninja-forms')); ?>
                </div>
                <span
                    class="gsc-pro-badge"><?php echo esc_html(__('Advanced options are available in PRO', 'gsheetconnector-ninja-forms')); ?></span>
            </div>
        </div>

        <!-- Feature Tabs -->
        <div class="gsc-pro-tabs pt-20 pb-20 pl-20 pr-20">
            <div>
                <div class="mb-20 fw-600 text-dark pro-roll-sub-header">
                    <?php echo esc_html(__('Role Permissions', 'gsheetconnector-ninja-forms')); ?></div>
                <div class="gsc-pro-grid">
                    <ul>
                        <li><?php esc_html_e('Allow specific WordPress roles', 'gsheetconnector-ninja-forms'); ?></li>
                        <li><?php esc_html_e('Enable/disable integration access', 'gsheetconnector-ninja-forms'); ?></li>
                        <li><?php esc_html_e('Control form feed visibility', 'gsheetconnector-ninja-forms'); ?></li>
                        <li><?php esc_html_e('Restrict settings management', 'gsheetconnector-ninja-forms'); ?></li>
                    </ul>
                </div>
            </div>

            <div>
                <div class="mb-20 fw-600 text-dark pro-roll-sub-header">
                    <?php echo esc_html(__('Security Control', 'gsheetconnector-ninja-forms')); ?></div>
                <div class="gsc-pro-grid">
                    <ul>
                        <li><?php esc_html_e('Prevent unauthorized changes', 'gsheetconnector-ninja-forms'); ?></li>
                        <li><?php esc_html_e('Secure Google Sheet credentials', 'gsheetconnector-ninja-forms'); ?>
                        </li>
                        <li><?php esc_html_e('Role-based configuration control', 'gsheetconnector-ninja-forms'); ?>
                        </li>
                        <li><?php esc_html_e('Protect integration settings', 'gsheetconnector-ninja-forms'); ?></li>
                    </ul>
                </div>
            </div>

            <div>
                <div class="mb-20 fw-600 text-dark pro-roll-sub-header">
                    <?php echo esc_html(__('Management Benefits', 'gsheetconnector-ninja-forms')); ?></div>
                <div class="gsc-pro-grid">
                    <ul>
                        <li><?php esc_html_e('Grant access to trusted editors', 'gsheetconnector-ninja-forms'); ?>
                        </li>
                        <li><?php esc_html_e('Hide settings from subscribers', 'gsheetconnector-ninja-forms'); ?></li>
                        <li><?php esc_html_e('Team-based permission structure', 'gsheetconnector-ninja-forms'); ?>
                        </li>
                        <li><?php esc_html_e('Improved dashboard security', 'gsheetconnector-ninja-forms'); ?></li>
                    </ul>
                </div>
            </div>

            <div>
                <div class="mb-20 fw-600 text-dark pro-roll-sub-header">
                    <?php echo esc_html(__('Audit & Monitoring', 'gsheetconnector-ninja-forms')); ?></div>
                <div class="gsc-pro-grid">
                    <ul>
                        <li><?php esc_html_e('Track role-based changes', 'gsheetconnector-ninja-forms'); ?></li>
                        <li><?php esc_html_e('Monitor integration access', 'gsheetconnector-ninja-forms'); ?></li>
                        <li><?php esc_html_e('Review permission updates', 'gsheetconnector-ninja-forms'); ?></li>
                        <li><?php esc_html_e('Maintain admin accountability', 'gsheetconnector-ninja-forms'); ?></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="gsc-pro-footer text-center">
            <a href="https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro" target="_blank"
                class="btn btn-primary text-decoration-none link-hover-white">
                <?php echo esc_html(__('Upgrade to Unlock', 'gsheetconnector-ninja-forms')); ?>
            </a>
        </div>

    </div>
    <!--End Pro Setting(Roll Permissions)-->

    <div class="nf-role-settings" id="gsc-googlesheet">
        <div class="wrap w-100 m-0">
            <div class="inner-wrap w-100 bg-white p-40 blur-pro-feature">
                <div class="heading mt-0"><?php echo esc_html__('Access Management', 'gsheetconnector-ninja-forms'); ?></div>
                <p><?php echo esc_html__('Control which user roles are allowed to access and manage the Google Sheets integration for your forms.', 'gsheetconnector-ninja-forms'); ?></p>

                <div class="nf_ninja-card">
                    <div class="gsc-access-wrapper mt-30">
                        <div class="gsc-access-box bg-white pt-15 pb-15 pl-15 pr-15">
                            <div class="para-heading fw-600 mb-20"><?php echo esc_html__('Plugin Access Control', 'gsheetconnector-ninja-forms'); ?></div>
                            <p><?php echo esc_html__('Control who can access and manage the GSheetConnector plugin settings.', 'gsheetconnector-ninja-forms'); ?></p>
                            <div class="gsc-role-card mb-10">
                                <div class="custom-check d-flex justify-between alien-center">
                                    <label class="role-label gsc-switch"><?php echo esc_html__('Administrator', 'gsheetconnector-ninja-forms'); ?></label>
                                    <input type="checkbox" class="check-toggle" disabled="disabled" checked="checked" />
                                    <label class="button-toggle"></label>
                                </div>
                            </div>

                            <?php foreach ($participating_roles as $role => $display_name) : ?>
                                <?php
                                if ($role === 'administrator') {
                                    continue;
                                }
                                $checked = (!empty($roles) && is_array($roles) && in_array(esc_attr($role), $roles)) ? 'checked="checked"' : '';
                                ?>
                                <div class="gsc-role-card mb-10">
                                    <div class="custom-check d-flex justify-between alien-center">
                                        <label class="role-label gsc-switch"><?php echo esc_html($display_name); ?></label>
                                        <input type="checkbox" class="check-toggle"
                                            name="<?php echo esc_attr($role); ?>[]"
                                            value="<?php echo esc_attr($role); ?>"
                                            <?php echo $checked; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $checked only ever holds the hardcoded literal 'checked="checked"' or '' from line 144, not user input. 
                                            ?> />
                                        <label class="button-toggle"></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="gsc-access-info">
                            <div class="para-heading fw-600 mb-20"><?php echo esc_html__('Permission Guidelines', 'gsheetconnector-ninja-forms'); ?></div>
                            <ul>
                                <li><?php echo esc_html__('Control which user roles can access the GSheetConnector plugin', 'gsheetconnector-ninja-forms'); ?></li>
                                <li><?php echo esc_html__('Allow selected users to manage Google Sheets integration settings', 'gsheetconnector-ninja-forms'); ?></li>
                                <li><?php echo esc_html__('Restrict access to sensitive features like feeds, logs, and configurations', 'gsheetconnector-ninja-forms'); ?></li>
                                <li><?php echo esc_html__('Only enabled roles will see the plugin menu in the admin panel', 'gsheetconnector-ninja-forms'); ?></li>
                                <li><?php echo esc_html__('Recommended: Allow only Administrators and trusted Editors', 'gsheetconnector-ninja-forms'); ?></li>
                            </ul>
                        </div>
                    </div>



                    <div class="gsc-access-wrapper mt-30">
                        <div class="gsc-access-box bg-white pt-15 pb-15 pl-15 pr-15">
                            <div class="para-heading fw-600 mb-20"><?php echo esc_html__('Form Permissions', 'gsheetconnector-ninja-forms'); ?></div>
                            <p><?php echo esc_html__('ontrol who can configure Google Sheets integration inside Ninja.', 'gsheetconnector-ninja-forms'); ?></p>
                            <div class="gsc-role-card mb-10">
                                <div class="custom-check d-flex justify-between alien-center">
                                    <label class="role-label gsc-switch"><?php echo esc_html__('Administrator', 'gsheetconnector-ninja-forms'); ?></label>
                                    <input type="checkbox" class="check-toggle" disabled="disabled" checked="checked" />
                                    <label class="button-toggle"></label>
                                </div>
                            </div>

                            <?php foreach ($participating_roles as $role => $display_name) : ?>
                                <?php
                                if ($role === 'administrator') {
                                    continue;
                                }
                                $checked = (!empty($roles) && is_array($roles) && in_array(esc_attr($role), $roles)) ? 'checked="checked"' : '';
                                ?>
                                <div class="gsc-role-card mb-10">
                                    <div class="custom-check d-flex justify-between alien-center">
                                        <label class="role-label gsc-switch"><?php echo esc_html($display_name); ?></label>
                                        <input type="checkbox" class="check-toggle"
                                            name="<?php echo esc_attr($role); ?>[]"
                                            value="<?php echo esc_attr($role); ?>"
                                            <?php echo $checked; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $checked only ever holds the hardcoded literal 'checked="checked"' or '' from line 144, not user input. 
                                            ?> />
                                        <label class="button-toggle"></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="gsc-access-info">
                            <div class="para-heading fw-600 mb-20"><?php echo esc_html__('Permission Guidelines', 'gsheetconnector-ninja-forms'); ?></div>
                            <ul>
                                <li><?php echo esc_html__('Control which user roles can access Google Sheet settings inside Ninja Forms', 'gsheetconnector-ninja-forms'); ?></li>
                                <li><?php echo esc_html__('Allow users to configure sheet connections in individual forms', 'gsheetconnector-ninja-forms'); ?></li>
                                <li><?php echo esc_html__('Prevent unauthorized users from modifying form integrations', 'gsheetconnector-ninja-forms'); ?></li>
                                <li><?php echo esc_html__('Recommended: Allow only Administrators and trusted Editors', 'gsheetconnector-ninja-forms'); ?></li>
                            </ul>
                        </div>
                    </div>


                    <div class="select-info text-right mt-30">
                        <input type="submit" class="btn btn-primary button-large" name="nf_settings"
                            value="<?php echo esc_html__('Save Settings', 'gsheetconnector-ninja-forms'); ?>" />
                    </div>
                </div>
            </div>


        </div>
    </div>
</form>