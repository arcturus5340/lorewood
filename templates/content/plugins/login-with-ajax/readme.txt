=== Login With Ajax ===
Contributors: netweblogic
Tags: login, ajax, ajax login, registration, redirect redirect, buddypress, multi site, sidebar, admin, widget
Requires at least: 3.7
Tested up to: 5.0
Stable tag: 3.1.7
License: GPLv2 or later

Add smooth ajax login/registration effects and choose where users get redirected upon log in/out. Supports SSL, MultiSite, and BuddyPress.

== Description ==

Login With Ajax is for sites that need user logins or registrations and would like to avoid the normal wordpress login pages, this plugin adds the capability of placing a login widget in the sidebar with smooth AJAX login effects.

Some of the features:

* AJAX-powered, no screen refreshes! 
 * Login
 * Registration
 * Remember/Reset Password
* Custom Login/Logout redirections
 * Redirect users to custom URLs on Login and Logout
 * Redirect users with different roles to custom URLs
 * WPML - Language-specific redirects
* SSL-compatible
* Fallback mechanism, will still work on javascript-disabled browsers
* Compatible with Wordpress, MultiSite, BuddyPress and many other plugins that alter the login/registration form (e.g. captcha plugins)
* Customizable, upgrade-safe widgets
* shortcode and template tags available
* Widget specific option to show link to profile page

