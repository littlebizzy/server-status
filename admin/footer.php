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

		// Concatenate and return
		return $text.' | Memory Usage : '.esc_html($memory['usage_ini']).' ('.esc_html($memory['percent']).'%) of '.esc_html($memory['limit_ini']).' | '.esc_html(SVRSTS_Core_Status::server_ip_address()).' ('.esc_html(gethostname()).')';
	}



}
