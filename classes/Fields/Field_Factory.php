<?php

namespace Wiredot\Preamp\Fields;

class Field_Factory {

	protected $type;
	protected $id;
	protected $value;
	protected $attributes;

	public function __construct($id, $type, $value = '', $attributes = array()) {
		$this->id = $id;
		$this->type = $type;
		$this->value = $value;
		$this->attributes = $attributes;
	}

	public function getField() {
		switch ($this->type) {
			case 'text':
				$field = new Wiredot\Preamp\Fields\Input;
				break;
			
			default:
				# code...
				break;
		}

		return $field->getField();
	}

	public function showField() {
		echo $this->getField();
	}
	
}