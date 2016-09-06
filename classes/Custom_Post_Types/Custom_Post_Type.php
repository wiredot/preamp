<?php

namespace Wiredot\Preamp\Custom_Post_Types;

class Custom_Post_Type {

	private $post_type;
	private $args;
	
	function __construct($post_type = '', $args = array()) {
		$this->post_type = $post_type;
		$this->args = $args;

		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	public function register_post_type() {
		// echo $this->post_type;

		register_post_type( $this->post_type, $this->args );
		// echo $this->post_type;
		//exit;
	}
}