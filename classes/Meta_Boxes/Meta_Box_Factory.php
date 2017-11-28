<?php

namespace Wiredot\Preamp\Meta_Boxes;

class Meta_Box_Factory {

	private $meta_boxes;

	function __construct( $meta_boxes ) {
		if ( ! is_array( $meta_boxes ) ) {
			return;
		}

		foreach ( $meta_boxes as $type => $type_meta_boxes ) {
			$this->init_meta_boxes( $type, $type_meta_boxes );
		}
	}

	public function init_meta_boxes( $type, $meta_boxes ) {
		foreach ( $meta_boxes as $meta_box_id => $meta_box ) {
			$this->init_meta_box( $type, $meta_box_id, $meta_box );
		}
	}

	public function init_meta_box( $type, $meta_box_id, $meta_box ) {
		switch ( $type ) {
			case 'post':
				new Post_Meta_Box( $meta_box_id, $meta_box );
				break;

			case 'term':
				new Term_Meta_Box( $meta_box_id, $meta_box );
				break;

			case 'user':
				new User_Meta_Box( $meta_box_id, $meta_box );
				break;
		}
	}
}
