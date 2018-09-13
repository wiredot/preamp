<?php

namespace Wiredot\Preamp\Settings;

use Wiredot\Preamp\Menus\Menu_Factory;
use Wiredot\Preamp\Submenus\Submenu_Factory;


class Settings_Factory {

	private $settings;

	function __construct( $settings ) {
		$this->settings = $settings;
	}

	public function add_settings() {
		if ( isset( $this->settings['menu'] ) ) {
			$Menu = new Menu_Factory( $this->settings['menu'] );
			$Menu->add_menus();
		}

		if ( isset( $this->settings['submenu'] ) ) {
			$Submenu = new Submenu_Factory( $this->settings['submenu'] );
			$Submenu->add_submenus();
		}
	}
}
