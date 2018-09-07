<?php

namespace Wiredot\Preamp\Image_Sizes;

class Image_Size_Factory {

	private $image_sizes = array();

	public function __construct( $image_sizes ) {
		$this->image_sizes = $image_sizes;
	}

	public function add_image_sizes() {
		foreach ( $this->image_sizes as $name => $image_size ) {
			$image_size = new Image_Size( $name, $image_size['width'], $image_size['height'], $image_size['crop'] );
			$image_size->add_image_size();
		}
	}
}
