<?php

namespace Wiredot\Preamp\Settings;

class Section_Factory {

	private $sections;

	function __construct( $sections ) {
		$this->sections = $sections;
	}

	public function get_section( $id ) {
		if ( isset( $this->sections[ $id ] ) ) {
			$Section = new Section( $id, $this->sections[ $id ]['name'], $this->sections[ $id ]['description'], $this->sections[ $id ]['fields'] );
			return $Section->get_section();
		}
		return null;
	}
}
