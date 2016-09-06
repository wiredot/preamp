<?php

namespace Wiredot\Preamp\Meta_Boxes;

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
		
	}
}