If you have any problems with the plugin after reading the FAQ, Other Notes, etc. please visit the [support forums](http://wordpress.org/support/plugin/login-with-ajax).

= Translated Languages Available =

Here's a list of currently translated languages. Translations that have been submitted are greatly appreciated and hopefully make this plugin a better one. If you'd like to contribute, please have a look at [our translation site](http://translate.netweblogic.com/projects/login-with-ajax), or let us know on the [support forums](http://wordpress.org/support/plugin/login-with-ajax).

* Afrikaans - [Johnny Dunhin](http://helpendehand.co.za)
* Albanian - [Besnik Bleta](http://blogu.programeshqip.org/)
* Arabic (SA) - Adel Madshel
* Bosnian - Nenad Cvetkovic
* Catalan - Xavi Valenti
* Chinese - [Simon Lau](http://fashion-bop.com)
* Czech -  Marek Liolias, Ondra Kalous, Henry Greek, Viktor Jezek 
* Danish - Christian B.
* Dutch - Sjors Spoorendonk
* Estonian - Oliver Busch
* Finnish - Jaakko Kangosjärvi
* French - [Geoffroy Deleury](http://wall.clan-zone.dk)
* German - Linus Metzler
* Greek - George Alexakis, Lamprakis Fousekis
* Hebrew - Menachem Shapiro
* Hindi - Anonymous
* Hungarian - Lorinc Borda
* Italian - Marco aka teethgrinder
* Japanese - [Ryuei Sasaki](http://ryueisasaki.com/)
* Limburgish - Anonymous
* Lithuanian - [Gera Dieta](http://www.kulinare.lt/)
* Persian - [Mohammad Hosein Ameri](http://khandoon.ir/), [Mohammad Akbari](http://www.mo-akbari.com)
* Polish - Ryszard Rysz
* Portuguese (Brazil) - Humberto S. Ribeiro, Diogo Goncalves, Fabiano Arruda
* Romanian - Gabriel Berzescu
* Russian - [xl32](http://wordpress.org/support/profile/xl32),
* Slovak - [Branco](http://webhostinggeeks.com/blog/)
* Spanish - Myself and [Danilo Casati](http://e-rgonomy.com)
* Swedish - Tommy Wahlund
* Turkish - Mesut Soylu, Oskay Yurtturk

== Installation ==

1. Upload this plugin to the `/wp-content/plugins/` directory and unzip it, or simply upload the zip file within your wordpress installation.

2. Activate the plugin through the 'Plugins' menu in WordPress

3. If you want login/logout redirections, go to Settings > Login With Ajax in the admin area and fill out the form.

4. Add the login with ajax widget to your sidebar, [lwa] on your pages, or use login_with_ajax() in your template.

5. Happy logging in!

== Notes ==

= Upgrading from v3 to v3.1 =
Due to the improvmenets necessary (specifically allowing multiple LWA widgets on one page), it was important to modify the template files to use classnames instead of ids.

If you have customized your widgets as per the instructions below, you will need make some modifications to your templates, and probably re-evaluate whether you still need custom JS if you went that far.

We've tried to minimize potential conflicts arising from this, but you should consider revising your template regarding these two points:

* LoginWithAjax is now a static class, so things like $this->function() and $this->variable should become LoginWithAjax::function() and LoginWithAjax::$variable
* Element IDs are now classnames, and are converted like so (we do have backwards compatibility to account for this, but still recommended):
 * LoginWithAjax becomes lwa
 * classname is all lowercase
 * underscores become hyphens
 * Example : LoginWithAjax_Form > lwa-form

= Shortcodes & Template Tags =

You can use the [shortcode](http://codex.wordpress.org/Shortcode) [login-with-ajax] or [lwa] and [template tag](http://codex.wordpress.org/Template_Tags) `login_with_ajax()` with these options :

* profile_link - (1 or 0)
 * If value is 1 (default), a profile link to wp-admin appears.
* registration - (1 or 0)
 * If value is 1 (default), a registration link appears, providing you have registration enabled in your WP settings.
* template - (template name/directory)
 * If this template directory exists, this template will be used. Default is 'default' template.
* remember - (1 or 0)
 * If value is 1 (default), a remember password link appears for password recovery
* redirect
 * Successful logins are redirected to this URL 
 
= SSL Logins =

To force SSL, see [http://codex.wordpress.org/Administration_Over_SSL]("this page"). The plugin will automatically detect the wordpress settings.

= Customizing the Widget =
You can customize the html widgets in an upgrade-safe manner by copying files and editing them within your theme. Firstly, you need to understand how Login With Ajax loads templates:

* When looking for files/templates there is an order of precedence - active child theme (if applicable), active parent themes, and finally the plugin folder:
  * `wp-content/themes/your-theme/plugins/login-with-ajax/`
  * `wp-content/themes/parent-theme/plugins/login-with-ajax/`
  * `wp-content/plugins/login-with-ajax/widget/`

* Login With Ajax loads only one CSS and JS file which contains code for all templates. The plugin checks the locations above and loads the one it finds first. The default files are:
 * `wp-content/plugins/login-with-ajax/widget/login-with-ajax.js`
 * `wp-content/plugins/login-with-ajax/widget/widget.css`
 
* One caveat for JavaScript files, if you've enabled WP_DEBUG, then LWA will look for a file called `login-with-ajax.source.js`, a non-minified version of the normal JS file.

* Login With Ajax then checks for template folders which are loaded according to the preference highlighted above.
  * When a user is logged out, the `widget_out.php` will be used.
  * If logged out, then `widget_in.php` will be used
  * If either of these files don't exist in your template, the one located in the default folder will be used (which you can also override in your theme). 
  
**Examples**

If you wanted to change some text on the default theme, you could simply copy `wp-content/plugins/login-with-ajax/widget/default` to `wp-content/themes/yourtheme/plugins/login-with-ajax/default` and edit the files as needed.

If you need to change the CSS file, copy the file `wp-content/plugins/login-with-ajax/widget/widget.css` over to `wp-content/themes/yourtheme/plugins/login-with-ajax/widget.css` and edit accordingly.

The JavaScript ajax magic relies on the class names and hierarchical structure within the template files, if you want to modify the templates without adding your own JS, make sure you keep these class names and structure intact.

== Screenshots ==

1. Add a  fully customizable login widget to your sidebars.

2. Smoothen the process via ajax login, avoid screen refreshes on failures.

3. If your login is unsuccessful, user gets notified without loading a new page!

4. Customizable login/logout redirection settings.

5. Choose what your users see once logged in.

== Frequently Asked Questions ==

= The registration link doesn't show! What's wrong? =
Before you start troubleshooting, make sure your blog has registrations enabled via the admin area (Settings > General) and that your widget has the registration link box checked.

= AJAX Registrations don't work! What's wrong? =
Firstly, you should make sure that you can register via the normal wp-admin login, if something goes wrong there the problem is not login with ajax. Please note that currently there is no AJAX registration with BuddyPress due to it rewriting the login area (this will be resolved soon).

= How can I customize the login widget? =
See the notes section about customizing a widget.

= How do I use SSL with this plugin? =
Yes, see the notes section.

= Do you have a shortcode or template tag? =
Yes, see the notes section.

For further questions and answers (or to submit one yourself) go to our [http://netweblogic.com/forums/](support forums).


== Changelog ==
= 3.1.7 =
* fixed XSS security vulnerability on LWA settings page allowing code injection if an authorized user follows a properly structured url to that page, this does not affect the security of the login forms, only the settings page. Kudos Neven Biruski from DefenceCode for responsible disclosure.
* changed our hooks logout_url and login_redirect added as actions to filters which prevented functionality in some situations

= 3.1.6 =
* added Persian translation, thanks to Mohammad Akbari
* fixed PHP 7 deprecated construct error
* fixed deprecated get_currentuserinfo function PHP warning
* added option to show direct links (no AJAX forms) to password recovery and registration forms
* fixed missing text domain in widget/modal/widget_out.php
* fixed server errors when deactivating BuddyPress whilst LWA is active

= 3.1.5 =
* fixed 4.3 password update which now sends a link for user to set password instead
* fixed login redirect issues when using shortcode and template tags
* added script localization via LWA JS parameter,
* changed JS destination url to admin-ajax.php which fixes some incompatibilities with WPML
* fixed WPML status messages not being translated upon login/registration/password-recovery
* added and updated loads of languages, see here for full list - http://translate.netweblogic.com/projects/login-with-ajax/
* added some minor label layout improvements for admin area labels/forms
* added minor widget admin settings layout improvements

= 3.1.4 =
* fixed MIME type errors between HTTP <> HTTPS ajax requests,
* added redirect shortcode attribute

= 3.1.3 =
* fixed JS/CSS file overriding problems
* fixed class html error in widget/default/widget_in.php
* fixed redirection issues when not using JS
* fixed MultiSite registrations not adding user to blog with default role only 'subscriber' (props to Renato Baccaro)
* fixed vulnerability where registration is still possible even if registration is disabled in settings (props to Kevin Niehage @weizenspreu)
* fixed problems when trying to log in from an http page when admin ssl is enforced
* fixed CSS to hide table borders of default widget for the twenty fourteen theme
* added Hebrew, thanks to Menachem Shapiro
* fixed HTML validation issues in widgets
* added error fallback during ajax request
* added new icons and headers for wordpress.org plugin pages
* added redirect shortcode attribute for custom redirect

= 3.1.2 =
* updated Russian, Swedish and POT language files
* added Afrikaans translation
* added login_form action to divs-only and modal templates,
* fixed php warning in login-with-ajax.php
* fixed custom registration email not working since 3.1
* fixed logged in 'hi' title not showing up and is now configurable in widget settings

= 3.1.1 =
* fixed graceful fallback for themes with broken JS
* added loading of source JS if WP_DEBUG is enabled
* moved reveal.js source code into source js file
* fixed shortcode php warning
* added template shortcode/template attribute
* profile_link and registration arguments are now considered true/1 by default, to avoid confusion with missing links
* added 'remember' argument which controls whether to show/hide password recovery link
* fixed widget settings not remembering unchecked checkboxes
* added Slovak
* removed strtolower and using CSS now in widget_in.php
* moved register_widget into own function called by widgets_init
* changed some lwa-... ids to classes in widget_in.php
* added Slovak, updated Russian languages
* updated the POT file

= 3.1 =
* fixed json_encode issue
* overhaul of JS, now leaner and meaner
* modified template structure to allow multiple login forms
* added template selection to each widget
* added title choice to widget
* removed inclusion of wp-includes/registration.php during regsitration (not needed since WP 3.1)
* added two new templates to choose in widgets
* new light-weight modal using tweaked Reveal library - http://zurb.com/playground/reveal-modal-plugin
* improved css
* improved html structures in widget templates
* added sainitization in widget templates
* fixed CSRF vulnerability in admin settings page - thanks to Charlie Eriksen via Secunia SVCRP
* moved WP Widget before/after and open/close tags out of templates and into the WP_Widget class
* LoginWithAjax class is now completely static
* added some MS fixes for registration, now works with BuddyPress

= 3.0.4.1 =
* fixed xss vulnerability for re-enlistment on wordpress repo, more on the way

= 3.0.4 =
* updated russian translation
* added japanese
* updated iranian
* added registration attribute to template tags/shortcode

= 3.0.3 =
* scrollbar issue in default widget
* added hungarian

= 3.0.2 =
* got rid of (hopefully all) php warnings

= 3.0.1 =
* Fixed unexpected #LoginWithAjax_Footer showing up at bottom
* Fixed link problems for sub-directory blogs (using bloginfo('wpurl') now)
* Added Albanian
* Replace Spanish with revised version

= 3.0 =
* Option to choose from various widget templates.

= 3.0b3 =
* %LASTURL% now works for logins as well
* Profile link plays nice with buddypress
* Added fix to stop wp_new_user_notification conflicts
* Empty logins now have an error message too.

= 3.0b =
* Various bug fixes
* Improved JavaScript code
* Ajax Registration Option

= 2.21 =
* Redirect bug fix
* Hopefully fixed encoding issue

= 2.2 =
* Added Polish, Turkish and Brazilian Translation
* Fixed buddypress avatar not showing when logged in
* Removed capitalization of username in logged in widget
* Fixed all other known bugs
* Added placeholders for redirects (e.g. %USERNAME% for username when logged in)
* Added seamless login, screen doesn't refresh upon successful login.

= 2.1.5 =
* Changed logged in widget to fix avatar display issue for both BuddyPress and WP. (Using ID instead of email for get_avatar and changed depreciated BP function).
* Added Danish Translation

= 2.1.4 =
* Added Chinese Translations
* CSS compatability with themes improvement.

= 2.1.3 =
* Added Italian Translations
* Added space in widget after "Hi" when logged in.
* CSS compatability with themes improvement.

= 2.1.2 =
* Added German Translations
* Fixed JS url encoding issue

= 2.1.1 =
* Added Finnish, Russian and French Translations
* Made JS success message translatable
* Fixed encoding issue (e.g. # fails in passwords) in the JS