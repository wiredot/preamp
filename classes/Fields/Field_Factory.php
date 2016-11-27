<?php

namespace Wiredot\Preamp\Fields;

use Wiredot\Preamp\Fields\Text;
use Wiredot\Preamp\Fields\Email;
use Wiredot\Preamp\Fields\Textarea;
use Wiredot\Preamp\Fields\Select;

class Field_Factory {

	protected $type;
	protected $label;
	protected $name;
	protected $id;
	protected $value;
	protected $attributes;
	protected $options;

	public function __construct($type, $label, $name, $id, $value, $attributes = array(), $options = array()) {
		$this->type = $type;
		$this->label = $label;
		$this->name = $name;
		$this->id = $id;
		$this->value = $value;
		$this->attributes = $attributes;
		$this->options = $options;
	}

	public function getField() {
		switch ($this->type) {
			case 'text':
				$field = new Text($this->label, $this->name, $this->id, $this->value, $this->attributes);
				break;

			case 'email':
				$field = new Email($this->label, $this->name, $this->id, $this->value, $this->attributes);
				break;

			case 'textarea':
				$field = new Textarea($this->label, $this->name, $this->id, $this->value, $this->attributes);
				break;
			
			case 'select':
				$field = new Select($this->label, $this->name, $this->id, $this->value, $this->attributes, $this->options);
				break;
			
			case 'checkbox':
				$field = new Checkbox($this->label, $this->name, $this->id, $this->value, $this->attributes, $this->options);
				break;
			
			case 'radio':
				$field = new Radio($this->label, $this->name, $this->id, $this->value, $this->attributes, $this->options);
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