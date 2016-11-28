<?php

namespace Wiredot\Preamp\Meta_Boxes;

use Wiredot\Preamp\Fields\Field_Factory;
use Wiredot\Preamp\Twig;
use Wiredot\Preamp\Form\Row;
use Wiredot\Preamp\Form\Label;

class Post_Meta_Box extends Meta_Box {
	
	private $meta_box;
	private $meta_box_id;

	function __construct($meta_box_id, $meta_box) {
		$this->meta_box_id = $meta_box_id;
		$this->meta_box = $meta_box;

		// add meta boxes
		add_action('admin_init', array($this, 'add_meta_box'));

		// save meta boxes
		add_action('save_post_'.$meta_box['post_type'], array($this, 'save_meta_box'), 10, 3);
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

	public function add_meta_box_content($post, $meta_box) {
		if (is_array($this->meta_box['fields'])) {

			$fields = wp_nonce_field( 'preamp_mb_'.$this->meta_box_id.'_nonce', 'preamp_mb_'.$this->meta_box_id.'_nonce', false, false );
			
			foreach ($this->meta_box['fields'] as $key => $meta_box_field) {
				if ( ! isset($meta_box_field['attributes']) ) {
					$meta_box_field['attributes'] = array();
				}

				if ( ! isset($meta_box_field['options']) ) {
					$meta_box_field['options'] = array();
				}

				if ( ! isset($meta_box_field['labels']) ) {
					$meta_box_field['labels'] = array();
				}

				$value = get_post_meta( $post->ID, $key, true );

				$field = new Field_Factory($meta_box_field['type'], $meta_box_field['label'], $key, $key, $value, $meta_box_field['attributes'], $meta_box_field['options'], $meta_box_field['labels']);


				$row = new Row($field->get_field());
				$fields.= $row->get_row();

				// $label = new Label($key, $key);
				// $fields.= $label->getLabel();

				// $fields.= $field->get_field();
			}

			$Twig = new Twig;
			echo $Twig->twig->render('meta_box.html',
				array(
					'fields' => $fields
				)
			);
		}
	}

	public function save_meta_box($post_id, $post, $update) {
		// return if autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
			return;
		}
		if ( ! isset( $_POST['preamp_mb_'.$this->meta_box_id.'_nonce'] ) || ! wp_verify_nonce( $_POST['preamp_mb_'.$this->meta_box_id.'_nonce'], 'preamp_mb_'.$this->meta_box_id.'_nonce' ) ) {
			return;
		}

		foreach ($this->meta_box['fields'] as $key => $field) {
			switch ($field['type']) {
				default:
					$this->save_meta_box_field($post_id, $key, $field['type']);
					break;
			}
		}
	}

	public function save_meta_box_field($post_id, $key, $field_type) {
		if ( isset( $_POST[$key] ) ){
			$value = $_POST[$key];

			switch ($field_type) {
				default:
					if (is_array($value)) {
						$sanitized_value = array_map('sanitize_text_field', $value);
					} else {
						$sanitized_value = sanitize_text_field($value);
					}
					break;
			}

			// save data
			update_post_meta( $post_id, $key, $sanitized_value );
		} else {
			// delete data
			delete_post_meta( $post_id, $key );
		}
	}
}