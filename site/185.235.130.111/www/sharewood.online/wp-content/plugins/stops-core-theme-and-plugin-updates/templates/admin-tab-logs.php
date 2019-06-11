<?php if (!defined('ABSPATH')) die('No direct access.'); ?>
<div id="result"></div>
<h3><?php esc_html_e('Enable/disable logs');?></h3>
<?php
if (isset($_POST['form-disable-logs']) || (isset($disable_logs) && false !== $disable_logs)) {
	if (!isset($disable_logs)) {
		check_admin_referer('enable_disable_logs');
	}
	$options = MPSUM_Updates_Manager::get_options('core');
	MPSUM_Logs::drop();
	$options['logs'] = 'off';
	MPSUM_Updates_Manager::update_options($options, 'core');
	printf('<div class="updated"><p><strong>%s</strong></p></div>', esc_html__('Logs are now disabled.', 'stops-core-theme-and-plugin-updates'));
}
if (isset($_POST['form-enable-logs']) || (isset($enable_logs) && false !== $enable_logs)) {
	if (!isset($enable_logs)) {
		check_admin_referer('enable_disable_logs');
	}
	$options = MPSUM_Updates_Manager::get_options('core');
	$options['logs'] = 'on';
	MPSUM_Updates_Manager::update_options($options, 'core');
	MPSUM_Logs::run()->build_table();
	printf('<div class="updated"><p><strong>%s</strong></p></div>', esc_html__('Logs are now enabled.', 'stops-core-theme-and-plugin-updates'));
}
?>
<form action="<?php add_query_arg(array('tab' => 'logs'), MPSUM_Admin::get_url());?>" method="POST">
<?php wp_nonce_field('enable_disable_logs'); ?>
<?php
$options = MPSUM_Updates_Manager::get_options('core', true);
if (isset($options['logs']) && 'on' === $options['logs']) {
	?>
	<p><?php esc_html_e('Logs are currently enabled.', 'stops-core-theme-and-plugin-updates'); ?></p>
	<?php
	printf('<p class="submit"><input type="submit" name="form-disable-logs" id="form-disable-logs" class="button button-primary" value="%1$s" /></p>', esc_attr__('Disable logs', 'stops-core-theme-and-plugin-updates'));
} else {
	?>
	<p><?php esc_html_e('Logs are currently disabled.', 'stops-core-theme-and-plugin-updates'); ?></p>
	<?php
	printf('<p class="submit"><input type="submit" name="form-enable-logs" id="form-enable-logs" class="button button-primary" value="%1$s" /></p>', esc_attr__('Enable logs', 'stops-core-theme-and-plugin-updates'));
}
?>
</form>
<?php
if (isset($options['logs']) && 'on' === $options['logs']) :
?>
	<h3><?php esc_html_e('Clear logs', 'stops-core-theme-and-plugin-updates'); ?></h3>
<?php
	printf('<p class="submit"><input type="submit" name="clear-log" id="clear-logs" class="button button-primary" value="%1$s" /></p>', esc_attr__('Clear Now', 'stops-core-theme-and-plugin-updates'));
	do_action('eum_logs');
?>
	<h3><?php esc_html_e('Update Logs', 'stops-core-theme-and-plugin-updates'); ?></h3>
	<p><?php esc_html_e('Please note that this feature does not necessarily work for premium themes and plugins.', 'stops-core-theme-and-plugin-updates');?></p>
<?php
	$core_options = MPSUM_Updates_Manager::get_options('core');
	$logs_table = new MPSUM_Logs_List_Table($args = array('paged' => $paged, 'view' => $view, 'status' => $status, 'action_type' => $action_type, 'type' => $type, 'm' => $m, 'is_search' => $is_search, 'search_term' => $search_term, 'order' => $order));
	$logs_table->prepare_items();
	$logs_table->views();
	$logs_table->display();
endif;
	