<?php

namespace Preamp;

class Languages {

	private $languages = array();

	public function add_language($name, $prefix) {
		$new_language = new Language($name, $prefix);

		$this->languages[$prefix] = $new_language;			
	}

	public function list_languages() {
		return $this->languages;
	}
}