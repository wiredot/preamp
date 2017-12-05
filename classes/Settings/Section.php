<?php

namespace Wiredot\Preamp\Settings;

use Wiredot\Preamp\Core;
use Wiredot\Preamp\Languages;
use Wiredot\Preamp\Twig;
use Wiredot\Preamp\Form\Row;
use Wiredot\Preamp\Form\Row_Multilingual;

class Section {

	private $id;
	private $name;
	private $description;
	private $fields;

	public function __construct( $id, $name, $description, $fields ) {
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->fields = $fields;
	}

	public function get_section() {
		$rows = $this->get_rows();
		$Twig = new Twig;
		return $Twig->twig->render(
			'settings/section.twig',
			array(
				'id' => $this->id,
				'name' => $this->name,
				'description' => $this->description,
				'rows' => $rows,
			)
		);
	}

	public function get_rows() {
		$rows = '';
		foreach ( $this->fields as $key => $field ) {
			if ( Languages::has_languages() && isset( $field['translate'] ) && $field['translate'] ) {
				$values = array();
				$Row = new Row_Multilingual( $key, $key, $field, $values );
			} else {
				$value = 'ass';
				$Row = new Row( $key, $key, $field, $value );
			}
			$rows .= $Row->get_row();
		}

		return $rows;
	}
}
