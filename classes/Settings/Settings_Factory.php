<?php

namespace Wiredot\Preamp\Settings;

class Settings_Factory {

	private $settings;

	function __construct( $settings ) {
		$this->settings = $settings;
	}

	public function add_settings() {
		// print_r($this->settings);
		foreach ( $this->settings['page'] as $menu_slug => $settings ) {

			$Settings = new Settings( $settings['parent_slug'], $settings['page_title'], $settings['menu_title'], $settings['capability'], $menu_slug, $settings['options_prefix'] );
			$Settings->add_settings_page();
		}
	}
}
