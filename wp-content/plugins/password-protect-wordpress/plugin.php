<?php
/*
Plugin Name: Private Blog
Plugin URI: http://www.volcanicpixels.com/password-protect-wordpress-plugin/
Description: Private Blog is a wordpress plugin which allows you to password protect all of your wordpress blog including all posts and feeds with a single password.
Version: 5.0.1
Author: Daniel Chatfield
Author URI: http://www.danielchatfield.com
License: GPLv2
*/
?>
<?php
error_reporting(0);
include( dirname( __FILE__ ) ."/lava/lava.php" );

$pluginName = "Private Blog";
$pluginVersion = "5.0.0";

$thePlugin = lava::newPlugin( __FILE__, $pluginName, $pluginVersion );
$pluginSlug = $thePlugin->_slug();

	
/**
 * 
 * Define the plugin settings:
 *	Enabled
 *	Multiple Passwords
 *	Passwords
 *	Login Duration
 *	Add logout button
 */


// To change maximum passwords change the value of the variable below
global $maxPasswords;
$maxPasswords = 10;

$thePlugin->_settings()
	->addSetting( "enabled" )
		->setName( __( "Enable Password Protection", $pluginSlug ) )
		->setType( "checkbox" )
		->setDefault( "on" )
		->setHelp( __( "When enabled visitors to your site will need to login to access it.", $pluginSlug ) )
	->addSetting( "use_template_hook" )
		->setName( __( "Enable this ONLY if the login page never appears (RSS feeds will not be public)", $pluginSlug ) )
		->setType( "checkbox" )
		->setDefault( "off" )
	->addSetting( "multiple_passwords" )
		->setName( __( "Enable multiple passwords", $pluginSlug ) )
		->setType( "checkbox" )
		->setDefault( "off" )
		->setHelp( sprintf( __( "When enabled, upto %s different passwords can be set.", $pluginSlug ), 10 ) )
		->addTag( "is-premium" )
;

for( $i = 1; $i <= $maxPasswords; $i++ )
{
	$default = ( 1 == $i )? "password" : "";//set the default for the first password and leave the rest blank
	$name = ( 1 == $i )? __( "Password", $pluginSlug ) : ""; //set the name for the first password and leave the rest blank
	$namePlural = __( "Passwords", $pluginSlug );
	$tag = ( 1 != $i )? "multi-password" : "";//add the "multi-pasword" tag to all the passswords except number 1

	$colourArray = array(
		"#26d2e1",//light blue
		"#e10808",//red
		"#e17812",//orange
		"#a4e19c",//light green
		"#FEDA71", //light yellow
		"#f0518b", //pink
		"#5d5042", //turd
		"#ab6fd1", //purple
		"#69aeb4", //turqoise
		"#97dd10" //grass green
	);
	$numberColours = count( $colourArray );
	$colour = $colourArray[ ($i - 1) % $numberColours ];//cycle through the pre-defined colours. Flexible code allows for more colours to be defined easily and more passwords.

	$thePlugin->_settings()
		->addSetting( "password".$i."_value" )
			->setName( $name )
			->setType("password")
			->setDefault( $default )
			->setProperty('placeholder', __( "Leave blank to disable", $pluginSlug ) )
			->addTag( $tag )//makes it easy to select all multi password settings
			->addTag( "password-label" )
			->bindData( "name-singular", $name )
			->bindData( "name-plural", $namePlural )
			->bindData( "pass-short-name", "password".$i )
		->addSetting( "password".$i."_name" )
			->setType("text")
			->setDefault( $i )
			->setVisibility( false )
		->addSetting( "password".$i."_colour" )
			->setType("text")
			->setDefault( $colour )
			->setVisibility( false )
	;
}

$defaultTimeout = 60*60*24;//1 day

