<?php

namespace Wiredot\Preamp\Fields;

use \WP_Query;

class Post extends Field {

	protected $type = 'select';

	public function __construct( $label, $name, $id, $value, $attributes, $options = array(), $labels = array(), $arguments = array() ) {
		$this->label = $label;
		$this->name = $name;
		$this->id = $id;
		$this->value = $value;
		$this->attributes = $attributes;
		$this->labels = $labels;
		$this->arguments = $arguments;
		$this->options = $this->get_options();
	}

	public function get_options() {
		$default_arguments = array(
			'posts_per_page' => -1,
			'post_type' => 'page',
		);

		$arguments = array_merge( $default_arguments, $this->arguments );

		$loop_links = new WP_Query( $arguments );
		$posts = $loop_links->posts;
		if ( ! $posts ) {
			return null;
		}

		$options = array();

		$options[0] = '-- select --';
		foreach ( $posts as $post ) {
			$options[ $post->ID ] = $post->post_title;
		}

		return $options;
	}
}
