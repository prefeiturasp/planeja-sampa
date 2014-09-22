=== WP Missed Schedule Fix Failed Future Posts ===
Contributors: slangjis
Donate link: http://slangji.wordpress.com/donate/
Tags: missed, schedule, scheduled, future, posts, fix, cron, missed-schedule, missed-scheduled, scheduled-posts, sla, slangjis
Stable tag: 2013.1024.8888
Requires at least: 2.6
Tested up to: 3.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
find missed schedule posts that match this problem, every 5 minute, and it republish them correctly fixed, 10 items per session.
== Description ==
find missed schedule posts that match this problem, every 5 minute, and it republish them correctly fixed, 10 items per session.

WP Missed Schedule Fix Future Posts: find Scheduled that match this problem, and it Republish them Correctly 10 items per session, every 5 minute, to no waste resources. All others will be solved on next sessions, until no longer exist: 10 failed future posts every minute, 120 failed future posts every hour, 1 session every 5 minutes, 12 sessions every hour.

This plugin not decrase server performaces, why it check WordPress "Virtual Cron Job" Function Behavior, to no waste resources, and not "Physical Cron Job" at scale!

For not use too many resources, process 10 items per session, every 5 minutes.

The default 10 Failed Future Posts per session, every 1 minute, was introduced for compatibility with default WordPress Items Feed Syndication.

This plugin is designed, on fact, for heavy use of Scheduled Future Posts and RSS Grabbing (as FeedWordPress or WP-O-Matic), but also work well with a simple WordPress Blog or for use as a CMS.
`
New WP Missed Schedule Features and Future Updates.

Actual Version RC2013:

1.  Introducing (a New Idea of sLa) Key Tag Authenticity!
2.  All in One Gold Versions with vary Frequency of Checking.
3.  HyperDB Table Query Formatting Compatibility.
4.  Fast Queries (with index table) and CPU Load Optimization.
5.  Full WordPress 3.7+ Compatible.
6.  Support WordPress 3.8+ Coming Soon 2013 Releases.
7.  Compatibility of W3 Total Cache and other Caching Plugins.
8.  JetPack and others Social Publishing Plugins Transparency.
9.  Interference Free with others Scheduled Cron Jobs a scale.

Developmental (in becoming) Version DEV:

1.  Prioritization of Plugin Loading. (now active for test)
2.  Admin DashBoard Options Control Panel. (for testing only)
3.  Admin DashBoard Help and FAQ Panel. (for testing only)
4.  Preemptive Support WordPress 3.9+ Future 2014 Release.

Future 2014/2015 Version PRO?:

1.  Prioritization of Plugin Loading.
2.  Check Plugin Authenticity Before Activation.
3.  Enable or Disable Header and Footer Log.
4.  Admin DashBoard Options Control Panel.
5.  Admin DashBoard Options Help and FAQ Panel.
6.  Customization of all Plugin Options and features.
7.  Manual Change the User Level Permissions.
8.  Manual Change the Frequency of Checking.
9.  Manual Change the Number of Checking Sessions.
10. Manual Change the Number of Failed Posts Fixed.
11. Manual Scheduling Plugin Checking and Fixing.
12. Switching from Local GMT and UTC Time. (more user control)
`
In the Future (PRO?) Version 2014/2015 of this plugin, it will be introduced the New Admin DashBoard Options Control Panel to Optimize all of its Features!

Scheduled Posts Regression <a href="http://core.trac.wordpress.org/ticket/22944" title="WordPress 3.5 Scheduled Posts Regression Ticket #22944">#22944</a> fixed on WP 3.5.1 is not related to this plugin.

