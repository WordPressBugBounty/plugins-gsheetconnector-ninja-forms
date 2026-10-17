<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit();
}
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$gsc_njf_pro_forms = Ninja_Forms()->form()->get_forms();// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

?>

<div class="wrap w-100 m-0">
    <div class="inner-wrap w-100 bg-white p-40">
        <div class="heading mt-0"><?php echo esc_html__( 'Ninja Forms - Google Sheet Settings', 'gsheetconnector-ninja-forms' ); ?></div>
        <p><?php echo esc_html__( 'Select a form to configure the Ninja Forms GSheetConnector integration.', 'gsheetconnector-ninja-forms' ); ?></p>

        <div class="card-wp dropdownoption-ninja border-select-box row shadow-box mt-40 p-30">
            <div class="col-6">
                <div class="form-group">
                    <input type="hidden" name="gscwpfp_redirect_auth" id="gscwpfp_redirect_auth" value="">
                    <label for="gsc_ff_dropdown_option">
                        Select Ninja Form
                        <span class="tooltip" data-tooltip="Choose the form whose submissions you want to send automatically to Google Sheets." data-tooltip-pos="right" data-tooltip-length="medium">
                            <i class="fa-solid fa-circle-question help-icon"></i>
                        </span>
                    </label>

                    <select id="ninja_select" class="gsc-select" name="ninja">
                        <option value=""><?php echo esc_html__( 'Select Form', 'gsheetconnector-ninja-forms' ); ?></option>

                        <?php foreach ( $gsc_njf_pro_forms as $form ) :// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
                            $form_id    = $form->get_id();// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
                            $form_title = $form->get_setting( 'title' );// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
                            $form_link  = site_url( "wp-admin/admin.php?page=ninja-forms&form_id={$form_id}" );// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
                        ?>
                            <option value="<?php echo esc_url( $form_link ); ?>" data-form-id="<?php echo esc_attr( $form_id ); ?>">
                                <?php echo esc_html( $form_title ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="int-meth-btn-ninja ml-15 mt-25">
                    <span class="loading-sign-select">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </div>
            </div>
        </div>
    </div>
</div>