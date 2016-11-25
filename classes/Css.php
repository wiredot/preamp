<?php

namespace Wiredot\Preamp;

class Css {

	private $css;

	public function __construct($css) {
		if ( ! is_array($css)) {
			return;
		}

		$this->css = $css;

		add_filter('admin_enqueue_scripts', array($this,'register_admin_css_files'));
		add_filter('wp_enqueue_scripts', array($this,'register_front_css_files'));
	}

	public function register_front_css_files() {
		$this->register_css_files('front');
	}

	public function register_admin_css_files() {
		$this->register_css_files('admin');
	}

	private function register_css_files($mode) {
		foreach ( $this->css as $name => $css ) {
			if ($css[$mode] === true) {
				$this->register_css_file($name, $css);
			}
		}
	}

	private function register_css_file($name, $css) {
		if ( ! isset($css['files']) || ! is_array($css['files'])) {
			return;
		}

		foreach ($css['files'] as $css_name => $css_link) {
			$this->display_css_file($css_name, $css_link, $css['dependencies'], '', $css['media']);
		}
	}

	private function display_css_file($name, $file, $dependencies, $version, $media) {
		wp_register_style($name, $file, $dependencies, $version, $media);
		wp_enqueue_style($name);
	}
}