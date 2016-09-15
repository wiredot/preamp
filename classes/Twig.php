<?php

namespace Wiredot\Preamp;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Extension_Debug;

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
	}
}