<?php

namespace Wiredot\Preamp\Settings;

use Wiredot\Preamp\Twig;
use Wiredot\Preamp\Core;
use Wiredot\Preamp\Settings\Tab_Factory;

class Settings {

	private $parent_slug;
	private $page_title;
	private $menu_title;
	private $capability;
	private $menu_slug;
	private $options_prefix;

	function __construct( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $options_prefix ) {
		$this->parent_slug = $parent_slug;
		$this->page_title = $page_title;
		$this->menu_title = $menu_title;
		$this->capability = $capability;
		$this->menu_slug = $menu_slug;
		$this->options_prefix = $options_prefix;

		add_action( 'wp_ajax_preamp_settings_save_' . $this->menu_slug, array( $this, 'save_settings' ) );
	}

	public function add_settings_page() {
		add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
	}

	public function add_submenu_page() {
		add_submenu_page(
			$this->parent_slug,
			$this->page_title,
			$this->menu_title,
			$this->capability,
			$this->menu_slug,
			array( $this, 'settings_page' )
		);
	}

	public function settings_page() {
		$Twig = new Twig;
		echo $Twig->twig->render(
			'settings/settings.twig',
			array(
				'page_title' => $this->page_title,
				'tabs' => $this->get_tabs(),
				'menu_slug' => $this->menu_slug,
			)
		);
	}

	public function get_tabs() {
		$tabs = array();

		$config = Core::get_config( 'settings' );
		$Tab_Factory = new Tab_Factory( $config['tab'], $this->options_prefix );
		return $Tab_Factory->get_tab( $this->menu_slug );
	}

	public function save_settings() {
		header( 'content-type:application/json' );

		$tab = $_POST['preamp-settings-tab'];

		$config = Core::get_config( 'settings' );
		$Tab_Factory = new Tab_Factory( $config['tab'], $this->options_prefix );
		$Tab_Factory->save_tab( $tab );

		$response = array(
			'success' => 1,
			'message' => __( 'Good job!', 'preamp' ),
		);
		echo json_encode( $response );
		exit;
	}
}
