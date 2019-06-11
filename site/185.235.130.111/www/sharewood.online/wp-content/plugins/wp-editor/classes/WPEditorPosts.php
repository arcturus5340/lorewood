<?php
class WPEditorPosts {
	
	public static function add_posts_jquery( $editor ) {
		global $post;
		if ( WPEditorSetting::get_value( 'enable_post_editor' ) ) {
			$theme = WPEditorSetting::get_value( 'post_editor_theme' ) ? WPEditorSetting::get_value( 'post_editor_theme' ) : 'default';
			$activeLine = WPEditorSetting::get_value( 'enable_post_active_line' ) == 1 ? 'activeline-' . $theme : false;
			$post_editor_settings = array(
				'mode' => 'text/html',
				'theme' => $theme,
				'activeLine' => $activeLine,
				'lineNumbers' => WPEditorSetting::get_value( 'enable_post_line_numbers' ) == 1 ? true : false,
				'lineWrapping' => WPEditorSetting::get_value( 'enable_post_line_wrapping' ) == 1 ? true : false,
				'enterImgUrl' => __( 'Enter the URL of the image:', 'wp-editor' ),
				'enterImgDescription' => __( 'Enter a description of the image:', 'wp-editor' ),
				'lookupWord' => __( 'Enter a word to look up:', 'wp-editor' ),
				'tabSize' => WPEditorSetting::get_value( 'enable_post_tab_size' ) ? WPEditorSetting::get_value( 'enable_post_tab_size' ) : 4,
				'indentWithTabs' => WPEditorSetting::get_value( 'enable_post_tab_characters' ) == 'tabs' ? true : false,
				'indentUnit' => WPEditorSetting::get_value( 'post_indent_unit' ) == '' ? 2 : WPEditorSetting::get_value( 'post_indent_unit' ),
				'editorHeight' => WPEditorSetting::get_value( 'enable_post_editor_height' ) ? WPEditorSetting::get_value( 'enable_post_editor_height' ) : false,
				'fontSize' => WPEditorSetting::get_value("change_post_editor_font_size") ? WPEditorSetting::get_value("change_post_editor_font_size") . "px" : "12px",
				'save' => isset( $post->post_status ) && $post->post_status == 'publish' ? __( 'Update', 'wp-editor' ) : __( 'Save', 'wp-editor' )
			);
			WPEditorAdmin::editor_stylesheet_and_scripts();
			wp_enqueue_script( 'wp-editor-posts-jquery' );
			wp_localize_script( 'wp-editor-posts-jquery', 'WPEPosts', $post_editor_settings );
		}
		return $editor;
	}
	
}