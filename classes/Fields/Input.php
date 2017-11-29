<?php

namespace Wiredot\Preamp\Fields;

use Wiredot\Preamp\Twig;

class Input {

	protected $type;
	protected $name;
	protected $id;
	protected $value;
	protected $attributes;
	protected $size;

	public function __construct( $type, $name, $id, $value, $attributes, $size = '' ) {
		$this->type = $type;
		$this->name = $name;
		$this->id = $id;
		$this->value = $value;
		$this->attributes = $attributes;
		$this->size = $size;

		if ( $size ) {
			if ( isset( $this->attributes['class'] ) ) {
				$this->attributes['class'] .= ' ' . $size . '-text';
			} else {
				$this->attributes['class'] = $size . '-text';
			}
		}
	}

	public function get_field() {
		$Twig = new Twig;

		return $Twig->twig->render(
			'fields/input.twig',
			array(
				'type' => $this->type,
				'name' => $this->name,
				'id' => $this->id,
				'value' => $this->value,
				'attributes' => $this->attributes,
			)
		);
	}
}
