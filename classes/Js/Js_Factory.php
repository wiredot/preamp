<?php

namespace Wiredot\Preamp\Js;

class Js_Factory {

	private $js_files;

	public function __construct($js_files) {
		$this->js_files = $js_files;
	}

	public function register_js_files() {
		foreach ( $this->js_files as $handle => $js_file ) {
			if ( ! isset($js_file['files']) || ! isset($js_file['url']) ) {
				continue;
			}

			foreach ($js_file['files'] as $key => $file) {
				$js_file['files'][$key] = $js_file['url'] . '/' . $file;
			}

			if ( isset($js_file['front']) && $js_file['front'] ) {
				$mode = 'front';
			} else if ( isset($js_file['admin']) && $js_file['admin']) {
				$mode = 'admin';
			} else {
				continue;
			}

			if ( ! isset($js_file['dependencies'])) {
				$js_file['dependencies'] = false;
			}

			$js = new Js($mode, $handle, $js_file['files'], $js_file['dependencies'], $js_file['footer']);
			$js->register_js_files();
		}
	}
}