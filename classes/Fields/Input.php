<?php

namespace Wiredot\Preamp\Fields;

use Wiredot\Preamp\Twig;

class Input extends Field {

	protected $type = 'text';

	function html() {
		$Twig = new Twig;
		echo $Twig->twig->render('test.html', array('name' => 'Fabien'));	
		//return '<input type="'.$this->type.'" name="'.$this->name.'" value="'.$this->value.'">';
	}
}