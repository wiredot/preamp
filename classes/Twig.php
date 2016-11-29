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
		$function = new Twig_SimpleFunction('image', function ($image_id, $params = array(), $attributes = array()) {
    		$Image = new Image($image_id, $params, $attributes);
    		return $Image->get_image();
		});
		$this->twig->addFunction($function);
	}
}