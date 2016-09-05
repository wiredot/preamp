<?php

namespace Preamp;

class Custom_Post_Type {

	private $post_type;
	private $args;
	
	function __construct($post_type = '') {
		$this->post_type = $post_type;

		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	public function register_post_type() {
		//echo $this->post_type;
		echo $this->post_type;
		//exit;
	}
}