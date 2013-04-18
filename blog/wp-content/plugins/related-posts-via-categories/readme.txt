=== Related Posts via Categories ===
Contributors: alphasis
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JQCM6H5BSMZSY
Tags: related posts, related, category, categories, post, posts, custom post, custom posts, similar, similar posts, plugin
Requires at least: 3.2
Tested up to: 3.4.2
Stable tag: 2.1.1
License: GPLv2 or later

This Plugin will display the related posts list via categories.

== Description ==

This Plugin will display the related posts list via categories.
It can automatically display the related posts list after the content of any single post of selected post types by options.
There are many options for the display, order, exclusion and post-type selection.
This plugin works when viewing a post of selected post types by options.
This plugin requires jQuery 1.6 or higher.

= Official Page =

* Plugin's Official Page : [English](http://alphasis.info/developments/wordpress-plugins/related-posts-via-categories/ "Plugin's Official Page in English") / [Japanese](http://alphasis.info/kaihatu/wordpress-plugins/related-posts-via-categories/ "Plugin's Official Page in Japanese")
* Author's Website : [alphasis.info](http://alphasis.info/ "alphasis.info") (Japanese) &raquo; [WordPress Plugins](http://alphasis.info/developments/wordpress-plugins/ "WordPress Plugins") (English)

= Related Posts via Categories - Options =
'Settings' &raquo; 'Related Posts via Categories'

= Display =

* Auto display
	* This option automatically displays related posts after the content on the single post page.
	* If this option is off, you will need to manually insert `display_related_posts_via_categories()` into your theme files.
* Maximum number of related posts
	* If you do not enter anything: 10
* Related posts title
	* For example: Related Posts
* Before related posts title and related posts
	* For example: `<div id="related-posts-via-categories">`
* After related posts title and related posts
	* For example: `</div>`
* Before related posts title
	* For example: `<h2 id="related-posts-via-categories-title">`
* After related posts title
	* For example: `</h2>`
* Before related posts
	* For example: `<ul id="related-posts-via-categories-list">`
* After related posts
	* For example: `</ul>`
* Before each related post
	* For example: `<li class="related-posts-via-categories-item">`
* After each related post
	* For example: `</li>`

= Order =

* Order By
	* Related Scores : High  ( Speedy ) 
	* Related Scores : High / Date Published : New ( Default setting )
	* Related Scores : High / Date Published : Old
	* Related Scores : Low / Date Published : New
	* Related Scores : Low / Date Published : Old
	* Related Scores : High / Date Modified : New
	* Related Scores : High / Date Modified : Old
	* Related Scores : Low / Date Modified : New
	* Related Scores : Low / Date Modified : Old
	* Date Published : New
	* Date Published : Old
	* Date Modified : New
	* Date Modified : Old

= Exclude =

* Exclude (Posts)
	* The IDs of any posts you want to exclude, separated by commas.
* Exclude (Categories)
	* Select checkboxes of any categories you want to exclude.

= Post types =

* Select checkboxes of any post types you want to include in the list of Related Posts and display the list.
* Displays checkboxes of them when there is any custom post types.

= Localization =

* Japanese (ja) - [alphasis](http://alphasis.info/ "alphasis.info") (plugin author)
* Persian (fa_IR) - [Omid Sheerkavand](http://qanal.ir/ "Omid Sheerkavand")
* Armenian (hy_AM) - Artak Kolyan
* Current release supports English, Persian, Armenian and Japanese.

== Installation ==

1. Upload `related-posts-via-categories` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= This plugin is free? =
* Yes.
* [Please pay if you like "Related Posts via Categories".](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JQCM6H5BSMZSY "Please pay if you like this plugin") 

== Screenshots ==

1. Display ( If the user is not logged in. )
2. Display ( If the user is logged in. )
3. Settings &raquo; Related Posts via Categories
4. Related Posts via Categories - Options &raquo; Display
5. Related Posts via Categories - Options &raquo; Order
6. Related Posts via Categories - Options &raquo; Exclude
7. Related Posts via Categories - Options &raquo; Post types
8. Related Posts via Categories - Options &raquo; Promotion

== Changelog ==

= 2.1.1 =
* Added Armenian ( hy_AM ) language files.
* Tested up to: WordPress 3.4.2

= 2.1.0 =
* Changed the total source code.
* Came to require jQuery 1.6 or higher.
* Added post-type selection option.

= 1.1.1 =
* PHP code was changed to more strict.

= 1.1.0 =
* Added a CSS class or ID.
	* For example: `<ul id="related-posts-via-categories-list">`
* This plugin came to require jQuery.

= 1.0.2 =
* Added Persian ( fa_IR ) language files.
* Compatible up to: WordPress 3.2.1

= 1.0.1 =
* PHP code was changed to more strict.
* Compatible up to: WordPress 3.1

= 1.0.0 =
* Initial release

== Upgrade Notice ==

none

