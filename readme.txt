=== Fan Page ===
Contributors: delower186
Tags: social, fan page, like page, like box, likebox, page plugin, widget, shortcode, responsive, template tag, sidebar, fb page plugin
Requires at least: 5.2 or higher
Tested up to: 5.7
Stable tag: 1.0.0
Requires PHP: 6.8
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

You can easily embed and promote any public Fan page on your wordpres page. Visitors can like, share, see Page events or even sand a message without having to leave your site. You can use the Fan Page plugin for any Page that is not restricted, for example, by country or age.

== Description ==

= Description =

FB Page Plugin enables Facebook Page admins to promote their Pages and embed a simple feed of content from a Page into any WordPress blog. **FB Page** plugin enables users to:

* See how many users already like this Page, and which of their friends like it too
* Read recent posts from the Page
* Like the Page with one click, without needing to visit the Page
* See Page Events
* Send messages to your Page\*

\**To enable messaging on your Facebook page go to your Page Settings. In the row Messages check Allow people to contact my Page privately by showing the Message button*

You can easily integrate Like Page using WordPress Widgets and Shortcodes. Visit [Plugin Page](https://delower.me/fb-page/ "See 'FB Page' Page") for more info and examples.

= Our Other Plugins =

* [WP To Do](https://wordpress.org/plugins/wp-todo/ "See plugin demo") - Responsive WordPress To Do List


== Installation ==
**Installation**

1. Upload `fb-page` directory to your `/wp-content/plugins` directory
1. Activate plugin in WordPress admin

**Customization**

1. In WordPress dashboard, go to **Appearance > Widgets**. 
1. Drag and Drop **FB Page** into your sidebar.
1. Click triangle near **FB Page** header.
1. Enter your FB Page URL (not your profile URL).
1. Choose width, height and other options you like.

**or**

Use `[fanpage]` shortcode inside your post or page. This shortcode support all default parametrs:


* url - any Fan Page URL (not your personal page!)
* width - number (min 280, max 500)
* height - number
* hide_cover - *true* or *false*
* show_facepile - *true* or *false*
* small_header - *true* or *false*
* timeline - *true* or *false*
* events - *true* or *false*
* messages - *true* or *false*
* locale - valid language code (e.g. *en_US* or *es_MX*) see [.xml file](http://www.facebook.com/translations/FacebookLocales.xml "Facebook locales XML") with all Facebook locales


If you want Page Plugin *320 pixels width* and *showing posts* you need to use it next way:

`[fanpage width=320 show_posts=true url=http://www.facebook.com/yourPageName]`

**or**

Use `fanpageplugin()` template tag in your theme files.

`<?php if ( function_exists("fanpageplugin") ) {
	$args = array(
		'url'			=> 'http://www.facebook.com/Delower-103206595201617',
		'width'		=> '300',
		'hide_cover'=> true,
		'locale'		=> 'en_US'
	);
	fanpageplugin( $args );
} ?>`

== Frequently Asked Questions ==

= I see the message “Error: Not a valid FB Page url.”. What am I doing wrong? =

FB Page Plugin is only for Pages and **not** for Profiles, Events and Groups.

== Screenshots ==

1. Widget in the dashboard.
2. Widget with posts on the sidebar.

== Changelog ==

= 1.0.0 =
Very first release
