<?php

namespace Wiredot\Preamp\Sidebars;

class Sidebar {

	private $args;

	function __construct( $args ) {
		$this->args = $args;
	}

	public function register_sidebar() {
		register_sidebar( $args );
		print_r($args);
	}
}
