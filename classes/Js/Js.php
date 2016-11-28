<?php

namespace Wiredot\Preamp\Js;

class Js {

	private $name;
	private $files;
	private $mode;
	private $dependencies;

	public function __construct($mode, $name, $files, $dependencies = array(), $footer = false) {
		$this->name = $name;
		$this->files = $files;
		$this->mode = $mode;
		$this->dependencies = $dependencies;
		$this->footer = $footer;
	}

	public function register_js_files() {
		if ($this->mode == 'front') {
			add_filter('wp_enqueue_scripts', array($this,'register_js_file'));
		} else {
			add_filter('admin_enqueue_scripts', array($this,'register_js_file'));
		}
	}

	public function register_js_file() {
		if (is_array($this->files)) {
			foreach ($this->files as $js_name => $js_link) {
				$this->display_js_file($js_name, $js_link, $this->dependencies, $this->footer);
			}
		} else {
			$this->display_js_file($this->name, $this->files, $this->dependencies, $this->footer);
		}
	}

	private function display_js_file($handle, $src, $dependencies, $footer) {
		wp_deregister_script($handle);
		wp_register_script($handle, $src, $dependencies, false , $footer);
		wp_enqueue_script($handle);
	}
}