<?php

/**
 * Server Status - Admin - Dashboard class
 *
 * @package Server Status
 * @subpackage Server Status Admin Dashboard
 */
class SVRSTS_Admin_Dashboard {



	/**
	 * Add widget hook action
	 */
	public static function add_widget() {
		wp_add_dashboard_widget('server_status_dashboard', 'Server Status', array (__CLASS__, 'widget'));
	}



	/**
	 * Widget body
	 */
	public static function widget() {

		// Load status class
		require_once(SVRSTS_PATH.'/core/status.php');

		// Globals
		global $_wp_using_ext_object_cache;

		// Disk space cached
		$disk_space = SVRSTS_Core_Status::disk_space();

		// Opcache data
		$opcache_size = ini_get('opcache.memory_consumption');
		$max_accelerated_files = ini_get('opcache.max_accelerated_files');
		$revalidate_freq = ini_get('opcache.revalidate_freq');

		// Get memory array
		$memory = SVRSTS_Core_Status::memory_usage();

		// Setup items
		$items = apply_filters('srvsts_items', array(
			'OS Type' 								=> esc_html(php_uname()).' ('.esc_html(PHP_INT_SIZE * 8).' bit)',
			'Server Software' 						=> esc_html($_SERVER['SERVER_SOFTWARE']),
			'CPU Cores'								=> esc_html(SVRSTS_Core_Status::cpu_cores()).' Cores',
			'Load Average'							=> esc_html(SVRSTS_Core_Status::cpu_load_average()),
			'IP (Hostname)' 						=> esc_html(SVRSTS_Core_Status::server_ip_address()).' ('.esc_html(gethostname()).')',
			'Disk Space'							=> esc_html($disk_space['used']).' ('.esc_html($disk_space['uper']).'%) used of '.$disk_space['total'].' total',
			'Database Size'							=> esc_html(SVRSTS_Core_Status::database_size()),
			'Database Name (Hostname)' 				=> esc_html(DB_NAME).' ('.esc_html(DB_HOST).')',
			'MySQL Version'							=> esc_html(SVRSTS_Core_Status::mysql_version()),
			'Database Charset (Collate)' 			=> esc_html(defined('DB_CHARSET')? DB_CHARSET : '').' ('. esc_html((!defined('DB_COLLATE') || '' === DB_COLLATE)? 'n/a' : DB_COLLATE).')',
			'Document Root' 						=> esc_html($_SERVER['DOCUMENT_ROOT']),
			'Theme Path'							=> esc_html(TEMPLATEPATH),
			'WP Locale (Charset)'					=> esc_html(get_bloginfo('language')).' ('.esc_html(get_bloginfo('charset')).')',
			'Server Timezone (WP)'					=> esc_html(date_default_timezone_get()). ' ('.esc_html(SVRSTS_Core_Status::wp_timezone()).')',
			'PHP Version'							=> esc_html(PHP_VERSION),
			'PHP Extensions'						=> esc_html(implode(', ', get_loaded_extensions())),
			'Max Upload, Post Size (Input Vars)' 	=> esc_html(ini_get('upload_max_filesize')).', '.esc_html(ini_get('post_max_size')).' ('.esc_html(ini_get('max_input_vars')).')',
			'Memory Usage'							=> esc_html($memory['usage_ini']).' ('.esc_html($memory['percent']).'%) of '.esc_html($memory['limit_ini']).' (actual limit)',
			'WP Memory Limit'						=> esc_html(WP_MEMORY_LIMIT).' (defined limit)',
			'WP Admin Memory Limit' 				=> esc_html(WP_MAX_MEMORY_LIMIT).' (defined limit)',
			'WP Debug'								=> ((defined('WP_DEBUG') && WP_DEBUG)? 'Enabled' : 'Disabled').' ('.((defined('WP_DEBUG_LOG') && WP_DEBUG_LOG)? 'logging' : 'no logging').', '.((defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY)? 'display' : 'no display').')',
			'Opcache Size (Max Files, Revalidate)' 	=> (empty($opcache_size)? 0 : esc_html($opcache_size).'M').' ('.(empty($max_accelerated_files)? 0 : esc_html($max_accelerated_files)).', '.(empty($revalidate_freq)? 'n/a' : esc_html($revalidate_freq)).' secs)',
			'Page Cache (Plugin)'					=> (defined('WP_CACHE') && WP_CACHE)? 'Enabled' : 'Disabled',
			'Object Cache'							=> $_wp_using_ext_object_cache? 'Enabled' : 'Disabled',
		));

		// Save any transient data
		SVRSTS_Core_Status::save_data();

		// Check display
		$display = defined('SVRSTS_DISPLAY')? SVRSTS_DISPLAY : false;

		// Table display mode
		if ('grid' == $display) {

			// Init table
			$col = 0;
			$table = '';

			// Enum items
			foreach ($items as $label => $value) {

				// Extended row
				$length = strlen($value);
				if ($length >= 50 || ($length >= 25 && false !== strpos($value, '/'))) {

					// Previous
					if (1 == $col)
						$table .= '<td>&nbsp;</td></tr>';

					// Two columns row
					$table .= '<tr><td colspan="2"><strong>'.$label.'</strong><br /><span>'.$value.'</span></td></tr>';

					// Restart
					$col = 0;

				// Colum
				} else {

					// Start
					$col++;
					if (1 == $col)
						$table .= '<tr>';

					// Content
					$table .= '<td><strong>'.$label.'</strong><br /><span>'.$value.'</span></td>';

					// End
					if (2 == $col) {
						$table .= '</tr>';
						$col = 0;
					}
				}
			}

			// End table
			echo '<table class="widefat fixed striped">'.$table.'</table>';

		// Widefat lines
		} elseif ('widefat' == $display) {

			$table = '';
			foreach ($items as $label => $value)
				$table .= '<tr><td><strong>'.$label.'</strong>: <span>'.$value.'</span></td></tr>';
			echo '<table class="widefat striped">'.$table.'</table>';

		// Default mode
		} else {

			$table = '';
			foreach ($items as $label => $value)
				$table .= '<tr><td><strong>'.$label.'</strong>: <span>'.$value.'</span></td></tr>';
			echo '<table class="striped">'.$table.'</table>';
		}
	}



}
