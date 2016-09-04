<?php

namespace Preamp;

class Language {

	private $name;
	private $prefix;

	public function __construct($name, $prefix) {
		$this->name = $name;
		$this->prefix = $prefix;
	}

	public function get_name() {
		return $this->name;
	}

	
}