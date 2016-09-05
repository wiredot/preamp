<?php

namespace Preamp;

use Preamp\Meta_Boxes\Post_Meta_Box;

class Meta_Box_Factory {

	private $meta_boxes;
	
	function __construct($meta_boxes) {
		$this->meta_boxes = $meta_boxes;
		print_r($meta_boxes);

		$this->register_meta_boxes();
	}

	public function register_meta_boxes() {
		if (is_array($this->meta_boxes)) {
			foreach ($this->meta_boxes as $post_type => $meta_box) {
				print_r($meta_box);
				switch ($meta_box['type']) {
					case 'post':
						new Post_Meta_Box($meta_box);
						break;
					
					default:
						# code...
						break;
				}
			}
		}
	}
}