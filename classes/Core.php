<?php

namespace Wiredot\Preamp;

use Wiredot\Preamp\Custom_Post_Types\Custom_Post_Type_Factory;
use Wiredot\Preamp\Meta_Boxes\Meta_Box_Factory;
use Wiredot\Preamp\Taxonomies\Taxonomy_Factory;
use Wiredot\Preamp\Sidebars\Sidebar_Factory;
use Wiredot\Preamp\Nav_Menus\Nav_Menu_Factory;
use Wiredot\Preamp\Css\Css_Factory;
use Wiredot\Preamp\Js\Js_Factory;
use Wiredot\Preamp\Admin\Admin;

class Core {
	private $languages;
	private $custom_post_types;
	private $meta_boxes;

	private static $config;

	private static $instance = null;

	private function __construct( $url ) {
		define( 'PREAMP_URL', $url );

		add_action( 'plugins_loaded', array( $this, 'setup' ) );
	}

	public static function run( $url ) {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Core ) ) {
			self::$instance = new Core( $url );

			$Config = new Config;
			self::$config = $Config->get_config();
		}

		return self::$instance;
	}

	public function setup() {

		if ( is_admin() ) {
			new Admin( self::$config );
		}

		// register all custom post types
		if ( isset( self::$config['custom_post_type'] ) ) {
			new Custom_Post_Type_Factory( self::$config['custom_post_type'] );
		}

		// register all custom post types
		if ( isset( self::$config['language'] ) ) {
			Languages::set_languages( self::$config['language'] );
		}

		// register all meta boxes
		if ( isset( self::$config['meta_box'] ) ) {
			new Meta_Box_Factory( self::$config['meta_box'] );
		}

		// register all taxonomies
		if ( isset( self::$config['taxonomy'] ) ) {
			$taxonomy = new Taxonomy_Factory( self::$config['taxonomy'] );
			$taxonomy->register_taxonomies();
		}

		// register all sidebars
		if ( isset( self::$config['sidebar'] ) ) {
			$sidebars = new Sidebar_Factory( self::$config['sidebar'] );
			$sidebars->register_sidebars();
		}

		// register nav menus
		if ( isset( self::$config['nav_menu'] ) ) {
			$nav_menus = new Nav_Menu_Factory( self::$config['nav_menu'] );
			$nav_menus->register_nav_menus();
		}

		if ( isset( self::$config['css'] ) ) {
			$css = new Css_Factory( self::$config['css'] );
			$css->register_css_files();
		}

		if ( isset( self::$config['js'] ) ) {
			$js = new Js_Factory( self::$config['js'] );
			$js->register_js_files();
		}
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
}
