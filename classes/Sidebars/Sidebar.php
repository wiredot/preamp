<?php

namespace Wiredot\Preamp\Sidebars;

class Sidebar {

	private $args;

	function __construct( $args ) {
		$this->args = $args;

		add_action( 'widgets_init', array( $this, 'register_sidebar' ) );
	}

	public function register_sidebar() {
		register_sidebar( $args );
	}
}
