<?php

namespace Wiredot\Preamp;

use phpThumb;

class Image {

	private $image_id;
	
	private $params = array(
		'w' => 100,
		'h' => null,
		'q' => 95,
		'zc' => 1
	);

	private $attributes = array(
		'class' => null,
		'alt' => null,
		'title' => null,
		'idtag' => null
	);

	private $metadata;

	public function __construct($image_id, $params = array(), $attributes = array()) {
		$this->image_id = $image_id;
		$this->params = array_merge($this->params, $params);
		$this->attributes = array_merge($this->attributes, $attributes);
		$this->metadata = $this->get_metadata($image_id);
	}

	public function show_image() {
		echo $this->get_image();
	}

	public function get_image() {
		$image_url = $this->get_url();

		if ( ! $image_url ) {
			return null;
		}

		$img = '<img src="'; 
		$img.= $this->metadata['upload_url'].$this->metadata['filename'];
		$img.= '"';

		if ($this->attributes['class']) {
			$img.= ' class="'.$this->attributes['class'].'"';
		}
		
		if ($this->attributes['idtag']) {
			$img.= ' id="'.$this->attributes['idtag'].'"';
		}
		
		if ($this->attributes['alt']) {
			$img.= ' alt="'.$this->attributes['alt'].'"';
		}

		if ($this->attributes['title']) {
			$img.= ' title="'.$this->attributes['title'].'"';
		}
		
		if ($this->params['w']) {
			$img.= ' width="'.$this->params['w'].'"';
		}

		if (isset($this->params['h']) && $this->params['h']) {
			$img.= ' height="'.$this->params['h'].'"';
		}
		
		$img.= '>';

		return $img;

	}

	public function show_url() {
		echo $this->get_url();
	}

	public function get_url() {
		if ( is_null($this->metadata) ) {
			return null;
		}

		if ( ! $this->metadata['cached'] ) {
			$image = $this->render();

			if ( ! $image ) {
				return null;
			}
		}

		return $this->metadata['upload_url'].$this->metadata['filename'];
	}

	public function get_metadata($image_id) {
		$wp_metadata = wp_get_attachment_metadata( $image_id );
		if ( ! $wp_metadata ) {
			return null;
		}

		$metadata = array();
		$wp_upload_dir = wp_upload_dir();

		$metadata['upload_url'] = $wp_upload_dir['baseurl'].'/'.dirname($wp_metadata['file']).'/';
		$metadata['upload_dir'] = $wp_upload_dir['basedir'].'/'.dirname($wp_metadata['file']).'/';
		$metadata['old_filename'] = basename($wp_metadata['file']);
		$metadata['old_extension'] = pathinfo($metadata['old_filename'], PATHINFO_EXTENSION);
		$metadata['old_name'] = basename($metadata['old_filename'], '.' . $metadata['old_extension']);
		$metadata['name'] = $this->get_filename($metadata['old_name'], $this->params);
		$metadata['extension'] = $metadata['old_extension'];
		$metadata['filename'] = $metadata['name'].'.'.$metadata['old_extension'];
		
		if (file_exists($metadata['upload_dir'].$metadata['filename'])) {
			$metadata['cached'] = true;
		} else {
			$metadata['cached'] = false;
		}

		return $metadata;
	}

	private function get_filename($old_name, $params) {
		$name = $old_name;
		if (isset($params['w'])) {
			$name.= '_'.$params['w'];
		}

		if (isset($params['h'])) {
			$name.= '_'.$params['h'];
		}

		return $name;
	}

	private function render() {
		$phpThumb = new phpThumb();

		$phpThumb->setSourceFilename( $this->metadata['upload_dir'] . $this->metadata['old_filename'] );

		foreach ($this->params as $key => $param) {
			$phpThumb->setParameter($key, $param);
		}

		if ($phpThumb->GenerateThumbnail()) {
			if ($phpThumb->RenderToFile($this->metadata['upload_dir'].$this->metadata['filename'])) {

				$phpThumb->purgeTempFiles();

				return 1;
			}
		}

		return null;
	}
}

function wppg_image() {
	echo 'sd';
}