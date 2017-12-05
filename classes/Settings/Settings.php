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
	private $tabs;

	function __construct( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $tabs ) {
		$this->parent_slug = $parent_slug;
		$this->page_title = $page_title;
		$this->menu_title = $menu_title;
		$this->capability = $capability;
		$this->menu_slug = $menu_slug;
		$this->tabs = $tabs;
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
			)
		);
	}

	public function get_tabs() {
		if ( ! is_array( $this->tabs ) ) {
			return array();
		}

		$tabs = array();

		foreach ( $this->tabs as $tab ) {
			$config = Core::get_config( 'settings' );
			if ( isset( $config['tab'][ $tab ] ) ) {
				$Tab_Factory = new Tab_Factory( $config['tab'] );
				$tab_object = $Tab_Factory->get_tab( $tab );
				if ( $tab_object ) {
					$tabs[ $tab ] = $tab_object;
				}
			}
		}

		return $tabs;
	}
}
