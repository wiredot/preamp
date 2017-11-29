<?php

namespace Wiredot\Preamp\Form;

use Wiredot\Preamp\Twig;
use Wiredot\Preamp\Fields\Field_Factory;

class Row {

	protected $field;
	protected $id;
	protected $value;

	public function __construct( $id, $field, $value = '' ) {
		$this->id = $id;
		$this->field = $field;
		$this->value = $value;
	}

	public function get_row() {
		$field = new Field_Factory( $this->id, $this->field, $this->value );

		if ( 'checkbox' == $this->field['type'] && ! count( $this->field['options'] ) ) {
			$this->field['type'] = 'checkboxes';
		}

		$Twig = new Twig;
		return $Twig->twig->render(
			'forms/row.twig',
			array(
				'field' => $field->get_field(),
				'type' => $this->field['type'],
				'label' => $this->field['label'],
				'description' => $this->field['description'],
				'id' => $this->id,
			)
		);
	}

	public function show_row() {
		echo $this->get_row();
	}
}
