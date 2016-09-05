<?php

namespace Preamp\Custom_Post_Types;

class Custom_Post_Type_Factory {

	private $custom_post_types;
	
	function __construct($custom_post_types) {
		$this->custom_post_types = $custom_post_types;
		// print_r($custom_post_types);

		$this->register_post_types();
	}

	public function register_post_types() {
		if (is_array($this->custom_post_types)) {
			foreach ($this->custom_post_types as $post_type => $custom_post_type) {
				new Custom_Post_Type($post_type);
			}
		}
	}
}