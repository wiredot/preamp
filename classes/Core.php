<?php

namespace Wiredot\Preamp;

use Wiredot\Preamp\Custom_Post_Types\Custom_Post_Type_Factory;
use Wiredot\Preamp\Meta_Boxes\Meta_Box_Factory;
use Wiredot\Preamp\Css\Css_Factory;
use Wiredot\Preamp\Js\Js_Factory;
use Wiredot\Preamp\Admin\Admin;

class Core {

	private $languages;
	private $custom_post_types;
	private $meta_boxes;

	private $path;
	private $url;

	private static $template_directories = array();
	private static $config_directories = array();
	
	private static $config;

	private static $instance = null;

	private function __construct($path, $url) {
		define('PREAMP_URL', $url);
		
		$this->path = $path;
		$this->url = $url;
		$this->set_directories();
		$this->setup();
	}

	public static function run($path, $url) {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Core ) ) {
			self::$instance = new Core($path, $url);
		}

		return self::$instance;
	}

	public function setup() {
		$Config = new Config;
		self::$config = $Config->get_config();

		if (is_admin()) {
			new Admin(self::$config);
		}
		
		// register all custom post types
		if (isset(self::$config['custom_post_type'])) {
			new Custom_Post_Type_Factory(self::$config['custom_post_type']);
		}

		// register all meta boxes
		if (isset(self::$config['meta_box'])) {
			new Meta_Box_Factory(self::$config['meta_box']);
		}

		if (isset(self::$config['css'])) {
			$css = new Css_Factory(self::$config['css']);
			$css->register_css_files();
		}

		if (isset(self::$config['js'])) {
			$js = new Js_Factory(self::$config['js']);
			$js->register_js_files();
		}
	}

	public function set_directories() {
		if (is_dir(dirname(dirname(__FILE__)).'/templates/')) {
			self::$template_directories[] = dirname(dirname(__FILE__)).'/templates/';
		}

		$active_plugins = get_option('active_plugins');

		if ($active_plugins) {
			foreach ($active_plugins AS $plugin) {
				if (is_dir(WP_PLUGIN_DIR.'/'.plugin_dir_path($plugin).'config/')) {
					self::$config_directories[] = WP_PLUGIN_DIR.'/'.plugin_dir_path($plugin).'config/';
				}

				if (is_dir(WP_PLUGIN_DIR.'/'.plugin_dir_path($plugin).'templates/')) {
					self::$template_directories[] = WP_PLUGIN_DIR.'/'.plugin_dir_path($plugin).'templates/';
				}
			}
		}
		
		if (is_dir(get_template_directory().'/config/')) {
			self::$config_directories[] = get_template_directory().'/config/';
		}
		if (is_dir(get_template_directory().'/templates/')) {
			self::$template_directories[] = get_template_directory().'/templates/';
		}
	}

	public static function get_template_directories() {
		return self::$template_directories;
	}

	public static function get_config($key) {
		if ($key) {
			if (isset(self::$config[$key])) {
				return self::$config[$key];
			} else {
				return null;
			}
		}
		
		return self::$config;
	}
}