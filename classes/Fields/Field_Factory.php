<?php

namespace Wiredot\Preamp\Fields;

class Field_Factory {

	protected $id;
	protected $name;
	protected $field;

	public function __construct( $id, $name, $field, $value ) {
		$this->id = $id;
		$this->name = $name;
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

		if ( ! isset( $this->field['fields'] ) ) {
			$this->field['fields'] = array();
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
			case 'time':
				$field = new Input( $this->field['type'], $this->name, $this->id, $this->value, $this->field['attributes'], $this->field['size'] );
				break;

			case 'textarea':
				$field = new Textarea( $this->field['label'], $this->name, $this->id, $this->value, $this->field['attributes'] );
				break;

			case 'editor':
				$field = new Editor( $this->field['label'], $this->name, $this->id, $this->value, $this->field['attributes'] );
				break;

			case 'select':
				$field = new Select( $this->field['label'], $this->name, $this->id, $this->value, $this->field['attributes'], $this->field['options'] );
				break;

			case 'checkbox':
				$field = new Checkbox( $this->field['label'], $this->name, $this->id, $this->value, $this->field['attributes'], $this->field['options'] );
				break;

			case 'radio':
				$field = new Radio( $this->field['label'], $this->name, $this->id, $this->value, $this->field['attributes'], $this->field['options'] );
				break;

			case 'post':
				$field = new Post( $this->field['label'], $this->name, $this->id, $this->value, $this->field['attributes'], $this->field['options'], $this->field['labels'], $this->field['arguments'] );
				break;

			case 'user':
				$field = new User( $this->field['label'], $this->name, $this->id, $this->value, $this->field['attributes'], $this->field['options'], $this->field['labels'], $this->field['arguments'] );
				break;

			case 'user_role':
				$field = new User_Role( $this->field['label'], $this->name, $this->id, $this->value, $this->field['attributes'], $this->field['options'], $this->field['labels'], $this->field['arguments'] );
				break;

			case 'upload':
				$field = new Upload( $this->field['label'], $this->name, $this->id, $this->value, $this->field['attributes'], $this->field['options'], $this->field['labels'] );
				break;

			// case 'table':
			// 	$field = new Upload( $this->field['label'], $this->name, $this->id, $this->value, $this->field['attributes'], $this->field['options'], $this->field['labels'] );
			// 	break;

			case 'group':
				$field = new Group( $this->field['label'], $this->name, $this->id, $this->value, $this->field['fields'] );
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
