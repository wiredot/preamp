<?php

namespace Preamp;

class Custom_Post_Type_Factory {

	private $post_type;
	private $args;
	
	function __construct($post_type = '') {
		//$Preamp = Preamp::get_instance();
		// //$Preamp = Preamp::run();
		// print_r(Preamp::get_config());
		// print_r($Preamp);
		//print_r($Preamp->get_config());
		//print_r($Preamp);
		//exit;
		echo 'asd';
	}

	public function register_post_type() {
		echo 'asd';
		$Preamp = Preamp::run();
		print_r($Preamp->get_config());
		exit;
		//echo $this->post_type;
		//exit;
	}
}