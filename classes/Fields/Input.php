<?php

namespace Preamp\Fields;

class Input extends Base_Field {

	protected $type = 'text';

	function html() {
		return '<input type="'.$this->type.'" name="'.$this->name.'" value="'.$this->value.'">';
	}
}