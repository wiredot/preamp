<?php

namespace Wiredot\Preamp\Admin;

use Wiredot\Preamp\Image;

class Custom_Columns {

	private $columns;

	public function __construct($columns) {
		$this->columns = $columns;
	}

	public function set_columns() {
		if (isset($_GET['post_type']) && $_GET['post_type'] == $this->columns['post_type']) {
			add_filter('manage_edit-' . $this->columns['post_type'] . '_columns', array($this, 'modify_title_bar'));
		}

		add_action('manage_posts_custom_column', array($this, 'modify_columns'), 10, 2);
		add_action('manage_pages_custom_column', array($this, 'modify_columns'), 10, 2);
	}

	public function modify_title_bar($columns) {
		$new_columns = array();

		if (isset($columns['cb'])) {
			$new_columns['cb'] = $columns['cb'];
		}

		foreach ($this->columns['columns'] as $key => $column) {

			switch($column) {
				case 'title':
				case 'date':
				case 'author':
					$column_name = ucfirst($column);
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
				case (preg_match('/taxonomy:(.*)/', $column, $matches) ? true : false) :
					$taxonomy = get_taxonomy($matches[1]);
					$column_name = $taxonomy->labels->name;
					break;
				default:
					$column_name = $column;
					break;
			}
			$new_columns[$column] = $column_name;
		}

		return $new_columns;
	}

	public function modify_columns($column, $post_id) {
		switch($column) {
			case 'featured_image':
				$post_thumbnail_id = get_post_thumbnail_id($post_id);
				
				if ($post_thumbnail_id) {
					$params = array(
						'w' => 50,
						'h' => 50,
						'zc' => 1,
						'q' => 70
					);
					$Image = new Image($post_thumbnail_id, $params);
					$Image->show_image();
				}
				break;
		}
	}
}