The configuration of this plugin is Automattic! and not need other actions from the Administrator except installing, uninstall or delete it!
`
Nothing is written into space disk!
No need to delete anything from hosting space when deactivate!
No need to delete anything from hosting space when deleted!
No need to delete anything from the database when deactivate!
No need to delete anything from the database when deleted!
No need to delete anything from the wp_option when deactivate!
No need to delete anything from the wp_option when deleted!
Not need other actions except installing or uninstall it!
wp_option table is auto cleaned when deactivate or deleted!
`
Full Compatible with WordPress Versions from 2.6+ to 3.6+ add Preemptive Support for Coming Soon 3.7+ ~ 3.8+ and 3.9+ Future Releases. Ready to Single, Network Multisite installations, and old WPMU 2.6+ to 2.9+ (latest is 2.9.2) Multi Users. Run on Shared, Dedicated, Cloud and VPS Hosting, with high and low resources. Work under [GPLv2](http://www.gnu.org/licenses/gpl-2.0.html) or later License. Implement [GNU style](http://www.gnu.org/prep/standards/standards.html) coding standard indentation.

* [My Others WordPress Plugins](http://slangji.wordpress.com/plugins/)
 * [WP Overview (lite)](http://wordpress.org/plugins/wp-overview-lite/)
 * [WP Missed Schedule](http://wordpress.org/plugins/wp-missed-schedule/)
 * [WP Admin Bar Removal](http://wordpress.org/plugins/wp-admin-bar-removal/) Linked and reviewed at [softpedia.com](http://webscripts.softpedia.com/script/Modules/WordPress-Plugins/Admin-Bar-Removal-completely-disable-73547.html)
 * [WP Admin Bar Removal Node](http://wordpress.org/plugins/wp-admin-bar-node-removal/)
 * [WP ToolBar Removal](http://wordpress.org/plugins/wp-toolbar-removal/) Linked and reviewed at [softpedia.com](http://webscripts.softpedia.com/script/Modules/WordPress-Plugins/ToolBar-Removal-completely-disable-73548.html)
 * [WP ToolBar Removal Node](http://wordpress.org/plugins/wp-toolbar-node-removal/)
 * [Noindex (login) WordPress Deindexing](http://wordpress.org/plugins/wp-login-deindexing/) (refreshed)
 * [Noindex (total) WordPress Deindexing](http://wordpress.org/plugins/wp-total-deindexing/) (refreshed)
 * [IE Enhancer and Modernizer](http://wordpress.org/plugins/wp-ie-enhancer-and-modernizer/) Linked and reviewed at [softpedia.com](http://webscripts.softpedia.com/script/Modules/WordPress-Plugins/IE-Enhancer-and-Modernizer-73546.html)
 * [Memory Load Consumption db size Usage Indicator](http://wordpress.org/plugins/wp-memory-db-indicator/) (new)
 * [Header and Footer Log](http://wordpress.org/plugins/wp-header-footer-log/) Only For Developers (and advanced users)
== Installation ==
= Warning: =
Please noted that WP Missed Scheduled stop to work if installed on /mu-plugins/ directory! Install and Activate it only on Default Mode trough Plugin Control Panel.
= How to Repair /mu-plugins/ db wp_option Table Data? =
Activate and Deactivate, (one time only) [WPMS db Cleaner](http://downloads.wordpress.org/plugin/wp-missed-schedule.wpms-mu-plugins-clnr.zip) before activate version 2013.0726.6666 or any later newer release.
= For users of single WordPress 2.6+ (via FTP) =
1. Download WP Missed Schedule from wordpress.org plugin repository.
2. Upload it into /wp-content/plugins`/wp-missed-schedule/` via FTP.
3. Activate WP Missed Schedule.
= For users of single WordPress 2.7+ (manual) =
1. Download WP Missed Schedule from wordpress.org plugin repository.
2. Upload it into your WordPress directly from Plugin Add Feature.
3. It will create a directory /wp-content/plugins`/wp-missed-schedule/`
4. Activate WP Missed Schedule.
= For users of single WordPress 2.7+ (auto) =
1. Search WP Missed Schedule from Plugin Add Feature.
2. Install it live directly from wordpress.org repository.
3. It will create a directory /wp-content/plugins`/wp-missed-schedule/`
4. Activate WP Missed Schedule.
= How to uninstall WP Missed Schedule =
1. Disable WP Missed Schedule from Menu Plugins of Control Panel.
2. Delete WP Missed Schedule from Menu Plugins of Control Panel.
= Troubleshooting =
If all else fails and your site is broken remove directly via ftp on your host space /home/your-wp-install-dir/wp-content/plugins`/wp-missed-schedule/`.
== Frequently Asked Questions ==
wordpress comes with its own cron job that allows you to schedule your posts and events. however, in many situations, the wp-cron is not working well and leads to posts missed their publication schedule and/or scheduled events not executed.

To understand why this happen, we need to know that the WP-Cron is not a real cron job. It is in fact a virtual cron that only works when a page is loaded. In short, when a page is requested on the frontend/backend, WordPress will first load WP-Cron, follow by the necessary page to display to your reader. The loaded WP-Cron will then check the database to see if there is any thing that needs to be done.

Reasons for WP-Cron to fail could be due to:

1. DNS issue in the server.
2. Plugins conflict
3. Heavy load in the server which results in WP-Cron not executed fully
4. WordPress bug
5. Using of cache plugins that prevent the WP-Cron from loading
6. And many other reasons

Publish a bunch of future posts noticed that they won't publish and when time comes to go live they just turn Missed Schedule.
Took a look at the Wordpress code and noticed future posts get assigned a cronjob `($unix_time_stamp, 'publish_future_post', array($post_ID))` [wp_schedule_single_event](http://codex.wordpress.org/Function_Reference/wp_schedule_single_event)
Why don't you just look at the database and publish all posts with future status and date in past?
My plugin WP Missed Shcedule looks for posts with a date in the past that still have `post_status=future`. It will take each `post_ID` and publish [wp_publish_post](http://codex.wordpress.org/Function_Reference/wp_publish_post) it.
= How to Work? =
This plugin will check every 1 minute, if there are posts that match the problem described. ('WPMS_DELAY' ,1) To not use too many resources, it fix for 10 items per session, one session every 1 minute. LIMIT 10 All others failed will be solved in future sessions, until no longer exist. When you activate this plugin the first 10 "Missed Scheduled Future Posts" are fixed immediately. All others are fixed the next batch. On some case (rare?) are also fixed live. If you have "Missed Scheduled Future Posts" after this plugin is activated, is not one error or bug: wait the next checking. If "Missed Scheduled Future Posts" persist, verify that WordPress installation is clean, or exist conflict with other plugins.

N.B. If have active others plugins with the same functions of "WP Missed Schedule" this is on conflict and not work. I suggest to delete or deactivate all others, clean related database options table, and use only "WP Missed Schedule". In the same way "WP Missed Schedule" could create conflicts with other plugins with the same functions. In this case, delete or disable it and only used the others.
= Dealing with WordPress "Missed Schedule" =
If you are scheduling blog posts in WordPress and seeing a "Missed Schedule" message, it's likely caused by an issue with your web server, or it is WordPress that is causing the problem of your blog posts not being posted as scheduled. This is an annoying problem. However, there is a very simple fix that is easy to do. The "Missed Schedule" problem seems to point to the web server and WordPress. The "time/date" comparison needs to match in order for your blog posts to get published as scheduled. If you are currently using the WordPress, blogging platform, you can easily fix the issue by modifying the wp-cron.php file which is located in the root folder. You simply open your notepad editor in Windows and search for the following line of code, which is located towards the bottom on the file wp-cron.php file.

This is the code you need to search for: update_option(’doing_cron’, 0);

This is the code you need to replace it with: //update_option(’doing_cron’, 0);

Next step is to save the wp-cron.php file and upload to your web server. However, make sure that you renamed the current "wp-cron.php" on the web server to "wp-cron.php-org", just in case there is an issue, and you need to resort back to the original file. The final step is to schedule another blog post and make sure that it processes correctly and that it gets published according to schedule. To manually run the cron, you'll need to type or paste the code below in your Internet browser URL without the brackets. "yourdomain.com/wp-cron.php"
If things are working correctly, it should return a blank screen. Furthermore, this should update the time/date" comparison between your web server and WordPress.
= The Missed Schedule Problem =
The way WordPress handles scheduling is that whenever a page is loaded, either from your blog or in your admin control panel, the file wp-cron.php is loaded. At normal, if correctly configured, the server can talk to itself just fine and WordPress scheduling system will works perfectly. It’s only when you start doing strange and weird things like not having DNS setup properly or blocking loopback connections then it will cause you problems. It is possible that certain web hosts are not allowing WordPress cron jobs to run but for many that is not the issue as scheduled posting was working before upgrading to WordPress 2.7.

In WordPress 2.7, the cron job design, which is the core of the scheduling engine, is significantly changed as you can from both wp-cron.php and cron.php in /wp-includes/ folder. In WordPress 2.7 wp-cron.php, there are references to local-time and doing_cron option is set to zero. This is not exist in WordPress version 2.6.5. This might be the cause of the problem as it’s very likely that your web server time is off by a few seconds or minutes from the WordPress official time. And doing_cron argument is set to zero making it absolutely necessary that your web server and WordPress time to match with each other in order for the scheduled post to go through.
= Solutioni #1 =
If you think that your web server settings is the cause of the problem, simply type this URL in your browser http://www.yourblog.com/wp-cron.php (replace yourblog with your actual domain name) to verify. If you see a blank screen, then your web server settings is ok. You can proceed to solution #2. If you see some error pages, then kindly check with your web hosting technical staff and ask for their help.
= Solution #2 =
This is the solution to fix local-time and doing_cron option in wp-cron.php. If your programming is good enough, you are free to change the code and fix the issue yourselves. Remember to backup your WordPress before applying any change in production.

If you’re not familiar with programming, don’t worry, there is a simple solution.

   1. Download WordPress version 2.6.5 from WordPress repository.
   2. Extract both wp-cron.php and cron.php file from version 2.6.5.
   3. Backup your WordPress database.
   4. Rename both wp-cron.php and cron.php in your web server to other name.
   5. Upload both wp-cron.php and cron.php extracted from version 2.6.5 to your web server via FTP client.
= Conclusion = 
I hope the fix working fine for you. WordPress should really look into this issue seriously and provide a fix or help to resolve the issue faced by many of the bloggers. If WordPress is not able to publish future post at predefined time, it should recheck it periodically for several time, says every 5/10/15 minutes, and publish the post as soon as possible.
== Screenshots ==
1. Missed Scheduled Screenshot
2. Planification Manquee Screenshot
3. Programmazione Mancante Screenshot
== Changelog ==
= Todo List =
Actual Version RC2013:

1.  Support for WordPress 3.9+ 2014 Release (2014.0000.1111)
2.  Support for WordPress 3.8+ Coming Soon 2013 Release (2013.0000.9999)
3.  Full Support for WordPress 3.7+ Release (2013.1024.8888)
4.  Tiket #4218662 [HyperDB Query Compatibility](http://wordpress.org/support/topic/resolving-table_name-from-query-broken-in-select-query) (2013.0730.7777)
5.  Realtime Checking: 10 Future Posts for 5 Minute (2013.0726.6666)  
6.  Queries and CPU Load Optimization (2013.0725.5555) by Jack Hayhurst
7.  All in One Versions with vary Frequency of Check (2013.0531.4444)
8.  Key Tag Plugin Authenticity (2013.0131.3333)
9.  Tiket #4163854 [W3 Total Cache Conflict](http://wordpress.org/support/topic/any-conflicts-with-w3-cache/) (2013.0131.3333)
10. Compatibility of Third Party Caching Plugins (2013.0131.3333)
11. Tiket #3712701 [TimeZone Issues](http://wordpress.org/support/topic/publishing-ahead-of-schedule-timezone-issues/) (2013.0131.3333)
12. Realtime Checking: 10 Future Posts for 1 Minute (2013.0131.3333)
13. Default WordPress Items Feed Syndication Support (2013.0131.3333)
14. Transparency of JetPack and others Publishing Plugins (2013.0131.3333)
15. Free Interference with others Scheduled Cron Jobs (2013.0131.3333)
16. Full Support for WordPress 3.6+ Release (2013.0131.3333)
17. Tiket #3786523 [Strange Messages](http://wordpress.org/support/topic/activate-the-plugin-then-show-strange-messages/) (2013.0130.2222)
18. Realtime Checking: 5 Future Posts for 1 Minute (2013.0130.2222)

Developmental (in becoming) Version DEV:

1. Admin DashBoard Options Control Panel (2013.0000.2013-DEV) concept only 
2. Admin DashBoard Help and FAQ Panel (2013.0000.2013-DEV) concept only
3. Prioritization Plugin Loading (2013.0824.0312-DEV) now active for test
4. Preemptive Support WP 3.9+ Future 2014 Release (2013.0730.1530-DEV)

Future 2014/2015 Version PRO?:

1. Prioritization of Plugin Loading (2014.0000.2015-PRO)
1. Check Plugin Authenticity Before Activation (2014.0000.2015-PRO)
2. Admin DashBoard Options Control Panel (2014.0000.2015-PRO)
3. Admin DashBoard Options Control Panel Help and FAQ (2014.0000.2015-PRO)
4. Enable or Disable Header and Footer Log (2014.0000.2015-PRO)
5. Customization of all Plugin Options (2014.0000.2015-PRO)
6. Manual Change User Level Permissions (2014.0000.2015-PRO)
7. Tiket #3740220 [Change Frequency](http://wordpress.org/support/topic/change-frequency/) (2014.0000.2015-PRO)
8. Manual Scheduling the Number of Checking Sessions (2014.0000.2015-PRO)
9. Manual Change the Number of Failed Posts Fixed (2014.0000.2015-PRO)
10. Manual Scheduling Plugin Checking and Fixing (2014.0000.2015-PRO)
11. Switching from Local GMT and UTC Time. (2014.0000.2015-PRO)
= Warning Notice =
`
All previous versions, on fact, before the last stable,
are deprecated and no longer supported in this project!

We always recommend upgrading to the latest version!
`
= Disclaimer =
`
Please noted that Special Gold Edition is dedicated to expert
and "Advanced Users" and was installed only on manual mode!
`
= Legend =
`
Major Stable Build is identified with "current year"
on final Version number (2013.1231.*2013*) for example.

Incremental Build is identified with "progressive number"
on final Version number (2013.1024.*8888*) for example.
`
= Development Release =
[Version 2013 Build 1009-BUGFIX.1916-DEVELOPMENTAL](http://downloads.wordpress.org/plugin/wp-missed-schedule.zip)
= 2013.1024.8888 =
* Recommended Update [STABLE] Fixed infrequent freeze when deactivate or delete it!
 * Please update as soon as possible!
 * Official Release Candidate 2013 (RC2013 IS EXPECTED ON 2013/12)
 * Full Support and Compatibility for WordPress 3.7+ ~ 3.8+
 * NEW Special Gold All in One Update Versions with vary Frequency of Check.
 * BUXFIX Fixed infrequent freeze when deactivate or delete it!
 * UPDATED Stability and Performances.
 * BUMP Version 2013 Build 1024 Revision 8888
= 2013.0730.7777 =
* Release Candidate 2013 [STABLE] WP Missed Scheduled Release Candidate 2013
 * Please update as soon as possible!
 * Full Support and Compatibility for WordPress 3.7+ ~ 3.8+
 * NEW Tiket #4218662 [HyperDB Query Compatibility](http://wordpress.org/support/topic/resolving-table_name-from-query-broken-in-select-query)
 * NEW Introducing Concept of PRO Version for Future Development and Survivor.
 * UPDATED Realtime Checking: 10 Future Posts for 5 Minute.
 * BUMP Version 2013 Build 0730 Revision 7777
= 2013.0726.6666 =
* Bugfix Update [OMG] Resolved Issue Discuss [Here](http://wordpress.org/support/topic/bug-published-future-posts-after-update)
 * Please update as soon as possible!
 * Full Support and Compatibility for WordPress 3.6+
 * NEW Fast Queries and CPU Load (5 minutes) Optimization by Jack Hayhurst.
 * UPDATED Security Rules.
 * REMOVED /mu-plugins/ Outdated Install Support! Refer to [WPMS db Cleaner](http://downloads.wordpress.org/plugin/wp-missed-schedule.wpms-mu-plugins-clnr.zip)
 * REVERTED Default Failed Post Checking from 1 minute to 5 minutes.
 * BUMP Version 2013 Build 0726 Revision 6666
= 2013.0725.5555 =
* Recommended Update [BUGFIX-PERFORMANCES-SECURITY] Future Start Now!
 * Please update as soon as possible!
 * Full Support and Compatibility for WordPress 3.6+
 * NEW Fast Queries and CPU Load (5 minutes) Optimization by Jack Hayhurst.
 * UPDATED Security Rules.
 * REMOVED /mu-plugins/ Outdated Install Support! Refer to [WPMS db Cleaner](http://downloads.wordpress.org/plugin/wp-missed-schedule.wpms-mu-plugins-clnr.zip)
 * REVERTED Default Failed Post Checking from 1 minute to 5 minutes.
 * BUMP Version 2013 Build 0725 Revision 5555
= 2013.0531.4444 =
* Special Gold Update [STABLE] All in One Versions with vary Frequency of Check
 * Please update as soon as possible!
 * Full Compatible with WordPress 2.6+ to 3.5+
 * Full Support for WordPress 3.6+ Coming Soon Release.
 * NEW Transparency of JetPack and others Publishing Plugins.
 * BUGFIX Fixed Tiket #3712701 [TimeZone Issues](http://wordpress.org/support/topic/publishing-ahead-of-schedule-timezone-issues/)
 * ENHANCEMENT Compatibility of Third Party Caching Plugins.
 * BUGFIX Fixed Ticket #4163854 [W3 Total Cache Conflict](http://wordpress.org/support/topic/any-conflicts-with-w3-cache/)
 * ENHANCEMENT Default WordPress Items Feed Syndication Support.
 * UPDATE Checking 10 Posts for 1 Minute (previous 5 items) ('LIMIT' 10)
 * REMOVED ('WPMS_DELAY' ,1)
 * SECURITY Introducing (a New Idea of sLa) Check Key Tag Authenticity.
 * UPDATE Key Tag Checking Mechanism.
 * BUMP Version 2013 Build 0531 Revision 4444
= 2013.0131.3333 =
* Recommended Update [BUGFIX] Fixed Ticket #3712701 [TimeZone Issues](http://wordpress.org/support/topic/publishing-ahead-of-schedule-timezone-issues/)
 * Please update as soon as possible!
 * Full Compatible with WordPress 2.6+ to 3.5+
 * Preemptive Support for WordPress 3.6+ Coming Soon Release.
 * NEW Transparency of JetPack and others Publishing Plugins.
 * BUGFIX Fixed Tiket #3712701 [TimeZone Issues](http://wordpress.org/support/topic/publishing-ahead-of-schedule-timezone-issues/)
 * ENHANCEMENT Compatibility of Third Party Caching Plugins.
 * BUGFIX Fixed Ticket #4163854 [W3 Total Cache Conflict](http://wordpress.org/support/topic/any-conflicts-with-w3-cache/)
 * ENHANCEMENT Default WordPress Items Feed Syndication Support.
 * UPDATE Checking 10 Posts for 1 Minute (previous 5 items) ('LIMIT' 10)
 * UPDATE checking interval every 1 minute (previous 5 minutes) ('WPMS_DELAY' ,1)
 * SECURITY Introducing (a New Idea of sLa) Check Key Tag Authenticity.
 * UPDATE Key Tag Checking Mechanism.
 * BUMP Version 2013 Build 0131 Revision 3333
= 2013.0130.2222 =
* Recommended Update [BUGFIX] Fixed (unusual/infrequent) Ticket [#3786523](http://wordpress.org/support/topic/activate-the-plugin-then-show-strange-messages/)
 * Please update as soon as possible!
 * Full Compatible with WordPress 2.6+ to 3.5+
 * Preemptive Support for WordPress 3.6+ Coming Soon Release.
 * BUGFIX Fixed Ticket [#3786523](http://wordpress.org/support/topic/activate-the-plugin-then-show-strange-messages/) in Some Circumstances Hosting Configurations.
 * ENHANCEMENT Checking Interval Modified from 5 Minutes to 1 Minute.
 * UPDATE checking interval every 1 minute (previous 5 minutes) ('WPMS_DELAY' ,1)
 * SECURITY Introducing (a New Idea of sLa) Check Key Tag Authenticity.
 * BUMP Version 2013 Build 0130 Revision 2222
= 2013.0106.1111 =
* Silent Update [STABLE] Try WP 3.5 Scheduled Posts Regression Ticket <a href="http://core.trac.wordpress.org/ticket/22944" title="WordPress 3.5 Scheduled Posts Regression Ticket #22944">#22944</a>
 * Please update as soon as possible!
 * Full Compatible with WordPress 2.6+ to 3.5+
 * BUMP Version 2013 Build 0106 Revision 1111
= 2012.0613.2012 =
* Major Update [CERTIFIED] WP 2.6+ to 3.4+ Single and MultiSite Environment
 * Please update as soon as possible!
 * Full Compatible with WordPress 2.6+ to 3.4+
 * UPDATE Check every 15 minutes 'WPMS_DELAY',15
 * UPDATE Fix 10 items per session 'LIMIT' 10
 * BUMP Version 2012 Build 0613 Revision 2012
= 2011.0920.2011 =
* Major Update [CERTIFIED] WP 2.6+ to 3.3+ Single and MultiSite Environment
 * Please update as soon as possible!
 * Full Compatible with WordPress 2.6+ to 3.3+
 * UPDATE Check every 15 minutes 'WPMS_DELAY',15
 * UPDATE Fix 10 items per session 'LIMIT' 10
 * BUMP Version 2011 Build 0920 Revision 2011
= 2011.0424.3333 =
* Silent Update [MAINTENANCE] WP 3.1 and 3.1.1 Upgrade. Fixed slowness.
 * PLEASE Update as soon as possible!
 * UPGRADE Make it full compatible with WordPress 3.1 and 3.1.1 a.k.a 3.1+
 * NEW Replaced wp_future_post function with wpms_future_post
 * NEW Very realtime missed scheduled failed future posts recovery and fixing
 * EXPLAINED WP Missed Schedule fix one failed post in a minute: cool!
 * UPDATE Preemptive support for WordPress 3.1.2-alpha and 3.2-bleeding
 * UPDATE Now fix 5 items per session (previous 10) 'LIMIT' 0,5
 * FIXED Low resource hosting slowness when execute session task
 * IMPROVED Code cleanup and compress again for new faster loading
 * IMPROVED Functions redefinied for best timeline
 * BUMP Version 2011 Build 0424 Revision 3333
= 2011.0214.2222 =
* Silent Update [MAINTENANCE] WP 3.0.5 and 3.1-RC4-17441 Upgrade.
 * Please update as soon as possible!
 * UPGRADE Make it full compatible with WordPress 3.0.5
 * FIXED Some Hosting Crash with Full Strict Security Rules (.htaccess)
 * UPDATE check every 5 minutes (previous 15 minutes) ('WPMS_DELAY' ,5)
 * UPDATE Preemptive support for WordPress 3.1-RC4-17441
 * Bump Version 2011 Build 0214 Revision 2222
= 2011.0107.1111 =
* Major Update [CERTIFIED] WP 3.1-RC2-17229 Compatibility Upgrade.
 * Please update as soon as possible!
 * First 2011 Major Release (Zero Bug Certified) :)
 * UPDATE Preemptive support for WordPress 3.1-RC2-17229
 * Bump Version 2011 Build 0107 Revision 1111
= 2010.1231.2010 =
* Major Update [STABLE] Full WP 3.0.4 and 3.1-RC1-17163 Zero Bugs Compatibility Upgrade.
 * Please update as soon as possible!
 * Fix Missed Scheduled Future Posts Cron Job.
 * ZERO-BUGS Full Last 2010 Major Release.
 * UPDATE Preemptive support for WordPress 3.1-RC1-17163
 * FIXED WordPress [wp_schedule_single_event](http://codex.wordpress.org/Function_Reference/wp_schedule_single_event) Function Behavior.
 * FIXED WordPress [wp_publish_post](http://codex.wordpress.org/Function_Reference/wp_publish_post) Function Behavior.
 * Make it full compatible with WordPress 3.0.4
 * Fixed Execution Time.
 * Reduce Code Bloat.
 * Code Cleanup for faster loading.
 * Work with single WordPress 2.6.x to 3.1.x and old MU.
 * Bump Version 2010 Build 1231 Revision 2010
= 2010.1226.0246 =
* Silent Update [MAINTENANCE] WP 3.1-RC1 Compatibility Upgrade.
 * Please update as soon as possible!
 * UPDATE Preemptive support for WordPress 3.1-RC1
 * Bump Version 2010 Build 1226 Revision 0246
= 2010.1220.0048 =
* Silent Update [MAINTENANCE] WP 3.1-beta2-16997 Compatibility Upgrade.
 * Please update as soon as possible!
 * UPDATE Preemptive support for WordPress 3.1-beta2-16997
 * Bump Version 2010 Build 1220 Revision 0048
= 2010.1211.0038 =
* Silent Update [MAINTENANCE] WP 3.0.3 and 3.1-beta1-16732 Compatibility Upgrade.
 * Please update as soon as possible!
 * UPDATE Make it full compatible with WP 3.0.3
 * UPDATE Preemptive support for WordPress 3.1-beta1-16732
 * Bump Version 2010 Build 1211 Revision 0038
= 2010.1201.1918 =
* Silent Update [MAINTENANCE] WP 3.0.2 and 3.1-beta1 Compatibility Upgrade.
 * Please update as soon as possible!
 * NEW Make it full compatible with WP 3.0.2
 * NEW Preemptive support for WordPress 3.1-beta1
 * NEW More Accurate Links on Plugin Control Panel Description
 * Bump Version 2010 Build 1201 Revision 1918
= 2010.0821.1539 =
* Silent Update [BUGFIX] Reduced Bloat and Code Cleanup.
 * Please update as soon as possible!
 * Bump Version 2010 Build 0821 Revision 1539
= 2010.0816.2254 =
* First Public Stable Release (full WP 3.0.1 compatible)
 * Fix Missed Scheduled Future Posts Cron Job
 * Make it full compatible with WP 3.0.1
 * Preemptive support for WordPress 3.1-alpha
 * Code Cleanup for faster loading.
 * Bump Version 2010 Build 0816 Revision 2254
= 2009.1218.2009 =
* Make it full compatible with WP 2.9 and WPMU
 * Preemptive support for WordPress 3.0-alpha
 * Fixed Execution Time
 * Bump Version 2009 Build 1218 Revision 2009
= 2008.1210.2008 =
* Make it full compatible with WP 2.7 and WPMU
 * Preemptive support for WordPress 2.8-alpha
 * Reduce Code Bloat
 * Bump Version 2008 Build 1210 Revision 2008
= Common Rules =
1. Compatible with: WordPress, bbPress, Buddypress.
1. Ready to Single and Network Multisite Environment.
1. Plugin Memory Consumption (less of 1KB or no more)
1. Full Strict Security Rules Applied.
1. Work with Shared and VPS Hosting.
1. Work under [GPLv2](http://www.gnu.org/licenses/gpl-2.0.html) or later License.
1. Implement [GNU style](http://www.gnu.org/prep/standards/standards.html) coding standard indentation.
1. Meet detailed [Plugin Guidelines](http://wordpress.org/plugins/about/guidelines/) quality requirements.
`
Nothing is written into space disk!
No need to delete anything from hosting space when deactivate!
No need to delete anything from hosting space when deleted!
No need to delete anything from the database when deactivate!
No need to delete anything from the database when deleted!
No need to delete anything from the wp_option when deactivate!
No need to delete anything from the wp_option when deleted!
Not need other actions except installing or uninstall it!
wp_option table is auto cleaned when deactivate or deleted!
`
== Upgrade Notice ==
= 2013.1024.8888 =
Recommended Update RC2013 [STABLE] Fixed infrequent freeze when deactivate or delete it! Updated Stability and Performances. Official Release Candidate 2013: add Full Support and Compatibility for WordPress 3.7+ ~ 3.8+
