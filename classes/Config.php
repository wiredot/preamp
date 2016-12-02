<?php

namespace Wiredot\Preamp;

class Config {

	private $directories = array();
	
	private $config = array();

	public function __construct() {
		$this->get_directories();
		$this->load_directories();

		// print_r($this->config);
	}

	public function get_directories() {
		$active_plugins = get_option('active_plugins');

		if ($active_plugins) {
			foreach ($active_plugins AS $plugin) {
				$this->directories[] = array(
					'directory' => WP_PLUGIN_DIR.'/'.plugin_dir_path($plugin),
					'url' => dirname( plugins_url( $plugin )) 
				);
			}
		}
		
		$this->directories[] = array(
			'directory' => WP_PLUGIN_DIR.'/'.get_template_directory(),
			'url' => dirname( get_stylesheet_uri()) 
		);
	}

	public function load_directories() {
		// print_r($this->directories);
		foreach ($this->directories as $config_directory) {
			$config_part = self::load_config_directory($config_directory['directory'].'config/', $config_directory['url']);
			if ( is_array($config_part) ) {
				$this->config = array_replace_recursive( $this->config, $config_part );
			}
		}

		// print_r($this->config);
	}

	private static function load_config_directory($directory, $url) {
		$config = array();

		// get all files from config folder
		if (file_exists($directory) && $handle = opendir($directory)) {

			// for each file with .config.php extension
			while (false !== ($filename = readdir($handle))) {
				if ($filename == "." || $filename == "..") {
					continue;
				}
				
				$config_part = array();

				if (is_dir($directory.$filename)) {
					$config_part = self::load_config_directory($directory.$filename.'/', $url);
				} else if (preg_match('/.config.php$/', $filename)) {
					$config_part = self::load_config_file($directory.$filename);
				}

				if (isset($config_part['css'])) {
					foreach ($config_part['css'] as $key => $config_css) {
						$config_part['css'][$key]['url'] = $url;
					}
				}

				if (isset($config_part['js'])) {
					foreach ($config_part['js'] as $key => $config_js) {
						$config_part['js'][$key]['url'] = $url;
					}
				}

				if (isset($config_part['custom_post_type'])) {
					foreach ($config_part['custom_post_type'] as $key => $custom_post_type) {
						if (isset($custom_post_type['custom_menu_icon'])) {
							$config_part['custom_post_type'][$key]['menu_icon'] = $url.'/'.$custom_post_type['custom_menu_icon'];
							
						}
					}
				}
				
				if ( count($config_part) ) {
					$config = array_replace_recursive( $config, $config_part );
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

	public function get_config() {
		return $this->config;
	}
}