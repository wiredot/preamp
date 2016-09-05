<?php

namespace Preamp;

class Config {

	private $directories = array();
	
	private $config = array();

	public function __construct() {
		$this->get_directories();
		$this->load_directories();
	}

	public function get_directories() {
		$active_plugins = get_option('active_plugins');
		print_r($active_plugins);
		if ($active_plugins) {
			foreach ($active_plugins AS $plugin) {
				$this->directories[] = WP_PLUGIN_DIR.'/'.plugin_dir_path($plugin).'config/';
			}
		}
		
		$this->directories[] = get_template_directory().'/config/';
	}

	public function load_directories() {
		print_r($this->directories);
		foreach ($this->directories as $config_directory) {
			$config_part = self::load_config_directory($config_directory);
			if ( is_array($config_part) ) {
				$this->config = array_replace_recursive( $this->config, $config_part );
			}
		}

		print_r($this->config);
	}

	private static function load_config_directory($directory) {
		$config = array();

		// get all files from config folder
		if (file_exists($directory) && $handle = opendir($directory)) {

			// for each file with .config.php extension
			while (false !== ($filename = readdir($handle))) {
				
				if (preg_match('/.config.php$/', $filename)) {
					$config_part = self::load_config_file($directory.$filename);
					if ( is_array($config_part) ) {
						$config = array_replace_recursive( $config, $config_part );
					}
				}
			}
			closedir($handle);
		}

		return $config;
	}

	private static function load_config_file($filename) {
		if (file_exists($filename)) {
			
			// get config array from file
			require_once $filename;
			if (isset($preamp['config'])) {
				return $preamp['config'];
			}
		}

		return null;
	}
}