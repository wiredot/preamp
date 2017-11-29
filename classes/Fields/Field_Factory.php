<?php

namespace Wiredot\Preamp\Fields;

class Field_Factory {

	protected $id;
	protected $field;

	public function __construct( $id, $field, $value ) {
		$this->id = $id;
		$this->field = $field;
		$this->value = $value;

		if ( ! isset( $this->field['attributes'] ) ) {
			$this->field['attributes'] = array();
		}

		if ( ! isset( $this->field['options'] ) ) {
			$this->field['options'] = array();
		}

		if ( ! isset( $this->field['labels'] ) ) {
			$this->field['labels'] = array();
		}

		if ( ! isset( $this->field['arguments'] ) ) {
			$this->field['arguments'] = array();
		}

		if ( ! isset( $this->field['size'] ) ) {
			$this->field['size'] = array();
		}
	}

	public function get_field() {
		switch ( $this->field['type'] ) {
			case 'text':
			case 'password':
			case 'email':
			case 'number':
			case 'range':
			case 'color':
			case 'url':
			case 'date':
				$field = new Input( $this->field['type'], $this->id, $this->id, $this->value, $this->field['attributes'], $this->field['size'] );
				break;

			case 'textarea':
				$field = new Textarea( $this->field['label'], $this->id, $this->id, $this->value, $this->field['attributes'] );
				break;

			case 'editor':
				$field = new Editor( $this->field['label'], $this->id, $this->id, $this->value, $this->field['attributes'] );
				break;

			case 'select':
				$field = new Select( $this->field['label'], $this->id, $this->id, $this->value, $this->field['attributes'], $this->field['options'] );
				break;

			case 'checkbox':
				$field = new Checkbox( $this->field['label'], $this->id, $this->id, $this->value, $this->field['attributes'], $this->field['options'] );
				break;

			case 'radio':
				$field = new Radio( $this->field['label'], $this->id, $this->id, $this->value, $this->field['attributes'], $this->field['options'] );
				break;

			case 'post':
				$field = new Post( $this->field['label'], $this->id, $this->id, $this->value, $this->field['attributes'], $this->field['options'], $this->field['labels'], $this->field['arguments'] );
				break;

			case 'user':
				$field = new User( $this->field['label'], $this->id, $this->id, $this->value, $this->field['attributes'], $this->field['options'], $this->field['labels'], $this->field['arguments'] );
				break;

			case 'user_role':
				$field = new User_Role( $this->field['label'], $this->id, $this->id, $this->value, $this->field['attributes'], $this->field['options'], $this->field['labels'], $this->field['arguments'] );
				break;

			case 'upload':
				$field = new Upload( $this->field['label'], $this->id, $this->id, $this->value, $this->field['attributes'], $this->field['options'], $this->field['labels'] );
				break;

			default:
				return;
				break;
		}

		return $field->get_field();
	}

	public function show_field() {
		echo $this->get_field();
	}

}
