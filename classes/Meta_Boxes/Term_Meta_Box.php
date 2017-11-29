<?php

namespace Wiredot\Preamp\Meta_Boxes;

use Wiredot\Preamp\Fields\Field_Factory;
use Wiredot\Preamp\Twig;
use Wiredot\Preamp\Form\Row;
use Wiredot\Preamp\Form\Label;

class Term_Meta_Box extends Meta_Box {

	public function __construct( $meta_box_id, $meta_box ) {
		$this->meta_box_id = $meta_box_id;
		$this->meta_box = $meta_box;

		// save meta boxes
		if ( is_array( $meta_box['taxonomy'] ) ) {
			foreach ( $meta_box['taxonomy'] as $taxonomy ) {
				add_action( $taxonomy . '_add_form_fields', array( $this, 'add_form_fields' ), 10, 3 );
			}
		} else {
			add_action( $meta_box['taxonomy'] . '_add_form_fields', array( $this, 'add_form_fields' ), 10, 3 );
			add_action( $meta_box['taxonomy'] . '_edit_form_fields', array( $this, 'edit_form_fields' ), 10, 2 );
		}
	}

	public function add_form_fields( $taxonomy ) {
		if ( is_array( $this->meta_box['fields'] ) ) {
			$fields = '';
			// $fields = wp_nonce_field( 'preamp-tmb_' . $this->meta_box_id . '_nonce', 'preamp-tmb_' . $this->meta_box_id . '_nonce', false, false );

			foreach ( $this->meta_box['fields'] as $key => $meta_box_field ) {
				$row = new Row( $key, $meta_box_field );
				$fields .= $row->get_row();
			}

			$Twig = new Twig;
			echo $Twig->twig->render(
				'forms/meta_box.twig',
				array(
					'fields' => $fields,
				)
			);
		}
	}

	public function edit_form_fields( $term, $taxonomy ) {
		if ( is_array( $this->meta_box['fields'] ) ) {
			$fields = wp_nonce_field( 'preamp-tmb_' . $this->meta_box_id . '_nonce', 'preamp-tmb_' . $this->meta_box_id . '_nonce', false, false );

			foreach ( $this->meta_box['fields'] as $key => $meta_box_field ) {
				$value = get_term_meta( $term->term_id, $key, true );
				$row = new Row( $key, $meta_box_field, $value );
				$fields .= $row->get_row();
			}

			$Twig = new Twig;
			echo $Twig->twig->render(
				'forms/meta_box.twig',
				array(
					'fields' => $fields,
				)
			);
		}
	}
}
