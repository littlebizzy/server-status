<?php
/*
Plugin Name: Server Status
Plugin URI: https://www.littlebizzy.com/plugins/server-status
Description: Useful statistics about server, memory usage, OS, software, IP address, hostnames, timezone, disk space, PHP, MySQL, Opcache, Charsets, caching, etc.
Version: 1.2.1
Author: LittleBizzy
Author URI: https://www.littlebizzy.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Copyright 2016 by LittleBizzy

*/


/* Checks */

// Avoid direct calls
defined('ABSPATH') or die('No soup for you!');

// Check admin area
if (!is_admin())
	return;

// This plugin constants
define('SVRSTS_FILE', __FILE__);
define('SVRSTS_PATH', dirname(SVRSTS_FILE));
define('SVRSTS_VERSION', '1.2.1');
define('SVRSTS_REFRESH', '30'); // Seconds


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
