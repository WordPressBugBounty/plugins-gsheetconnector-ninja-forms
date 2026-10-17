<?php if ( ! defined( 'ABSPATH' ) ) exit;

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
return apply_filters( 'nf_njgs_plugin_settings_groups', array(// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound

    'njgs' => array(
        'id' => 'njgs',
		'label' => __( 'njgs Settings', 'gsheetconnector-ninja-forms' ),
    ),

));
