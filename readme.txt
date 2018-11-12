=== Server Status (System Status) - CPU, RAM, PHP, etc ===

Contributors: littlebizzy
Donate link: https://www.patreon.com/littlebizzy
Tags: server, system, status, stats, health
Requires at least: 4.4
Tested up to: 5.0
Requires PHP: 7.0
Stable tag: 1.4.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Prefix: SVRSTS

Useful statistics about the server OS, CPU, RAM, load average, memory usage, IP address, hostname, timezone, disk space, PHP, MySQL, caches, etc.

== Description ==

Useful statistics about the server OS, CPU, RAM, load average, memory usage, IP address, hostname, timezone, disk space, PHP, MySQL, caches, etc.

* [**Join our FREE Facebook group for support**](https://www.facebook.com/groups/littlebizzy/)
* [**Worth a 5-star review? Thank you!**](https://wordpress.org/support/plugin/server-status-littlebizzy/reviews/?rate=5#new-post)
* [Plugin Homepage](https://www.littlebizzy.com/plugins/server-status)
* [Plugin GitHub](https://github.com/littlebizzy/server-status)

#### Current Features ####

Server Status is a simple WordPress plugin for quickly displaying important statistics and configuration settings in regard to your server and WordPress environment. Specifically, the plugin creates a single dashboard widget along with a small line of data in the WP Admin footer with key info in regard to memory usage, PHP configuration, and several other useful items.

This plugin does NOT aim to replace the need for researching `phpinfo` or `wp-config` settings when extensive server statistics or configuration settings are needed. Rather, it aims to highlight the most commonly required settings needed by WordPress developers in an effort to save time and improve productivity.

We've purposefully avoided having a "settings" page for this plugin in order to keep things as simple as possible. However, as with any dashboard widget, you can easily hide the Server Status widget if needed (although not the footer data).

The code aims to be as minimalistic as possible while adhering to best practices. It blocks direct calls of its PHP files for security reasons and also implements transients so that data is briefly cached for top speed and performance (although "Memory Usage" and "Load Average" are not currently cached with transients).

You may change display style using the following constant (below) change default "widefat" to "grid"...

Some details about the plugin implementation:

/server-status.php
- Main plugin file which loads the dashboard or footer admin files through the WP hooks.
- No direct HTTP calls allowed, aborting the code execution if ABSPATH WP constant not defined.
- Defines some plugin constants like plugin path or cache refresh duration.
- Returns execution to WP in case we are outside of WP admin area.
- Only two functions to handle the WP hooks, avoiding PHP parsing of not used code.

/admin/dashboard.php
- Loaded only in dashboard context.
- The widdget code first loads the /core/status.php file with common procedures.
- Database access and disk access are cached for 30 seconds into one WP transient: disk space, database size and mysql version.
- Only one database access reading the transient "srvsts_data", expiration time is defined in constant SVRSTS_REFRESH from main /server-status.php file.
- The widget displays items in a two columns table, except for long items where is used an entire row with one column.
- Exposes the WP filter "srvsts_items" allowing to add or modify more server status data.

/admin/footer.php
- This file is loaded after the "admin_footer_text" hook
- Loads the /core/status.php file with common procedures in a require_once way.
- Request the memory usage function without caching argument to get the current memory info.
- No database access because here we are not using procedures that requires transient data.

/core/status.php
- There is two parts, the first one is for server status procedures info, and the second part to check/save this data in a WP transient
- Memory usage procedure can be called with or without local cache argument (this value is not saved in the transient).

The code has been tested on PHP 7 with no errors.
Direct HTTP requests to any file does not produce error log records.
All classes are implemented with static methods, so there are no objects instances in memory.

#### Compatibility ####

This plugin has been designed for use on [SlickStack](https://slickstack.io) web servers with PHP 7.2 and MySQL 5.7 to achieve best performance. All of our plugins are meant for single site WordPress installations only; for both performance and usability reasons, we highly recommend avoiding WordPress Multisite for the vast majority of projects.

Any of our WordPress plugins may also be loaded as "Must-Use" plugins by using our free [Autoloader](https://github.com/littlebizzy/autoloader) script in the `mu-plugins` directory.

#### Defined Constants ####

    /* Plugin Meta */
    define('DISABLE_NAG_NOTICES', true);
    
    /* Server Status Functions */
    define('SERVER_STATUS_DISPLAY', 'widefat');

#### Plugin Features ####

* Prefix: SVRSTS
* Parent Plugin: N/A
* Disable Nag Notices: [Yes](https://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices#Disable_Nag_Notices)
* Settings Page: No
* PHP Namespaces: No
* Object-Oriented Code: No
* Includes Media (images, icons, etc): No
* Includes CSS: No
* Database Storage: Yes
  * Transients: Yes
  * WP Options Table: Yes
  * Other Tables: No
  * Creates New Tables: No
  * Creates New WP Cron Jobs: No
* Database Queries: Backend Only (Options API + Transients API)
* Must-Use Support: [Yes](https://github.com/littlebizzy/autoloader)
* Multisite Support: No
* Uninstalls Data: Yes

#### Special Thanks ####

[Alex Georgiou](https://www.alexgeorgiou.gr), [Automattic](https://automattic.com), [Brad Touesnard](https://bradt.ca), [Daniel Auener](http://www.danielauener.com), [Delicious Brains](https://deliciousbrains.com), [Greg Rickaby](https://gregrickaby.com), [Matt Mullenweg](https://ma.tt), [Mika Epstein](https://halfelf.org), [Mike Garrett](https://mikengarrett.com), [Samuel Wood](http://ottopress.com), [Scott Reilly](http://coffee2code.com), [Jan Dembowski](https://profiles.wordpress.org/jdembowski), [Jeff Starr](https://perishablepress.com), [Jeff Chandler](https://jeffc.me), [Jeff Matson](https://jeffmatson.net), [Jeremy Wagner](https://jeremywagner.me), [John James Jacoby](https://jjj.blog), [Leland Fiegel](https://leland.me), [Luke Cavanagh](https://github.com/lukecav), [Mike Jolley](https://mikejolley.com), [Pau Iglesias](https://pauiglesias.com), [Paul Irish](https://www.paulirish.com), [Rahul Bansal](https://profiles.wordpress.org/rahul286), [Roots](https://roots.io), [rtCamp](https://rtcamp.com), [Ryan Hellyer](https://geek.hellyer.kiwi), [WP Chat](https://wpchat.com), [WP Tavern](https://wptavern.com)

#### Disclaimer ####

We released this plugin in response to our managed hosting clients asking for better access to their server, and our primary goal will remain supporting that purpose. Although we are 100% open to fielding requests from the WordPress community, we kindly ask that you keep these conditions in mind, and refrain from slandering, threatening, or harassing our team members in order to get a feature added, or to otherwise get "free" support. The only place you should be contacting us is in our free [**Facebook group**](https://www.facebook.com/groups/littlebizzy/) which has been setup for this purpose, or via GitHub if you are an experienced developer. Thank you!

#### Our Philosophy ####

> "Decisions, not options." -- WordPress.org

> "Everything should be made as simple as possible, but not simpler." -- Albert Einstein, et al

> "Write programs that do one thing and do it well... write programs to work together." -- Doug McIlroy

> "The innovation that this industry talks about so much is bullshit. Anybody can innovate... 99% of it is 'Get the work done.' The real work is in the details." -- Linus Torvalds

#### Search Keywords ####

server monitor, server status, system status, tcp memory usage, wp memory usage, wp server stats, wp system health

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/server-status-littlebizzy`
2. Activate via WP Admin > Plugins
3. Test plugin is working:

After plugin activation, purge all caches. Then, under the Dashboard area of WP Admin you should see a new dashboard widget called Server Status with various system information visible (Admin-level users only). In addition at the very bottom of every WP Admin page you should see a single line of brief system stats visible as well.

4. For alternative layouts, use either `define('SVRSTS_DISPLAY', 'grid');` or `define('SVRSTS_DISPLAY', 'widefat');`

== Frequently Asked Questions ==

= Does this plugin show everything from `phpinfo` settings? =

No, and it's not meant to. Please read the description tab for more info.

= Does this plugin show everything from `wp-config` settings? =

No, and it's not meant to. Please read the description tab for more info.

= How can I change this plugin's settings? =

This plugin does not have a settings page and is designed for speed and simplicity.

= I have a suggestion, how can I let you know? =

Please avoid leaving negative reviews in order to get a feature implemented. Instead, use our free Facebook group.

== Changelog ==

= 1.4.0 =
* tested with WP 5.0
* updated plugin meta

= 1.3.2 =
* updated plugin meta

= 1.3.1 =
* updated recommended plugins

= 1.3.0 = 
* versioning correction (major changes in 1.2.10)
* (no code changes)

= 1.2.10 =
* added support for `define('SERVER_STATUS_DISPLAY', 'grid | widefat');`
* (old spelling `SVRSTS_DISPLAY` no longer supported)
* updated plugin meta

= 1.2.9 =
* added warning for Multisite installations
* updated recommended plugins

= 1.2.8 =
* better support for `DISABLE_NAG_NOTICES`

= 1.2.7 =
* tested with WP 4.9
* partial support for `DISABLE_NAG_NOTICES`
* updated recommended plugins
* updated plugin meta

= 1.2.6 
* optimized plugin code
* added rating request notice
* updated recommended plugins

= 1.2.5 =
* updated recommended plugins

= 1.2.4 =
* added recommended plugins notice

= 1.2.3 =
* tested with WP 4.8
* updated plugin meta

= 1.2.2 =
* updated plugin meta

= 1.2.1 =
* fixed Opcache size calculation bug
* tweaked size calculation for Memory Usage (no decimal, M not MB)

= 1.2.0 =
* CPU cores and load average now available
* better calculation rounding i.e. KB, MB, GB, TB
* disk space now total "used" instead of  total "free"
* WordPress timezone show alongside server timezone now
* tweaked formatting of sizes (added 2 decimal places, etc)
* Opcache now on a different line than Page Cache

= 1.1.1 =
* Database Charset/Collate layout tweaked (was backwards)
* Readme.txt revised for wordpress.org and minor changes to "short" description, etc in `server-status.php`
* Default layout changed to be much more compressed to save space.
* Alternative layout options added, which must be defined within `wp-config`

= 1.1.0 =
* entirely recoded with PHP 7 and Transients API
* tested with PHP 7.0

= 1.0.0 =
* initial release (private)
