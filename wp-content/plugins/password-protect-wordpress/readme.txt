=== Password Protect Wordpress ===
Contributors: volcanicpixels
Donate link: http://www.spiders-design.co.uk/donate/
Tags: password,protect,password protect,wordpress,blog, security
Requires at least: 3.3.1
Tested up to: 3.6.1
Stable tag:  5.0.1

This plugin password protects your wordpress blog with a single password.

== Description ==

Want to make your blog private from prying eyes? Well my password protection plugin is perfect for that job; with its intelligent UI and endless features it will make the chore of keeping your data secure and private easy.

> **NOTE** This plugin will not work on WPEngine due to their very aggressive caching.
  
  
> Use w3-total-cache instead of wp-super-cache.  

> Deactivating a caching plugin does NOT always purge the cache. Before deactivating go to the plugin admin page and turn off caching from there.

Features:

No user accounts - just a single password.  
Easy to use admin panel.  

Premium Features (paid upgrade):  

 - Multiple passwords
 - Choose which pages/posts/categories/tags/urls to protect
 - Customize the login page
 - Make RSS feeds public
 - Protect media files and attachments from hot-linking


Please review and vote that it works.

If you need a feature not offered or need support please [contact me](http://www.danielchatfield.com)

== Installation ==

This section describes how to install the plugin and get it working.


1. Unzip archive

2. Upload \`password-protect-wordpress\` to the \`/wp-content/plugins/\` directory

3. Activate the plugin through the 'Plugins' menu in WordPress

1. Edit the configuration by clicking on 'Private Blog' under settings


== Screenshots ==

1. Customizable login form

2. Easy to use admin panel can be accessed by clicking on 'Password Protect' under settings on the wordpress admin dashboard.

2. Change the CSS styles for the login page to get the look and feel right

== Changelog ==

= 5.0.1 =

* Added warning about incompatibility with WP-Super-Cache

= 5.0.0 =

* Fixed issue where on some sites clicking home would return user to login page
* Add "allow updates" option so that people who customise it can easily disable the updates.
* Migrated to legacy api domain
* Removed blue effect as it affects performance too much

= 4.11.9 =

* Added media patterns option for choosing what media to protect

= 4.11.8 =

* Fixed javascript errors reported by previous update
* Improved URL rewrite performance

= 4.11.7 =

* Updated readme
* Added javascript error reporting so that I can fix conflicts with other plugins.

= 4.11.6 =

* Fixed issue with logout link displaying when not logged in.

= 4.11.5 =

* Fixed issue with redirects when incorrect password is entered
* Fixed issue with multiple comma separated values not correctly being parsed

= 4.11.4 =

* Fixed issue with some blogs (I think)

= 4.11.3 =

* Emergency update

= 4.11.2 =

* Fixed bug with logout on paths
* Fixed typo
* Fixed issue where 'incorrect credential' notice would not be shown

= 4.11.1 =

* Added ability to auto-login users
* Added ability to auto-populate password field
* Prevented robots indexing login page

= 4.11.0 =

* Added ability to remove logs
* Added ability to name passwords
* Filter by URL pattern

= 4.10.10 =

* Added secure uploads support for more servers

= 4.10.9 =

* Added ability to protect uploads when using apache


= 4.10.8 =

* Made protecting certain content easier

= 4.10.7 =

* Added regular expressions

= 4.10.6 =

* Actually fixed it now

= 4.10.5 =

* Fixed bug where previous update only worked for posts and not pages.

= 4.10.4

* Added support for selecting which pages/posts to protect

= 4.10.3 =

* A fix for the bug where login attempts would be ignored when wp installation is in sub directory.

= 4.10.2 =

* Would help if I actually included the changed files when releasing an update (doh)



= 4.10.1 =

* Fixed an issue with "double login" where a domain could be accessed with or without www

* Fixed an issue with public RSS feeds

= 4.09 =

* Fixed regression where on some themes you had to login twice.
= 4.08 =

* Fixed issue where on some (poorly coded) themes logging in didn't work
= 4.04 =

* Fixed an issue with debug vars being printed to screen after saving settings

= 4.03 =

* Fixed an issue with some of the admin side javascript and IE
* Fixed an issue where if an HTML link was entered as the message for a skin the href attribute would be incorrectly urlized
* Changed encoding of settings to UTF-8 to non ASCII characters that are not covered by the HTML spec (Russian characters for example) 
= 4.02 =

* Fixed issue with template loader
* Fixed issue with header buffer being flushed by All In One SEO pack when theme uses weird get_header call (I mean WTF was that theme designer doing)
* Turned error reporting off
= 4.0 =

* Complete rewrite

= 3.9 =

* Links can now be in the message (by typing in the html)
= 3.7 =

* Fixed compatibility issue where setting headings were getting removed

* removed PHP short tags

= 3.3 =

* Multi password support

* Logs supports

= 3.0 =

* Extension support

* Multi site support

* New support page

* Fixed opera and ipad browser issues

* Internationalised

* logo upload button rather than just a textbox

* Helpful instructions

= 2.8 =

* Licensing improvements

* Added support for directories other than the default
= 2.7 =

* Password check on return key press

* Password box automatically has focus

* Preliminary support for Aruba Systems RMP (remote managment protocol)

* Fixed conflict with wordpress admin page
= 2.5 =

* Firefox options page fix

= 2.3 =

* Default logo file path fix

= 2.1 =

* Emergency Bug Fix

= 2.0 =

* Better documentation

* Revised Search engine access list

= 1.0 =

* First Version

* Search engine access


== Upgrade Notice ==

= 5.0.0 =

* Large update that fixes lot's of bugs and adds some new features (see readme).

= 4.11.6 =

* Fixed issue with logout link displaying when not logged in.

= 4.11.5 =
Fixes issue with login redirects and multiple comma delimited values

= 4.11.2 =

* When the wrong password is entered when using 'only protect certain pages' it now displays 'incorrect credentials' notice.
