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

<div id="catch-infinite-scroll">
    <div class="content-wrapper">
        <div class="header">
            <h2><?php esc_html_e( 'Settings', 'catch-infinite-scroll' ); ?></h2>
        </div> <!-- .Header -->
        <div class="content">
            <?php if( isset($_GET['settings-updated']) ) { ?>
                <div id="message" class="notice updated fade">
                    <p><strong><?php esc_html_e( 'Plugin Options Saved.', 'catch-infinite-scroll' ) ?></strong></p>
                </div>
            <?php } ?>

            <?php // Use nonce for verification.
                wp_nonce_field( basename( __FILE__ ), 'catch_infinite_scroll_nounce' );
            ?>

            <div id="catch_infinite_scroll_main">
                <form method="post" action="options.php">
                    <?php settings_fields( 'catch-infinite-scroll-group' ); ?>
                    <?php
                        $defaults = catch_infinite_scroll_default_options();
                        $settings = catch_infinite_scroll_get_options( 'catch_infinite_scroll_options' );
                    ?>
                    <div class="option-container">
                            <table class="form-table">
                                <tbody>

                                    <tr>
                                        <th scope="row"><?php esc_html_e( 'Load On (Trigger on)', 'catch-infinite-scroll' ); ?></th>

                                        <td>
                                            <?php
                                                echo '<select id="catch_infinite_scroll_options[trigger]" name="catch_infinite_scroll_options[trigger]" class="ctis-trigger">';
                                                    echo '<option value="scroll"' . selected( $settings['trigger'], 'scroll', false) . '>'. esc_html__( 'Scroll', 'catch-infinite-scroll') .'</option>';
                                                    echo '<option value="click"' . selected( $settings['trigger'], 'click', false) . '>'. esc_html__( 'Click', 'catch-infinite-scroll') .'</option>';
                                                echo '</select>';
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><?php esc_html_e( 'Navigation Selector', 'catch-infinite-scroll' ); ?></th>
                                        <td>
                                            <?php
                                                echo '<input type="text" id="catch_infinite_scroll_options[navigation_selector]" name="catch_infinite_scroll_options[navigation_selector]" value="'. wp_kses_post( $settings['navigation_selector'] ) .'"/>';
                                            ?>
                                            <span class="dashicons dashicons-info" title="<?php esc_html_e( 'Selector containing your theme\'s navigation.', 'catch-infinite-scroll' ); ?>"></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><?php esc_html_e( 'Next Selector', 'catch-infinite-scroll' ); ?></th>
                                        <td>
                                            <?php
                                                echo '<input type="text" id="catch_infinite_scroll_options[next_selector]" name="catch_infinite_scroll_options[next_selector]" value="'. wp_kses_post( $settings['next_selector'] ) .'"/>';
                                            ?>
                                            <span class="dashicons dashicons-info" title="<?php esc_html_e( 'Link to the next page.', 'catch-infinite-scroll' ); ?>"></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><?php esc_html_e( 'Content Selector', 'catch-infinite-scroll' ); ?></th>
                                        <td>
                                            <?php
                                                echo '<input type="text" id="catch_infinite_scroll_options[content_selector]" name="catch_infinite_scroll_options[content_selector]" value="'. wp_kses_post( $settings['content_selector'] ) .'"/>';
                                            ?>
                                            <span class="dashicons dashicons-info" title="<?php esc_html_e( 'Selector containing your theme\'s content.', 'catch-infinite-scroll' ); ?>"></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><?php esc_html_e( 'Item Selector', 'catch-infinite-scroll' ); ?></th>
                                        <td>
                                            <?php
                                                echo '<input type="text" id="catch_infinite_scroll_options[item_selector]" name="catch_infinite_scroll_options[item_selector]" value="'. wp_kses_post( $settings['item_selector'] ) .'"/>';
                                            ?>
                                            <span class="dashicons dashicons-info" title="<?php esc_html_e( 'Selector containing single post or product.', 'catch-infinite-scroll' ); ?>"></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><?php esc_html_e( 'Image', 'catch-infinite-scroll' ); ?></th>
                                        <td>
                                            <?php
                                                echo '<input type="text" class="image-url" id="catch_infinite_scroll_options[image]" name="catch_infinite_scroll_options[image]" placeholder="Image URL HERE" value="'. esc_url( $settings['image'] ) .'"/>';
                                            ?>
                                            <span class="ctis-image-holder">
                                                <?php if ( '' !== $settings['image'] ) {
                                                    echo '<img src="' . esc_url( $settings['image'] ) . '" />';
                                                } ?>
                                            </span>
                                            <button class="catch-infinite-scroll-upload-media-button button button-primary"><?php
                                                if ( $defaults['image'] === $settings['image'] ) {
                                                    esc_html_e( 'Upload', 'catch-infinite-scroll' );
                                                }
                                                else {
                                                    esc_html_e( 'Change', 'catch-infinite-scroll' );
                                                }
                                            ?></button>
                                            <?php
                                                $hide_class = '';
                                                if ( $defaults['image'] === $settings['image'] ){
                                                    $hide_class = 'ctis-hide';
                                                }
                                            ?><button class="catch-infinite-scroll-reset-media-button button button-primary <?php echo $hide_class; ?>"><?php esc_html_e( 'Reset', 'catch-infinite-scroll' ); ?></button>
                                        </td>
                                    </tr>

                                    <tr <?php echo ('scroll' === $settings['trigger'] ) ? ' style="display:none;"' : ''; ?>>
                                        <th scope="row"><?php esc_html_e( 'Load More Text', 'catch-infinite-scroll' ); ?></th>
                                        <td>
                                            <?php
                                                echo '<input type="text" id="catch_infinite_scroll_options[load_more_text]" name="catch_infinite_scroll_options[load_more_text]" value="'. wp_kses_post( $settings['load_more_text'] ) .'" class="ctis-more-text"/>';
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><?php esc_html_e( 'Finish Text', 'catch-infinite-scroll' ); ?></th>
                                        <td>
                                            <?php
                                                echo '<input type="text" id="catch_infinite_scroll_options[finish_text]" name="catch_infinite_scroll_options[finish_text]" value="'. wp_kses_post( $settings['finish_text'] ) .'"/>';
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><?php esc_html_e( 'Reset Options', 'catch-infinite-scroll' ); ?></th>
                                        <td>
                                            <?php
                                                echo '<input name="catch_infinite_scroll_options[reset]" id="catch_infinite_scroll_options[reset]" type="checkbox" value="1" class="catch_infinite_scroll_options[reset]" />' . esc_html__( 'Check to reset','catch-infinite-scroll' );
                                            ?>
                                            <span class="dashicons dashicons-info" title="<?php esc_html_e( 'Caution: Reset all settings to default. Refresh the page after save to view full effects.', 'catch-infinite-scroll' ); ?>"></span>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                            <?php submit_button( esc_html__( 'Save Changes', 'catch-infinite-scroll' ) ); ?>
                    </div><!-- .option-container -->
                </form>
            </div><!-- #catch_infinite_scroll_main -->
        </div><!-- .content -->
    </div><!-- .content-wrapper -->
</div><!-- .content-wrapper -->
