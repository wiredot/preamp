<?php

namespace Wiredot\Preamp\Admin;

use Wiredot\Preamp\Image;
use Wiredot\Preamp\Config;

class Custom_Columns {

	private $columns;
	private $post_type;
	private $meta_boxes;

	public function __construct( $post_type, $columns ) {
		$this->post_type = $post_type;
		$this->columns = $columns;

		if ( ! isset( $_GET['post_type'] ) || $_GET['post_type'] != $post_type ) {
			return;
		}

		$config = Config::get_config( 'meta_box' );
		if ( isset( $config['post'] ) ) {
			$this->meta_boxes = $config['post'];
		}

		add_filter( 'manage_edit-' . $post_type . '_columns', array( $this, 'modify_title_bar' ) );

		if ( is_post_type_hierarchical( $post_type ) ) {
			add_action( 'manage_pages_custom_column', array( $this, 'modify_columns' ), 10, 2 );
		} else {
			add_action( 'manage_posts_custom_column', array( $this, 'modify_columns' ), 10, 2 );
		}
	}

	public function modify_title_bar( $columns ) {
		$new_columns = array();

		if ( isset( $columns['cb'] ) ) {
			$new_columns['cb'] = $columns['cb'];
		}

		foreach ( $this->columns as $key => $column ) {

			switch ( $column ) {
				case 'title':
				case 'date':
				case 'author':
					$column_name = ucfirst( $column );
					break;
				case 'ID':
					$column_name = $column;
					break;
				case 'menu_order':
					$column_name = 'Order';
					break;
				case 'featured_image':
					$column_name = 'Image';
					break;
				case (preg_match( '/taxonomy:(.*)/', $column, $matches ) ? true : false):
					$taxonomy = get_taxonomy( $matches[1] );
					$column_name = $taxonomy->labels->name;
					break;
				default:
					$label = $this->get_label( $column );
					if ( $label ) {
						$column_name = $label;
					} else {
						$column_name = $column;
					}
					break;
			}
			$new_columns[ $column ] = $column_name;
		}

		return $new_columns;
	}

	public function modify_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'ID':
				echo $post_id;
				break;
			case 'featured_image':
				$post_thumbnail_id = get_post_thumbnail_id( $post_id );

				if ( $post_thumbnail_id ) {
					$params = array(
						'w' => 50,
						'h' => 50,
						'zc' => 1,
						'q' => 70,
					);
					$Image = new Image( $post_thumbnail_id, $params );
					$Image->show_image();
				}
				break;
			default:
				echo $this->get_value( $post_id, $column );
				break;
		}
	}

	public function get_label( $column ) {
		foreach ( $this->meta_boxes as $meta_box ) {
			if ( isset( $meta_box['fields'][ $column ]['label'] ) ) {
				return $meta_box['fields'][ $column ]['label'];
			}
		}

		return null;
	}

	public function get_value( $post_id, $column ) {
		$value = get_post_meta( $post_id, $column, true );

		foreach ( $this->meta_boxes as $meta_box ) {
			if ( isset( $meta_box['fields'][ $column ]['type'] ) ) {
				switch ( $meta_box['fields'][ $column ]['type'] ) {
					case 'select':
						if ( isset( $meta_box['fields'][ $column ]['options'][ $value ] ) ) {
							return $meta_box['fields'][ $column ]['options'][ $value ];
						}
						break;
				}
			}
		}

		return $value;
	}
}
