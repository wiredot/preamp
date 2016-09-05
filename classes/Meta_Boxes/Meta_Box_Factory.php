<?php

namespace Preamp\Meta_Boxes;

class Meta_Box_Factory {

	private $meta_boxes;
	
	function __construct($meta_boxes) {
		$this->meta_boxes = $meta_boxes;
		// print_r($meta_boxes);

		$this->register_meta_boxes();
	}

	public function register_meta_boxes() {
		if (is_array($this->meta_boxes)) {
			foreach ($this->meta_boxes as $post_type => $meta_box) {
				
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