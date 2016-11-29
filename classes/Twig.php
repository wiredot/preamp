<?php

namespace Wiredot\Preamp;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_SimpleFunction;

class Twig {

	public $twig;

	private $directories = array();

	public function __construct() {
		$directories = Core::get_template_directories();
		$loader = new Twig_Loader_Filesystem($directories);
		$this->twig = new Twig_Environment($loader, array(
    		'debug' => true
		));
		$this->twig->addExtension(new Twig_Extension_Debug());

		$this->twig->registerUndefinedFunctionCallback( array($this, 'undefined_function') );
		
		$image = new Twig_SimpleFunction('image', function ($image_id, $params = array(), $attributes = array()) {
    		$Image = new Image($image_id, $params, $attributes);
    		return $Image->get_image();
		});
		$this->twig->addFunction($image);

		$alt = new Twig_SimpleFunction('alt', function ($post_id = null) {
    		return get_post_meta( $post_id, '_wp_attachment_image_alt', true );
		});
		$this->twig->addFunction($alt);
		
		$caption = new Twig_SimpleFunction('caption', function ($post_id = null) {
    		return get_the_excerpt( $post_id );
		});
		$this->twig->addFunction($caption);
	}

	public function undefined_function( $function_name ) {
		if ( function_exists( $function_name ) ) {
			return new Twig_SimpleFunction(
				$function_name,
				function () use ( $function_name ) {
					ob_start();
					$return = call_user_func_array( $function_name, func_get_args() );
					$echo   = ob_get_clean();
					return empty( $echo ) ? $return : $echo;
				},
				array( 'is_safe' => array( 'all' ) )
			);
		}
		return false;
	}
}