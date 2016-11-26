<?php

namespace Wiredot\Preamp\Form;

use Wiredot\Preamp\Twig;

class Label {

	protected $label;
	protected $for;

	public function __construct($label, $for) {
		$this->label = $label;
		$this->for = $for;
	}

	public function getLabel() {
		$Twig = new Twig;
		return $Twig->twig->render('forms/label.html',
			array(
				'label' => $this->label,
				'for' => $this->for
			)
		);
	}

	public function showLabel() {
		echo $this->getLabel();
	}
}