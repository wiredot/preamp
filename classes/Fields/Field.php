<?php

namespace Wiredot\Preamp\Fields;

use Wiredot\Preamp\Twig;

class Field {

	protected $label;
	protected $name;
	protected $id;
	protected $value;
	protected $attributes;
	protected $options;

	public function __construct( $label, $name, $id, $value, $attributes, $options = array() ) {
		$this->label = $label;
		$this->name = $name;
		$this->id = $id;
		$this->value = $value;
		$this->attributes = $attributes;
		$this->options = $options;
	}

	public function get_field() {
		$Twig = new Twig;

		return $Twig->twig->render(
			'fields/' . $this->type . '.twig',
			array(
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
