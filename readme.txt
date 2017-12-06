=== Server Status ===

Contributors: littlebizzy
Tags: server, system, status, stats, health
Requires at least: 4.4
Tested up to: 4.9
Requires PHP: 7.0
Stable tag: 1.2.8
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Prefix: SVRSTS

Useful statistics about the server OS, CPU, RAM, load average, memory usage, IP address, hostname, timezone, disk space, PHP, MySQL, caches, etc.

== Description ==

Useful statistics about the server OS, CPU, RAM, load average, memory usage, IP address, hostname, timezone, disk space, PHP, MySQL, caches, etc.

* [Plugin Homepage](https://www.littlebizzy.com/plugins/server-status)
* [Plugin GitHub](https://github.com/littlebizzy/server-status)
* [SlickStack](https://slickstack.io)

#### The Long Version ####

Server Status is a simple WordPress plugin for quickly displaying important statistics and configuration settings in regard to your server and WordPress environment. Specifically, the plugin creates a single dashboard widget along with a small line of data in the WP Admin footer with key info in regard to memory usage, PHP configuration, and several other useful items.

This plugin does NOT aim to replace the need for researching `phpinfo` or `wp-config` settings when extensive server statistics or configuration settings are needed. Rather, it aims to highlight the most commonly required settings needed by WordPress developers in an effort to save time and improve productivity.

We've purposefully avoided having a "settings" page for this plugin in order to keep things as simple as possible. However, as with any dashboard widget, you can easily hide the Server Status widget if needed (although not the footer data).

The code aims to be as minimalistic as possible while adhering to best practices. It blocks direct calls of its PHP files for security reasons and also implements transients so that data is briefly cached for top speed and performance (although "Memory Usage" and "Load Average" are not currently cached with transients).

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

This plugin has been designed for use on LEMP (Nginx) web servers with PHP 7.0 and MySQL 5.7 to achieve best performance. All of our plugins are meant for single site WordPress installations only; for both performance and security reasons, we highly recommend against using WordPress Multisite for the vast majority of projects.

#### Plugin Features ####

* Settings Page: No
* Premium Version Available: No
* Includes Media (Images, Icons, Etc): No
* Includes CSS: Backend Only
* Database Storage: Yes
  * Transients: Yes
  * Options: Yes
  * Creates New Tables: No
* Database Queries: Backend Only (Options API + Transients API)
* Must-Use Support: Yes (Use With [Autoloader](https://github.com/littlebizzy/autoloader))
* Multisite Support: No
* Uninstalls Data: Yes

#### WP Admin Notices ####

This plugin generates multiple [Admin Notices](https://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices) in the WP Admin dashboard. The first is a notice that fires during plugin activation which recommends several related free plugins that we believe will enhance this plugin's features; this notice will re-appear approximately once every 6 months as our code and recommendations evolve. The second is a notice that fires a few days after plugin activation which asks for a 5-star rating of this plugin on its WordPress.org profile page. This notice will re-appear approximately once every 9 months. These notices can be dismissed by clicking the **(x)** symbol in the upper right of the notice box. These notices may annoy or confuse certain users, but are appreciated by the majority of our userbase, who understand that these notices support our free contributions to the WordPress community while providing valuable (free) recommendations for optimizing their website.

If you feel that these notices are too annoying, than we encourage you to consider one or more of our upcoming premium plugins that combine several free plugin features into a single control panel, or even consider developing your own plugins for WordPress, if supporting free plugin authors is too frustrating for you. A final alternative would be to place the defined constant mentioned below inside of your `wp-config.php` file to manually hide this plugin's nag notices:

    define('DISABLE_NAG_NOTICES', true);

Note: This defined constant will only affect the notices mentioned above, and will not affect any other notices generated by this plugin or other plugins, such as one-time notices that communicate with admin-level users.

#### Code Inspiration ####

This plugin was partially inspired either in "code or concept" by the open-source software and discussions mentioned below:

* [WP Memory Usage](https://wordpress.org/plugins/wp-memory-usage/)
* [WP System Health](https://wordpress.org/plugins/wp-system-health/)
* [TCP Memory Usage](https://wordpress.org/plugins/tpc-memory-usage/)
* [WP Server Stats](https://wordpress.org/plugins/wp-server-stats/)
* [Server Monitor](https://wordpress.org/plugins/server-monitor/)

#### Recommended Plugins ####

We invite you to check out a few other related free plugins that our team has also produced that you may find especially useful:

* [404 To Homepage](https://wordpress.org/plugins/404-to-homepage-littlebizzy/)
* [CloudFlare](https://wordpress.org/plugins/cf-littlebizzy/)
* [Disable Author Pages](https://wordpress.org/plugins/disable-author-pages-littlebizzy/)
* [Disable Cart Fragments](https://wordpress.org/plugins/disable-cart-fragments-littlebizzy/)
* [Disable Embeds](https://wordpress.org/plugins/disable-embeds-littlebizzy/)
* [Disable Emojis](https://wordpress.org/plugins/disable-emojis-littlebizzy/)
* [Disable Empty Trash](https://wordpress.org/plugins/disable-empty-trash-littlebizzy/)
* [Disable Image Compression](https://wordpress.org/plugins/disable-image-compression-littlebizzy/)
* [Disable Search](https://wordpress.org/plugins/disable-search-littlebizzy/)
* [Disable WooCommerce Status](https://wordpress.org/plugins/disable-wc-status-littlebizzy/)
* [Disable WooCommerce Styles](https://wordpress.org/plugins/diable-wc-styles-littlebizzy/)
* [Disable XML-RPC](https://wordpress.org/plugins/disable-xml-rpc-littlebizzy/)
* [Download Media](https://wordpress.org/plugins/download-media-littlebizzy/)
* [Download Plugin](https://wordpress.org/plugins/download-plugin-littlebizzy/)
* [Download Theme](https://wordpress.org/plugins/download-theme-littlebizzy/)
* [Duplicate Post](https://wordpress.org/plugins/duplicate-post-littlebizzy/)
* [Export Database](https://wordpress.org/plugins/export-database-littlebizzy/)
* [Force HTTPS](https://wordpress.org/plugins/force-https-littlebizzy/)
* [Force Strong Hashing](https://wordpress.org/plugins/force-strong-hashing-littlebizzy/)
* [Google Analytics](https://wordpress.org/plugins/ga-littlebizzy/)
* [Index Autoload](https://wordpress.org/plugins/index-autoload-littlebizzy/)
* [Maintenance Mode](https://wordpress.org/plugins/maintenance-mode-littlebizzy/)
* [Profile Change Alerts](https://wordpress.org/plugins/profile-change-alerts-littlebizzy/)
* [Remove Category Base](https://wordpress.org/plugins/remove-category-base-littlebizzy/)
* [Remove Query Strings](https://wordpress.org/plugins/remove-query-strings-littlebizzy/)
* [Server Status](https://wordpress.org/plugins/server-status-littlebizzy/)
* [StatCounter](https://wordpress.org/plugins/sc-littlebizzy/)
* [View Defined Constants](https://wordpress.org/plugins/view-defined-constants-littlebizzy/)
* [Virtual Robots.txt](https://wordpress.org/plugins/virtual-robotstxt-littlebizzy/)

#### Premium Plugins ####

We invite you to check out a few premium plugins that our team has also produced that you may find especially useful:

* [Purge Them All](https://www.littlebizzy.com/plugins/purge-them-all)
* [Speed Demon](https://www.littlebizzy.com/plugins/speed-demon)
* [SEO Genius](https://www.littlebizzy.com/plugins/seo-genius)
* [Great Migration](https://www.littlebizzy.com/plugins/great-migration)
* [Security Guard](https://www.littlebizzy.com/plugins/security-guard)
* [Genghis Khan](https://www.littlebizzy.com/plugins/genghis-khan)

#### Related Services ####

The following paid services are available on a case-by-case basis to upstanding members of the WordPress community:

* [SSL Setup](https://www.littlebizzy.com/services/ssl)
* [DNSSEC Setup](https://www.littlebizzy.com/services/dnssec)
* [G Suite Setup](https://www.littlebizzy.com/services/google-apps)
* [SendGrid Setup](https://www.littlebizzy.com/services/sendgrid)
* [MailGun Setup](https://www.littlebizzy.com/services/mailgun)

#### Special Thanks ####

We thank the following groups for their generous contributions to the WordPress community which have particularly benefited us in developing our own free plugins and paid services:

* [Automattic](https://automattic.com)
* [Daniel Auener](http://www.danielauener.com)
* [Delicious Brains](https://deliciousbrains.com)
* [Greg Rickaby](https://gregrickaby.com)
* [Matt Mullenweg](https://ma.tt)
* [Mika Epstein](https://halfelf.org)
* [Samuel Wood](http://ottopress.com)
* [Scott Reilly](http://coffee2code.com)
* [Jan Dembowski](https://profiles.wordpress.org/jdembowski)
* [Jeff Starr](https://perishablepress.com)
* [Jeff Chandler](https://jeffc.me)
* [Jeff Matson](https://jeffmatson.net)
* [John James Jacoby](https://jjj.blog)
* [Leland Fiegel](https://leland.me)
* [Rahul Bansal](https://profiles.wordpress.org/rahul286)
* [Roots](https://roots.io)
* [rtCamp](https://rtcamp.com)
* [WP Chat](https://wpchat.com)
* [WP Tavern](https://wptavern.com)

#### Disclaimer ####

We released this plugin in response to our managed hosting clients asking for better access to their server, and our primary goal will remain supporting that purpose. Although we are 100% open to fielding requests from the WordPress community, we kindly ask that you keep the above mentioned goals in mind, thanks!

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/server-status-littlebizzy`
2. Activate via WP Admin > WP Admin
3. Check your WP Admin dashboard (admin users only) and footer area to view the statistics
4. For alternative layouts, put either `define('SVRSTS_DISPLAY', 'grid');` or `define('SVRSTS_DISPLAY', 'widefat');` in your `wp-config` file

== Frequently Asked Questions ==

= Does this plugin show everything from `phpinfo` settings? =

No, and it's not meant to. Please read the description tab for more info.

= Does this plugin show everything from `wp-config` settings? =

No, and it's not meant to. Please read the description tab for more info.

= How can I change this plugin's settings? =

This plugin does not have a settings page and is designed for speed and simplicity.

= I have a suggestion, how can I let you know? =

Please avoid leaving negative reviews in order to get a feature implemented. Instead, we kindly ask that you post your feedback on the wordpress.org support forums by tagging this plugin in your post. If needed, you may also contact our homepage.

== Changelog ==

= 1.2.8 =
* better support for `define('DISABLE_NAG_NOTICES', true);`

= 1.2.7 =
* tested with WP 4.9
* updated plugin meta
* updated recommended plugins
* partial support for `define('DISABLE_NAG_NOTICES', true);`

= 1.2.6 =
* optimized plugin code
* updated recommended plugins
* added rating request

= 1.2.5 =
* updated recommended plugins

= 1.2.4 =
* added recommended plugins

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
* Database Charset/Collate layout tweaked (was backwards).
* Readme.txt revised for wordpress.org and minor changes to "short" description, etc in `server-status.php`
* Default layout changed to be much more compressed to save space.
* Alternative layout options added, which must be defined within `wp-config`

= 1.1.0 =
* re-written with PHP 7 and Transients API

= 1.0.0 =
* initial release (private)
