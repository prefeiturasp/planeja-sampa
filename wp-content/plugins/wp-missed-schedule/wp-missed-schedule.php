<?php 
/*
Plugin Name: WP Missed Schedule - RC2013
Plugin URI: http://slangji.wordpress.com/wp-missed-schedule/
Description: WP Missed Schedule Fix Future Posts Virtual Cron Job: find only Scheduled that match this problem, no others or not Missed posts, and it Republish them Correctly 10 items per session, every 5 minutes. All others will be solved on next sessions, to no waste resources, until no longer exist; 10 failed future posts every 5 minutes, 120 failed future posts every hour, 1 session every 5 minutes, 12 sessions every hour. The default 10 Failed Future Posts per session, was introduced for compatibility with default WordPress Items Feed Syndication. This plugin is designed, on fact, for heavy use of Scheduled Future Posts and RSS Grabbing (as FeedWordPress or WP-O-Matic), but also work well with a simple WordPress Blog or for use as a CMS. The configuration of this plugin is automatic and not need other actions except installing, uninstall or delete it! Compatible with HyperDB Table Query Formatting.
Version: 2013.1024.8888
Author: sLa NGjI's
Author URI: http://slangji.wordpress.com/
Requires at least: 2.6
Tested up to: 3.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Indentation: GNU style coding standard
Indentation URI: http://www.gnu.org/prep/standards/standards.html
 *
 * DEVELOPMENT release: Version 2013 Build 1009 Revision 1916
 *
 * PRO release: Version 2014 Build 0000 Revision 2015
 *
 * [WP Missed Schedule](http://wordpress.org/plugins/wp-missed-schedule/) Fix Missed Scheduled Future Posts Virtual Cron Job
 *
 * Copyright (C) 2008-2014 [slangjis](http://slangji.wordpress.com/) (email: <slangjis [at] googlegmail [dot] com>))
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the [GNU General Public License](http://wordpress.org/about/gpl/)
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see [GNU General Public Licenses](http://www.gnu.org/licenses/),
 * or write to the Free Software Foundation, Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * DISCLAIMER
 *
 * The license under which the WordPress software is released is the GPLv2 (or later) from the
 * Free Software Foundation. A copy of the license is included with every copy of WordPress.
 *
 * Part of this license outlines requirements for derivative works, such as plugins or themes.
 * Derivatives of WordPress code inherit the GPL license.
 *
 * There is some legal grey area regarding what is considered a derivative work, but we feel
 * strongly that plugins and themes are derivative work and thus inherit the GPL license.
 *
 * The license for this software can be found on [Free Software Foundation](http://www.gnu.org/licenses/gpl-2.0.html) and as license.txt into this plugin package.
 *
 * The author of this plugin is available at any time, to make all changes, or corrections, to respect these specifications.
 *
 * THERMS
 *
 * This uses (or it parts) code derived from
 *
 * wp-header-footer-log.php by slangjis <slangjis [at] googlemail [dot] com>
 * Copyright (C) 2008-2013 [slangjis](http://slangji.wordpress.com/) (email: <slangjis [at] googlemail [dot] com>)
 *
 * according to the terms of the GNU General Public License version 2 (or later)
 *
 * This wp-header-footer-log.php uses (or it parts) code derived from
 *
 * wp-footer-log.php by slangjis <slangjis [at] googlemail [dot] com>
 * Copyright (C) 2008-2013 [slangjis](http://slangji.wordpress.com/) (email: <slangjis [at] googlemail [dot] com>)
 *
 * sLa2sLaNGjIs.php by slangjis <slangjis [at] googlemail [dot] com>
 * Copyright (C) 2009-2013 [slangjis](http://slangji.wordpress.com/) (email: <slangjis [at] googlemail [dot] com>)
 *
 * according to the terms of the GNU General Public License version 2 (or later)
 *
 * According to the Terms of the GNU General Public License version 2 (or later) part of Copyright belongs to your own author and part belongs to their respective others authors:
 *
 * Copyright (C) 2008-2013 [slangjis](http://slangji.wordpress.com/) (email: <slangjis [at] googlemail [dot] com>)
 *
 * VIOLATIONS
 *
 * [Violations of the GNU Licenses](http://www.gnu.org/licenses/gpl-violation.en.html)
 * The author of this plugin is available at any time, to make all changes, or corrections, to respect these specifications.
 *
 * GUIDELINES
 *
 * This software meet [Detailed Plugin Guidelines](http://wordpress.org/plugins/about/guidelines/) paragraphs 1,4,10,12,13,16,17 quality requirements.
 * The author of this plugin is available at any time, to make all changes, or corrections, to respect these specifications.
 *
 * CODING
 *
 * This software implement [GNU style](http://www.gnu.org/prep/standards/standards.html) coding standard indentation.
 * The author of this plugin is available at any time, to make all changes, or corrections, to respect these specifications.
 *
 * VALIDATION
 *
 * This readme.txt rocks. Seriously. Flying colors. It meet the specifications according to WordPress [Readme Validator](http://wordpress.org/plugins/about/validator/) directives.
 * The author of this plugin is available at any time, to make all changes, or corrections, to respect these specifications.
 *
 * THANKS
 *
 * [nicokaiser](http://wordpress.org/support/topic/plugin-uses-post_date_gmt-which-is-not-indexed)
 * Jack Hayhurst <jhayhurst [at] liquidweb [dot] com> for MySQL Queries and Server Load Optimization and Index Suggestion ...
 * [Arkadiusz Rzadkowolski](http://profiles.wordpress.org/fliespl/) for resolving table_name from query broken in select query: HyperDB Compatibility!
 * [milewis1](http://profiles.wordpress.org/milewis1/) for WordPress blog's timezone implementation instead of the MySQL time.
 */

	/**
	 * @package WP Missed Schedule
	 * @subpackage WordPress PlugIn
	 * @description Fix Missed Scheduled Future Posts Virtual Cron Job
	 * @since 2.6.0
	 * @tested 3.8.0
	 * @version 2013.1024.8888
	 * @1stversion 2008.1210.2008
	 * @status STABLE release
	 * @development Code in Becoming!
	 * @install The configuration of this Plugin is automatic!
	 * @author sLa NGjI's
	 * @license GPLv2 or later
	 * @indentation GNU style coding standard
	 * @keybit b79H8651411574J4YQCeLCQM540kT29FPANa8zFXj3lC62m78BbFMtb3g46FsK338
	 * @keysum 1FAC9ED8CAFF53960A2A10D2F8746C55
	 * @keytag 6d807758f47abdb4ae626b9fa261d2f5
	 */

	if ( !function_exists( 'add_action' ) )
		{
			header( 'HTTP/0.9 403 Forbidden' );
			header( 'HTTP/1.0 403 Forbidden' );
			header( 'HTTP/1.1 403 Forbidden' );
			header( 'Status: 403 Forbidden' );
			header( 'Connection: Close' );
				exit();
		}

	global $wp_version;

	if ( $wp_version < 2.6 )
		{
			wp_die( __( 'This Plugin Requires WordPress 2.6+ or Greater: Activation Stopped!' ) );
		}

	define( 'WPMS_OPTION', 'wp_missed_schedule' );

	function wpms_init()
		{
			$last = get_option( WPMS_OPTION, false );

			if ( ( $last !== false ) && ( $last > ( time() - ( 5 * 60 ) ) ) )
				return;

			update_option( WPMS_OPTION, time() );

			global $wpdb;

$qry = <<<SQL

			SELECT ID FROM {$wpdb->posts} WHERE ( ( post_date > 0 && post_date <= %s ) ) AND post_status = 'future' LIMIT 0,10

SQL;

			$sql = $wpdb->prepare( $qry, current_time( 'mysql', 0 ) );

			$scheduledIDs=$wpdb->get_col($sql);

			if ( !count( $scheduledIDs ) )
				return;

			foreach ( $scheduledIDs as $scheduledID )
				{
					if ( !$scheduledID )
						continue;

					wp_publish_post( $scheduledID );

				}
		}
	add_action( 'init', 'wpms_init', 0 );

	function wpms_rml( $links, $file )
		{
			if ( $file == plugin_basename( __FILE__ ) )
				{
					$links[] = '<a href="http://slangji.wordpress.com/donate/">' . __( 'Donate', 'wpms' ) . '</a>';
					$links[] = '<a href="http://slangji.wordpress.com/contact/">' . __( 'Contact', 'wpms' ) . '</a>';
					$links[] = '<a href="http://slangji.wordpress.com/plugins/">' . __( 'Others plugins', 'wpms' ) . '</a>';
				}
			return $links;
		}
	add_filter( 'plugin_row_meta', 'wpms_rml', 10, 2 );

	function wpms_log()
		{
			echo "\n<!--Plugin WP Missed Schedule 2013.1024.8888 Active - Tag ".md5(md5("b79H8651411574J4YQCeLCQM540kT29FPANa8zFXj3lC62m78BbFMtb3g46FsK338"."1FAC9ED8CAFF53960A2A10D2F8746C55"))."-->\n\n";
		}
	add_action( 'wp_head', 'wpms_log' );
	add_action( 'wp_footer', 'wpms_log' );

	function wpms_clnp()
		{
			delete_option( WPMS_OPTION );
		}
	register_deactivation_hook( __FILE__, 'wpms_clnp', 0 );
?>