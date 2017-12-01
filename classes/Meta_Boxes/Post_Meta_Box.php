<?php

namespace Wiredot\Preamp\Meta_Boxes;

use Wiredot\Preamp\Twig;
use Wiredot\Preamp\Form\Row;

class Post_Meta_Box extends Meta_Box {

	function __construct( $meta_box_id, $meta_box ) {
		$this->meta_box_id = $meta_box_id;
		$this->meta_box = $meta_box;

		// add meta boxes
		add_action( 'admin_init', array( $this, 'add_meta_box' ) );

		// save meta boxes
		if ( is_array( $meta_box['post_type'] ) ) {
			foreach ( $meta_box['post_type'] as $post_type ) {
				add_action( 'save_post_' . $post_type, array( $this, 'save_meta_box' ), 10, 3 );
			}
		} else {
			add_action( 'save_post_' . $meta_box['post_type'], array( $this, 'save_meta_box' ), 10, 3 );
		}
	}

	public function add_meta_box() {
		add_meta_box(
			$this->meta_box_id,
			$this->meta_box['name'],
			array( $this, 'add_meta_box_content' ),
			$this->meta_box['post_type'],
			$this->meta_box['context'],
			$this->meta_box['priority'],
			$this->meta_box
		);

		if ( is_array( $this->meta_box['post_type'] ) ) {
			foreach ( $this->meta_box['post_type'] as $post_type ) {
				add_filter( 'postbox_classes_' . $post_type . '_' . $this->meta_box_id, array( $this, 'add_my_meta_box_classes' ) );
			}
		} else {
			add_filter( 'postbox_classes_' . $this->meta_box['post_type'] . '_' . $this->meta_box_id, array( $this, 'add_my_meta_box_classes' ) );
		}
	}

	public function add_my_meta_box_classes( $classes = array() ) {
		if ( isset( $this->meta_box['template'] ) && $this->meta_box['template'] ) {
			$classes[] = 'preamp-template';

			if ( is_array( $this->meta_box['template'] ) ) {
				foreach ( $this->meta_box['template'] as $template ) {
					$classes[] = 'preamp-template-' . $template;
				}
			} else {
				$classes[] = 'preamp-template-' . $this->meta_box['template'];
			}
		}
		return $classes;
	}

	public function add_meta_box_content( $post, $meta_box ) {
		if ( is_array( $this->meta_box['fields'] ) ) {

			$rows = wp_nonce_field( 'preamp-mb_' . $this->meta_box_id . '_nonce', 'preamp-mb_' . $this->meta_box_id . '_nonce', false, false );

			foreach ( $this->meta_box['fields'] as $key => $meta_box_field ) {
				$value = get_post_meta( $post->ID, $key, true );
				$row = new Row( $key, $key, $meta_box_field, $value );
				$rows .= $row->get_row();
			}

			$Twig = new Twig;
			echo $Twig->twig->render(
				'forms/meta_box.twig',
				array(
					'rows' => $rows,
				)
			);
		}
	}

	public function save_meta_box( $post_id, $post, $update ) {
		// return if autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! isset( $_POST[ 'preamp-mb_' . $this->meta_box_id . '_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'preamp-mb_' . $this->meta_box_id . '_nonce' ], 'preamp-mb_' . $this->meta_box_id . '_nonce' ) ) {
			return;
		}

		foreach ( $this->meta_box['fields'] as $meta_key => $field ) {
			switch ( $field['type'] ) {
				case 'upload':
					$this->save_upload_field( $post_id, $meta_key );
					break;
				default:
					$this->save_meta_box_field( $post_id, $meta_key, $field['type'] );
					break;
			}
		}
	}

	public function save_meta_box_field( $post_id, $meta_key, $field_type ) {
		if ( isset( $_POST[ $meta_key ] ) ) {
			$value = $_POST[ $meta_key ];

			switch ( $field_type ) {
				default:
					if ( is_array( $value ) ) {
						$sanitized_value = $this->array_map_r( 'sanitize_text_field', $value );
					} else {
						$sanitized_value = sanitize_text_field( $value );
					}
					break;
			}
			// save data
			update_post_meta( $post_id, $meta_key, $sanitized_value );
		} else {
			// delete data
			delete_post_meta( $post_id, $meta_key );
		}
	}

	public function array_map_r( $function, $array ) {
		$new_array = array();

		foreach ( $array as $key => $value ) {
			$new_array[ $key ] = ( is_array( $value ) ? $this->array_map_r( $function, $value ) : ( is_array( $function ) ? call_user_func_array( $function, $value ) : $function( $value ) ) );
		}

		return $new_array;
	}

	public function save_upload_field( $post_id, $meta_key ) {
		if ( isset( $_POST[ $meta_key ] ) && is_array( $_POST[ $meta_key ] ) ) {
			// validate file ids
			$files = array_map( 'intval', $_POST[ $meta_key ] );
		} else {
			return;
		}

		if ( isset( $_POST[ $meta_key . '_title' ] ) && is_array( $_POST[ $meta_key . '_title' ] ) ) {
			$file_title = array_map( 'sanitize_text_field', $_POST[ $meta_key . '_title' ] );
		}

		if ( isset( $_POST[ $meta_key . '_caption' ] ) && is_array( $_POST[ $meta_key . '_caption' ] ) ) {
			$file_caption = array_map( 'sanitize_text_field', $_POST[ $meta_key . '_caption' ] );
		}

		if ( isset( $_POST[ $meta_key . '_alt' ] ) && is_array( $_POST[ $meta_key . '_alt' ] ) ) {
			$file_alt = array_map( 'sanitize_text_field', $_POST[ $meta_key . '_alt' ] );
		}

		foreach ( $files as $key => $file_id ) {
			$title = '';
			$caption = '';
			$alt = '';

			if ( isset( $file_title[ $key ] ) ) {
				$title = $file_title[ $key ];
			}

			if ( isset( $file_caption[ $key ] ) ) {
				$caption = $file_caption[ $key ];
			}

			if ( isset( $file_alt[ $key ] ) ) {
				$alt = $file_alt[ $key ];
			}

			$this->update_image_data( $file_id, $title, $caption, $alt );
		}

		update_post_meta( $post_id, $meta_key, $files );
	}

	public function update_image_data( $post_id, $title, $caption, $alt = '' ) {
		global $wpdb;

		$wpdb->update(
			$wpdb->posts,
			array(
				'post_title' => $title,
				'post_excerpt' => $caption,
			),
			array(
				'ID' => $post_id,
			)
		);

		if ( $alt ) {
			update_post_meta( $post_id, '_wp_attachment_image_alt', $alt );
		} else {
			delete_post_meta( $post_id, '_wp_attachment_image_alt' );
		}
	}
}
