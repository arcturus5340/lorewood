=== WP Editor ===
Contributors: benjaminprojas
Donate link: http://wpeditor.net/
Tags: code editor, plugin editor, theme editor, page editor, post editor, pages, posts, html, codemirror, plugins, themes, editor, fancybox, post.php, post-new.php, ajax, syntax highlighting, html syntax highlighting
Requires at least: 3.0
Tested up to: 4.6.1
Stable tag: 1.2.6.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP Editor is a plugin for WordPress that replaces the default plugin and theme editors as well as the page/post editor.

== Description ==

WP Editor is a plugin for WordPress that replaces the default plugin and theme editors as well as the page/post editor. Using integrations with CodeMirror and FancyBox to create a feature rich environment, WP Editor completely reworks the default WordPress file editing capabilities. Using Asynchronous Javascript and XML (AJAX) to retrieve files and folders, WP Editor sets a new standard for speed and reliability in a web-based editing atmosphere.

= Features: =
* CodeMirror
* Active Line Highlighting
* Line Numbers
* Line Wrapping
* Eight Editor Themes with Syntax Highlighting
* Fullscreen Editing (ESC, F11)
* Text Search (CMD + F, CTRL + F)
* Individual Settings for Each Editor
* FancyBox for image viewing
* AJAX File Browser
* Allowed Extensions List
* Easy to use Settings Section

== Installation ==

