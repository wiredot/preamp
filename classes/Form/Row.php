<?php

namespace Wiredot\Preamp\Form;

use Wiredot\Preamp\Twig;
use Wiredot\Preamp\Fields\Field_Factory;

class Row {

	protected $id;
	protected $name;
	protected $value;
	protected $type;
	protected $label;
	protected $attributes;
	protected $options;

	public function __construct($id, $name, $value, $type, $label, $attributes = array(), $options = array()) {
		$this->id = $id;
		$this->name = $name;
		$this->value = $value;
		$this->type = $type;
		$this->label = $label;
		$this->attributes = $attributes;
		$this->options = $options;
	}

	public function getRow() {

		$field = new Field_Factory($this->type, $this->label, $this->name, $this->id, $this->value, $this->attributes, $this->options);

		$Twig = new Twig;
		return $Twig->twig->render('forms/row.html',
			array(
				'field' => $field->getField()
			)
		);
	}

	public function showRow() {
		echo $this->getRow();
	}
}