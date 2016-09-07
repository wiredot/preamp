<?php

namespace Wiredot\Preamp\Fields;

use Wiredot\Preamp\Fields\Text;
use Wiredot\Preamp\Fields\Email;

class Field_Factory {

	protected $type;
	protected $id;
	protected $value;
	protected $attributes;

	public function __construct($type, $name, $id, $value = '', $attributes = array()) {
		$this->type = $type;
		$this->name = $name;
		$this->id = $id;
		$this->value = $value;
		$this->attributes = $attributes;
	}

	public function getField() {
		switch ($this->type) {
			case 'text':
				$field = new Text($this->name, $this->id, $this->value, $this->attributes);
				break;

			case 'email':
				$field = new Email($this->name, $this->id, $this->value, $this->attributes);
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