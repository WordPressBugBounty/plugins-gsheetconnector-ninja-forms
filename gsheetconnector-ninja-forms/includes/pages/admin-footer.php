<?php
// Custom footer text with review link
function gsheetconnector_admin_footer_text() {
    $review_url  = 'https://wordpress.org/support/plugin/gsheetconnector-ninja-forms/reviews/';
    $plugin_name = 'GSheetConnector For Ninja Forms';

    $text = sprintf(
        /* translators: %1$s: plugin name, %2$s: link to reviews */
        esc_html__(
            'Enjoy using %1$s? Check out our reviews or leave your own on %2$s.',
            'gsheetconnector-ninja-forms'
        ),
        '<strong>' . esc_html( $plugin_name ) . '</strong>',
        '<a href="' . esc_url( $review_url ) . '" target="_blank" rel="noopener">' . esc_html__( 'WordPress.org', 'gsheetconnector-ninja-forms' ) . '</a>'
    );

    echo wp_kses_post( '<span id="footer-left" class="alignleft">' . $text . '</span>' );
}
add_filter( 'admin_footer_text', 'gsheetconnector_admin_footer_text' );
?>

<div class="gsheetconnect-footer-promotion">
  <p><?php echo esc_html__( 'Made with ♥ by the GSheetConnector Team', 'gsheetconnector-ninja-forms' ); ?></p>

  <ul class="gsheetconnect-footer-promotion-links">
    <li><a href="https://www.gsheetconnector.com/support" target="_blank" rel="noopener"><?php esc_html_e( 'Support', 'gsheetconnector-ninja-forms' ); ?></a></li>
    <li><a href="https://www.gsheetconnector.com/docs/ninja-forms-gsheetconnector" target="_blank" rel="noopener"><?php esc_html_e( 'Docs', 'gsheetconnector-ninja-forms' ); ?></a></li>
    <li><a href="https://profiles.wordpress.org/westerndeal/#content-plugins" target="_blank" rel="noopener"><?php esc_html_e( 'Free Plugins', 'gsheetconnector-ninja-forms' ); ?></a></li>
  </ul>

  <ul class="gsheetconnect-footer-promotion-social">
    <li><a href="https://www.facebook.com/gsheetconnectorofficial" target="_blank" rel="noopener"><i class="fa-brands fa-square-facebook"></i></a></li>
    <li><a href="https://www.instagram.com/gsheetconnector/" target="_blank" rel="noopener"><i class="fa-brands fa-square-instagram"></i></a></li>
    <li><a href="https://www.linkedin.com/company/gsheetconnector/" target="_blank" rel="noopener"><i class="fa-brands fa-linkedin"></i></a></li>
    <li><a href="https://twitter.com/gsheetconnector?lang=en" target="_blank" rel="noopener"><i class="fa-brands fa-square-x-twitter"></i></a></li>
    <li><a href="https://www.youtube.com/@GSheetConnector" target="_blank" rel="noopener"><i class="fa-brands fa-square-youtube"></i></a></li>
  </ul>
</div>