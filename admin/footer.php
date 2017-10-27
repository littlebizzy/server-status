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
		require_once(dirname(dirname(__FILE__)).'/core/status.php');

		// Get memory array
		$memory = SVRSTS_Core_Status::memory_usage(false);

		// Concatenate and return
		return $text.' | Memory Usage : '.$memory['usage'].'M&nbsp;('.$memory['percent'].'%) of '.$memory['limit'].' | '.SVRSTS_Core_Status::server_ip_address().' ('.gethostname().')';
	}



}
