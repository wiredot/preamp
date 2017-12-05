<?php

namespace Wiredot\Preamp\Settings;

use Wiredot\Preamp\Core;
use Wiredot\Preamp\Twig;

class Section {

	private $id;
	private $name;
	private $fields;

	public function __construct( $id, $name, $fields ) {
		$this->id = $id;
		$this->name = $name;
		$this->fields = $fields;
	}

	public function get_section() {
		$Twig = new Twig;
		return $Twig->twig->render(
			'settings/section.twig',
			array(
				'id' => $this->id,
				'name' => $this->name,
			)
		);
	}
}