1. Upload the `wp-editor.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions == 

= Does WP Editor provide syntax highlighting for the page/post editor? =
Yes! This feature was added in version 1.1 of WP Editor. If it's not enabled by default, visit the settings page, click on the "Post Editor" section and enable it there.

= Can I search for text within a file? =
Yes! Just use the key combination, CTRL + F or CMD + F and it will open up a search dialog box that will allow you to type in a search string.

= Does WP Editor have a fullscreen mode? =
Yes! Just hit the Esc key or click on the fullscreen button on the post editor and it will take the editor to the full size of the browser.

= I don't like the active line highlighting in the editor, can I disable this? =
Yes! Just visit the settings page and you can disable the active line highlighting there.

= A file type that my theme uses is not supported by WP Editor, how can I get it added? =
If there is a filetype that is not supported by WP Editor, just visit the [WP Editor support page](http://wpeditor.net/support) and fill out the support form.  We will do our best to include it by the next release.

= My custom quicktags don't work with WP Editor, why not? =
WP Editor uses a custom method for creating quicktags to get them to work with the update.  If you use a specific quicktag that is not supported, fill out our [support form](http://wpeditor.net/support) and we will try to implement it as soon as possible.

= Is WP Editor better than the competition? =
Yes! Well, we like to think so.  If there is a feature that another plugin has that you think WP Editor should include, let us know. We will do our best to add it in to the plugin!

= I love WP Editor! Can I help somehow? =
Yes! While we don't have a need for further developers at this time, any financial contributions are welcome!  Just visit the [WP Editor](http://wpeditor.net) website and click on the donate link, and thank you!

== Screenshots ==

1. Settings page for WP Editor
2. Syntax highlighting in Page/Post editor
3. Updated plugin editor with Ajax file browser
4. Updated theme editor with file upload enabled
5. Fancybox integration

== Changelog ==

= 1.2.6.3 =
* Fixed multiple XSS vulnerabilities

= 1.2.6.2 =
* Fixed issues with QuickTags not working reliably
* Fixed issues with inserting images not working reliably

= 1.2.6.1 =
* Fixed Firefox conflict: NS_ERROR_FAILURE
* Fixed 'Add Media' button not working

= 1.2.6 =
* Added Nivo Lightbox to replace outdated Fancybox
* Added support for SCSS files
* Updated CodeMirror library to 5.13.4 including all extensions
* Updated CodeMirror instance to use wp_editor to avoid conflicts
* Updated quicktags to use default WordPress quicktags
* Updated ajax requests to use wp_json_encode
* Updated registering CodeMirror script to try to avoid conflicts
* Fixed CSRF and permissions vulnerabilities
* Fixed issue with duplicate editors on page/post editor
* Fixed broken functions in plugin/theme editors
* Fixed duplicating backslashes for unc paths

= 1.2.5.3 =
* Update language text domain

= 1.2.5.2 =
* Fixed compatibility with WordPress 4.3

= 1.2.5.1 =
* Fixed javascript error that broke the editors

= 1.2.5 =
* Added the ability to change the indent size
* Fixed javascript error when clicking preview in posts view
* Fixed file download not working on all plugin/theme files
* Fixed missing CSS styles for quicktags

= 1.2.4 =
* Added ability to edit .xml files
* Fixed font issue in fullscreen mode
* Fixed fullscreen save not working

= 1.2.3 =
* Added ability to create new plugins and themes from inside the plugin/theme editors
* Added ability to download plugins and themes
* Added ability to download individual plugin and theme files being edited
* Added ability to save page/post editor in fullscreen mode
* Updated width of AJAX Browser in plugin/theme editors
* Fixed QuickPress editor not working for Author Role
* Fixed PHP Warning when viewing drop-ins and mustuse plugins
* Fixed display of warning message for active theme
* Fixed typos on settings page

= 1.2.2 = 
* Fixed issues with PHP 5.4+
* Fixed issue with selecting theme file types not working
* Fixed issue with double scrollbar
* Fixed issue with error message appearing when trying to save a post/page
* Fixed issue with settings link not appearing for symlink installs

= 1.2.1 =
* Added feature to preserve scroll position in post editor on save and toggle between Visual/Text views
* Added z-index fix for gutter when WordPress menu is collapsed
* Added ability to edit .less files
* Added ability to change the editor font size for plugin, theme and page/post editors
* Fixed window resize issue with codemirror expanding past its boundaries

= 1.2 =
* Added Save, Undo, Redo, Search, Find Next, Find Prev, Replace, Replace All and Fullscreen buttons to plugin/theme editors
* Added ability to hide WP Editor menu icon from menu sidebar and move it to settings submenu
* Added ability to check if file is writable and add error messages accordingly in plugin/theme editors
* Added highlighting of shortcodes in Post editor
* Updated is_writeable() to is_writable()
* Fixed conflicts with TinyMCE Advanced and Ultimate TinyMCE plugins
* Fixed conflict with WooThemes themes

= 1.1.0.3 =
* Added Ambiance, Blackboard, Lesser Dark, Vibrant Ink and XQ-Dark themes
* Fixed text selection in all editors
* Fixed post editor highlighter not loading when visual editor is disabled

= 1.1.0.2 = 
* Added ability to customize tab characters and size for all editors
* Added ability to set custom editor heights for all editors
* Updated CodeMirror library to 2.33
* Updated CSS to work with new version of CodeMirror
* Fixed issue with media button toolbar not inserting shortcodes/content when in visual mode
* Fixed issue with blockquote QuickTag inserting twice

= 1.1.0.1 = 
* Fixed Upload/Insert media buttons not working in page/post editor
* Removed legacy CSS theme files

= 1.1 = 
* Added syntax highlighter for page/post editor
* Added in monospace font for default theme/plugin/post editor
* Updated Edit links in plugins page to work with WP 3.4 updates
* Updated CSS themes to limit number of resources loaded
* Updated CSS highlighting for Ruby Blue and Monokai themes
* Fixed theme and plugin editors to allow disabling of line wrapping

= 1.0.3 =
* Updated CSS for theme/plugin editor
* Updated default theme/plugin editor font
* Fixed issue with theme editor in non 3.4 installations of WordPress

= 1.0.2 =
* Fixed invalid foreach statement for WP 3.4 in Theme editor
* Fixed invalid URL for images in root directory of themes
* Updated editor to keep line position when saving files
* Removed tab characters in all code and replaced with spaces
* Updated contextual help in the code editor pages
* Added ability to upload files in Theme/Plugin Editor

= 1.0.1 =
* Updated WP Editor to work with WordPress 3.4+
* Added HTML and HTM icons

= 1.0 BETA =
* Initial release of WP Editor

== Upgrade Notice ==

= 1.2.6.3 =
Fixed multiple XSS vulnerabilities

= 1.2.6.2 =
Fixed issues with QuickTags not working reliably
Fixed issues with inserting images not working reliably

= 1.2.6.1 =
Fixed Firefox conflict: NS_ERROR_FAILURE
Fixed 'Add Media' button not working

= 1.2.6 =
Added Nivo Lightbox to replace outdated Fancybox
Added support for SCSS files
Updated CodeMirror library to 5.13.4 including all extensions
Updated CodeMirror instance to use wp_editor to avoid conflicts
Updated quicktags to use default WordPress quicktags
Updated ajax requests to use wp_json_encode
Updated registering CodeMirror script to try to avoid conflicts
Fixed CSRF and permissions vulnerabilities
Fixed issue with duplicate editors on page/post editor
Fixed broken functions in plugin/theme editors
Fixed duplicating backslashes for unc paths

= 1.2.5.3 =
Update language text domain

= 1.2.5.2 =
Fixed compatibility with WordPress 4.3

= 1.2.5.1 =
Fixed javascript error that broke the editors

= 1.2.5 =
Added the ability to change the indent size
Fixed javascript error when clicking preview in posts view
Fixed file download not working on all plugin/theme files
Fixed missing CSS styles for quicktags

= 1.2.4 =
Added ability to edit .xml files
Fixed font issue in fullscreen mode
Fixed fullscreen save not working

= 1.2.3 =
Added ability to create new plugins and themes from inside the plugin/theme editors
Added ability to download plugins and themes
Added ability to download individual plugin and theme files being edited
Added ability to save page/post editor in fullscreen mode
Fixed PHP Warning when viewing drop-ins and mustuse plugins

= 1.2.2 = 
Fixed issues with PHP 5.4+
Fixed issue with selecting theme file types not working
Fixed issue with double scrollbar
Fixed issue with error message appearing when trying to save a post/page

= 1.2.1 =
Added feature to preserve scroll position in post editor on save and toggle between Visual/Text views
Added z-index fix for gutter when WordPress menu is collapsed
Added ability to edit .less files
Added ability to change the editor font size for plugin, theme and page/post editors
Fixed window resize issue with codemirror expanding past its boundaries

= 1.2 =
Compatibility with WordPress 3.5
Added Save, Undo, Redo, Search, Find Next, Find Prev, Replace, Replace All and Fullscreen buttons to plugin/theme editors
Added ability to hide WP Editor menu icon from menu sidebar and move it to settings submenu
Added ability to check if file is writable and add error messages accordingly in plugin/theme editors
Added highlighting of shortcodes in Post editor
Fixed conflicts with multiple plugins and themes

= 1.1.0.3 =
Fixed text selection in all editors
Fixed post editor highlighter not loading when visual editor is disabled

= 1.1.0.2 = 
CodeMirror 2.33
Fixed issue with media button toolbar not inserting shortcodes/content when in visual mode
Fixed issue with blockquote QuickTag inserting twice

= 1.1.0.1 = 
Fixed media buttons not working in Page/Post editor

= 1.1 =
Added features including support for syntax highlighting in the page/post editor

= 1.0.1 =
This version provides support for WordPress 3.4 and moves WP Editor out of beta