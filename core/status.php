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

		// Memory consumption
		$usage = function_exists('memory_get_usage')? memory_get_usage(true) : 0;

		// PHP memory limit
		$limit_ini = ini_get('memory_limit');
		$limit = self::ini2bytes(ini_get('memory_limit'));

		// Usage percent
		$percent = (empty($usage) || empty($limit))? 0 : round(($usage * 100) / $limit);

		// Usage units without decimals
		$usage = self::format_bytes($usage, 0);
		$usage_ini = self::format_bytes_2_ini($usage);

		// Set array
		$memory = array(
			'usage' 	=> $usage,
			'usage_ini' => $usage_ini,
			'limit' 	=> self::format_bytes($limit),
			'limit_ini' => $limit_ini,
			'percent' 	=> $percent,
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

			// Prepare data
			$free  = disk_free_space('/');
			$total = disk_total_space('/');
			$used  = $total - $free;
			$uper  = empty($used)? 0 : round(($used * 100) / $total);

			// Current values
			$disk_space = array(
				'free'  => self::format_bytes($free),
				'used'	=> self::format_bytes($used),
				'uper'  => $uper,
				'total' => self::format_bytes($total),
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
			}

			// Format total
			$database_size = self::format_bytes($database_size);

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



	/**
	 * Detect number of CPU cores
	 */
	public static function cpu_cores() {

		// Check transient
		$cpu_cores = self::get_data('cpu_cores');
		if (false === $cpu_cores) {

			// Default
			$cpu_cores = 'n/a';

			// Execute command
			$uname = ''.@shell_exec('uname');
			$uname = strtolower(trim($uname));

			// Generic linux
			if ('linux' == $uname) {
				$cmd = 'cat /proc/cpuinfo | grep processor | wc -l';

			// For FreeBSD
			} elseif ('freebsd' == $uname) {
				$cmd = "sysctl -a | grep 'hw.ncpu' | cut -d ':' -f2";
			}

			// Check command
			if (isset($cmd)) {
				$num = ''.@shell_exec($cmd);
				$num = (int) trim($num);
				if ($num > 0)
					$cpu_cores = $num;
			}

			// Add data
			self::add_data('cpu_cores', $cpu_cores);
		}

		// Done
		return $cpu_cores;
	}



	/**
	 * Retrieve the current server load average
	 */
	public static function cpu_load_average() {

		// Defaults
		$load = 'n/a';

		// Check via PHP function
		$avg = function_exists('sys_getloadavg')? sys_getloadavg() : false;
		if (!empty($avg) && is_array($avg) && 3 == count($avg))
			$load = implode(', ', $avg);

		// Done
		return $load;
	}



	/**
	 * Check WP Timezone or offset
	 */
	public static function wp_timezone() {

		// Direct value
		$timezone = get_option('timezone_string');

		// Create a UTC+- zone if no timezone string exists
		if (empty($timezone)) {

			// Current offset
			$current_offset = get_option('gmt_offset');

			// No offset
			if (0 == $current_offset) {
				$timezone = 'UTC+0';

			// Negative offset
			} elseif ($current_offset < 0) {
				$timezone = 'UTC'.$current_offset;

			// Plus offset
			} else {
				$timezone = 'UTC+'.$current_offset;
			}

			// Normalize
			$timezone = str_replace(array('.25','.5','.75'), array(':15',':30',':45'), $timezone);
		}

		// Done
		return $timezone;
	}



	// Util
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Cast PHP ini values
	 */
	public static function ini2bytes($value) {

		// Check values
		if (empty($value))
			return 0;

		// Extract unit
		$unit = strtoupper(substr($value, -1));
		$value = (int) substr($value, 0, -1);

		// Check KB
		if ('K' == $unit) {
			$value = $value * 1024;

		// Check MB
		} elseif ('M' == $unit) {
			$value = $value * 1048576;

		// Check GB
		} elseif ('G' == $unit) {
			$value = $value * 1073741824;
		}

		// Done
		return $value;
	}



	/**
	 * Cast formatted size to ini units
	 */
	public static function format_bytes_2_ini($value) {
		return str_replace(array(' KB',' MB',' GB', ' TB'), array('K', 'M', 'G', 'T'), $value);
	}



	/**
	 * A wrapper to format bytes for ini_get values
	 */
	public static function format_bytes_ini_val($value) {
		return self::format_bytes(self::ini2bytes($value));
	}



	/**
	 * A wrapper to format bytes and ini_get method
	 */
	public static function format_bytes_ini_get($key) {
		return self::format_bytes(self::ini2bytes(ini_get($key)));
	}



	/**
	 * Format size from bytes to KB, MB, GB or TB
	 */
	public static function format_bytes($bytes, $precision = 2, $number_format = true) {

		// Supported units
		$units = array('B', 'KB', 'MB', 'GB', 'TB');

		// Prepate values
		$bytes = max($bytes, 0);
		$pow = floor(($bytes? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		// Uncomment one of the following alternatives
		$bytes /= pow(1024, $pow);
		if (0 == $pow) {
			$pow = 1;
			if ($bytes > 0)
				$bytes /= 1024;
		}

		// Round and format
		$value = round($bytes, $precision);
		if ($number_format && function_exists('number_format_i18n'))
			$value = number_format_i18n($value, $precision);

		// Done
		return $value.' '.$units[$pow];
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
