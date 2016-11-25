<?php

namespace Wiredot\Preamp;

class Js {

	private $js;

	public function __construct($js) {
		if ( ! is_array($js)) {
			return;
		}

		$this->js = $js;

		add_filter('admin_enqueue_scripts', array($this,'register_admin_js_files'));
		add_filter('wp_enqueue_scripts', array($this,'register_front_js_files'));
	}

	public function register_front_js_files() {
		$this->register_js_files('front');
	}

	public function register_admin_js_files() {
		$this->register_js_files('admin');
	}

	private function register_js_files($mode) {
		foreach ( $this->js as $name => $js ) {
			if ($js[$mode] === true) {
				$this->register_js_file($name, $js);
			}
		}
	}

	private function register_js_file($name, $js) {
		if ( ! isset($js['files']) || ! is_array($js['files'])) {
			return;
		}

		foreach ($js['files'] as $js_name => $js_link) {
			$this->display_js_file($js_name, $js_link, $js['dependencies'], '', $js['footer']);
		}
	}

	private function display_js_file($handle, $src, $dependencies, $version, $footer) {
		wp_deregister_script($handle);
		wp_register_script($handle, $src, $dependencies, $version, $footer);
		wp_enqueue_script($handle);
	}
}