<?php

namespace Wiredot\Preamp\Fields;

use Wiredot\Preamp\Twig;

class Wysiwyg extends Field {

	protected $type = 'wysiwyg';

	public function get_field() {
		$Twig = new Twig;

		return $Twig->twig->render(
			'fields/wysiwyg.twig',
			array(
				'label' => $this->label,
				'value' => $this->value,
				'id' => $this->id,
				'name' => $this->name,
			)
		);
	}
}
