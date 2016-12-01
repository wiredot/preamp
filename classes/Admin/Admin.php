<?php

namespace Wiredot\Preamp\Admin;

use Wiredot\Preamp\Css\Css;
use Wiredot\Preamp\Js\Js;

/**
 * Meadow extension for Twig with WordPress specific functionality.
 */
class Admin {

	private $config = array();

	public function __construct($config) {
		$this->config = $config;

		$this->init_admin_css();
		$this->init_admin_js();

		if (isset($this->config['admin_custom_columns'])) {
			$custom_columns = new Custom_Columns_Factory($this->config['admin_custom_columns']);
			$custom_columns->set_custom_columns();
		}
	}

	public function init_admin_css() {
		$preamp_css = new Css('preamp', PREAMP_URL.'vendor/wiredot/preamp/assets/css/preamp.css', 'admin');
		$preamp_css->register_css_files();
	}

	public function init_admin_js() {
		$preamp_js = new Js('admin', 'preamp', PREAMP_URL.'vendor/wiredot/preamp/assets/js/preamp.js', 'admin');
		$preamp_js->register_js_files();
	}

}