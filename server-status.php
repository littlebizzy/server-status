<?php
/*
Plugin Name: Server Status
Plugin URI: https://www.littlebizzy.com/plugins/server-status
Description: Useful statistics about the server OS, CPU, RAM, load average, memory usage, IP address, hostname, timezone, disk space, PHP, MySQL, caches, etc.
Version: 1.2.8
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Prefix: SVRSTS
*/

// Admin Notices module
require_once dirname(__FILE__).'/admin-notices.php';
SVRSTS_Admin_Notices::instance(__FILE__);


/**
 * Plugin code
 */

// Avoid direct calls
defined('ABSPATH') or die('No soup for you!');

// Admin check
if (!is_admin())
	return;

// Plugin constants
define('SVRSTS_FILE', __FILE__);
define('SVRSTS_PATH', dirname(SVRSTS_FILE));
define('SVRSTS_VERSION', '1.2.8');
define('SVRSTS_REFRESH', '30'); // seconds


/* Dashboard */

// Dashboard hook
add_action('wp_dashboard_setup', 'svrsts_dashboard_setup');

// Dashboard loader
function svrsts_dashboard_setup() {
	require_once(SVRSTS_PATH.'/admin/dashboard.php');
	SVRSTS_Admin_Dashboard::add_widget();
}


/* Admin Footer */

// Footer hook
add_filter('admin_footer_text', 'svrsts_admin_footer_text');

// Footer loader
function svrsts_admin_footer_text($text) {
	require_once(SVRSTS_PATH.'/admin/footer.php');
	return SVRSTS_Admin_Footer::add_text($text);
}
