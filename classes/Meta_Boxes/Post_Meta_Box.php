<?php

namespace Wiredot\Preamp\Meta_Boxes;

use Wiredot\Preamp\Twig;
use Wiredot\Preamp\Form\Row;
use Wiredot\Preamp\Utilities;
use Wiredot\Preamp\Form\Row_Multilingual;
use Wiredot\Preamp\Languages;

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

		if ( isset( $this->meta_box['condition'] ) && $this->meta_box['condition'] ) {
			$classes[] = 'preamp-condition preamp-condition-active';

			if ( is_array( $this->meta_box['condition'] ) ) {
				foreach ( $this->meta_box['condition'] as $key => $values ) {
					$classes[] = 'preamp-condition-' . $key;

					if ( is_array( $values ) ) {
						foreach ( $values as $value ) {
							$classes[] = 'preamp-condition-' . $key . '-' . $value;
						}
					} else {
						$classes[] = 'preamp-condition-' . $key . '-' . $values;
					}
				}
			}
		}

		return $classes;
	}

	public function add_meta_box_content( $post, $meta_box ) {
		$this->meta_box = apply_filters( 'preamp_meta_box_post-' . $this->meta_box_id, $this->meta_box );
		if ( is_array( $this->meta_box['fields'] ) ) {

			$rows = wp_nonce_field( 'preamp-mb_' . $this->meta_box_id . '_nonce', 'preamp-mb_' . $this->meta_box_id . '_nonce', false, false );

			foreach ( $this->meta_box['fields'] as $key => $meta_box_field ) {
				if ( Languages::has_languages() && isset( $meta_box_field['translate'] ) && $meta_box_field['translate'] ) {
					$values = $this->get_multilingual_values( $post->ID, $key );
					$row = new Row_Multilingual( $key, $key, $meta_box_field, $values );
				} else {
					$value = get_post_meta( $post->ID, $key, true );
					$row = new Row( $key, $key, $meta_box_field, $value );
				}
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

	public function get_multilingual_values( $post_id, $key ) {
		$languages = Languages::get_languages();
		$values = array();
		foreach ( $languages as $language_id => $language ) {
			$values[ $language_id ] = get_post_meta( $post_id, $key . $language['postmeta_suffix'], true );
		}

		return $values;
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
			if ( isset( $field['translate'] ) && $field['translate'] ) {
				$languages = Languages::get_languages();
				foreach ( $languages as $language_id => $language ) {
					$this->save_meta_box_field_by_type( $field['type'], $post_id, $meta_key . $language['postmeta_suffix'] );
				}
			} else {
				$this->save_meta_box_field_by_type( $field['type'], $post_id, $meta_key );
			}
		}
	}

	public function save_meta_box_field_by_type( $type, $post_id, $meta_key ) {
		switch ( $type ) {
			case 'upload':
				$this->save_upload_field( $post_id, $meta_key );
				break;
			default:
				$this->save_meta_box_field( $post_id, $meta_key, $type );
				break;
		}
	}

	public function save_meta_box_field( $post_id, $meta_key, $field_type ) {
		if ( isset( $_POST[ $meta_key ] ) ) {
			$value = $_POST[ $meta_key ];

			switch ( $field_type ) {
				default:
					if ( is_array( $value ) ) {
						$sanitized_value = Utilities::array_map_r( 'sanitize_text_field', $value );
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
