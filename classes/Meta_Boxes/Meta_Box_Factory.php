<?php

namespace Wiredot\Preamp\Meta_Boxes;

class Meta_Box_Factory {

	private $meta_boxes;

	function __construct( $meta_boxes ) {
		if ( ! is_array( $meta_boxes ) ) {
			return;
		}

		foreach ( $meta_boxes as $meta_box_id => $meta_box ) {
			if ( is_array( $meta_box['post_type'] ) ) {
				foreach ( $meta_box['post_type'] as $post_type ) {
					$new_meta_box = $meta_box;
					$new_meta_box['post_type'] = $post_type;
					$this->init_meta_box( $meta_box['type'], $meta_box_id, $new_meta_box );
				}
			} else {
				$this->init_meta_box( $meta_box['type'], $meta_box_id, $meta_box );
			}
		}
	}

	public function init_meta_box( $type, $meta_box_id, $meta_box ) {
		switch ( $type ) {
			case 'post':
				new Post_Meta_Box( $meta_box_id, $meta_box );
				break;

			default:
				# code...
				break;
		}
	}
}
