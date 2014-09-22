=== Plugin Name ===
Contributors: rfrankel
Donate link: http://plugins.swampedpublishing.com/wp-super-faq
Tags: faq, frequently asked questions, qna, question and answer, jquery
Requires at least: 3.1
Tested up to: 3.3
Stable tag: 0.5.6

A lightweight FAQ/QNA plugin that includes an FAQ shortcode for your site. A simple jQuery animation is included to show/hide each question.

== Description ==

WP Super FAQ uses the WordPress 3.1+ custom post types and taxonomies to include support for an FAQ (Frequently Asked Questions/Question and Answer) on your site.  The interface uses jQuery to provide a small animation that lets users click the questions they are interested in to display the answer.  The goal of this plugin was for extremely lightweight code that provides easy setup, addition of questions, and a clean user interface.  Also included in this plugin is the option of putting questions in different 'categories' to display.  Please see the screenshots for examples.  If you have feedback or questions head over to my [feedback and support](http://plugins.swampedpublishing.com/wp-super-faq) page for this plugin.

== Installation ==

## Installation of WP Super FAQ is extremely easy.  It installs like any WordPress plugin and uses a simple shortcode to place on your pages. ##

1. Upload the `wp_super_faq` folder to the `/wp-content/plugins/` directory of your WordPress installation.
2. Activate the plugin through the 'Plugins' menu in WordPress

## To add a question ##
1. This process is very similar to adding a post.  In the Admin section of your site click on the new `FAQ` tab.
2. Click `Add New Question`
3. Add the question in the title text box and the answer in the content text box.
4. Click `'Publish` and you are done! 

## To use the plugin from your WordPress Admin area: ##
1. Use the shortcodes defined in the FAQ in your pages.  For a simple FAQ you can use `[wp_super_faq]`.

## To use the plugin from a PHP template: ##
1. Place `<?php do_action('[wp_super_faq]'); ?>` in your templates.  You can use any shortcode defined in the FAQ.

== Frequently Asked Questions ==

= What are the possible shortcodes for WP Super FAQ? =

* The most basic usage is simply `[wp_super_faq]`.  By default WP Super FAQ will not display the questions by category.  
* If you would like to display your questions by category you can use `[wp_super_faq show_categories=true]`.  This will display a header for each category and place the relevant questions in each section.
* If you would like to display a SINGLE category you can use `[wp_super_faq show_specific_category=slug]` where slug is the SLUG of the category you would like to display.  The slug can be found by clicking on `FAQ Categories` in the admin area.

= Can I use this in PHP instead of a shortcode? =

Yes.  WordPress supplies a nice function to use shortcodes in PHP.  For WP Super FAQ you would use `<?php echo do_shortcode( '[wp_super_faq]' ) ?>`.  A reference for this function can be found [here](http://codex.wordpress.org/Function_Reference/do_shortcode).

= Can I reorder the questions? =

Yes you can (although it is slightly obfuscated).  To reorder the questions you just have to reorder the dates of the Questions in the Admin area. 

= Can I reorder the categories? =

You can do this too.  All you have to do is use the `show_specific_category` style shortcode and place a few of them on the same page in whatever order you would like.

== Screenshots ==

1. A screenshot of the default shortcode with WP Super FAQ.
2. A screenshot with both questions clicked on with the default shortcode.
3. A screenshot showing the FAQ by category.
4. A screenshot showing the FAQ by category with the questions clicked on.

== Changelog ==
= 0.5.6 = 
1. Minor modifications for WordPress 3.3 compatibility.
2. Moved the wp_super_faq javascript to load at end of body instead of into the header.  

= 0.5.5 =
1. Fixed plugin for working with non-latin strings in slugs.
2. Fixed IE7 Bug! IDs for the heading and answer can not be the same for Javascript in IE7.  They were made unique in this version. 

= 0.5.4 =
1. Added a test to see if get_current_screen exists before using it.
2. Fixed the queries so showposts and posts_per_page are both -1.  This should override the WP posts per page setting in the backend.  This was supposed to be fixed in 0.3 (see below) but it wasn't for certain themes.

= 0.5.3 =
Fixed an error with the show_categories shortcode.  This bug causes funny line breaks in some themes.

= 0.5.2 =
Minor update to try and add better instructions for FAQ categories.

= 0.5.1 =
Fixed a bug with show_categories=true.  The ID was not displaying directly.

= 0.5 =
Now using WordPress default jQuery library instead of grabbing the Google CDN version.
Upgraded the output so that you can now display multiple shortcodes on a page and also have other HTML.

= 0.4 = 
Added a shortcode to display a single category of the FAQ. `[wp_super_faq show_specific_category=slug]`

= 0.3 = 
Added a fix that makes sure that the FAQ shows all of the questions regardless of what is set under Settings > Readings.

= 0.2 =
Added `register_taxonomy` into function call to fix `Call to a member function add_rewrite_tag() on a non-object in taxonomy` 

= 0.1 =
Initial release.

== Upgrade Notice ==
= 0.5.6 = 
Minor modifications for WordPress 3.3 compatibility.

= 0.5.5 =
Required Update.  IE7 functionality was fixed.  Fixed plugin for working with non-latin characters in slugs.

= 0.5.4 =
Added a test to see if get_current_screen exists before using it.
Fixed the queries so showposts and posts_per_page are both -1.  This should override the WP posts per page setting in the backend.  This was supposed to be fixed in 0.3 (see below) but it wasn't for certain themes.

= 0.5.3 =
Fixed an error with the show_categories shortcode.  This bug causes funny line breaks in some themes.

= 0.5.2 = 
Minor updated changing some instructions in the back-end.  Not a required update but recommended.  

= 0.5.1 = 
Minor updated but fixes a bug in the show_categories=true shortcode.  If you are using this shortcode you need to upgrade.

= 0.5 =
Now uses internal jQuery library and allows for multiple shortcodes on a page.  Please report any issues [here](http://plugins.swampedpublishing.com/wp-super-faq)!! 

= 0.4 =
Added new shortcode for showing a single category.

= 0.3 =
Minor update.  You should upgrade to stay up-to-date.  This bug will affect some of you that are using the 'Show At Most' feature of WordPress.

= 0.2 =
Bug fix.  Upgrade Immediately.

= 0.1 =
Initial release.