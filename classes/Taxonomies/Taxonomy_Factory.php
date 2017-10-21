<?php

namespace Wiredot\Preamp\Taxonomies;

class Taxonomy_Factory {

	private $taxonomies;

	function __construct( $taxonomies ) {
		if ( ! is_array( $taxonomies ) ) {
			return;
		}

		foreach ( $taxonomies as $taxonomy_id => $taxonomy ) {
			$this->init_taxonomy( $taxonomy_id, $taxonomy['post_type'], $taxonomy['args'] );
		}
	}

	public function init_taxonomy( $taxonomy_id, $post_type, $args ) {
		new Taxonomy( $taxonomy_id, $post_type, $args );
	}
}
