<?php

namespace Wiredot\Preamp\Fields;

use Wiredot\Preamp\Twig;

class Input {

	protected $type;
	protected $label;
	protected $name;
	protected $id;
	protected $value;
	protected $attributes;
	protected $options;

	public function __construct($type, $label, $name, $id, $value, $attributes, $options = array()) {
		$this->type = $type;
		$this->label = $label;
		$this->name = $name;
		$this->id = $id;
		$this->value = $value;
		$this->attributes = $attributes;
		$this->options = $options;
	}

	public function getField() {
		$Twig = new Twig;

		return $Twig->twig->render('fields/input.html',
			array(
				'type' => $this->type,
				'label' => $this->label,
				'name' => $this->name,
				'id' => $this->id,
				'value' => $this->value,
				'attributes' => $this->attributes,
				'options' => $this->options,
			)
		);
	}
}