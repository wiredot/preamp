<?php

namespace Wiredot\Preamp;

use Wiredot\Preamp\Custom_Post_Types\Custom_Post_Type_Factory;
use Wiredot\Preamp\Meta_Boxes\Meta_Box_Factory;
use Wiredot\Preamp\Taxonomies\Taxonomy_Factory;
use Wiredot\Preamp\Sidebars\Sidebar_Factory;
use Wiredot\Preamp\Settings\Settings_Factory;
use Wiredot\Preamp\Nav_Menus\Nav_Menu_Factory;
use Wiredot\Preamp\Css\Css_Factory;
use Wiredot\Preamp\Js\Js_Factory;
use Wiredot\Preamp\Admin\Admin;

class Core {

	private static $instance = null;

	private function __construct( $url ) {
		define( 'PREAMP_URL', $url );
		$this->url = $url;

		add_action( 'plugins_loaded', array( $this, 'setup' ) );
	}

	public static function run( $url, $dir ) {
		Config::add_directory( $url, $dir );
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Core ) ) {
			self::$instance = new Core( $url );
		}

		return self::$instance;
	}

	public function setup() {
		Config::setup();

		if ( is_admin() ) {
			new Admin( Config::get_config() );
		}

		// register all custom post types
		if ( Config::get_config( 'custom_post_type' ) ) {
			new Custom_Post_Type_Factory( Config::get_config( 'custom_post_type' ) );
		}

		// register all custom post types
		if ( Config::get_config( 'language' ) ) {
			Languages::set_languages( Config::get_config( 'language' ) );
		}

		// register all meta boxes
		if ( Config::get_config( 'meta_box' ) ) {
			new Meta_Box_Factory( Config::get_config( 'meta_box' ) );
		}

		// register all taxonomies
		if ( Config::get_config( 'taxonomy' ) ) {
			$taxonomy = new Taxonomy_Factory( Config::get_config( 'taxonomy' ) );
			$taxonomy->register_taxonomies();
		}

		// register all sidebars
		if ( Config::get_config( 'sidebar' ) ) {
			$sidebars = new Sidebar_Factory( Config::get_config( 'sidebar' ) );
			$sidebars->register_sidebars();
		}

		// add settings
		if ( Config::get_config( 'settings' ) ) {
			$settings = new Settings_Factory( Config::get_config( 'settings' ) );
			$settings->add_settings();
		}

		// register nav menus
		if ( Config::get_config( 'nav_menu' ) ) {
			$nav_menus = new Nav_Menu_Factory( Config::get_config( 'nav_menu' ) );
			$nav_menus->register_nav_menus();
		}

		if ( Config::get_config( 'css' ) ) {
			$css = new Css_Factory( Config::get_config( 'css' ) );
			$css->register_css_files();
		}

		if ( Config::get_config( 'js' ) ) {
			$js = new Js_Factory( Config::get_config( 'js' ) );
			$js->register_js_files();
		}
	}
}
