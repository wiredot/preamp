<?php

namespace Wiredot\Preamp\Fields;

use Wiredot\Preamp\Twig;
use Wiredot\Preamp\Form\Row;

class Group {

	protected $label;
	protected $name;
	protected $id;
	protected $value;
	protected $fields;
	protected $size;

	public function __construct( $label, $name, $id, $value, $fields ) {
		$this->label = $label;
		$this->name = $name;
		$this->id = $id;
		$this->value = $value;
		$this->fields = $fields;
	}

	public function get_field() {
		$Twig = new Twig;

		$rows = '';

		$values = array(
			1 => array(
				'text' => 'Adsf df',
				'textb' => 'Koasdia',
			),
			2 => array(
				'text' => 'PWOWO',
				'textb' => 'NJUDUHUNE',
			),
		);

		$group_items = $this->get_group_items( $this->name, $this->fields, $this->value );

		$new_group_item = '';

		foreach ( $this->fields as $name => $field ) {
			$row = new Row( $this->id, $this->name . '[' . $key . '][' . $name . ']', $field, '' );
			$new_group_item .= $row->get_row();
		}

		return $Twig->twig->render(
			'forms/group.twig',
			array(
				'label' => $this->label,
				'name' => $this->name,
				'id' => $this->id,
				'value' => $this->value,
				'group_items' => $group_items,
				'new_group_item' => $new_group_item,
			)
		);
	}

	public function get_group_items( $group_name, $fields, $values ) {
		$Twig = new Twig;

		if ( ! is_array( $values ) ) {
			return;
		}

		$group_items = '';

		foreach ( $values as $key => $value ) {

			$rows = '';

			foreach ( $fields as $name => $field ) {
				$item_value = $value[ $name ];
				$row = new Row( $key, $group_name . '[' . $key . '][' . $name . ']', $field, $item_value );
				$rows .= $row->get_row();
			}

			$group_items .= $Twig->twig->render(
				'forms/group_item.twig',
				array(
					'rows' => $rows,
				)
			);
		}

		return $group_items;
	}
}
