<?php

namespace Preamp\Fields;

class Base_Field {

	protected $name;
	protected $value;
	protected $attributes;
	
	function __construct($name, $value = '', $attributes = array()) {
		$this->name = $name;
		$this->value = $value;
		$this->attributes = $attributes;
	}
}