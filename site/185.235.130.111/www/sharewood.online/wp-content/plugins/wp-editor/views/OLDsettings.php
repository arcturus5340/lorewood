<?php
	$tab = 'overview';
	if ( WPEditorSetting::get_value( 'settings_tab' ) ) {
		$tab = WPEditorSetting::get_value( 'settings_tab' );
	}
	if ( !WPEditorSetting::get_value( 'run_overview' ) ) {
		$tab = 'overview';
	}
	WPEditorSetting::set_value( 'run_overview', 1 );
	$success_message = '';
?>

<div class="wrap">
	<div id="icon-wpeditor" class="icon32"></div>
	<h2><?php _e( 'WP Editor Settings', 'wp-editor' ); ?></h2>
	<div id="settings-main">
		<div id="settings-main-wrap">
			<div id="settings-back"></div>
			<div id="save-result"></div>
			<div id="settings-columns">
				<div class="settings-tabs">
					<ul>
						<li id="settings-main-settings-tab"><a id="settings-link-main-settings" href="javascript:void(0)"><?php _e( 'Main Settings', 'wp-editor' ); ?></a></li>
						<li id="settings-themes-tab"><a id="settings-link-themes" href="javascript:void(0)"><?php _e( 'Theme Editor', 'wp-editor' ); ?></a></li>
						<li id="settings-plugins-tab"><a id="settings-link-plugins" href="javascript:void(0)"><?php _e( 'Plugin Editor', 'wp-editor' ); ?></a></li>
						<li id="settings-posts-tab"><a id="settings-link-posts" href="javascript:void(0)"><?php _e( 'Post Editor', 'wp-editor' ); ?></a></li>
						<li id="settings-overview-tab"><a id="settings-link-overview" href="javascript:void(0)"><?php _e( 'Overview', 'wp-editor' ); ?></a></li>
					</ul>
				</div>
				<div id="settings-loading">
					<h2><?php _e( 'loading...', 'wp-editor' ); ?></h2>
				</div>
				<div id="settings-main-settings" class="settings-body">
					<form action="" method="post" class="ajax-settings-form" id="settings-form">
						<?php wp_nonce_field( 'wp_editor_ajax_nonce_settings_main', 'wp_editor_ajax_nonce_settings_main' ); ?>
						<input type="hidden" name="action" value="save_wpeditor_settings" />
						<input type="hidden" name="_success" value="Your main settings have been saved." />
						<input type="hidden" name="_tab" value="main-settings" />
						<div id="replace-plugin-edit-links" class="section">
							<div class="section-header">
								<h3><?php _e( 'Plugin Edit Links', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="replace_plugin_edit_links"><?php _e( 'Replace Default Plugin Edit Links:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="replace_plugin_edit_links" value="1" <?php echo (WPEditorSetting::get_value( 'replace_plugin_edit_links' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="replace_plugin_edit_links" value="0" <?php echo (WPEditorSetting::get_value( 'replace_plugin_edit_links' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will replace the default edit links on the Installed Plugins page with WP Editor links.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="hide-default-editors" class="section">
							<div class="section-header">
								<h3><?php _e( 'Hide Default Editors', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="hide_default_plugin_editor"><?php _e( 'Hide Default Plugin Editor:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="hide_default_plugin_editor" value="1" <?php echo (WPEditorSetting::get_value( 'hide_default_plugin_editor' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="hide_default_plugin_editor" value="0" <?php echo (WPEditorSetting::get_value( 'hide_default_plugin_editor' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will hide the default Edit submenu for the plugins page.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
									<li>
										<label for="hide_default_theme_editor"><?php _e( 'Hide Default Theme Editor:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="hide_default_theme_editor" value="1" <?php echo (WPEditorSetting::get_value( 'hide_default_theme_editor' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="hide_default_theme_editor" value="0" <?php echo (WPEditorSetting::get_value( 'hide_default_theme_editor' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will hide the default Edit submenu for the themes page.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="logging" class="section">
							<div class="section-header">
								<h3><?php _e( 'Logging', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="wpeditor_logging"><?php _e( 'Enable Logging:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="wpeditor_logging" value="1" <?php echo (WPEditorSetting::get_value( 'wpeditor_logging' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="wpeditor_logging" value="0" <?php echo (WPEditorSetting::get_value( 'wpeditor_logging' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will enable diagnostic logging on the site.	WARNING: This file grows quickly so please only enable if you are troubleshooting.<br />Default: No", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="menu-location" class="section">
							<div class="section-header">
								<h3><?php _e( 'WP Editor Menu Location', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="hide_wpeditor_menu"><?php _e( 'Hide WP Editor Icon:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="hide_wpeditor_menu" value="1" <?php echo (WPEditorSetting::get_value( 'hide_wpeditor_menu' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="hide_wpeditor_menu" value="0" <?php echo (WPEditorSetting::get_value( 'hide_wpeditor_menu' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("If set to yes, this will hide the WP Editor icon from the menu on the left. You will be able to access this settings page from the main Settings drop down instead.<br />Default: No", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="save-settings">
							<ul>
								<li>
									<input type='submit' name='submit' class="button-primary" value="<?php _e( 'Save Settings', 'wp-editor' ); ?>" />
								</li>
							</ul>
						</div>
					</form>
				</div>
				<div id="settings-themes" class="settings-body">
					<form action="" method="post" class="ajax-settings-form" id="theme-settings-form">
						<?php wp_nonce_field( 'wp_editor_ajax_nonce_settings_themes', 'wp_editor_ajax_nonce_settings_themes' ); ?>
						<input type="hidden" name="action" value="save_wpeditor_settings" />
						<input type="hidden" name="_success" value="Your theme settings have been saved." />
						<input type="hidden" name="_tab" value="themes" />
						<div id="theme-editor-theme" class="section">
							<div class="section-header">
								<h3><?php _e( 'Editor Theme', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="theme_editor_theme"><?php _e( 'Theme:', 'wp-editor' ); ?></label>
										<select id="theme_editor_theme" name="theme_editor_theme">
											<?php
											$theme = 'default';
											if (WPEditorSetting::get_value( 'theme_editor_theme' ) ) {
												$theme = WPEditorSetting::get_value( 'theme_editor_theme' );
											}
											?>
											<option value="default" <?php echo ( $theme == 'default' ) ? 'selected="selected"' : '' ?>><?php _e( 'Default', 'wp-editor' ); ?></option>
											<option value="ambiance" <?php echo ( $theme == 'ambiance' ) ? 'selected="selected"' : '' ?>><?php _e( 'Ambiance', 'wp-editor' ); ?></option>
											<option value="blackboard" <?php echo ( $theme == 'blackboard' ) ? 'selected="selected"' : '' ?>><?php _e( 'Blackboard', 'wp-editor' ); ?></option>
											<option value="cobalt" <?php echo ( $theme == 'cobalt' ) ? 'selected="selected"' : '' ?>><?php _e( 'Cobalt', 'wp-editor' ); ?></option>
											<option value="eclipse" <?php echo ( $theme == 'eclipse' ) ? 'selected="selected"' : '' ?>><?php _e( 'Eclipse', 'wp-editor' ); ?></option>
											<option value="elegant" <?php echo ( $theme == 'elegant' ) ? 'selected="selected"' : '' ?>><?php _e( 'Elegant', 'wp-editor' ); ?></option>
											<option value="lesser-dark" <?php echo ( $theme == 'lesser-dark' ) ? 'selected="selected"' : '' ?>><?php _e( 'Lesser Dark', 'wp-editor' ); ?></option>
											<option value="monokai" <?php echo ( $theme == 'monokai' ) ? 'selected="selected"' : '' ?>><?php _e( 'Monokai', 'wp-editor' ); ?></option>
											<option value="neat" <?php echo ( $theme == 'neat' ) ? 'selected="selected"' : '' ?>><?php _e( 'Neat', 'wp-editor' ); ?></option>
											<option value="night" <?php echo ( $theme == 'night' ) ? 'selected="selected"' : '' ?>><?php _e( 'Night', 'wp-editor' ); ?></option>
											<option value="rubyblue" <?php echo ( $theme == 'rubyblue' ) ? 'selected="selected"' : '' ?>><?php _e( 'Ruby Blue', 'wp-editor' ); ?></option>
											<option value="vibrant-ink" <?php echo ( $theme == 'vibrant-ink' ) ? 'selected="selected"' : '' ?>><?php _e( 'Vibrant Ink', 'wp-editor' ); ?></option>
											<option value="xq-dark" <?php echo ( $theme == 'xq-dark' ) ? 'selected="selected"' : '' ?>><?php _e( 'XQ-Dark', 'wp-editor' ); ?></option>
										</select>
									</li>
									<li class="indent description">
										<p><?php _e("This allows you to select the theme for the theme editor.<br />Default: Default", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="theme-editor-extensions" class="section">
							<div class="section-header">
								<h3><?php _e( 'Extensions', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="theme_editor_allowed_extensions"><?php _e( 'Allowed Extensions:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<?php
										$allowed_extensions = WPEditorSetting::get_value( 'theme_editor_allowed_extensions' );
										if ( $allowed_extensions) {
											$allowed_extensions = explode( '~', $allowed_extensions);
										}
										else {
											$allowed_extensions = array();
										}
										?>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="php" <?php echo in_array( 'php', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.php', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="js" <?php echo in_array( 'js', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.js', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="css" <?php echo in_array( 'css', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.css', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="scss" <?php echo in_array( 'scss', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.scss', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="txt" <?php echo in_array( 'txt', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.txt', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="htm" <?php echo in_array( 'htm', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.htm', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="html" <?php echo in_array( 'html', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.html', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="jpg" <?php echo in_array( 'jpg', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.jpg', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="jpeg" <?php echo in_array( 'jpeg', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.jpeg', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="png" <?php echo in_array( 'png', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.png', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="gif" <?php echo in_array( 'gif', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.gif', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="sql" <?php echo in_array( 'sql', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.sql', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="po" <?php echo in_array( 'po', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.po', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="pot" <?php echo in_array( 'pot', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.pot', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="less" <?php echo in_array( 'less', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.less', 'wp-editor' ); ?></label>
										<input type="checkbox" name="theme_editor_allowed_extensions[]" value="xml" <?php echo in_array( 'xml', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.xml', 'wp-editor' ); ?></label>
									</li>
									<li class="indent description">
										<p><?php _e( 'Select which extensions you would like the theme editor browser to be able to access.', 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="change-theme-editor-font-size" class="section">
							<div class="section-header">
								<h3><?php _e( 'Font Size', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="change_theme_editor_font_size"><?php _e( 'Change Font Size:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input class="small-text" name="change_theme_editor_font_size" value="<?php echo WPEditorSetting::get_value( 'change_theme_editor_font_size' ); ?>" />
									</li>
									<li class="indent description">
										<p><?php _e("This will set the font size in pixels for the theme editor.<br />Default: 12", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-theme-line-numbers" class="section">
							<div class="section-header">
								<h3><?php _e( 'Line Numbers', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_theme_line_numbers"><?php _e( 'Enable Line Numbers:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="enable_theme_line_numbers" value="1" <?php echo (WPEditorSetting::get_value( 'enable_theme_line_numbers' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="enable_theme_line_numbers" value="0" <?php echo (WPEditorSetting::get_value( 'enable_theme_line_numbers' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will enable line numbers for the theme editor.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-theme-line-wrapping" class="section">
							<div class="section-header">
								<h3><?php _e( 'Line Wrapping', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_theme_line_wrapping"><?php _e( 'Enable Line Wrapping:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="enable_theme_line_wrapping" value="1" <?php echo (WPEditorSetting::get_value( 'enable_theme_line_wrapping' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="enable_theme_line_wrapping" value="0" <?php echo (WPEditorSetting::get_value( 'enable_theme_line_wrapping' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will enable line wrapping for the theme editor.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-theme-active-line" class="section">
							<div class="section-header">
								<h3><?php _e( 'Active Line Highlighting', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_theme_active_line"><?php _e( 'Highlight Active Line:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="enable_theme_active_line" value="1" <?php echo (WPEditorSetting::get_value( 'enable_theme_active_line' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="enable_theme_active_line" value="0" <?php echo (WPEditorSetting::get_value( 'enable_theme_active_line' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will enable highlighting of the active line for the theme editor.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="theme-indent-unit" class="section">
							<div class="section-header">
								<h3><?php _e( 'Indent Size', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="theme_indent_unit"><?php _e( 'Indent Size:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input class="small-text" name="theme_indent_unit" value="<?php echo WPEditorSetting::get_value( 'theme_indent_unit' ); ?>" />
									</li>
									<li class="indent description">
										<p><?php _e("This will set the size of the indent.<br />Default: 2", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-theme-tab-characters" class="section">
							<div class="section-header">
								<h3><?php _e( 'Tab Characters', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_theme_tab_characters"><?php _e( 'Tab Characters:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<select name="enable_theme_tab_characters">
											<option value="spaces"<?php echo WPEditorSetting::get_value( 'enable_theme_tab_characters' ) == 'spaces' ? ' selected="selected"' : ''; ?>><?php _e( 'Spaces', 'wp-editor' ); ?></option>
											<option value="tabs"<?php echo WPEditorSetting::get_value( 'enable_theme_tab_characters' ) == 'tabs' ? ' selected="selected"' : ''; ?>><?php _e( 'Tabs', 'wp-editor' ); ?></option>
										</select>
									</li>
									<li class="indent description">
										<p><?php _e("This will set the tab character for the theme editor.<br />Default: Spaces", 'wp-editor' ); ?></p>
									</li>
									<li>
										<label for="enable_theme_tab_size"><?php _e( 'Tab Size:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input class="small-text" name="enable_theme_tab_size" value="<?php echo WPEditorSetting::get_value( 'enable_theme_tab_size' ); ?>" />
									</li>
									<li class="indent description">
										<p><?php _e("This will set the tab size for the theme editor.<br />Default: 2", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-theme-editor-height" class="section">
							<div class="section-header">
								<h3><?php _e( 'Editor Height', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_theme_editor_height"><?php _e( 'Editor Height:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input class="small-text" name="enable_theme_editor_height" value="<?php echo WPEditorSetting::get_value( 'enable_theme_editor_height' ); ?>" />
									</li>
									<li class="indent description">
										<p><?php _e("This will set the height in pixels for the theme editor.<br />Default: 450", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-theme-file-upload" class="section">
							<div class="section-header">
								<h3><?php _e( 'File Upload', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="theme_file_upload"><?php _e( 'Enable File Upload:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="theme_file_upload" value="1" <?php echo (WPEditorSetting::get_value( 'theme_file_upload' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="theme_file_upload" value="0" <?php echo (WPEditorSetting::get_value( 'theme_file_upload' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will enable a file upload option for the theme editor.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-theme-create-new" class="section">
							<div class="section-header">
								<h3><?php _e( 'Create New Themes', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="theme_create_new"><?php _e( 'Enable Creating Themes:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="theme_create_new" value="1" <?php echo (WPEditorSetting::get_value( 'theme_create_new' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="theme_create_new" value="0" <?php echo (WPEditorSetting::get_value( 'theme_create_new' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will allow you to create new themes within the Theme Editor.<br />Default: No", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="save-settings">
							<ul>
								<li>
									<input type='submit' name='submit' class="button-primary" value="<?php _e( 'Save Settings', 'wp-editor' ); ?>" />
								</li>
							</ul>
						</div>
					</form>
				</div>
				<div id="settings-plugins" class="settings-body">
					<form action="" method="post" class="ajax-settings-form" id="plugin-settings-form">
						<?php wp_nonce_field( 'wp_editor_ajax_nonce_settings_plugins', 'wp_editor_ajax_nonce_settings_plugins' ); ?>
						<input type="hidden" name="action" value="save_wpeditor_settings" />
						<input type="hidden" name="_success" value="Your plugin settings have been saved." />
						<input type="hidden" name="_tab" value="plugins" />
						<div id="plugin-editor-theme" class="section">
							<div class="section-header">
								<h3><?php _e( 'Editor Theme', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="plugin_editor_theme"><?php _e( 'Theme:', 'wp-editor' ); ?></label>
										<select id="plugin_editor_theme" name="plugin_editor_theme">
											<?php
											$theme = 'default';
											if (WPEditorSetting::get_value( 'plugin_editor_theme' ) ) {
												$theme = WPEditorSetting::get_value( 'plugin_editor_theme' );
											}
											?>
											<option value="default" <?php echo ( $theme == 'default' ) ? 'selected="selected"' : '' ?>><?php _e( 'Default', 'wp-editor' ); ?></option>
											<option value="ambiance" <?php echo ( $theme == 'ambiance' ) ? 'selected="selected"' : '' ?>><?php _e( 'Ambiance', 'wp-editor' ); ?></option>
											<option value="blackboard" <?php echo ( $theme == 'blackboard' ) ? 'selected="selected"' : '' ?>><?php _e( 'Blackboard', 'wp-editor' ); ?></option>
											<option value="cobalt" <?php echo ( $theme == 'cobalt' ) ? 'selected="selected"' : '' ?>><?php _e( 'Cobalt', 'wp-editor' ); ?></option>
											<option value="eclipse" <?php echo ( $theme == 'eclipse' ) ? 'selected="selected"' : '' ?>><?php _e( 'Eclipse', 'wp-editor' ); ?></option>
											<option value="elegant" <?php echo ( $theme == 'elegant' ) ? 'selected="selected"' : '' ?>><?php _e( 'Elegant', 'wp-editor' ); ?></option>
											<option value="lesser-dark" <?php echo ( $theme == 'lesser-dark' ) ? 'selected="selected"' : '' ?>><?php _e( 'Lesser Dark', 'wp-editor' ); ?></option>
											<option value="monokai" <?php echo ( $theme == 'monokai' ) ? 'selected="selected"' : '' ?>><?php _e( 'Monokai', 'wp-editor' ); ?></option>
											<option value="neat" <?php echo ( $theme == 'neat' ) ? 'selected="selected"' : '' ?>><?php _e( 'Neat', 'wp-editor' ); ?></option>
											<option value="night" <?php echo ( $theme == 'night' ) ? 'selected="selected"' : '' ?>><?php _e( 'Night', 'wp-editor' ); ?></option>
											<option value="rubyblue" <?php echo ( $theme == 'rubyblue' ) ? 'selected="selected"' : '' ?>><?php _e( 'Ruby Blue', 'wp-editor' ); ?></option>
											<option value="vibrant-ink" <?php echo ( $theme == 'vibrant-ink' ) ? 'selected="selected"' : '' ?>><?php _e( 'Vibrant Ink', 'wp-editor' ); ?></option>
											<option value="xq-dark" <?php echo ( $theme == 'xq-dark' ) ? 'selected="selected"' : '' ?>><?php _e( 'XQ-Dark', 'wp-editor' ); ?></option>
										</select>
									</li>
									<li class="indent description">
										<p><?php _e("This allows you to select the theme for the plugin editor.<br />Default: Default", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="plugin-editor-extensions" class="section">
							<div class="section-header">
								<h3><?php _e( 'Extensions', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="plugin_editor_allowed_extensions"><?php _e( 'Allowed Extensions:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<?php
										$allowed_extensions = WPEditorSetting::get_value( 'plugin_editor_allowed_extensions' );
										if ( $allowed_extensions) {
											$allowed_extensions = explode( '~', $allowed_extensions);
										}
										else {
											$allowed_extensions = array();
										}
										?>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="php" <?php echo in_array( 'php', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.php', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="js" <?php echo in_array( 'js', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.js', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="css" <?php echo in_array( 'css', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.css', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="scss" <?php echo in_array( 'scss', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.scss', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="txt" <?php echo in_array( 'txt', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.txt', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="htm" <?php echo in_array( 'htm', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.htm', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="html" <?php echo in_array( 'html', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.html', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="jpg" <?php echo in_array( 'jpg', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.jpg', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="jpeg" <?php echo in_array( 'jpeg', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.jpeg', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="png" <?php echo in_array( 'png', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.png', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="gif" <?php echo in_array( 'gif', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.gif', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="sql" <?php echo in_array( 'sql', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.sql', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="po" <?php echo in_array( 'po', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.po', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="pot" <?php echo in_array( 'pot', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.pot', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="less" <?php echo in_array( 'less', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.less', 'wp-editor' ); ?></label>
										<input type="checkbox" name="plugin_editor_allowed_extensions[]" value="xml" <?php echo in_array( 'xml', $allowed_extensions) ? 'checked="checked"' : '' ?>>
										<label class="checkbox_label"><?php _e( '.xml', 'wp-editor' ); ?></label>
									</li>
									<li class="indent description">
										<p><?php _e( 'Select which extensions you would like the plugin editor browser to be able to access.', 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="change-plugin-editor-font-size" class="section">
							<div class="section-header">
								<h3><?php _e( 'Font Size', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="change_plugin_editor_font_size"><?php _e( 'Change Font Size:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input class="small-text" name="change_plugin_editor_font_size" value="<?php echo WPEditorSetting::get_value( 'change_plugin_editor_font_size' ); ?>" />
									</li>
									<li class="indent description">
										<p><?php _e("This will set the font size in pixels for the plugin editor.<br />Default: 12", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-plugin-line-numbers" class="section">
							<div class="section-header">
								<h3><?php _e( 'Line Numbers', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_plugin_line_numbers"><?php _e( 'Enable Line Numbers:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="enable_plugin_line_numbers" value="1" <?php echo (WPEditorSetting::get_value( 'enable_plugin_line_numbers' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="enable_plugin_line_numbers" value="0" <?php echo (WPEditorSetting::get_value( 'enable_plugin_line_numbers' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will enable line numbers for the plugin editor.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-plugin-line-wrapping" class="section">
							<div class="section-header">
								<h3><?php _e( 'Line Wrapping', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_plugin_line_wrapping"><?php _e( 'Enable Line Wrapping:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="enable_plugin_line_wrapping" value="1" <?php echo (WPEditorSetting::get_value( 'enable_plugin_line_wrapping' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="enable_plugin_line_wrapping" value="0" <?php echo (WPEditorSetting::get_value( 'enable_plugin_line_wrapping' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will enable line wrapping for the plugin editor.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-plugin-active-line" class="section">
							<div class="section-header">
								<h3><?php _e( 'Active Line Highlighting', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_plugin_active_line"><?php _e( 'Highlight Active Line:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="enable_plugin_active_line" value="1" <?php echo (WPEditorSetting::get_value( 'enable_plugin_active_line' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="enable_plugin_active_line" value="0" <?php echo (WPEditorSetting::get_value( 'enable_plugin_active_line' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will enable highlighting of the active line for the plugin editor.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="plugin-indent-unit" class="section">
							<div class="section-header">
								<h3><?php _e( 'Indent Size', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="plugin_indent_unit"><?php _e( 'Indent Size:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input class="small-text" name="plugin_indent_unit" value="<?php echo WPEditorSetting::get_value( 'plugin_indent_unit' ); ?>" />
									</li>
									<li class="indent description">
										<p><?php _e("This will set the size of the indent.<br />Default: 2", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-plugin-tab-characters" class="section">
							<div class="section-header">
								<h3><?php _e( 'Tab Characters', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_plugin_tab_characters"><?php _e( 'Tab Characters:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<select name="enable_plugin_tab_characters">
											<option value="spaces"<?php echo WPEditorSetting::get_value( 'enable_plugin_tab_characters' ) == 'spaces' ? ' selected="selected"' : ''; ?>><?php _e( 'Spaces', 'wp-editor' ); ?></option>
											<option value="tabs"<?php echo WPEditorSetting::get_value( 'enable_plugin_tab_characters' ) == 'tabs' ? ' selected="selected"' : ''; ?>><?php _e( 'Tabs', 'wp-editor' ); ?></option>
										</select>
									</li>
									<li class="indent description">
										<p><?php _e("This will set the tab character for the plugin editor.<br />Default: Spaces", 'wp-editor' ); ?></p>
									</li>
									<li>
										<label for="enable_plugin_tab_size"><?php _e( 'Tab Size:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input class="small-text" name="enable_plugin_tab_size" value="<?php echo WPEditorSetting::get_value( 'enable_plugin_tab_size' ); ?>" />
									</li>
									<li class="indent description">
										<p><?php _e("This will set the tab size for the plugin editor.<br />Default: 2", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-plugin-editor-height" class="section">
							<div class="section-header">
								<h3><?php _e( 'Editor Height', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_plugin_editor_height"><?php _e( 'Editor Height:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input class="small-text" name="enable_plugin_editor_height" value="<?php echo WPEditorSetting::get_value( 'enable_plugin_editor_height' ); ?>" />
									</li>
									<li class="indent description">
										<p><?php _e("This will set the height in pixels for the plugin editor.<br />Default: 450", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-plugin-file-upload" class="section">
							<div class="section-header">
								<h3><?php _e( 'File Upload', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="plugin_file_upload"><?php _e( 'Enable File Upload:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="plugin_file_upload" value="1" <?php echo (WPEditorSetting::get_value( 'plugin_file_upload' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="plugin_file_upload" value="0" <?php echo (WPEditorSetting::get_value( 'plugin_file_upload' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will enable a file upload option for the plugin editor.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-plugin-create-new" class="section">
							<div class="section-header">
								<h3><?php _e( 'Create New Plugins', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="plugin_create_new"><?php _e( 'Enable Creating Plugins:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="plugin_create_new" value="1" <?php echo (WPEditorSetting::get_value( 'plugin_create_new' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="plugin_create_new" value="0" <?php echo (WPEditorSetting::get_value( 'plugin_create_new' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will allow you to create new plugins within the Plugin Editor.<br />Default: No", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="save-settings">
							<ul>
								<li>
									<input type='submit' name='submit' class="button-primary" value="<?php _e( 'Save Settings', 'wp-editor' ); ?>" />
								</li>
							</ul>
						</div>
					</form>
				</div>
				<div id="settings-posts" class="settings-body">
					<form action="" method="post" class="ajax-settings-form" id="post-settings-form">
                        <?php wp_nonce_field( 'wp_editor_ajax_nonce_settings_posts', 'wp_editor_ajax_nonce_settings_posts' ); ?>
						<input type="hidden" name="action" value="save_wpeditor_settings" />
						<input type="hidden" name="_success" value="Your post editor settings have been saved." />
						<input type="hidden" name="_tab" value="posts" />
						<div id="enable-post-editor" class="section">
							<div class="section-header">
								<h3><?php _e( 'Enable Post Editor', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_post_editor"><?php _e( 'Enable the Posts Editor:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="enable_post_editor" value="1" <?php echo (WPEditorSetting::get_value( 'enable_post_editor' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="enable_post_editor" value="0" <?php echo (WPEditorSetting::get_value( 'enable_post_editor' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will enable/disable the post editor.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="post-editor-theme" class="section">
							<div class="section-header">
								<h3><?php _e( 'Editor Theme', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="post_editor_theme"><?php _e( 'Theme:', 'wp-editor' ); ?></label>
										<select id="post_editor_theme" name="post_editor_theme">
											<?php
											$theme = 'default';
											if (WPEditorSetting::get_value( 'post_editor_theme' ) ) {
												$theme = WPEditorSetting::get_value( 'post_editor_theme' );
											}
											?>
											<option value="default" <?php echo ( $theme == 'default' ) ? 'selected="selected"' : '' ?>><?php _e( 'Default', 'wp-editor' ); ?></option>
											<option value="ambiance" <?php echo ( $theme == 'ambiance' ) ? 'selected="selected"' : '' ?>><?php _e( 'Ambiance', 'wp-editor' ); ?></option>
											<option value="blackboard" <?php echo ( $theme == 'blackboard' ) ? 'selected="selected"' : '' ?>><?php _e( 'Blackboard', 'wp-editor' ); ?></option>
											<option value="cobalt" <?php echo ( $theme == 'cobalt' ) ? 'selected="selected"' : '' ?>><?php _e( 'Cobalt', 'wp-editor' ); ?></option>
											<option value="eclipse" <?php echo ( $theme == 'eclipse' ) ? 'selected="selected"' : '' ?>><?php _e( 'Eclipse', 'wp-editor' ); ?></option>
											<option value="elegant" <?php echo ( $theme == 'elegant' ) ? 'selected="selected"' : '' ?>><?php _e( 'Elegant', 'wp-editor' ); ?></option>
											<option value="lesser-dark" <?php echo ( $theme == 'lesser-dark' ) ? 'selected="selected"' : '' ?>><?php _e( 'Lesser Dark', 'wp-editor' ); ?></option>
											<option value="monokai" <?php echo ( $theme == 'monokai' ) ? 'selected="selected"' : '' ?>><?php _e( 'Monokai', 'wp-editor' ); ?></option>
											<option value="neat" <?php echo ( $theme == 'neat' ) ? 'selected="selected"' : '' ?>><?php _e( 'Neat', 'wp-editor' ); ?></option>
											<option value="night" <?php echo ( $theme == 'night' ) ? 'selected="selected"' : '' ?>><?php _e( 'Night', 'wp-editor' ); ?></option>
											<option value="rubyblue" <?php echo ( $theme == 'rubyblue' ) ? 'selected="selected"' : '' ?>><?php _e( 'Ruby Blue', 'wp-editor' ); ?></option>
											<option value="vibrant-ink" <?php echo ( $theme == 'vibrant-ink' ) ? 'selected="selected"' : '' ?>><?php _e( 'Vibrant Ink', 'wp-editor' ); ?></option>
											<option value="xq-dark" <?php echo ( $theme == 'xq-dark' ) ? 'selected="selected"' : '' ?>><?php _e( 'XQ-Dark', 'wp-editor' ); ?></option>
										</select>
									</li>
									<li class="indent description">
										<p><?php _e("This allows you to select the theme for the post editor.<br />Default: Default", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="change-post-editor-font-size" class="section">
							<div class="section-header">
								<h3><?php _e( 'Font Size', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="change_post_editor_font_size"><?php _e( 'Change Font Size:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input class="small-text" name="change_post_editor_font_size" value="<?php echo WPEditorSetting::get_value( 'change_post_editor_font_size' ); ?>" />
									</li>
									<li class="indent description">
										<p><?php _e("This will set the font size in pixels for the post editor.<br />Default: 12", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-post-line-numbers" class="section">
							<div class="section-header">
								<h3><?php _e( 'Line Numbers', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_post_line_numbers"><?php _e( 'Enable Line Numbers:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="enable_post_line_numbers" value="1" <?php echo (WPEditorSetting::get_value( 'enable_post_line_numbers' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="enable_post_line_numbers" value="0" <?php echo (WPEditorSetting::get_value( 'enable_post_line_numbers' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will enable line numbers for the post editor.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-post-line-wrapping" class="section">
							<div class="section-header">
								<h3><?php _e( 'Line Wrapping', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_post_line_wrapping"><?php _e( 'Enable Line Wrapping:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="enable_post_line_wrapping" value="1" <?php echo (WPEditorSetting::get_value( 'enable_post_line_wrapping' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="enable_post_line_wrapping" value="0" <?php echo (WPEditorSetting::get_value( 'enable_post_line_wrapping' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will enable line wrapping for the post editor.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-post-active-line" class="section">
							<div class="section-header">
								<h3><?php _e( 'Active Line Highlighting', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_post_active_line"><?php _e( 'Highlight Active Line:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input type="radio" name="enable_post_active_line" value="1" <?php echo (WPEditorSetting::get_value( 'enable_post_active_line' ) == 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'Yes', 'wp-editor' ); ?>
										<input type="radio" name="enable_post_active_line" value="0" <?php echo (WPEditorSetting::get_value( 'enable_post_active_line' ) != 1 ) ? 'checked="checked"' : ''; ?>> <?php _e( 'No', 'wp-editor' ); ?>
									</li>
									<li class="indent description">
										<p><?php _e("This will enable highlighting of the active line for the post editor.<br />Default: Yes", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="post-indent-unit" class="section">
							<div class="section-header">
								<h3><?php _e( 'Indent Size', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="post_indent_unit"><?php _e( 'Indent Size:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input class="small-text" name="post_indent_unit" value="<?php echo WPEditorSetting::get_value( 'post_indent_unit' ); ?>" />
									</li>
									<li class="indent description">
										<p><?php _e("This will set the size of the indent.<br />Default: 2", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-post-tab-characters" class="section">
							<div class="section-header">
								<h3><?php _e( 'Tab Characters', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_post_tab_characters"><?php _e( 'Tab Characters:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<select name="enable_post_tab_characters">
											<option value="spaces"<?php echo WPEditorSetting::get_value( 'enable_post_tab_characters' ) == 'spaces' ? ' selected="selected"' : ''; ?>><?php _e( 'Spaces', 'wp-editor' ); ?></option>
											<option value="tabs"<?php echo WPEditorSetting::get_value( 'enable_post_tab_characters' ) == 'tabs' ? ' selected="selected"' : ''; ?>><?php _e( 'Tabs', 'wp-editor' ); ?></option>
										</select>
									</li>
									<li class="indent description">
										<p><?php _e("This will set the tab character for the post editor.<br />Default: Spaces", 'wp-editor' ); ?></p>
									</li>
									<li>
										<label for="enable_post_tab_size"><?php _e( 'Tab Size:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input class="small-text" name="enable_post_tab_size" value="<?php echo WPEditorSetting::get_value( 'enable_post_tab_size' ); ?>" />
									</li>
									<li class="indent description">
										<p><?php _e("This will set the tab size for the post editor.<br />Default: 2", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="enable-post-editor-height" class="section">
							<div class="section-header">
								<h3><?php _e( 'Editor Height', 'wp-editor' ); ?></h3>
							</div>
							<div class="section-body">
								<ul>
									<li>
										<label for="enable_post_editor_height"><?php _e( 'Editor Height:', 'wp-editor' ); ?></label>
									</li>
									<li class="indent">
										<input class="small-text" name="enable_post_editor_height" value="<?php echo WPEditorSetting::get_value( 'enable_post_editor_height' ); ?>" />
									</li>
									<li class="indent description">
										<p><?php _e("This will set the height in pixels for the post editor.<br />Default: 450", 'wp-editor' ); ?></p>
									</li>
								</ul>
							</div>
						</div>
						<div id="save-settings">
							<ul>
								<li>
									<input type='submit' name='submit' class="button-primary" value="<?php _e( 'Save Settings', 'wp-editor' ); ?>" />
								</li>
							</ul>
						</div>
					</form>
				</div>
				<div id="settings-overview" class="settings-body">
					<div id="wpeditor-overview" class="section">
						<div class="section-header">
							<h3><?php _e( 'WP Editor Overview', 'wp-editor' ); ?></h3>
						</div>
						<div class="section-body">
							<ul>
								<li>
									<p><strong><?php _e( 'What is WP Editor?', 'wp-editor' ); ?></strong></p>
								</li>
								<li class="indent">
									<p><?php _e( 'WP Editor is a plugin for WordPress that replaces the default plugin and theme editors.	Using integrations with <a href="http://codemirror.net" target="_blank">CodeMirror</a> and <a href="http://fancybox.net" target="_blank">FancyBox</a> to create a feature rich environment, WP Editor completely reworks the default WordPress file editing capabilities.	Using Asynchronous Javascript and XML (AJAX) to retrieve files and folders, WP Editor sets a new standard for speed and reliability in a web-based editing atmosphere.', 'wp-editor' ); ?></p>
								</li>
								<li>
									<br />
									<p><strong><?php _e( 'Features', 'wp-editor' ); ?></strong></p>
								</li>
								<li class="indent">
									<ul class="normal_list">
										<li><strong><a href="http://codemirror.net" target="_blank"><?php _e( 'CodeMirror', 'wp-editor' ); ?></a></strong></li>
										<ul class="normal_list">
											<li><?php _e( 'Active Line Highlighting', 'wp-editor' ); ?></li>
											<li><?php _e( 'Line Numbers', 'wp-editor' ); ?></li>
											<li><?php _e( 'Line Wrapping', 'wp-editor' ); ?></li>
											<li><?php _e( 'Eight Editor Themes with Syntax Highlighting', 'wp-editor' ); ?></li>
											<li><?php _e( 'Fullscreen Editing (ESC, F11)', 'wp-editor' ); ?></li>
											<li><?php _e( 'Text Search (CMD + F, CTRL + F)', 'wp-editor' ); ?></li>
											<li><?php _e( 'Individual Settings for Each Editor', 'wp-editor' ); ?></li>
										</ul>
										<li><<?php _e( 'strong><a href="http://fancybox.net" target="_blank">FancyBox</a> for image viewing', 'wp-editor' ); ?></strong></li>
										<li><strong><?php _e( 'AJAX File Browser', 'wp-editor' ); ?></strong></li>
										<li><strong><?php _e( 'Allowed Extensions List', 'wp-editor' ); ?></strong></li>
										<li><strong><?php _e( 'Easy to use Settings Section', 'wp-editor' ); ?></strong></li>
									</ul>
								</li>
								<li>
									<br />
									<p><strong><?php _e( 'The Future of WP Editor', 'wp-editor' ); ?></strong></p>
								</li>
								<li class="indent">
									<p><?php _e( 'WP Editor is brand new! This means that there is a lot more work that will be going into the plugin to make it better. Since it is currently in Beta mode, we would appreciate all the feedback you can give to make this a better product.	Please visit <a href="http://wpeditor.net/beta">http://wpeditor.net/beta</a> to give feedback, request features or just to leave a comment for the developers.	We would appreciate any input you can give!', 'wp-editor' ); ?></p>
									<p><?php _e( 'That being said, please be patient with us as we are working on this project in our spare time. While we hope that WP Editor can remain free, it does cost us to continue to develop and maintain it. If you feel so inclined, we would appreciate any donation that you might give to enable us to spend more time developing the plugin and keeping this great product up to date! Use the Donate button below to keep WP Editor free!', 'wp-editor' ); ?></p>
									<p>
										<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DCFRQLH3DMMS4" target="_blank" class="donate">Donate Today!</a>
									</p>
								</li>
								<li>
									<br />
									<p><strong><?php _e( 'Support', 'wp-editor' ); ?></strong></p>
								</li>
								<li class="indent">
									<p><?php _e( 'Support for WP Editor is provided on a first-come, first-serve basis. You can however, get to the top of the queue by paying a per-incident fee based on what you feel your ticket is worth.	The more you pay, the faster we will get to your ticket. Following are a few examples:', 'wp-editor' ); ?></p>
									<ul class="normal_list">
										<li><?php _e( '$1.00+ - We will answer your inquiry before we answer anyone who hasn\'t paid yet', 'wp-editor' ); ?></li>
										<li><?php _e( '$5.00+ - We will answer your inquiry before the $1.00 inquiries', 'wp-editor' ); ?></li>
										<li><?php _e( '$50.00+ - We will answer your inquiry within a 24 hour period', 'wp-editor' ); ?></li>
										<li><?php _e( '$100.00+ - We will answer your inquiry within a 12 hour period', 'wp-editor' ); ?></li>
										<li><?php _e( '$1000.00+ - We will stop what we are doing to answer your inquiry', 'wp-editor' ); ?></li>
										<li><?php _e( '$100,000.00+ - We will consider quitting our day jobs to make sure your issue is resolved', 'wp-editor' ); ?></li>
									</ul>
									<p><?php _e( 'To get support, please visit <a href="http://wpeditor.net/support" target="_blank">http://wpeditor.net/support</a>. If you want paid support, create your support request and then click on the button below to make a payment and put the text you entered in the "brief description" into the "Add special instructions to the seller" box of the payment form.', 'wp-editor' ); ?></p>
									<p>
										<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YEPC72BPT73PC" target="_blank" class="support">Pay for Support</a>
									</p>
								</li>
								<li class="indent description">
									<br />
									<p><?php _e( 'To learn more, please visit <a href="http://wpeditor.net" target="_blank">http://wpeditor.net</a>', 'wp-editor' ); ?></p>
								</li>
							</ul>
						</div>
				</div>
			</div>
			<br clear="both" />
		</div>
	</div>
</div>
<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			settingsTabs( '<?php echo $tab; ?>' );
		})
	})(jQuery);
</script>