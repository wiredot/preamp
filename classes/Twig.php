<?php

namespace Wiredot\Preamp;

use Twig_Loader_Filesystem;
use Twig_Environment;

class Twig {

	public $twig;

	private $directories = array();

	public function __construct() {
		$directories = Core::get_template_directories();
		$loader = new Twig_Loader_Filesystem($directories);
		$this->twig = new Twig_Environment($loader);
	}
}