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
	protected $labels;

	public function __construct($type, $label, $name, $id, $value = '', $attributes = array(), $options = array(), $labels = array()) {
		$this->type = $type;
		$this->label = $label;
		$this->name = $name;
		$this->id = $id;
		$this->value = $value;
		$this->attributes = $attributes;
		$this->options = $options;
		$this->labels = $labels;
	}

	public function get_field() {
		switch ($this->type) {
			case 'text':
			case 'password':
			case 'email':
			case 'number':
			case 'range':
			case 'color':
			case 'url':
			case 'date':
				$field = new Input($this->type, $this->label, $this->name, $this->id, $this->value, $this->attributes);
				break;

			case 'textarea':
				$field = new Textarea($this->label, $this->name, $this->id, $this->value, $this->attributes);
				break;
			
			case 'editor':
				$field = new Editor($this->label, $this->name, $this->id, $this->value, $this->attributes);
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
			
			case 'upload':
				$field = new Upload($this->label, $this->name, $this->id, $this->value, $this->attributes, $this->options, $this->labels);
				break;
			
			default:
				return;
				break;
		}

		return $field->get_field();
	}

	public function showField() {
		echo $this->get_field();
	}
	
}