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

	// public function get_directories() {
	// 	$directories = array();

	// 	$active_plugins = get_option( 'active_plugins' );

	// 	if ( $active_plugins ) {
	// 		foreach ( $active_plugins as $plugin ) {
	// 			$directories[] = array(
	// 				'directory' => WP_PLUGIN_DIR . '/' . plugin_dir_path( $plugin ),
	// 				'url' => dirname( plugins_url( $plugin ) ),
	// 			);
	// 		}
	// 	}

	// 	$directories[] = array(
	// 		'directory' => get_template_directory() . '/',
	// 		'url' => dirname( get_stylesheet_uri() ),
	// 	);

	// 	return $directories;
	// }

	public function load_directories() {
		$config = array();
		foreach ( self::$directories as $config_directory ) {
			$config_part = self::load_config_directory( $config_directory . 'config/', '' );
			if ( is_array( $config_part ) ) {
				$config = array_replace_recursive( $config, $config_part );
			}
		}

		return apply_filters('preamp_wpep_config', $config);
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

	public static function set_directory( $dir ) {
		self::$directories[] = $dir;		
	}
}

// <?php

// namespace Wiredot\Preamp;

// use Wiredot\Preamp\Core;

// class Config {

// 	private $dir;

// 	private static $config = array();

// 	public function __construct( $dir ) {
// 		$this->dir = $dir;

// 		self::$config = $this->load_config();
// 		// print_r(self::$config);
// 	}

// 	// public function get_directories() {
// 	// 	$directories = array();

// 	// 	$active_plugins = get_option( 'active_plugins' );

// 	// 	if ( $active_plugins ) {
// 	// 		foreach ( $active_plugins as $plugin ) {
// 	// 			$directories[] = array(
// 	// 				'directory' => WP_PLUGIN_DIR . '/' . plugin_dir_path( $plugin ),
// 	// 				'url' => dirname( plugins_url( $plugin ) ),
// 	// 			);
// 	// 		}
// 	// 	}

// 	// 	$directories[] = array(
// 	// 		'directory' => get_template_directory() . '/',
// 	// 		'url' => dirname( get_stylesheet_uri() ),
// 	// 	);

// 	// 	return $directories;
// 	// }

// 	// public function load_directories() {
// 	// 	$config = array();
// 	// 	foreach ( $this->directories as $config_directory ) {
// 	// 		$config_part = self::load_config_directory( $config_directory['directory'] . 'config/', $config_directory['url'] );
// 	// 		if ( is_array( $config_part ) ) {
// 	// 			$config = array_replace_recursive( $config, $config_part );
// 	// 		}
// 	// 	}

// 	// 	$this->config = $config;
// 	// }

// 	private function load_config() {
// 		$config = $this->load_config_directory( $this->dir . 'config/' );
// echo 'preamp_'.CORE::$ns.'_config';
// 		return apply_filters('preamp_'.CORE::$ns.'_config', $config);
// 	}

// 	public function load_config_directory( $dir ) {
// 		$full_config = array();
// 		if ( file_exists( $dir ) && $handle = opendir( $dir ) ) {
// 			while ( false !== ($filename = readdir( $handle )) ) {
// 				if ( '.' == $filename || '..' == $filename ) {
// 					continue;
// 				}

// 				$config_part = array();

// 				if ( is_dir( $dir . $filename ) ) {
// 					$config_part = $this->load_config_directory( $dir . $filename . '/' );
// 				} else if ( preg_match( '/.config.php$/', $filename ) ) {
// 					$config_part = $this->load_config_file( $dir . $filename );
// 				}

// 				if ( count( $config_part ) ) {
// 					$full_config = array_replace_recursive( $full_config, $config_part );
// 				}
// 			}
// 			closedir( $handle );
// 		}

// 		return $full_config;
// 	}

// 	private static function load_config_directorys( $directory, $url ) {
// 		$full_config = array();

// 		// get all files from config folder
// 		if ( file_exists( $directory ) && $handle = opendir( $directory ) ) {

// 			// for each file with .config.php extension
// 			while ( false !== ($filename = readdir( $handle )) ) {
// 				if ( '.' == $filename || '..' == $filename ) {
// 					continue;
// 				}

// 				$config_part = array();

// 				if ( is_dir( $directory . $filename ) ) {
// 					$config_part = self::load_config_directory( $directory . $filename . '/', $url );
// 				} else if ( preg_match( '/.config.php$/', $filename ) ) {
// 					$config_part = self::load_config_file( $directory . $filename );
// 				}

// 				if ( isset( $config_part['css'] ) ) {
// 					foreach ( $config_part['css'] as $key => $config_css ) {
// 						$config_part['css'][ $key ]['url'] = $url;
// 					}
// 				}

// 				if ( isset( $config_part['js'] ) ) {
// 					foreach ( $config_part['js'] as $key => $config_js ) {
// 						$config_part['js'][ $key ]['url'] = $url;
// 					}
// 				}

// 				if ( isset( $config_part['custom_post_type'] ) ) {
// 					foreach ( $config_part['custom_post_type'] as $key => $custom_post_type ) {
// 						if ( isset( $custom_post_type['custom_menu_icon'] ) ) {
// 							$config_part['custom_post_type'][ $key ]['menu_icon'] = $url . '/' . $custom_post_type['custom_menu_icon'];

// 						}
// 					}
// 				}

// 				if ( count( $config_part ) ) {
// 					$config = array_replace_recursive( $config, $config_part );
// 				}
// 			}
// 			closedir( $handle );
// 		}

// 		return $config;
// 	}

// 	private function load_config_file( $filename ) {
// 		if ( file_exists( $filename ) ) {

// 			// get config array from file
// 			require_once $filename;
// 			if ( isset( $config ) ) {
// 				return $config;
// 			}
// 		}

// 		return null;
// 	}

	
// }

