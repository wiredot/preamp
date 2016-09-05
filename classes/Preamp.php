<?php

namespace Preamp;

use Preamp\Custom_Post_Types\Custom_Post_Type_Factory;
use Preamp\Meta_Boxes\Meta_Box_Factory;

class Preamp {

	private $languages;
	private $custom_post_types;
	private $meta_boxes;

	private static $instance = null;

	private $config_directories = array();

	public $config;

	private function __construct() {
		$this->setup();
	}

	public static function run() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Preamp ) ) {
			self::$instance = new Preamp();
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

		// $this->config = Config::load_config($this->config_directories);
		// print_r($this->config_directories);
		// print_r($this->config);
		// if (isset($this->config['custom_post_type'])) {
		// 	foreach ($this->config['custom_post_type'] as $key => $cpt) {
		// 		new Custom_Post_Type($key);
		// 	}
		// }

		// print_r($this->config);
		//exit;
	}

	public function get_config() {
		return $this->config;
	}

	public function load_config() {
		//print_r($this->config_directories);
	}

	public static function get_instance() {
		echo 'asd';
		if (self::$instance) {
			echo 'aa';
		}
		return self::$instance;
	}
}