$thePlugin->_settings()
	->addSetting( "timeout_length" )
		->setName( __( "Duration that user stays logged in", $pluginSlug ) )
		->setType( "timeperiod" )
		->setHelp( __( "The length of inactivity before the user must login again. Set to 0 to timeout when browser closes.", $pluginSlug ) )
		->setDefault( $defaultTimeout )
		->addTag( "is-premium" )
	->addSetting( "logout_link" )
		->setName( __( "Add Logout link to navigation", $pluginSlug ) )
		->setType( "checkbox" )
		->setDefault( "off" )
		->setHelp( __( "When enabled, the plugin will attempt to put a logout link in the navigation", $pluginSlug ) )
		->addTag( "is-premium" )
	->addSetting( "logout_link_menu" )
		->setType( "select" )
		->addTag( "no-margin" )
		->settingToggledBy( "logout_link" )
	->addSetting( "rss_feed_visible" )
		->setName( __( "Make RSS Feeds public", $pluginSlug ) )
		->setType( "checkbox" )
		->setDefault( "off" )
		->setHelp( __( "When enabled, the RSS feed (which contains post content) will be publicly available", $pluginSlug ) )
		->addTag( 'is-premium' )
	->addSetting( "protect_certain_pages" )
		->setName( __( "Only protect certain pages/categories/tags/post types/url patterns", $pluginSlug ) )
		->setType( "checkbox" )
		->setDefault( "off" )
		->setHelp( __( "When enabled, you can choose posts, categories, tags etc. to protect", $pluginSlug ) )
		->addTag( 'is-premium' )
	->addSetting( "pages_to_protect" )
		->setName( __( "Pages/posts to protect", $pluginSlug ) )
		->setType( "text" )
		->setDefault( "Hello World" )
		->setHelp( __( "Enter either the ID, the slug ( e.g. hello-world ) or title. Do not enter the whole URL.", $pluginSlug ) )
		->addTag( 'is-premium' )
		->settingToggledBy('protect_certain_pages')
	->addSetting( "categories_to_protect" )
		->setName( __( "Categories to protect", $pluginSlug ) )
		->setType( "text" )
		->setDefault( "" )
		->setHelp( __( "Enter a comma delimited list of category IDs, names or slugs", $pluginSlug ) )
		->addTag( 'is-premium' )
		->settingToggledBy('protect_certain_pages')
	->addSetting( "tags_to_protect" )
		->setName( __( "Tags to protect", $pluginSlug ) )
		->setType( "text" )
		->setDefault( "" )
		->setHelp( __( "Enter a comma delimited list of tag IDs, names or slugs", $pluginSlug ) )
		->addTag( 'is-premium' )
		->settingToggledBy('protect_certain_pages')
	->addSetting( "post_types_to_protect" )
		->setName( __( "Post types to protect", $pluginSlug ) )
		->setType( "text" )
		->setDefault( "" )
		->setHelp( __( "Enter a comma delimited list of post-type IDs, slugs or names", $pluginSlug ) )
		->addTag( 'is-premium' )
		->settingToggledBy('protect_certain_pages')
	->addSetting( "urls_to_protect" )
		->setName( __( "Url patterns to protect", $pluginSlug ) )
		->setType( "text" )
		->setDefault( "" )
		->setHelp( __( "Enter a comma delimited list of patterns to match against. E.g. to protect everything with 'members' in the url enter 'members'. Do not enter the whole URL.", $pluginSlug ) )
		->addTag( 'is-premium' )
		->settingToggledBy('protect_certain_pages')



	->addSetting( "unprotect_certain_pages" )
		->setName( __( "Do not protect certain pages/categories/tags/post types/url patterns", $pluginSlug ) )
		->setType( "checkbox" )
		->setDefault( "off" )
		->setHelp( __( "When enabled, you can choose posts, categories, tags etc. to protect.", $pluginSlug ) )
		->addTag( 'is-premium' )
	->addSetting( "pages_to_unprotect" )
		->setName( __( "Pages/posts to not protect", $pluginSlug ) )
		->setType( "text" )
		->setDefault( "" )
		->setHelp( __( "Enter either the ID, the slug ( e.g. hello-world ) or title", $pluginSlug ) )
		->setInlineHelp( "" )
		->addTag( 'is-premium' )
		->settingToggledBy('unprotect_certain_pages')
	->addSetting( "categories_to_unprotect" )
		->setName( __( "Categories to not protect", $pluginSlug ) )
		->setType( "text" )
		->setDefault( "" )
		->setHelp( __( "Enter a comma delimited list of category IDs, names or slugs", $pluginSlug ) )
		->addTag( 'is-premium' )
		->settingToggledBy('unprotect_certain_pages')
	->addSetting( "tags_to_unprotect" )
		->setName( __( "Tags to not protect", $pluginSlug ) )
		->setType( "text" )
		->setDefault( "" )
		->setHelp( __( "Enter a comma delimited list of tag IDs, names or slugs", $pluginSlug ) )
		->addTag( 'is-premium' )
		->settingToggledBy('unprotect_certain_pages')
	->addSetting( "post_types_to_unprotect" )
		->setName( __( "Post types to not protect", $pluginSlug ) )
		->setType( "text" )
		->setDefault( "" )
		->setHelp( __( "Enter a comma delimited list of post-type IDs, slugs or names", $pluginSlug ) )
		->addTag( 'is-premium' )
		->settingToggledBy('unprotect_certain_pages')
	->addSetting( "urls_to_unprotect" )
		->setName( __( "Url patterns to unprotect", $pluginSlug ) )
		->setType( "text" )
		->setDefault( "" )
		->setHelp( __( "Enter a comma delimited list of patterns to match against (e.g. '/members' NOT http://site.com/members", $pluginSlug ) )
		->addTag( 'is-premium' )
		->settingToggledBy('unprotect_certain_pages')


	->addSetting( "record_logs" )
		->setName( __( "Create a log of all logins and logouts", $pluginSlug ) )
		->setType( "checkbox" )
		->setDefault( "off" )
		->addTag( "is-premium" )
		->setHelp( __( "When enabled, every attempt to login will be logged", $pluginSlug ) )
	->addSetting( "secure_media" )
		->setName( __( "Block access to media unless loggedin (only works on apache with permalinks)", $pluginSlug ) )
		->setType( "checkbox" )
		->setDefault( "off" )
		->addTag( "is-premium" )
		->setHelp( __( "When enabled access to media files will be blocked if user is not logged in", $pluginSlug ) )
	->addSetting( "secure_media_patterns" )
	    ->setHelp( "By default all media will be blocked, use this to only protect some media." )
	    ->setType("text")
	    ->setName("Protected Media Patterns (Advanced use ONLY)")
	    ->settingToggledBy("secure_media")
	->addSetting("allow_updates")
	    ->setHelp( "Switching this off will stop WordPress from updating this plugin.")
	    ->setType("checkbox")
	    ->setDefault("on")
	    ->setName("Allow Plugin Updates")
