<?php

namespace Preamp;

class Preamp {

	private $languages;
	private $custom_post_types;
	private $meta_boxes;

	private static $instance = null;

	private $config_directories = array();

	private function __construct($config_directory) {
		$this->config_directories[] = $config_directory;
		
	}

	public static function init($config_directory = '') {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Preamp ) ) {
			self::$instance = new Preamp($config_directory);
		}
		return self::$instance;
	}

	public function load_config() {
		print_r($this->config_directories);
	}
}