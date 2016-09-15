<?php

namespace Wiredot\Preamp\Fields;

use Wiredot\Preamp\Fields\Text;
use Wiredot\Preamp\Fields\Email;
use Wiredot\Preamp\Fields\Textarea;
use Wiredot\Preamp\Fields\Select;

class Field_Factory {

	protected $type;
	protected $id;
	protected $value;
	protected $field;

	public function __construct($type, $name, $id, $value, $field) {
		$this->type = $type;
		$this->name = $name;
		$this->id = $id;
		$this->value = $value;
		$this->field = $field;
	}

	public function getField() {
		switch ($this->type) {
			case 'text':
				$field = new Text($this->name, $this->id, $this->value, $this->field['attributes']);
				break;

			case 'email':
				$field = new Email($this->name, $this->id, $this->value, $this->field['attributes']);
				break;

			case 'textarea':
				$field = new Textarea($this->name, $this->id, $this->value, $this->field['attributes']);
				break;
			
			case 'select':
				$field = new Select($this->name, $this->id, $this->value, $this->field['attributes'], $this->field['options']);
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