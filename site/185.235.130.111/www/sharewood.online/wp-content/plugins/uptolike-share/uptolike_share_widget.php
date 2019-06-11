<?php
/**
 * Plugin Name: UpToLike Social Share Buttons
 * Plugin URI: https://uptolike.com/
 * Description: Uptolike Social Share Buttons - social bookmarking widget with sharing statistics.
 * Version: 1.5.9
 * Requires at least: 4.1
 * Tested up to: 4.7.4
 * Author: Uptolike Team
 * Author URI: https://uptolike.com/
 *
 * @package UpToLike
 * @category Statistic
 * @author UptolikeTeam
 */

include 'widget_options.php';

add_filter('plugin_action_links', 'uptolike_action_links', 10, 2);

function uptolike_action_links($links, $file) {
    if (false === strpos($file, basename(__FILE__)))
        return $links;
    $links[] = '<a href="' . add_query_arg(array('page' => 'uptolike_settings'), admin_url('plugins.php')) . '">' . __('Settings') . '</a>';
    return $links;
}

add_filter('plugin_row_meta', 'uptolike_description_links', 10, 4);

function uptolike_description_links($links, $file) {
    if (false === strpos($file, basename(__FILE__)))
        return $links;
    $links[] = '<a href="' . add_query_arg(array('page' => 'uptolike_settings'), admin_url('plugins.php')) . '">' . __('Settings') . '</a>';
    return $links;
}

register_activation_hook(__FILE__, 'uptolike_install');
register_uninstall_hook(__FILE__, 'uptolike_delete_plugin');

function uptolike_delete_plugin() {
    $delete = get_option('uptolike_options');
    $delete['uptolike_email'] = '';
    $delete['id_number'] = '';
    update_option('uptolike_options', $delete);
}


function uptolike_install() {
    $options = get_option('my_option_name');
    if (!is_bool($options)) {
        $options['uptolike_email'] = '';
        $options['id_number'] = '';
        update_option('uptolike_options', $options);
        delete_option('my_option_name');
    }
}
