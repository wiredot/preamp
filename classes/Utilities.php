<?php

namespace Wiredot\Preamp;

class Utilities {

	public static function array_map_r( $function, $array ) {
		$new_array = array();

		foreach ( $array as $key => $value ) {
			$new_array[ $key ] = ( is_array( $value ) ? self::array_map_r( $function, $value ) : ( is_array( $function ) ? call_user_func_array( $function, $value ) : $function( $value ) ) );
		}

		return $new_array;
	}
}
