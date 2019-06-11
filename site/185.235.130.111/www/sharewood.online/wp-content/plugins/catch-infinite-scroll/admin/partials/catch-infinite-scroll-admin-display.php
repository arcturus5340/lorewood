<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       catchplugins.com
 * @since      1.0.0
 *
 * @package    Catch_Infinite_Scroll
 * @subpackage Catch_Infinite_Scroll/admin/partials
 */

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Catch Infinite Scroll', 'catch-infinite-scroll' ); ?></h1>

    <div class="catchp-content-wrapper">
        <div class="catchp_widget_settings">

            <h2 class="nav-tab-wrapper">
                <a class="nav-tab nav-tab-active" id="dashboard-tab" href="#dashboard"><?php esc_html_e( 'Dashboard', 'catch-infinite-scroll' ); ?></a>
                <a class="nav-tab" id="features-tab" href="#features"><?php esc_html_e( 'Features', 'catch-infinite-scroll' ); ?></a>
                <a class="nav-tab" id="premium-extensions-tab" href="#premium-extensions"><?php esc_html_e( 'Compare Table', 'catch-infinite-scroll' ); ?></a>
            </h2>

            <div id="dashboard" class="wpcatchtab  nosave active">

                <?php require_once plugin_dir_path( dirname( __FILE__ ) ) . '/partials/catch-infinite-scroll-display-dashboard.php'; ?>

                <div id="go-premium" class="content-wrapper col-2">

                    <div class="header">
                        <h2><?php esc_html_e( 'Go Premium!', 'catch-infinite-scroll' ); ?></h2>
                    </div> <!-- .Header -->

                    <div class="content">
                        <button type="button" class="button dismiss">
                            <span class="screen-reader-text"><?php esc_html_e( 'Dismiss this item.', 'catch-infinite-scroll' ); ?></span>
                            <span class="dashicons dashicons-no-alt"></span>
                        </button>
                        <ul class="catchp-lists">
                            <li><strong><?php esc_html_e( 'Button Display Option', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Finish Text Color', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Finish Text Font Family', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Finish Text Font Size (for mobile devices)', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Load More Adv Image URL', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Load More Adv Image Alt', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Load More Adv Image Target', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Load More Adv Option', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Load More Background Color', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Load More Border Color', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Load More Border Radius', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Load More Border Width', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Load More Hover Background Color', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Load More Hover Border Color', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Load More Hover Text Color', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Load More Text Color', 'catch-infinite-scroll' ); ?></strong></li>
                            <li><strong><?php esc_html_e( 'Load More Text Font Size (for mobile devices)', 'catch-infinite-scroll' ); ?></strong></li>
                        </ul>

                        <a href="https://catchplugins.com/plugins/catch-infinite-scroll-pro/" target="_blank"><?php esc_html_e( 'Find out why you should upgrade to Catch Infinite Scroll Premium »', 'catch-infinite-scroll' ); ?></a>
                    </div> <!-- .Content -->
                </div> <!-- #go-premium -->

                <div id="pro-screenshot" class="content-wrapper col-3">

                    <div class="header">
                        <h2><?php esc_html_e( 'Pro Screenshot', 'catch-infinite-scroll' ); ?></h2>
                    </div> <!-- .Header -->

                    <div class="content">
                        <ul class="catchp-lists">
                            <li><img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/screenshot-1.jpg' ); ?>"></li>
                            <li><img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/screenshot-2.jpg' ); ?>"></li>
                            <li><img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/screenshot-3.jpg' ); ?>"></li>
                            <li><img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/screenshot-4.jpg' ); ?>"></li>
                            <li><img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/screenshot-5.jpg' ); ?>"></li>
                        </ul>
                    </div> <!-- .Content -->
                </div> <!-- #pro-screenshot -->

            </div><!-- .dashboard -->

            <div id="features" class="wpcatchtab save">
                <div class="content-wrapper col-3">
                    <div class="header">
                        <h3><?php esc_html_e( 'Features', 'catch-infinite-scroll' ); ?></h3>

                    </div><!-- .header -->
                    <div class="content">
                        <ul class="catchp-lists">
                            <li>
                                <strong><?php esc_html_e( 'Easy to Set Up', 'catch-infinite-scroll' ); ?></strong>
                                <p><?php esc_html_e( 'The plugin for infinite scrolling is extremely easy to set up on any website. Anyone (even the beginners) can easily set it up. With zero coding knowledge, you can easily customize Catch Infinite Scroll plugin your way and enjoy the infinite scrolling on your website.', 'catch-infinite-scroll' ); ?></p>
                            </li>

                            <li>
                                <strong><?php esc_html_e( 'Incredible Support', 'catch-infinite-scroll' ); ?></strong>
                                <p><?php esc_html_e( 'We have a great line of support team and support documentation. You do not need to worry about how to use the plugins we provide, just refer to our Tech Support Forum. Further, if you need to do advanced customization to your website, you can always hire our theme customizer!', 'catch-infinite-scroll' ); ?></p>
                            </li>

                            <li>
                                <strong><?php esc_html_e( 'Infinite Scroll', 'catch-infinite-scroll' ); ?></strong>
                                <p><?php esc_html_e( 'One of the most amazing features that Catch Infinite Scroll carries is the infinite scrolling feature. If you want to engage your users to your content, then infinite scrolling is the best option to go for. With the feature enabled, the content will load automatically as the user reaches the end of a page.', 'catch-infinite-scroll' ); ?></p>
                            </li>

                            <li>
                                <strong><?php esc_html_e( 'Lightweight', 'catch-infinite-scroll' ); ?></strong>
                                <p><?php esc_html_e( 'It is extremely lightweight. You do not need to worry about it affecting the space and speed of your website.', 'catch-infinite-scroll' ); ?></p>
                            </li>
                            <li>
                                <strong><?php esc_html_e( 'Load More', 'catch-infinite-scroll' ); ?></strong>
                                <p><?php esc_html_e( 'Catch Infinite Scroll plugin provides you with the “Load More” button. In case you don’t want to use the infinite scrolling feature, you can add this button. All you have to do is click the button and it will automatically load more content on your website.', 'catch-infinite-scroll' ); ?></p>
                            </li>

                            <li>
                                <strong><?php esc_html_e( 'Supports all themes on WordPress', 'catch-infinite-scroll' ); ?></strong>
                                <p><?php esc_html_e( 'You don’t have to worry if you have a slightly different or complicated theme installed on your website. It supports all the themes on WordPress and makes your website more striking and playful.', 'catch-infinite-scroll' ); ?></p>
                            </li>
                        </ul>
                        <a href="https://catchplugins.com/plugins/catch-infinite-scroll-pro/" target="_blank"><?php esc_html_e( 'Upgrade to Catch Infinite Scroll Premium »', 'catch-infinite-scroll' ); ?></a>
                    </div><!-- .content -->
                </div><!-- content-wrapper -->
            </div> <!-- Featured -->

            <div id="premium-extensions" class="wpcatchtab  save">

                <div class="about-text">
                    <h2><?php esc_html_e( 'Get Catch Infinite Scroll Pro -', 'catch-infinite-scroll' ); ?> <a href="https://catchplugins.com/plugins/catch-infinite-scroll-pro/" target="_blank"><?php esc_html_e( 'Get It Here!', 'catch-infinite-scroll' ); ?></a></h2>
                    <p><?php esc_html_e( 'You are currently using the free version of Catch Infinite Scroll.', 'catch-infinite-scroll' ); ?><br />
                    <a href="https://catchplugins.com/plugins/" target="_blank"><?php esc_html_e( 'If you have purchased from catchplugins.com, then follow this link to the installation instructions (particularly step 1).', 'catch-infinite-scroll' ); ?></a></p>
                </div>

                <div class="content-wrapper">
                    <div class="header">
                        <h3><?php esc_html_e( 'Compare Table', 'catch-infinite-scroll' ); ?></h3>

                    </div><!-- .header -->
                    <div class="content">

                        <table class="widefat fixed striped posts">
                            <thead>
                                <tr>
                                    <th id="title" class="manage-column column-title column-primary"><?php esc_html_e( 'Features', 'catch-infinite-scroll' ); ?></th>
                                    <th id="free" class="manage-column column-free"><?php esc_html_e( 'Free', 'catch-infinite-scroll' ); ?></th>
                                    <th id="pro" class="manage-column column-pro"><?php esc_html_e( 'Pro', 'catch-infinite-scroll' ); ?></th>
                                </tr>
                            </thead>

                            <tbody id="the-list" class="ui-sortable">
                                <tr class="iedit author-self level-0 type-post status-publish format-standard hentry">
                                    <td>
                                        <strong><?php esc_html_e( 'Super Easy Setup', 'catch-infinite-scroll' ); ?></strong>
                                    </td>
                                    <td class="column column-free"><div class="table-icons icon-green">&#10003;</div></td>
                                    <td class="column column-pro"><div class="table-icons icon-green">&#10003;</div></td>
                                </tr>

                                <tr class="iedit author-self level-0 type-post status-publish format-standard hentry">
                                    <td>
                                        <strong><?php esc_html_e( 'Lightweight', 'catch-infinite-scroll' ); ?></strong>
                                    </td>
                                    <td class="column column-free"><div class="table-icons icon-green">&#10003;</div></td>
                                    <td class="column column-pro"><div class="table-icons icon-green">&#10003;</div></td>
                                </tr>

                                <tr class="iedit author-self level-0 type-post status-publish format-standard hentry">
                                    <td>
                                        <strong><?php esc_html_e( 'Custom Loading Image', 'catch-infinite-scroll' ); ?></strong>
                                    </td>
                                    <td class="column column-free"><div class="table-icons icon-green">&#10003;</div></td>
                                    <td class="column column-pro"><div class="table-icons icon-green">&#10003;</div></td>
                                </tr>

                                <tr class="iedit author-self level-0 type-post status-publish format-standard hentry">
                                    <td>
                                        <strong><?php esc_html_e( 'Custom Finish Text', 'catch-infinite-scroll' ); ?></strong>
                                    </td>
                                    <td class="column column-free"><div class="table-icons icon-green">&#10003;</div></td>
                                    <td class="column column-pro"><div class="table-icons icon-green">&#10003;</div></td>
                                </tr>

                                <tr class="iedit author-self level-0 type-post status-publish format-standard hentry">
                                    <td>
                                        <strong><?php esc_html_e( 'Supports All Themes', 'catch-infinite-scroll' ); ?></strong>
                                    </td>
                                    <td class="column column-free"><div class="table-icons icon-green">&#10003;</div></td>
                                    <td class="column column-pro"><div class="table-icons icon-green">&#10003;</div></td>
                                </tr>

                                <tr class="iedit author-self level-0 type-post status-publish format-standard hentry">
                                    <td>
                                        <strong><?php esc_html_e( 'Smooth and Uninterrupted Reading Experience', 'catch-infinite-scroll' ); ?></strong>
                                    </td>
                                    <td class="column column-free"><div class="table-icons icon-green">&#10003;</div></td>
                                    <td class="column column-pro"><div class="table-icons icon-green">&#10003;</div></td>
                                </tr>

                                <tr class="iedit author-self level-0 type-post status-publish format-standard hentry">
                                    <td>
                                        <strong><?php esc_html_e( 'Color Options', 'catch-infinite-scroll' ); ?></strong>
                                    </td>
                                    <td class="column column-free"><div class="table-icons icon-red">&#215;</div></td>
                                    <td class="column column-pro"><div class="table-icons icon-green">&#10003;</div></td>
                                </tr>

                                <tr class="iedit author-self level-0 type-post status-publish format-standard hentry">
                                    <td>
                                        <strong><?php esc_html_e( 'Font Family Option', 'catch-infinite-scroll' ); ?></strong>
                                    </td>
                                    <td class="column column-free"><div class="table-icons icon-red">&#215;</div></td>
                                    <td class="column column-pro"><div class="table-icons icon-green">&#10003;</div></td>
                                </tr>

                                <tr class="iedit author-self level-0 type-post status-publish format-standard hentry">
                                    <td>
                                        <strong><?php esc_html_e( 'Font Size Option', 'catch-infinite-scroll' ); ?></strong>
                                    </td>
                                    <td class="column column-free"><div class="table-icons icon-red">&#215;</div></td>
                                    <td class="column column-pro"><div class="table-icons icon-green">&#10003;</div></td>
                                </tr>

                                <tr class="iedit author-self level-0 type-post status-publish format-standard hentry">
                                    <td>
                                        <strong><?php esc_html_e( 'Ads Image/Code Option', 'catch-infinite-scroll' ); ?></strong>
                                    </td>
                                    <td class="column column-free"><div class="table-icons icon-red">&#215;</div></td>
                                    <td class="column column-pro"><div class="table-icons icon-green">&#10003;</div></td>
                                </tr>

                                <tr class="iedit author-self level-0 type-post status-publish format-standard hentry">
                                    <td>
                                        <strong><?php esc_html_e( 'Ads-free Dashboard', 'catch-infinite-scroll' ); ?></strong>
                                    </td>
                                    <td class="column column-free"><div class="table-icons icon-red">&#215;</div></td>
                                    <td class="column column-pro"><div class="table-icons icon-green">&#10003;</div></td>
                                </tr>

                            </tbody>

                        </table>

                    </div><!-- .content -->
                </div><!-- content-wrapper -->
            </div>

        </div><!-- .catchp_widget_settings -->


        <?php require_once plugin_dir_path( dirname( __FILE__ ) ) . '/partials/sidebar.php'; ?>
    </div> <!-- .catchp-content-wrapper -->

    <?php require_once plugin_dir_path( dirname( __FILE__ ) ) . '/partials/footer.php'; ?>
</div><!-- .wrap -->
