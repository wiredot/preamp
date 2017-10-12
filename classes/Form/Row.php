<?php

namespace Wiredot\Preamp\Form;

use Wiredot\Preamp\Twig;
use Wiredot\Preamp\Fields\Field_Factory;

class Row {

	protected $field;

	public function __construct($field) {
		$this->field = $field;
	}

	public function get_row() {

		$Twig = new Twig;
		return $Twig->twig->render('forms/row.twig',
			array(
				'field' => $this->field
			)
		);
	}

	public function show_row() {
		echo $this->get_row();
	}
}