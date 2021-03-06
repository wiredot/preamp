<?php

namespace Wiredot\Preamp\Custom_Post_Types;

class Custom_Post_Type_Factory {

	function __construct( $custom_post_types ) {
		if ( is_array( $custom_post_types ) ) {
			foreach ( $custom_post_types as $post_type => $custom_post_type ) {
				new Custom_Post_Type( $post_type, $custom_post_type );
			}
		}
	}
}
