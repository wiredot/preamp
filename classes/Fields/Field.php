<?php

namespace Wiredot\Preamp\Fields;

use Wiredot\Preamp\Twig;

class Field {

	protected $name;
	protected $id;
	protected $value;
	protected $attributes;
	protected $options;

	public function __construct($name, $id, $value, $attributes, $options = array()) {
		$this->id = $id;
		$this->name = $name;
		$this->value = $value;
		$this->attributes = $attributes;
		$this->options = $options;
	}

	public function getField() {
		$Twig = new Twig;

		return $Twig->twig->render('fields/'.$this->type.'.html',
			array(
				'name' => $this->name,
				'id' => $this->id,
				'value' => $this->value,
				'attributes' => $this->attributes,
				'options' => $this->options,
			)
		);
	}
}