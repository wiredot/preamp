<?php

namespace Wiredot\Preamp\Settings;

class Tab_Factory {

	private $tabs;

	function __construct( $tabs ) {
		$this->tabs = $tabs;
	}

	public function get_tab( $id ) {
		if ( isset( $this->tabs[ $id ] ) ) {
			$Tab = new Tab( $id, $this->tabs[ $id ]['name'], $this->tabs[ $id ]['sections'] );
			return $Tab->get_tab();
		}
		return null;
	}
}
