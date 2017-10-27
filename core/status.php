<?php

/**
 * Server Status - Core - Status class
 *
 * @package Server Status
 * @subpackage Server Status Core Status
 */
class SVRSTS_Core_Status {



	// Status methods
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Return an array with the current memory usage info
	 */
	public static function memory_usage($cached = true) {

		// Cached data
		static $memory;
		if (isset($memory) && $cached)
			return $memory;

		// Current memory values
		$usage = function_exists('memory_get_usage')? round(memory_get_usage(true) / 1024 / 1024, 2) : 0;
		$limit = @ini_get('memory_limit');

		// Set array
		$memory = array(
			'usage' => $usage,
			'limit' => $limit,
			'percent' => (empty($usage) || empty($limit))? 0 : round($usage / $limit * 100, 0),
		);

		// Done
		return $memory;
	}



	/**
	 * Return stored or current values of disk space
	 */
	public static function disk_space() {

		// Check item data
		$disk_space = self::get_data('disk_space');
		if (false === $disk_space) {

			// Current values
			$disk_space = array(
				'free'  => round(disk_free_space('/') / 1024 / 1024 / 1024),
				'total' => round(disk_total_space('/') / 1024 / 1024 / 1024),
			);

			// Add data
			self::add_data('disk_space', $disk_space);
		}

		// Done
		return $disk_space;
	}



	/**
	 * Return stored or current database size
	 */
	public static function database_size() {

		// Check transient
		$database_size = self::get_data('database_size');
		if (false === $database_size) {

			// Globals
			global $wpdb;

			// Initialize
			$database_size = 0;

			// Retrieve tables
			$tables = $wpdb->get_results("SHOW TABLE STATUS");
			if (!empty($tables) && is_array($tables)) {

				// Enum tables
				foreach ($tables as $table) {
					$data_length  = empty($table->Data_length)? 0 : (int) $table->Data_length;
					$index_length = empty($table->Index_length)? 0 : (int) $table->Index_length;
					$database_size += $data_length + $index_length;
				}

				// Check result
				if ($database_size > 0)
					$database_size = number_format($database_size / 1048576, 2);
			}

			// Add data
			self::add_data('database_size', $database_size);
		}

		// Done
		return $database_size;
	}



	/**
	 * Retrieves the MySQL version
	 */
	public static function mysql_version() {

		// Check transient
		$mysql_version = self::get_data('mysql_version');
		if (false === $mysql_version) {

			// Globals
			global $wpdb;

			// Retrieve data
			$mysql_version = $wpdb->get_var('SELECT VERSION() AS version');

			// Add data
			self::add_data('mysql_version', $mysql_version);
		}

		// Done
		return $mysql_version;
	}



	/**
	 * Return the current server IP address
	 */
	public static function server_ip_address() {
		return empty($_SERVER['SERVER_ADDR'])? (empty($_SERVER['LOCAL_ADDR'])? '' : $_SERVER['LOCAL_ADDR']) : $_SERVER['SERVER_ADDR'];
	}



	// Transient data
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Plugin data transient retrieval
	 */
	public static function get_data($key = null) {

		// Local cache
		static $data;
		if (isset($data)) {

			// Raw data
			if (!isset($key))
				return $data;

			// Return value
			return isset($data[$key])? $data[$key] : false;
		}

		// Initialize
		$data = array();

		// Retrieve data
		$transient = get_transient('srvsts_data');
		if (false === $transient || empty($transient) || !is_array($transient))
			return false;

		// Copy data
		$data = $transient;

		// Done
		return isset($data[$key])? $data[$key] : false;
	}



	/**
	 * Add transient item data
	 */
	public static function add_data($key = null, $value = null) {

		// Local cache
		static $data;

		// Check request
		if (!isset($key))
			return isset($data)? $data : false;

		// Check data
		if (!isset($data))
			$data = self::get_data();

		// Save value
		$data[$key] = $value;
	}



	/**
	 * Saves transient in database if any change or new item
	 */
	public static function save_data() {
		if (false !== ($transient = self::add_data()))
			set_transient('srvsts_data', $transient, SVRSTS_REFRESH);
	}



}
