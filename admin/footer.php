<?php

/**
 * Server Status - Admin - Footer class
 *
 * @package Server Status
 * @subpackage Server Status Admin Footer
 */
class SVRSTS_Admin_Footer {



	/**
	 * Add text to the footer filter
	 */
	public static function add_text($text) {

		// Load status class
		require_once(SVRSTS_PATH.'/core/status.php');

		// Get memory array
		$memory = SVRSTS_Core_Status::memory_usage(false);
		
		// Disk space cached
		$disk_space = SVRSTS_Core_Status::disk_space();

		// Concatenate and return
		return $text.'Server: '.esc_html(SVRSTS_Core_Status::server_ip_address()).' ('.esc_html(gethostname()).') - CPU Load: '.esc_html(SVRSTS_Core_Status::cpu_load_average()).' (1m, 5m, 15m average) - Memory: '.esc_html($memory['usage_ini']).' ('.esc_html($memory['percent']).'%) of '.esc_html($memory['limit_ini']).' allocated - Disk Space: '.esc_html($disk_space['used']).' ('.esc_html($disk_space['uper']).'%) of '.$disk_space['total'].' total';
	}



}
