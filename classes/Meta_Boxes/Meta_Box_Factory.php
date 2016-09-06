<?php

namespace Wiredot\Preamp\Meta_Boxes;

class Meta_Box_Factory {

	private $meta_boxes;
	
	function __construct($meta_boxes) {
		if (is_array($meta_boxes)) {
			foreach ($meta_boxes as $meta_box_id => $meta_box) {
				
				switch ($meta_box['type']) {
					case 'post':
						new Post_Meta_Box($meta_box_id, $meta_box);
						break;
					
					default:
						# code...
						break;
				}
			}
		}
	}
}