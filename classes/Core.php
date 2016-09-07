<?php

namespace Wiredot\Preamp;

use Wiredot\Preamp\Custom_Post_Types\Custom_Post_Type_Factory;
use Wiredot\Preamp\Meta_Boxes\Meta_Box_Factory;

class Core {

	private $languages;
	private $custom_post_types;
	private $meta_boxes;

	private static $template_directories = array();
	private static $config_directories = array();
	
	public $config;

	private static $instance = null;

	private function __construct() {
		$this->set_directories();
		$this->setup();
	}

	public static function run() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Core ) ) {
			self::$instance = new Core();
		}
		return self::$instance;
	}

	public function setup() {
		$Config = new Config;
		$this->config = $Config->get_config();

		// register all custom post types
		new Custom_Post_Type_Factory($this->config['custom_post_type']);

		// register all meta boxes
		new Meta_Box_Factory($this->config['meta_box']);
	}

	public function set_directories() {
		self::$template_directories[] = dirname(dirname(__FILE__)).'/templates/';

		$active_plugins = get_option('active_plugins');
		if ($active_plugins) {
			foreach ($active_plugins AS $plugin) {
				self::$config_directories[] = WP_PLUGIN_DIR.'/'.plugin_dir_path($plugin).'config/';
				self::$template_directories[] = WP_PLUGIN_DIR.'/'.plugin_dir_path($plugin).'templates/';
			}
		}
		
		self::$config_directories[] = get_template_directory().'/config/';
		self::$template_directories[] = get_template_directory().'/templates/';

	}

	public static function get_template_directories() {
		return self::$template_directories;
	}
}