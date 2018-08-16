<?php

namespace Wiredot\Preamp\Settings;

class Settings_Factory {

	private $settings;

	function __construct( $settings ) {
		$this->settings = $settings;
	}

	public function add_settings() {
		foreach ( $this->settings['page'] as $menu_slug => $settings ) {

			if ( $settings['submenu'] ) {
				$Menu = new Submenu( $settings['parent_slug'], $settings['page_title'], $settings['menu_title'], $settings['capability'], $menu_slug, $settings['options_prefix'] );
			} else {
				$Menu = new Menu( $settings['page_title'], $settings['menu_title'], $settings['capability'], $menu_slug, $settings['icon_url'], $settings['position'], $settings['options_prefix'] );
			}

			$Menu->add_settings_page();
		}
	}
}
