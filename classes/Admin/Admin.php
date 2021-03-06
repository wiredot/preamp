<?php

namespace Wiredot\Preamp\Admin;

use Wiredot\Preamp\Css\Css;
use Wiredot\Preamp\Js\Js;

/**
 * Meadow extension for Twig with WordPress specific functionality.
 */
class Admin {
	private $config = array();

	public function __construct( $config ) {
		$this->config = $config;

		$this->init_admin_css();
		$this->init_admin_js();

		if ( isset( $this->config['admin_custom_columns'] ) ) {
			$custom_columns = new Custom_Columns_Factory( $this->config['admin_custom_columns'] );
			$custom_columns->set_custom_columns();
		}
	}

	public function init_admin_css() {
		$preamp_css = new Css( 'preamp', PREAMP_URL . 'vendor/wiredot/preamp/assets/css/preamp.css', 'admin' );
		$preamp_css->register_css_files();

		$trumbowyg_css = new Css( 'trumbowyg', PREAMP_URL . 'vendor/wiredot/preamp/src/bower/trumbowyg/dist/ui/trumbowyg.css', 'admin' );
		$trumbowyg_css->register_css_files();
		$trumbowyg_table_css = new Css( 'trumbowyg_table', PREAMP_URL . 'vendor/wiredot/preamp/src/bower/trumbowyg/dist/plugins/table/ui/trumbowyg.table.css', 'admin' );
		$trumbowyg_table_css->register_css_files();
	}

	public function init_admin_js() {
		$preamp_js = new Js( 'admin', 'preamp', PREAMP_URL . 'vendor/wiredot/preamp/assets/js/preamp.js', 'admin' );
		$preamp_js->register_js_files();

		$trumbowyg_js = new Js( 'admin', 'trumbowyg', PREAMP_URL . 'vendor/wiredot/preamp/src/bower/trumbowyg/dist/trumbowyg.js', 'admin' );
		$trumbowyg_js->register_js_files();

		$trumbowyg_table_js = new Js( 'admin', 'trumbowyg-table', PREAMP_URL . 'vendor/wiredot/preamp/src/bower/trumbowyg/dist/plugins/table/trumbowyg.table.js', 'admin' );
		$trumbowyg_table_js->register_js_files();
	}

}