;


$thePlugin->_tables()
	->addTable( "access_logs" )
		->addField( "id" )
			->setType( "mediumint" )
			->setMaxLength( 9 )
			->setAutoIncrement( true )
		->addField( "timestamp" )//timestamp of entry
			->setType( "timestamp" )
		->addField( "password" )// the number of the password used (0 if NA)
		->addField( "password_name" )//The name of that password at the time of entry
		->addField( "password_color" )//The color of the password at time of entry
		->addField( "action" )//The action (login, logout, login attempt)
		->addField( "user_agent")//The user agent
			->setType( "text" )
		->addField( "device" )
		->addField( "browser" )//The browser (as pmdarsed at time of entry)
		->addField( "operating_system" )//The OS (as parsed at time of entry)
		->addField( "ip_address" )
;


$thePlugin->_pages()
	->addScript( $thePlugin->_slug( "zendesk" ), "https://assets.zendesk.com/external/zenbox/v2.6/zenbox.js" )
	->addStyle( $thePlugin->_slug( "zendesk" ), "https://assets.zendesk.com/external/zenbox/v2.6/zenbox.css")
	->addSettingsPage()
	->addSkinsPage()
		->setTitle( __( "Login Page Skin", $pluginSlug ) )
	->addPage( "access_logs", "PrivateBlogAccessLogs" )
		->setTitle( __( "Access Logs", $pluginSlug ) )
		->setDataSource( "access_logs" )
		->setDisplayOrder( "timestamp;action;password_name;browser;operating_system;device;ip_address" )
		->setOrderBy( "timestamp DESC" )/*
	->addPageFromTemplate( "custom", "custom" )
		->setTitle( __( "Plugin Customisations", $pluginSlug ) )*/
;

$thePlugin->_pages()
	->addCustomScripts()
	->addCustomStyles()
;

?>
