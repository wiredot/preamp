<?php

namespace Wiredot\Preamp\Meta_Boxes;

use Wiredot\Preamp\Fields\Field_Factory;

class Post_Meta_Box extends Meta_Box {
	
	private $meta_box;
	private $meta_box_id;

	function __construct($meta_box_id, $meta_box) {
		$this->meta_box_id = $meta_box_id;
		$this->meta_box = $meta_box;

		add_action('admin_init', array($this, 'add_meta_box'));
	}

	public function add_meta_box() {
		add_meta_box(
			$this->meta_box_id, 
			$this->meta_box['name'], 
			array($this, 'add_meta_box_content'), 
			$this->meta_box['post_type'], 
			$this->meta_box['context'],
			$this->meta_box['priority'], 
			$this->meta_box
		);
	}

	public function add_meta_box_content() {
		if (is_array($this->meta_box['fields'])) {
			foreach ($this->meta_box['fields'] as $key => $meta_box_field) {
				if ( ! isset($meta_box_field['attributes']) ) {
					$meta_box_field['attributes'] = array();
				}

				if ( ! isset($meta_box_field['options']) ) {
					$meta_box_field['options'] = array();
				}

				$field = new Field_Factory($meta_box_field['type'], $key, $key, 'asd', $meta_box_field);
				$field->showField();
			}
		}
	}
}