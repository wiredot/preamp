<?php

namespace Wiredot\Preamp;

class Config {

	private static $instance = null;

	private static $directories = array();

	private static $config = array();

	private function __construct() {
		self::$config = $this->load_directories();
	}

	public static function setup() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Config ) ) {
			self::$instance = new Config();
		}

		return self::$instance;
	}

	public function load_directories() {
		$config = $this->load_dir( array(), get_template_directory() . '/config/', get_template_directory_uri() );
		foreach ( self::$directories as $config_directory ) {
			$config = $this->load_dir( $config, $config_directory['dir'] . 'config/', $config_directory['url'] );
		}
// echo get_locale();
		// print_r( $config );

		return apply_filters( 'preamp_wpep_config', $config );
	}

	public function load_dir( $config, $dir, $url ) {
		$config_part = self::load_config_directory( $dir, $url );
		if ( is_array( $config_part ) ) {
			$config = array_replace_recursive( $config, $config_part );
		}

		return $config;
	}

	private static function load_config_directory( $directory, $url ) {
		$config = array();

		// get all files from config folder
		if ( file_exists( $directory ) && $handle = opendir( $directory ) ) {

			// for each file with .config.php extension
			while ( false !== ($filename = readdir( $handle )) ) {
				if ( '.' == $filename || '..' == $filename ) {
					continue;
				}

				$config_part = array();

				if ( is_dir( $directory . $filename ) ) {
					$config_part = self::load_config_directory( $directory . $filename . '/', $url );
				} else if ( preg_match( '/.config.php$/', $filename ) ) {
					$config_part = self::load_config_file( $directory . $filename );
				}

				if ( isset( $config_part['css'] ) ) {
					foreach ( $config_part['css'] as $key => $config_css ) {
						$config_part['css'][ $key ]['url'] = $url;
					}
				}

				if ( isset( $config_part['js'] ) ) {
					foreach ( $config_part['js'] as $key => $config_js ) {
						$config_part['js'][ $key ]['url'] = $url;
					}
				}

				if ( isset( $config_part['custom_post_type'] ) ) {
					foreach ( $config_part['custom_post_type'] as $key => $custom_post_type ) {
						if ( isset( $custom_post_type['custom_menu_icon'] ) ) {
							$config_part['custom_post_type'][ $key ]['menu_icon'] = $url . '/' . $custom_post_type['custom_menu_icon'];

						}
					}
				}

				if ( count( $config_part ) ) {
					$config = array_replace_recursive( $config, $config_part );
				}
			}
			closedir( $handle );
		}

		return $config;
	}

	private static function load_config_file( $filename ) {
		if ( file_exists( $filename ) ) {

			// get config array from file
			require_once $filename;
			if ( isset( $config ) ) {
				return $config;
			}
		}

		return null;
	}

	public static function get_config( $key = null ) {
		if ( ! $key ) {
			return self::$config;
		}

		if ( isset( self::$config[ $key ] ) ) {
			return self::$config[ $key ];
		}

		return null;
	}

	public static function add_directory( $url, $dir ) {
		self::$directories[] = array(
			'url' => $url,
			'dir' => $dir,
		);
	}
}
