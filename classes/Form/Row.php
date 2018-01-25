<?php

namespace Wiredot\Preamp\Form;

use Wiredot\Preamp\Twig;
use Wiredot\Preamp\Fields\Field_Factory;

class Row {

	protected $field;
	protected $id;
	protected $name;
	protected $value;
	protected $template;
	protected $condition;

	public function __construct( $id, $name, $field, $value = '' ) {
		$this->id = $id;
		$this->name = $name;
		$this->field = $field;
		$this->value = $value;

		if ( isset( $field['condition'] ) ) {
			$this->condition = $field['condition'];
		}

		if ( isset( $field['template'] ) ) {
			$this->template = $field['template'];
		}
	}

	public function get_row() {
		$field = new Field_Factory( $this->id, $this->name, $this->field, $this->value );

		if ( 'checkbox' == $this->field['type'] && ! count( $this->field['options'] ) ) {
			$this->field['type'] = 'checkboxes';
		}

		$class = '';

		if ( $this->template ) {
			$class .= 'preamp-template ';

			if ( is_array( $this->template ) ) {
				foreach ( $this->template as $template ) {
					$class .= 'preamp-template-' . $template . ' ';
				}
			} else {
				$class .= 'preamp-template-' . $this->template . ' ';
			}
		}

		if ( $this->condition ) {
			$class .= 'preamp-condition preamp-condition-active ';

			foreach ( $this->condition as $field_condition => $value ) {
				$class .= 'preamp-condition-' . $field_condition . ' ';

				if ( is_array( $value ) ) {
					foreach ( $value as $val ) {
						$class .= 'preamp-condition-' . $field_condition . '-' . $val . ' ';
					}
				} else {
					$class .= 'preamp-condition-' . $field_condition . '-' . $value . ' ';
				}
			}
		}

		if ( ! isset( $this->field['description'] ) ) {
			$this->field['description'] = '';
		}

		$Twig = new Twig;
		return $Twig->twig->render(
			'forms/row.twig',
			array(
				'field' => $field->get_field(),
				'type' => $this->field['type'],
				'label' => $this->field['label'],
				'description' => $this->field['description'],
				'class' => $class,
				'id' => $this->id,
			)
		);
	}

	public function show_row() {
		echo $this->get_row();
	}
}
