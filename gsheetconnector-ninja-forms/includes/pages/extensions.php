<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
?>
<div class="wrap w-100 m-0">
    <div class="inner-wrap w-100 bg-white p-40">
        <div class="gsc-about-wrapper animated-border">
            <input type="hidden" name="gs-ajax-nonce" id="gs-ajax-nonce"
                value="<?php echo esc_attr(wp_create_nonce('gs-ajax-nonce')); ?>" />

            <!-- Hero -->
            <div class="gsc-about-hero">

                <div class="gsc-about-hero-content">

                    <div class="about-us-heading fw-500 mb-20 text-center">
                        <?php esc_html_e('About GSheetConnector', 'gsheetconnector-ninja-forms'); ?>
                    </div>
                    <div class="about-us-subheading fw-300 mb-20 text-center">
                        <?php esc_html_e('Built by Experts. Trusted by Thousands.', 'gsheetconnector-ninja-forms'); ?>
                    </div>


                    <p class="text-dark about-us-descs text-center mb-10">
                        <?php esc_html_e(
                            'Led by experienced developers and powered by a passionate team, GSheetConnector helps WordPress users automate their workflows with confidence.',
                            'gsheetconnector-ninja-forms'
                        ); ?>
                    </p>

                    <p class="text-dark about-us-descs text-center">
                        <?php esc_html_e(
                            'We focus on building tools that are fast, secure, and easy to use - so you can spend less time managing data.',
                            'gsheetconnector-ninja-forms'
                        ); ?>
                    </p>

                    <div class="gsc-connection-box flex-wrap gsc-founder-card mt-30 justify-between">
                        <div class="d-flex flex-wrap align-center gap-10 responsive-center">
                            <div>
                                <img src="<?php echo esc_url(NINJAFORMS_GOOGLESHEET_URL); ?>/assets/img/founder.webp" alt="Founder">
                            </div>
                            <div class="responsive-text-center">
                                <p class="gsc-name">
                                    <?php echo esc_html__('WordPress Plugin Founder', 'gsheetconnector-ninja-forms'); ?>
                                </p>
                                <p class="gsc-role mb-0">
                                    <?php echo esc_html__('Product Lead & Visionary', 'gsheetconnector-ninja-forms'); ?>
                                </p>
                            </div>

                        </div>
                        <div class="gs-signature text-center">
                            <div class="founder-heading fw-500 text-dark mb-10">
                                <?php echo esc_html__('Abdullah Kaludi', 'gsheetconnector-ninja-forms'); ?>
                            </div>
                            <span>
                                <?php echo esc_html__('Founder & Lead Developer', 'gsheetconnector-ninja-forms'); ?>
                            </span>
                        </div>

                    </div>
                    <div class="heading mb-20 mt-20 text-center">
                        <?php esc_html_e('Meet Our Core Team', 'gsheetconnector-ninja-forms'); ?>
                    </div>
                    <!-- Team Avatars -->
                    <div class="gsc-about-avatars d-flex align-center flex-wrap gap-10 mt-30 justify-center">

                        <?php
                        $asset_url = NINJAFORMS_GOOGLESHEET_URL . '/assets/img/';
                        ?>
                        <img src="<?php echo esc_url($asset_url . 'avatar-2.jfif'); ?>" alt="">
                        <img src="<?php echo esc_url($asset_url . 'avatar-3.png'); ?>" alt="">
                        <img src="<?php echo esc_url($asset_url . 'avatar-5.jfif'); ?>" alt="">
                        <img src="<?php echo esc_url($asset_url . 'avatar-4.png'); ?>" alt="">
                        <img src="<?php echo esc_url($asset_url . 'avatar.jpeg'); ?>" alt="">
                    </div>

                </div>

            </div>


            <!-- Content -->
            <div class="gsc-about-content">


                <!-- Left -->
                <div class="gsc-about-text">

                    <div class="heading mt-0">
                        <?php esc_html_e('Our Mission', 'gsheetconnector-ninja-forms'); ?>
                    </div>

                    <p>
                        <?php esc_html_e(
                            'Our mission is to make form automation simple, secure, and reliable for every WordPress user - from beginners to enterprise teams.',
                            'gsheetconnector-ninja-forms'
                        ); ?>
                    </p>


                    <ul class="gsc-about-list">

                        <li><?php esc_html_e('Fast & Reliable Data Sync', 'gsheetconnector-ninja-forms'); ?></li>

                        <li><?php esc_html_e('100% Secure Authentication', 'gsheetconnector-ninja-forms'); ?></li>

                        <li><?php esc_html_e('Real-Time Google Sheets Updates', 'gsheetconnector-ninja-forms'); ?></li>

                        <li><?php esc_html_e('Easy Setup & Configuration', 'gsheetconnector-ninja-forms'); ?></li>

                        <li><?php esc_html_e('Dedicated Support Team', 'gsheetconnector-ninja-forms'); ?></li>


                    </ul>

                </div>


                <!-- Right -->
                <div class="gsc-about-stats">

                    <div class="gsc-stat-card d-flex align-center justify-center bg-white text-white">
                        <div class="gsc-stat-card-header">
                            <span class="nf-pro-counter" data-count="11">0</span><span class="suffix">+</span>
                        </div>
                        <span><?php esc_html_e('Forms Successfully Synced', 'gsheetconnector-ninja-forms'); ?></span>
                    </div>

                    <div class="gsc-stat-card d-flex align-center justify-center bg-white text-white">
                        <div class="gsc-stat-card-header">
                            <span class="nf-pro-counter" data-count="50">0</span><span class="suffix">+</span>
                        </div>
                        <span><?php esc_html_e('Trusted by Active Users', 'gsheetconnector-ninja-forms'); ?></span>
                    </div>

                    <div class="gsc-stat-card d-flex align-center justify-center bg-white text-white">
                        <div class="gsc-stat-card-header">
                            <span class="nf-pro-counter" data-count="99.9">0</span><span class="suffix">%</span>
                        </div>
                        <span><?php esc_html_e('Reliable Uptime', 'gsheetconnector-ninja-forms'); ?></span>
                    </div>

                    <div class="gsc-stat-card d-flex align-center justify-center bg-white text-white">
                        <div class="d-flex align-center gap-10">
                            <div class="gsc-stat-card-header">
                                <span class="nf-pro-counter" data-count="24">0</span><span class="suffix">/7</span>
                            </div>
                        </div>
                        <span><?php esc_html_e('Dedicated Support', 'gsheetconnector-ninja-forms'); ?></span>
                    </div>

                </div>

            </div>


        </div>
        <!-- End About Section -->


        <div class="extensions-list">
            <div class="heading mt-40"><?php esc_html_e('Add-ons & Extensions', 'gsheetconnector-ninja-forms'); ?></div>
            <p><?php echo esc_html__('Extend your Google Sheets integration with powerful add-ons. Install, manage, and activate extensions to unlock advanced features.', 'gsheetconnector-ninja-forms'); ?></p>

            <?php
            $all_plugins = get_plugins();
            $active_theme = wp_get_theme();
            $plugins = NJForm_gs_Connector_Utility::get_addon_extensions();
            ?>
            <div class="gsc-market-header">
                <div class="extensions-sub-heading"><?php echo esc_html(__('Installed & Active Plugins', 'gsheetconnector-ninja-forms')); ?></div>
                <div>
                    <p><?php echo esc_html(__('View and manage all currently installed and active connectors on your site. You can activate, deactivate, or upgrade plugins from here.', 'gsheetconnector-ninja-forms')); ?>
                    </p>
                </div>
            </div>
            <div class="gsheetconnector-addons-list gsc-ext-grid">

                <?php

                foreach ($plugins as $plugin => $details) {

                    // =========================
                    // MAIN PLUGIN CHECK
                    // =========================
                    $is_main_installed = false;
                    $is_main_active    = false;

                    if (!empty($details['mainPlugin']) && is_array($details['mainPlugin'])) {
                        foreach ($details['mainPlugin'] as $main_plugin) {
                            if (isset($all_plugins[$main_plugin])) {
                                $is_main_installed = true;
                            }
                            if (is_plugin_active($main_plugin)) {
                                $is_main_active = true;
                                break;
                            }
                        }
                    } elseif (!empty($details['mainPlugin']) && is_string($details['mainPlugin'])) {

                        $is_main_installed = isset($all_plugins[$details['mainPlugin']]);

                        $is_main_active = is_plugin_active($details['mainPlugin']);
                    }

                    $is_pro_installed = !empty($details['pro_plugin_active']) &&
                        isset($all_plugins[$details['pro_plugin_active']]);

                    $is_pro_active = !empty($details['pro_plugin_active']) &&
                        is_plugin_active($details['pro_plugin_active']);

                    $is_free_installed = !empty($details['connector']) &&
                        isset($all_plugins[$details['connector']]);

                    $is_free_active = !empty($details['connector']) &&
                        is_plugin_active($details['connector']);
                    // =========================
                    // MAIN INACTIVE → HIDE
                    // =========================
                    if (!$is_main_active) {
                        continue;
                    }
                    // =========================
                    // PRO ACTIVE → SHOW PRO
                    // =========================
                    if ($is_pro_active) {
                ?>
                        <div class="gsheetconnector-list-item gsc-ext-card">
                            <div class="addon-item-header">
                                <div class="extension-bg-color-set">
                                    <div class="gsc-ext-top">
                                        <a href="<?php echo esc_url($details['link']); ?>" target="_blank">
                                            <img src="<?php echo esc_url($details['img']); ?>"
                                                alt="<?php echo esc_attr($details['name']); ?>"
                                                class="gsc-ext-icon">
                                        </a>
                                        <div class="plugin-premium gsc-ext-badge pro-badge">
                                            <?php esc_html_e('Pro', 'gsheetconnector-ninja-forms'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="addon-item-header-meta">
                                    <div class="addon-item-meta-title">

                                        <a href="<?php echo esc_url($details['link']); ?>"
                                            target="_blank"
                                            class="addon-link">
                                            <?php echo esc_html($details['name']); ?>
                                        </a>
                                    </div>
                                    <div class="addon-item-header-meta-excerpt">
                                        <?php echo esc_html($details['text']); ?>
                                    </div>
                                    <div class="addon-item-footer">
                                        <div class="activated gsc-ext-actions">
                                            <a href="<?php echo esc_url($details['docs']); ?>"
                                                target="_blank"
                                                class="addon-link btn btn-default link-hover-white">
                                                <?php esc_attr_e('View Docs', 'gsheetconnector-ninja-forms'); ?>
                                            </a>
                                            <a href="javascript:void(0);"
                                                class="btn deactivate-btn nf-deactivate-plugin"
                                                data-download="<?php echo esc_attr($details['connector-pro']); ?>"
                                                data-plugin="<?php echo esc_attr($details['connector-pro']); ?>">
                                                <?php esc_html_e('Deactivate', 'gsheetconnector-ninja-forms'); ?>
                                            </a>
                                            <span class="loading-sign-deactive mt-25"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php

                        // =========================
                        // FREE ACTIVE → SHOW FREE
                        // =========================
                    } elseif ($is_free_active) {
                    ?>
                        <div class="gsheetconnector-list-item gsc-ext-card">
                            <div class="addon-item-header">
                                <div class="extension-bg-color-set">
                                    <div class="gsc-ext-top">
                                        <a href="<?php echo esc_url($details['buyLink']); ?>" target="_blank">
                                            <img src="<?php echo esc_url($details['img']); ?>"
                                                alt="<?php echo esc_attr($details['name']); ?>"
                                                class="gsc-ext-icon">
                                        </a>
                                        <div class="plugin-free gsc-ext-badge free-green">
                                            <?php esc_html_e('Free', 'gsheetconnector-ninja-forms'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="addon-item-header-meta">
                                    <div class="addon-item-meta-title">
                                        <a href="<?php echo esc_url($details['buyLink']); ?>"
                                            target="_blank"
                                            class="addon-link">
                                            <?php echo esc_html($details['name']); ?>
                                        </a>
                                    </div>
                                    <div class="addon-item-header-meta-excerpt">
                                        <?php echo esc_html($details['text']); ?>
                                    </div>
                                    <div class="activated gsc-ext-actions">

                                        <a href="<?php echo esc_url($details['docs']); ?>"
                                            target="_blank"
                                            class="addon-link btn btn-default link-hover-white">

                                            <?php esc_attr_e('View Docs', 'gsheetconnector-ninja-forms'); ?>

                                        </a>

                                        <a href="javascript:void(0);"
                                            class="btn deactivate-btn nf-deactivate-plugin"
                                            data-download="<?php echo esc_attr($details['connector']); ?>"
                                            data-plugin="<?php echo esc_attr($details['connector']); ?>">
                                            <?php esc_html_e('Deactivate', 'gsheetconnector-ninja-forms'); ?>
                                        </a>
                                        <span class="loading-sign-deactive mt-25"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>

            </div>
            <!-- Second section -->
            <div class="gsc-market-header">
                <div class="extensions-sub-heading">
                    <?php echo esc_html(__('Recommended Plugins', 'gsheetconnector-ninja-forms')); ?>
                </div>
                <div>
                    <p><?php echo esc_html(__('Discover recommended plugins that enhance your workflow and help you get more value from your Google Sheets integration.', 'gsheetconnector-ninja-forms')); ?></p>

                </div>
            </div>
            <div class="gsheetconnector-addons-list gsc-ext-grid">
                <?php foreach ($plugins as $plugin => $data):
                    $is_main_active = false;

                    if (!empty($data['mainPlugin'])) {
                        $main_plugins = is_array($data['mainPlugin']) ? $data['mainPlugin'] : [$data['mainPlugin']];
                        foreach ($main_plugins as $main_plugin) {
                            if (is_plugin_active($main_plugin)) {
                                $is_main_active = true;
                                break;
                            }
                        }
                    }
                    $active_theme      = wp_get_theme();
                    $active_theme_slug = $active_theme->get_stylesheet();
                    $is_theme_match    = ($active_theme_slug === $data['theme']);
                    $is_pro_installed  = !empty($data['pro_plugin_active']) && isset($all_plugins[$data['pro_plugin_active']]);
                    $is_pro_active     = !empty($data['pro_plugin_active']) && is_plugin_active($data['pro_plugin_active']);
                    $is_free_installed = !empty($data['connector']) && isset($all_plugins[$data['connector']]);
                    $is_free_active    = !empty($data['connector']) && is_plugin_active($data['connector']);
                    if (!$is_theme_match) {
                        if (!$is_main_active)                        continue;
                        if ($is_pro_active)                          continue;
                        if ($is_free_active && !$is_pro_installed)   continue;
                    }
                ?>
                    <?php if ($is_theme_match): ?>
                        <div class="gsheetconnector-list-item gsc-ext-card">
                            <div class="addon-item-header">
                                <div class="extension-bg-color-set">
                                    <div class="gsc-ext-top">
                                        <a href="<?php echo esc_url($data['buyLink']); ?>" target="_blank">
                                            <img src="<?php echo esc_url($data['img']); ?>" class="gsc-ext-icon"
                                                alt="<?php echo esc_attr($data['name']); ?>">
                                        </a>

                                    </div>
                                </div>
                                <div class="addon-item-header-meta">
                                    <div class="plugin-premium gsc-ext-badge pro-badge">
                                        <?php echo esc_html(__('Pro', 'gsheetconnector-ninja-forms')); ?>
                                    </div>
                                    <div class="addon-item-meta-title">
                                        <a href="<?php echo esc_url($data['buyLink']); ?>" target="_blank" class="addon-link">
                                            <?php echo esc_html($data['name']); ?>
                                        </a>
                                    </div>
                                    <div class="addon-item-header-meta-excerpt">
                                        <?php echo esc_html($data['text']); ?>
                                    </div>
                                    <div class="addon-item-footer">
                                        <div class="button-bar">
                                            <div class="addon-item-meta-title gsc-ext-actions">
                                                <a href="<?php echo esc_url($data['link']); ?>" target="_blank" class="btn activate">
                                                    <?php echo esc_html($data['button']); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="gsheetconnector-list-item gsc-ext-card">
                            <div class="addon-item-header">
                                <div class="extension-bg-color-set">
                                    <div class="gsc-ext-top">
                                        <a href="<?php echo esc_url($data['url']); ?>" target="_blank">
                                            <img src="<?php echo esc_url($data['img']); ?>"
                                                alt="<?php echo esc_attr($data['name']); ?>"
                                                class="gsc-ext-icon">
                                        </a>
                                        <?php if ($is_pro_installed && !$is_pro_active): ?>
                                            <div class="plugin-premium gsc-ext-badge pro-badge">
                                                <?php echo esc_html(__('Pro', 'gsheetconnector-ninja-forms')); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="plugin-free gsc-ext-badge free-green">
                                                <?php echo esc_html(__('Free', 'gsheetconnector-ninja-forms')); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="addon-item-header-meta">
                                    <div class="addon-item-meta-title">
                                        <a href="<?php echo esc_url($data['url']); ?>" target="_blank" class="addon-link">
                                            <?php echo esc_html($data['name']); ?>
                                        </a>
                                    </div>
                                    <div class="addon-item-header-meta-excerpt">
                                        <?php echo esc_html($data['text']); ?>
                                    </div>
                                    <div class="addon-item-footer">
                                        <div class="button-bar gsc-ext-actions">

                                            <a href="<?php echo esc_url($data['docs']); ?>" target="_blank"
                                                class="addon-link btn btn-default link-hover-white">
                                                <?php esc_html_e('View Docs', 'gsheetconnector-ninja-forms'); ?>
                                            </a>
                                            <?php if ($is_pro_installed && !$is_pro_active): ?>
                                                <button class="nf-activate-plugin-btn btn activate button-pro"
                                                    data-plugin="<?php echo esc_attr($data['pro_plugin_active']); ?>">
                                                    <?php esc_html_e('Activate', 'gsheetconnector-ninja-forms'); ?>
                                                </button>
                                                <span class="loading-sign-active mt-25"></span>
                                            <?php elseif ($is_free_installed && !$is_free_active): ?>
                                                <button class="nf-activate-plugin-btn btn activate button-free"
                                                    data-plugin="<?php echo esc_attr($data['connector']); ?>">
                                                    <?php esc_html_e('Activate', 'gsheetconnector-ninja-forms'); ?>
                                                </button>
                                                <span class="loading-sign-active mt-25"></span>
                                            <?php else: ?>
                                                <button class="nf-install-plugin-btn btn btn-default"
                                                    data-download="<?php echo esc_url($data['downloadLink']); ?>"
                                                    data-plugin="<?php echo esc_attr($plugin); ?>">
                                                    <?php echo esc_html($data['button']); ?>
                                                </button>
                                                <span class="loading-sign-install mt-25"></span>
                                                <button class="nf-activate-plugin-btn btn activate button-free"
                                                    data-plugin="<?php echo esc_attr($data['connector']); ?>"
                                                    style="display:none;">
                                                    <?php esc_html_e('Activate', 'gsheetconnector-ninja-forms'); ?>
                                                </button>
                                                <span class="loading-sign-active mt-25"></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>


            <!-- 3th section-->
            <!--NEW-->

            <div class="gsc-marketplace">
                <div class="gsc-market-header">
                    <div>
                        <div class="extensions-sub-heading"><?php echo esc_html(__('Explore More Plugins', 'gsheetconnector-ninja-forms')); ?></div>
                        <div>
                            <p class="mt-15 mb-0"><?php echo esc_html(__('Discover the right connector for your workflow and automate data syncing.', 'gsheetconnector-ninja-forms')); ?>
                            </p>
                        </div>
                    </div>
                    <div class="gsc-market-tabs d-flex flex-wrap gap-10 pt-15 pb-15 pl-15 pr-15">
                        <button class="gscnff-market-tab active fw-600" data-filter="all"><?php echo esc_html(__('All', 'gsheetconnector-ninja-forms')); ?></button>
                        <button class="gscnff-market-tab fw-600" data-filter="forms"><?php echo esc_html(__('Forms', 'gsheetconnector-ninja-forms')); ?></button>
                        <button class="gscnff-market-tab fw-600" data-filter="builders"><?php echo esc_html(__('Page Builders', 'gsheetconnector-ninja-forms')); ?></button>
                        <button class="gscnff-market-tab fw-600" data-filter="shop"><?php echo esc_html(__('eCommerce', 'gsheetconnector-ninja-forms')); ?></button>
                    </div>
                </div>
                <div class="gsheetconnector-addons-list gsc-ext-grid">
                    <?php foreach ($plugins as $plugin => $data):

                        // ---------------------------------
                        // MAIN PLUGIN ACTIVE CHECK
                        // ---------------------------------
                        $is_main_active = false;

                        if (!empty($data['mainPlugin'])) {
                            if (is_array($data['mainPlugin'])) {
                                foreach ($data['mainPlugin'] as $main_plugin) {
                                    if (is_plugin_active($main_plugin)) {
                                        $is_main_active = true;
                                        break;
                                    }
                                }
                            } elseif (is_string($data['mainPlugin'])) {
                                $is_main_active = is_plugin_active($data['mainPlugin']);
                            }
                        }

                        // ---------------------------------
                        // FREE / PRO INSTALLED CHECK
                        // ---------------------------------
                        $is_free_installed = !empty($data['connector'])
                            && isset($all_plugins[$data['connector']]);

                        $is_pro_installed  = !empty($data['pro_plugin_active'])
                            && isset($all_plugins[$data['pro_plugin_active']]);

                        /**
                         *  HIDE ADDON ONLY WHEN:
                         * - Main plugin not ACTIVE
                         * - AND Free OR Pro plugin is INSTALLED
                         */
                        if ($is_main_active) {
                            continue;
                        }

                        // ---------------------------------
                        // CATEGORY (FILTERING SAFE)
                        // ---------------------------------
                        $category = 'forms';

                        if (in_array($data['theme'], ['Avada', 'Divi', 'elements'], true)) {
                            $category = 'builders';
                        } elseif (in_array($data['theme'], ['woocommerce', 'easy-digital-downloads'], true)) {
                            $category = 'shop';
                        }
                    ?>

                        <div class="gsheetconnector-list-item gsc-ext-card gsc-market-item <?php echo esc_attr($category); ?>">
                            <div class="extension-bg-color-set">

                                <div class="gsc-ext-top">




                                    <a href="<?php echo esc_url($data['link']); ?>" target="_blank">
                                        <img src="<?php echo esc_url($data['img']); ?>" class="gsc-ext-icon" alt="">
                                    </a>

                                    <?php if (!empty($data['freeLink'])): ?>
                                        <span class="plugin-free gsc-ext-badge free-green"><?php echo esc_html(__('Free', 'gsheetconnector-ninja-forms')); ?></span>



                                    <?php else: ?>
                                        <span class="plugin-premium gsc-ext-badge pro-badge"><?php echo esc_html(__('Pro', 'gsheetconnector-ninja-forms')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="addon-item-header-meta">

                                <div class="addon-item-meta-title">
                                    <a href="<?php echo esc_url($data['link']); ?>"
                                        target="_blank"
                                        class="addon-link">
                                        <?php echo esc_html($data['name']); ?>
                                    </a>
                                </div>

                                <div class="addon-item-header-meta-excerpt">
                                    <?php echo esc_html($data['text']); ?>
                                </div>

                                <div class="addon-item-footer">
                                    <div class="button-bar gsc-ext-actions">



                                        <a href="<?php echo esc_url($data['docs']); ?>" target="_blank"
                                            class="btn btn-default link-hover-white"><?php echo esc_html(__('View Docs', 'gsheetconnector-ninja-forms')); ?>
                                        </a>


                                        <?php if (!empty($data['demo'])): ?>

                                            <a href="<?php echo esc_url($data['demo']); ?>" target="_blank"
                                                class="btn buy text-decoration-none"><?php echo esc_html(__('View Demo', 'gsheetconnector-ninja-forms')); ?>
                                            </a>

                                        <?php endif; ?>

                                    </div>
                                </div>

                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
            <!--NEW-->
        </div>

        <div id="nf-confirm-dective-popup" class="nf-popup-overlay d-none">
            <div class="nj-popup text-center">

                <div class="gsc-modal-icon">
                    <svg width="30px" height="30px" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.2202 21.25H5.78015C5.14217 21.2775 4.50834 21.1347 3.94373 20.8364C3.37911 20.5381 2.90402 20.095 2.56714 19.5526C2.23026 19.0101 2.04372 18.3877 2.02667 17.7494C2.00963 17.111 2.1627 16.4797 2.47015 15.92L8.69013 5.10999C9.03495 4.54078 9.52077 4.07013 10.1006 3.74347C10.6804 3.41681 11.3346 3.24518 12.0001 3.24518C12.6656 3.24518 13.3199 3.41681 13.8997 3.74347C14.4795 4.07013 14.9654 4.54078 15.3102 5.10999L21.5302 15.92C21.8376 16.4797 21.9907 17.111 21.9736 17.7494C21.9566 18.3877 21.7701 19.0101 21.4332 19.5526C21.0963 20.095 20.6211 20.5381 20.0565 20.8364C19.4919 21.1347 18.8581 21.2775 18.2202 21.25V21.25Z" stroke="#d97706" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10.8809 17.15C10.8809 17.0021 10.9102 16.8556 10.9671 16.7191C11.024 16.5825 11.1074 16.4586 11.2125 16.3545C11.3175 16.2504 11.4422 16.1681 11.5792 16.1124C11.7163 16.0567 11.8629 16.0287 12.0109 16.03C12.2291 16.034 12.4413 16.1021 12.621 16.226C12.8006 16.3499 12.9398 16.5241 13.0211 16.7266C13.1023 16.9292 13.122 17.1512 13.0778 17.3649C13.0335 17.5786 12.9272 17.7745 12.7722 17.9282C12.6172 18.0818 12.4203 18.1863 12.2062 18.2287C11.9921 18.2711 11.7703 18.2494 11.5685 18.1663C11.3666 18.0833 11.1938 17.9426 11.0715 17.7618C10.9492 17.5811 10.8829 17.3683 10.8809 17.15ZM11.2409 14.42L11.1009 9.20001C11.0876 9.07453 11.1008 8.94766 11.1398 8.82764C11.1787 8.70761 11.2424 8.5971 11.3268 8.5033C11.4112 8.40949 11.5144 8.33449 11.6296 8.28314C11.7449 8.2318 11.8697 8.20526 11.9959 8.20526C12.1221 8.20526 12.2469 8.2318 12.3621 8.28314C12.4774 8.33449 12.5805 8.40949 12.6649 8.5033C12.7493 8.5971 12.8131 8.70761 12.852 8.82764C12.8909 8.94766 12.9042 9.07453 12.8909 9.20001L12.7609 14.42C12.7609 14.6215 12.6808 14.8149 12.5383 14.9574C12.3957 15.0999 12.2024 15.18 12.0009 15.18C11.7993 15.18 11.606 15.0999 11.4635 14.9574C11.321 14.8149 11.2409 14.6215 11.2409 14.42Z" fill="#d97706"></path>
                    </svg>
                </div>

                <div class="gsc-modal-title">
                    <?php echo esc_html__('Deactivate Plugin', 'gsheetconnector-ninja-forms'); ?>
                </div>

                <p class="gsc-modal-text">
                    <?php echo esc_html__('Are you sure you want to deactivate this plugin?', 'gsheetconnector-ninja-forms'); ?>
                </p>

                <div class="popup-actions d-flex justify-center gap-10">

                    <button type="button"
                        class="btn deactivate-btn"
                        id="nf-dective-popup-cancel">
                        <?php echo esc_html__('Cancel', 'gsheetconnector-ninja-forms'); ?>
                    </button>

                    <button type="button"
                        class="btn btn-primary"
                        id="nf-deactive-popup-confirm">
                        <?php echo esc_html__('Deactivate', 'gsheetconnector-ninja-forms'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>