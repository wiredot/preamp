<?php

namespace Wiredot\Preamp\Fields;

use Wiredot\Preamp\Twig;

class Editor extends Field {

	protected $type = 'editor';

	public function get_field() {
		ob_start();
		$field['attributes']['textarea_name'] = $this->name;
		wp_editor( $this->value, $this->id, $field['attributes'] );
		$editor = ob_get_clean();

		$Twig = new Twig;

		return $Twig->twig->render(
			'fields/editor.twig',
			array(
				'label' => $this->label,
				'editor' => $editor,
			)
		);
	}
}
