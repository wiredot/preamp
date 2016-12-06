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

	private static $config;

	private static $instance = null;

	private function __construct($path, $url) {
		define('PREAMP_URL', $url);
		
		$this->path = $path;
		$this->url = $url;
		add_action( 'plugins_loaded', array( $this, 'setup' ) );
		//$this->setup();
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