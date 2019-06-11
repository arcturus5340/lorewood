<div id="save-result"></div>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e( 'Edit Plugins', 'wp-editor' ); ?></h2>
	<?php if ( in_array( $data['file'], (array) get_option( 'active_plugins', array() ) ) ): ?>
		<div class="updated">
			<p><?php _e( '<strong>This plugin is currently activated!<br />Warning:</strong> Making changes to active plugins is not recommended.	If your changes cause a fatal error, the plugin will be automatically deactivated.', 'wp-editor' ); ?></p>
		</div>
	<?php endif; ?>
	<?php if ( isset( $_GET['create-plugin'] ) && $_GET['create-plugin'] == 'success' ): ?>
		<div class="updated">
			<p><?php _e( '<strong>Your plugin was successfully created!</strong>', 'wp-editor' ); ?></p>
		</div>
	<?php endif; ?>
	<?php if ( isset( $_GET['error'] ) ): ?>
		<div class="error">
			<?php if ( $_GET['error'] == 1 ): ?>
				<p><strong><?php _e( 'You do not have sufficient permissions to download this plugin.', 'wp-editor' ); ?></strong></p>
			<?php elseif ( $_GET['error'] == 2 ): ?>
				<p><strong><?php _e( 'There was an error locating the file to download. Please try again later.', 'wp-editor' ); ?></strong></p>
			<?php elseif ( $_GET['error'] == 3 ): ?>
				<p><strong><?php _e( 'There was an error compressing the plugin files. Please try again later.', 'wp-editor' ); ?></strong></p>
			<?php elseif ( $_GET['error'] == 4 ): ?>
				<p><strong><?php _e( 'You do not have sufficient permissions to download this file.', 'wp-editor' ); ?></strong></p>
			<?php elseif ( $_GET['error'] == 5 ): ?>
				<p><strong><?php _e( 'Your plugin details were invalid. Please try again.', 'wp-editor' ); ?></strong></p>
			<?php elseif ( $_GET['error'] == 6 ): ?>
				<p><strong><?php _e( 'There was an error creating the plugin. Please try again later.', 'wp-editor' ); ?></strong></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="fileedit-sub">
		<div class="alignleft">
			<h3>
				<?php
					if ( is_plugin_active( $data['plugin'] ) ) {
						if ( is_writable( $data['real_file'] ) ) {
							echo __( 'Editing <span class="current_file">', 'wp-editor' ) . $data['file'] . __( '</span> (active)', 'wp-editor' );
						}
						else {
							echo __( 'Browsing <span class="current_file">', 'wp-editor' ) . $data['file'] . __( '</span> (active)', 'wp-editor' );
						}
					} else {
						if ( is_writable( $data['real_file'] ) ) {
							echo __( 'Editing <span class="current_file">', 'wp-editor' ) . $data['file'] . __( '</span> (inactive)', 'wp-editor' );
						}
						else {
							echo __( 'Browsing <span class="current_file">', 'wp-editor' ) . $data['file'] . __( '</span> (inactive)', 'wp-editor' );
						}
					}
				?>
			</h3>
		</div>
		<div class="alignright">
			<form action="plugins.php?page=wpeditor_plugin" method="post">
				<strong><label for="plugin"><?php _e( 'Select plugin to edit:', 'wp-editor' ); ?></label></strong>
				<select name="plugin" id="plugin">
					<?php
						foreach( $data['plugins'] as $plugin_key => $a_plugin ) {
							$plugin_name = $a_plugin['Name'];
							if ( $plugin_key == $data['plugin'] ) {
								$selected = ' selected="selected"';
							}
							else {
								$selected = '';
							}
							$plugin_name = esc_attr( $plugin_name );
							$plugin_key = esc_attr( $plugin_key ); ?>
							<option value="<?php echo $plugin_key; ?>" <?php echo $selected; ?>><?php echo $plugin_name; ?></option>
						<?php
						}
					?>
				</select>
				<input type='submit' name='submit' class="button-secondary" value="<?php _e( 'Select', 'wp-editor' ); ?>" />
			</form>
		</div>
		<br class="clear" />
	</div>

	<div id="templateside">
		<?php if ( WPEditorSetting::get_value( 'plugin_file_upload' ) ): ?>
			<h3><?php _e( 'Upload Files', 'wp-editor' ); ?></h3>
			<div id="plugin-upload-files">
				<?php if ( is_writable( $data['real_file'] ) ): ?>
					<form enctype="multipart/form-data" id="plugin_upload_form" method="POST">
							<!-- MAX_FILE_SIZE must precede the file input field -->
							<!--input type="hidden" name="MAX_FILE_SIZE" value="30000" /-->
							<p class="description">
								<?php _e( 'To', 'wp-editor' ); ?>: <?php echo basename(dirname( $data['current_plugin_root'] ) ) . '/' . basename( $data['current_plugin_root'] ) . '/'; ?>
							</p>
							<input type="hidden" name="current_plugin_root" value="<?php echo $data['current_plugin_root']; ?>" id="current_plugin_root" />
							<input type="text" name="directory" id="file_directory" style="width:190px" placeholder="<?php _e( 'Optional: Sub-Directory', 'wp-editor' ); ?>" />
							<!-- Name of input element determines name in $_FILES array -->
							<input name="file" type="file" id="upload_file" style="width:180px" />
							<div class="ajax-button-loader">
								<?php submit_button( __( 'Upload File', 'wp-editor' ), 'primary', 'submit', false ); ?>
								<div class="ajax-loader"></div>
							</div>
					</form>
				<?php else: ?>
					<p>
						<em><?php _e( 'You need to make this folder writable before you can upload any files. See <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">the Codex</a> for more information.' ); ?></em>
					</p>
				<?php endif; ?>
			</div>
			<div id="upload_message"></div>
		<?php endif; ?>
		
		<h3><?php _e( 'Plugin Files', 'wp-editor' ); ?></h3>
		<div id="plugin-editor-files">
			<ul id="plugin-folders" class="plugin-folders"></ul>
		</div>
	</div>
	
	<form name="template" id="template_form" action="" method="post" class="ajax-editor-update" style="float:left width:auto;overflow:hidden;position:relative;">
		<?php wp_nonce_field( 'edit-plugin_' . $data['real_file'] ); ?>
		<div>
			<textarea cols="70" rows="25" name="new-content" id="new-content" tabindex="1"><?php echo $data['content']; ?></textarea>
			<input type="hidden" name="action" value="save_files" />
			<input type="hidden" name="_success" id="_success" value="<?php _e( 'The file has been updated successfully.', 'wp-editor' ); ?>" />
			<input type="hidden" id="file" name="file" value="<?php echo esc_attr( $data['file'] ); ?>" />
			<input type="hidden" id="plugin-dirname" name="plugin" value="<?php echo esc_attr( $data['plugin'] ); ?>" />
			<input type="hidden" id="path" name="path" value="<?php echo esc_attr( $data['real_file'] ); ?>" />
			<input type="hidden" name="scroll_to" id="scroll_to" value="<?php echo $data['scroll_to']; ?>" />
			<input type="hidden" name="content-type" id="content-type" value="<?php echo $data['content-type']; ?>" />
			<?php
				$pathinfo = pathinfo( $data['plugin'] );
			?>
			<input type="hidden" name="extension" id="extension" value="<?php echo $pathinfo['extension']; ?>" />
		</div>
		<p class="submit">
			<?php if ( isset( $_GET['phperror'] ) ): ?>
				<input type="hidden" name="phperror" value="1" />
				<input type="submit" name="submit" class="button-primary" value="<?php _e( 'Update File and Attempt to Reactivate', 'wp-editor' ); ?>" />
			<?php else: ?>
				<input type="submit" name='submit' class="button-primary" value="<?php _e( 'Update File', 'wp-editor' ); ?>" />
			<?php endif; ?>
			<?php if ( WPEditorSetting::get_value( 'plugin_create_new' ) ): ?>
				<input type="button" name="plugin-create-new" class="button-primary plugin-create-new" value="<?php _e( 'Create New Plugin', 'wp-editor' ); ?>" />
			<?php endif; ?>
			<input type="button" class="button-secondary download-file" value="<?php _e( 'Download File', 'wp-editor' ); ?>" />
			<input type="button" class="button-secondary download-plugin" value="<?php _e( 'Download Plugin', 'wp-editor' ); ?>" />
		</p>
		<?php if ( ! is_writable( $data['real_file'] ) ): ?>
			<div class="error writable-error">
				<p>
					<em><?php _e( 'You need to make this file writable before you can save your changes. See <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">the Codex</a> for more information.' ); ?></em>
				</p>
			</div>
		<?php endif; ?>
	</form>
	<form name="plugin_create_form" id="plugin_create_form" style="display:none;" action="plugins.php?page=wpeditor_plugin" method="post">
		<?php wp_nonce_field( 'create_plugin_new', 'create_plugin_new' ); ?>
		<div>
			<?php if ( is_writable( WP_PLUGIN_DIR) ): ?>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><?php _e( 'Plugin Name', 'wp-editor' ); ?></th>
							<td>
								<input type="text" name="plugin-name" />
								<p class="description"><?php _e( 'Enter the name that you want to use for your new plugin.', 'wp-editor' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Plugin Folder', 'wp-editor' ); ?></th>
							<td>
								<input type="text" name="plugin-folder" />
								<p class="description"><?php _e( 'Enter the folder name that you want to use to create your new plugin. This will be the name of the new folder that is created and added to your plugins directory.', 'wp-editor' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Plugin Filename', 'wp-editor' ); ?></th>
							<td>
								<input type="text" name="plugin-filename" />
								<p class="description"><?php _e( 'Enter the filename that you want to use to create your new plugin. This will be the name of the file that is created and added to the folder specified above.', 'wp-editor' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"></th>
							<td>
								<?php submit_button( __( 'Create Plugin', 'wp-editor' ), 'primary', 'submit', false ); ?>
								<input type="button" name="cancel-plugin-create" class="cancel-plugin-create button-primary" value="<?php _e( 'Cancel', 'wp-editor' ); ?>" />
							</td>
						</tr>
					</tbody>
				</table>
			<?php else: ?>
				<p><?php _e( 'Your plugin folder is not writable.	In order to add a new plugin, this folder needs to be writable.', 'wp-editor' ); ?></p>
				<input type="button" name="cancel-plugin-create" class="cancel-plugin-create button-primary" value="<?php _e( 'Cancel', 'wp-editor' ); ?>" />
			<?php endif; ?>
		</div>
	</form>
	<?php if ( isset( $_GET['create_tab'] ) ): ?>
		<script type="text/javascript">
			(function( $){
				$(document).ready(function() {
					$( '#template_form, #templateside, .updated.below-h2, .fileedit-sub' ).hide();
					$( '#plugin_create_form' ).show();
				})
			})(jQuery);
		</script>	
	<?php endif; ?>
	<form action="" method="post" id="download_plugin_form">
		<input type="hidden" name="file" value="<?php echo esc_attr( $data['file'] ); ?>" />
		<input type="hidden" name="download_plugin" value="true" />
	</form>
	<form action="" method="post" id="download_file_form">
		<input type="hidden" name="file_path" id="file_path" value="<?php echo esc_attr( $data['real_file'] ); ?>" />
		<input type="hidden" name="download_plugin_file" value="true" />
	</form>
	<script type="text/javascript">
		(function( $){
			$(document).ready(function(){
				$( 'a.nivo-lightbox' ).nivoLightbox();
				$( '.cancel-plugin-create' ).click(function() {
					$( '#template_form, #templateside, .updated.below-h2, .fileedit-sub' ).show();
					$( '#plugin_create_form' ).hide();
				});
				$( '.plugin-create-new' ).click(function() {
					$( '#template_form, #templateside, .updated.below-h2, .fileedit-sub' ).hide();
					$( '#plugin_create_form' ).show();
				});
				$( '#template_form' ).submit(function(){ 
					$( '#scroll-to' ).val( $( '#new-content' ).scrollTop() ); 
				});
				$( '#new-content' ).scrollTop( $( '#scroll-to' ).val() );
				enablePluginAjaxBrowser( '<?php echo urlencode(( WPWINDOWS) ? str_replace("/", "\\", $data["real_file"] ) : $data["real_file"] ); ?>' );
				runCodeMirror( '<?php echo $pathinfo["extension"]; ?>' );
				$( '.ajax-loader' ).hide();
				$( '.download-plugin' ).click(function(e ) {
					e.preventDefault();
					$( '#download_plugin_form' ).submit();
				});
				$( '.download-file' ).click(function(e ) {
					e.preventDefault();
					$( '#download_file_form' ).submit();
				});
				$( '#plugin_upload_form' ).submit(function() {
					$( '.ajax-loader' ).show();

					var data = new FormData();
					$.each( $( 'input[type=file]' )[0].files, function( i, file ) {
						data.append( 'file-'+i, file );
					});
					data.append( 'action', 'upload_files' );
					data.append( 'wp_editor_ajax_nonce_upload_file_plugin', '<?php echo wp_create_nonce( "wp_editor_ajax_nonce_upload_file_plugin" ); ?>' );
					data.append( 'current_plugin_root', $( '#current_plugin_root' ).val() );
					data.append( 'directory', $( '#file_directory' ).val() );
					$.ajax({
						type: "POST",
						url: ajaxurl,
						data: data,
						contentType: false,
						processData: false,
						dataType: 'json',
						success: function(result) {
							if (result.error[0] === 0) {
								enablePluginAjaxBrowser( '<?php echo urlencode(( WPWINDOWS) ? str_replace("/", "\\", $data["real_file"] ) : $data["real_file"] ); ?>' );
								$( '#upload_message' ).html( '<p class="WPEditorAjaxSuccess" style="padding:5px;">' + result.success + '</p>' );
							}
							if (result.error[0] === -2) {
								$( '#upload_message' ).html( '<p class="WPEditorAjaxError" style="padding:5px;">' + result.error[1] + '</p>' );
							}
							else if (result.error[0] === -1 ) {
								$( '#upload_message' ).html( '<p class="WPEditorAjaxError" style="padding:5px;">' + result.error[1] + '</p>' );
							}
							$( '.ajax-loader' ).hide();
						}
					});
					return false;
				});
			})
		})(jQuery);
		function runCodeMirror(extension ) {
			if (extension === 'php' ) {
				var mode = 'application/x-httpd-php';
			}
			else if (extension === 'css' ) {
				var mode = 'css';
			}
			else if (extension === 'js' ) {
				var mode = 'javascript';
			}
			else if (extension === 'html' || extension === 'htm' ) {
				var mode = 'text/html';
			}
			else if (extension === 'xml' ) {
				var mode = 'application/xml';
			}
			<?php if ( WPEditorSetting::get_value( 'plugin_editor_theme' ) ): ?>
				var theme = '<?php echo WPEditorSetting::get_value("plugin_editor_theme"); ?>';
			<?php else: ?>
				var theme = 'default';
			<?php endif; ?>
			var activeLine = false;
			<?php if ( WPEditorSetting::get_value( 'enable_plugin_active_line' ) ): ?>
				var activeLine = 'activeline-' + theme;
			<?php endif; ?>
			wp_editor = CodeMirror.fromTextArea(document.getElementById( 'new-content' ), {
				mode: mode,
				theme: theme,
				<?php if ( WPEditorSetting::get_value( 'enable_plugin_line_numbers' ) ): ?>
					lineNumbers: true,
				<?php endif;
				if ( WPEditorSetting::get_value( 'enable_plugin_line_wrapping' ) ): ?>
					lineWrapping: true,
				<?php endif; ?>
				indentUnit: <?php echo WPEditorSetting::get_value( 'plugin_indent_unit' ) == '' ? 2 : WPEditorSetting::get_value( 'plugin_indent_unit' ); ?>,
				<?php if ( WPEditorSetting::get_value( 'enable_plugin_tab_characters' ) && WPEditorSetting::get_value( 'enable_plugin_tab_characters' ) == 'tabs' ): ?>
					indentWithTabs: true,
				<?php endif;
				if ( WPEditorSetting::get_value( 'enable_plugin_tab_size' ) ): ?>
					tabSize: <?php echo WPEditorSetting::get_value( 'enable_plugin_tab_size' ); ?>,
				<?php else: ?>
					tabSize: 2,
				<?php endif; ?>
				onCursorActivity: function() {
					if (activeLine ) {
						wp_editor.addLineClass(hlLine, null, null);
						hlLine = wp_editor.addLineClass(wp_editor.getCursor().line, null, activeLine );
					}
				},
				onChange: function() {
					changeTrue();
				},
				extraKeys: {
					"F11": function(cm) {
						cm.setOption("fullScreen", !cm.getOption("fullScreen"));
					},
					"Esc": function(cm) {
						if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
					}
				} // set fullscreen options here
			});
			$jq( '.CodeMirror' ).css( 'font-size', '<?php echo WPEditorSetting::get_value("change_plugin_editor_font_size") ? WPEditorSetting::get_value("change_plugin_editor_font_size") . "px" : "12px"; ?>' );
			if (activeLine ) {
				var hlLine = wp_editor.addLineClass(0, activeLine );
			}
			<?php if ( WPEditorSetting::get_value( 'enable_plugin_editor_height' ) ): ?>
				$jq = jQuery.noConflict();
				$jq( '.CodeMirror-scroll, .CodeMirror' ).height( '<?php echo WPEditorSetting::get_value("enable_plugin_editor_height"); ?>px' );
				var scrollDivHeight = $jq( '.CodeMirror-scroll div:first-child' ).height();
				var editorDivHeight = $jq( '.CodeMirror' ).height();
				if (scrollDivHeight > editorDivHeight) {
					$jq( '.CodeMirror-gutter' ).height(scrollDivHeight);
				}
			<?php endif; ?>
			if (!$jq( '.CodeMirror .quicktags-toolbar' ).length) {
				$jq( '.CodeMirror' ).prepend( '<div class="quicktags-toolbar">' + 
					'<a href="#" class="button-primary editor-button" id="plugin_save">save</a>&nbsp;' + 
					'<a href="#" class="button-secondary editor-button" id="plugin_undo">undo</a>&nbsp;' + 
					'<a href="#" class="button-secondary editor-button" id="plugin_redo">redo</a>&nbsp;' + 
					'<a href="#" class="button-secondary editor-button" id="plugin_search">search</a>&nbsp;' + 
					'<a href="#" class="button-secondary editor-button" id="plugin_find_prev">find prev</a>&nbsp;' + 
					'<a href="#" class="button-secondary editor-button" id="plugin_find_next">find next</a>&nbsp;' + 
					'<a href="#" class="button-secondary editor-button" id="plugin_replace">replace</a>&nbsp;' + 
					'<a href="#" class="button-secondary editor-button" id="plugin_replace_all">replace all</a>&nbsp;' + 
					'<a href="#" class="button-secondary editor-button" id="plugin_fullscreen">fullscreen</a>&nbsp;' + 
					'</div>'
				);
				$jq( '.CodeMirror-scroll' ).height( $jq( '.CodeMirror-scroll' ).height() - 33);
				wp_editor.focus();
			}
			$jq( '#plugin_fullscreen' ).on("click", function() {
				if (wp_editor.getOption("fullScreen")) {
					wp_editor.setOption("fullScreen", false);
				}
				else {
					wp_editor.setOption("fullScreen", true);
				}
				wp_editor.focus();
			})
			$jq( '#plugin_save' ).on("click", function() {
				$jq( '.ajax-editor-update' ).submit();
				wp_editor.focus();
			})
			$jq( '#plugin_undo' ).on("click", function() {
				wp_editor.undo();
				wp_editor.focus();
			})
			$jq( '#plugin_redo' ).on("click", function() {
				wp_editor.redo();
				wp_editor.focus();
			})
			$jq( '#plugin_search' ).on("click", function() {
				CodeMirror.commands.find(wp_editor);
				return false;
			})
			$jq( '#plugin_find_next' ).on("click", function() {
				CodeMirror.commands.findNext(wp_editor);
				return false;
			})
			$jq( '#plugin_find_prev' ).on("click", function() {
				CodeMirror.commands.findPrev(wp_editor);
				return false;
			})
			$jq( '#plugin_replace' ).on("click", function() {
				CodeMirror.commands.replace(wp_editor);
				return false;
			})
			$jq( '#plugin_replace_all' ).on("click", function() {
				CodeMirror.commands.replaceAll(wp_editor);
				return false;
			})
		}
	</script> 
</div>
<div class="alignright">
</div>
<br class="clear" />