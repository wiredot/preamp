<?php

namespace Wiredot\Preamp\Settings;

use Wiredot\Preamp\Core;

class Tab {

	private $id;
	private $name;
	private $sections;

	public function __construct( $id, $name, $sections ) {
		$this->id = $id;
		$this->name = $name;
		$this->sections = $sections;
	}

	public function get_tab() {
		return array(
			'id' => $this->id,
			'name' => $this->name,
			'sections' => $this->get_sections(),
		);
	}

	public function get_sections() {
		if ( ! is_array( $this->sections ) ) {
			return '';
		}

		$sections = '';

		foreach ( $this->sections as $section ) {
			$config = Core::get_config( 'settings' );
			if ( isset( $config['section'][ $section ] ) ) {
				$Section_Factory = new Section_Factory( $config['section'] );
				$sections .= $Section_Factory->get_section( $section );
			}
		}

		return $sections;
	}
}
