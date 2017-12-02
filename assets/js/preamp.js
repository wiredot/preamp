jQuery(document).ready(function($){
	// preampInitUploadButton($);
	// preampInitSortable($);
	// preampInitRemoveButton($);
	preampInitGroups();
	preampPageTemplateInit();
	preampConditionInit();
	preampTabsInit();
});

function preampInitGroups() {
	preampInitGroupRemoveItemButton();
	preampInitGroupNewItemButton();
}

function preampInitGroupRemoveItemButton() {
	jQuery('.preamp-group-remove').unbind('click');
	jQuery('.preamp-group-remove').click(function(event) {
		event.preventDefault();
		jQuery(this).parents('li').eq(0).remove();
	});
}

function preampInitGroupNewItemButton() {
	jQuery('.preamp-group-add').unbind('click');
	jQuery('.preamp-group-add').click(function(event) {
		event.preventDefault();
		var newButton = jQuery(this);
		var group = newButton.prev('.preamp-group');
		var item = group.children('.preamp-new-group-item');
		var newItem = item.clone();
		var nextKey = jQuery(this).attr('data-next-key');
		newButton.attr('data-next-key', parseInt(nextKey) + 1);
		newItem.removeClass('preamp-new-group-item').insertBefore(item);
		newItem.find('input, select, textarea').each(function(index, el) {
			var id = jQuery(this).attr('id');
			if ( typeof(id) != 'undefined' ) {
				var newId = id.replace('%%', nextKey);
				jQuery(this).attr('id', newId);
				jQuery(this).parents('tr').eq(0).find('label').attr('for', newId);
			}
			var name = jQuery(this).attr('name');
			var newName = name.replace('preamp_new_','').replace('%%', nextKey);
			jQuery(this).attr('name', newName);
		});
		preampInitGroupRemoveItemButton();
	});
}

function preampInitUploadButton(id, name, attributes, label_button, label_title) {
	jQuery('#button-'+id).click(function(event) {
		event.preventDefault('clicked');
		preampInitUpload(id, name, attributes, label_button, label_title);
	});
}

function preampInitUpload(id, name, attributes, label_button, label_title) {
	if (attributes.filetype == 'file') {
		attributes.filetype = '';
	}
	
	var link_status = jQuery('#button-'+id).attr('disabled');

	// ignore clicks when button is disabled
	if (link_status == 'disabled') {
		return null;
	}

	var tgm_media_frame;
	
		// If the frame already exists, re-open it.
		if ( tgm_media_frame ) {
			tgm_media_frame.open();
			return;
		}

		/**
		 * The media frame doesn't exist let, so let's create it with some options.
		 *
		 * This options list is not exhaustive, so I encourage you to view the
		 * wp-includes/js/media-views.js file to see some of the other default
		 * options that can be utilized when creating your own custom media workflow.
		 */
		tgm_media_frame = wp.media.frames.tgm_media_frame = wp.media({
			/**
			 * We can pass in a custom class name to our frame, so we do
			 * it here to provide some extra context for styling our
			 * media workflow. This helps us to prevent overwriting styles
			 * for other media workflows.
			 */
			className: 'media-frame tgm-media-frame',

			/**
			 * When creating a new media workflow, we are given two types
			 * of frame workflows to chose from: 'select' or 'post'.
			 *
			 * The 'select' workflow is the default workflow, mainly beneficial
			 * for uses outside of a post or post type experience where a post ID
			 * is crucial.
			 *
			 * The 'post' workflow is tailored to screens where utilizing the
			 * current post ID is critical.
			 *
			 * Since we only want to upload an image, let's go with the 'select'
			 * frame option.
			 */
			frame: 'select',

			/**
			 * We can determine whether or not we want to allow users to be able
			 * to upload multiple files at one time by setting this parameter to
			 * true or false. It defaults to true, but we only want the user to
			 * upload one file, so let's set it to false.
			 */
			multiple: attributes.multiple,

			/**
			 * We can set a custom title for our media workflow. I've localized
			 * the script with the object 'tgm_nmp_media' that holds our
			 * localized stuff and such. Let's populate the title with our custom
			 * text.
			 */
			title: label_title,

			/**
			 * We can force what type of media to show when the user views his/her
			 * library. Since we are uploading an image, let's limit the view to
			 * images only.
			 */
			library: {
				type: attributes.filetype
			},

			/**
			 * Let's customize the button text. It defaults to 'Select', but we
			 * can customize it here to give us better context.
			 *
			 * We can also determine whether or not the modal requires a selection
			 * before the button is enabled. It requires a selection by default,
			 * and since this is the experience desired, let's keep it that way.
			 *
			 * By default, the toolbar generated by this frame fires a generic
			 * 'select' event when the button is clicked. We could declare our
			 * own events here, but the default event will work just fine.
			 */
			button: {
				text:  label_button
			}
		});

		/**
		 * ========================================================================
		 * EVENT BINDING
		 *
		 * This section before opening the modal window should be used to bind to
		 * any events where we want to customize the view. This includes binding
		 * to any custom events that may have been generated by us creating
		 * custom controller states and views.
		 *
		 * The events used below are not exhaustive, so I encourage you to again
		 * study the wp-includes/js/media-views.js file for a better feel of all
		 * the potential events you can attach to.
		 * ========================================================================
		 */

		/**
		 * We are now attaching to the default 'select' event and grabbing our
		 * selection data. Since the button requires a selection, we know that a
		 * selection will be available when the event is fired.
		 *
		 * All we are doing is grabbing the current state of the frame (which will
		 * be 'library' since that's the only area where we can make a selection),
		 * getting the selection, calling the 'first' method to pluck the first
		 * object from the string and then forcing a faux JSON representation of
		 * the model.
		 *
		 * When all is said and done, we are given absolutely everything we need to
		 * insert the data into our custom input field. Specifically, our
		 * media_attachment object will hold a key titled 'url' that we want to use.
		 */
		tgm_media_frame.on('select', function() {
		// 	// Grab our attachment selection and construct a JSON representation of the model.
			var media_attachment = tgm_media_frame.state().get('selection').toJSON();
			jQuery.each(media_attachment, function( key, value ) {
				console.log(value);
				var image = '';
				if ( typeof value.sizes.thumbnail !== 'undefined') {
					image = value.sizes.thumbnail.url;
				} else {
					image = value.url;
				}
				var template = preampNewFileTemplate(name, value.id, value.title, value.caption, value.alt, image, attributes);
				
				new_file = jQuery('#button-'+id).prev('.preamp-upload-container').append(template);

				if ( ! attributes.multiple ) {
					jQuery('#button-'+id).attr("disabled", "disabled");;
				}
			});

			preampInitRemoveButton(jQuery);
		});

		// Now that everything has been set, let's open up the frame.
		tgm_media_frame.open();
}

function preampNewFileTemplate(name, id, title, caption, alt, photo, attributes) {
	var template = 
	'<li>' +
	' 	<div class="preamp-thumbnail">' +
	' 		<img src="'+photo+'">' +
	' 	</div>';
	' 	<div class="preamp-details">';

	if (attributes.title) {
		template = template + '		<div class="preamp-mb-row">' +
		'			<label for="'+name+'_title_'+id+'">Title</label>' +
		'			<input type="text" id="'+name+'_title_'+id+'" name="'+name+'_title[]" value="'+title+'">' +
		'		</div>';
	}

	if (attributes.caption) {
		template = template + '		<div class="preamp-mb-row">' +
		'			<label for="'+name+'_caption_'+id+'">Caption</label>' +
		'			<input type="text" id="'+name+'_caption_'+id+'" name="'+name+'_caption[]" value="'+caption+'">' +
		'		</div>';
	}
	if (attributes.alt) {
		template = template + '		<div class="preamp-mb-row">' +
		'			<label for="'+name+'_alt_'+id+'">Alt Text</label>' +
		'			<input type="text" id="'+name+'_alt_'+id+'" name="'+name+'_alt[]" value="'+alt+'">' +
		'		</div>';
	}
	template = template + '	</div>' +
	'	<input type="hidden" name="'+name+'[]" value="'+id+'">' +
	'	<a href="#" class="button preamp-remove-file">Remove Photo</a>' +
	'</li>';

	return template;
}

function preampInitSortable($) {
	$('.preamp-upload-container').sortable();
}

function preampInitRemoveButton($) {
	$('.preamp-remove-file').unbind('click');
	$('.preamp-remove-file').click(function(event) {
		event.preventDefault();
		$(this).parent('li').slideUp(300, function(){
			jQuery(this).parent('.preamp-upload-container').next('button').removeAttr("disabled");
			$(this).remove();
		});
	});
}

function preampPageTemplateInit() {
	preampPageTemplateShow();

	jQuery('#page_template').change(function(event) {
		preampPageTemplateShow();
	});
}

function preampPageTemplateShow() {
	var template = jQuery('#page_template').val();

	jQuery('.preamp-template-disabled').addClass('preamp-template').removeClass('preamp-template-disabled');
	jQuery('.preamp-template-' + template ).addClass('preamp-template-disabled').removeClass('preamp-template');
}

function preampConditionInit() {
	var all_selects = preampConditionGetSelects();
	jQuery(all_selects).each(function(index, el) {
		jQuery('#' + el).change(function(event) {
			preampConditionFieldSet(this);
		});
		var select = jQuery('#' + this);
		preampConditionFieldSet(select);
	});
}

function preampConditionFieldSet(select) {
	var id = jQuery(select).attr('id');
	jQuery('.preamp-condition-' + id).each(function(index, el) {
		var field = jQuery(this);
		preampConditionCheck(field);
	});
}

function preampConditionGetSelects() {
	var selects = [];
	jQuery('.preamp-select').each(function(index, el) {
		var id = jQuery(this).attr('id');
		if ( id.indexOf('%%') === -1 && jQuery('.preamp-condition-' + id).length ) {
			selects.push(id);
		}
	});

	return selects;
}

function preampConditionGetSelectsField(field) {
	var selects = [];
	jQuery('.preamp-select').each(function(index, el) {
		var id = jQuery(this).attr('id');
		if ( jQuery(field).hasClass('preamp-condition-' + id) ) {
			selects.push(id);
		}
	});

	return selects;
}

function preampConditionCheck(field) {
	var ok = false;

	var selects = preampConditionGetSelectsField(field);

	jQuery(selects).each(function(index, el) {
		var condition_field = this;
		var value = jQuery('#' + condition_field).val();
		if (Array.isArray(value)) {
			jQuery(value).each(function(index, el) {
				if (preamConditionCheckField(field, condition_field, this)) {
					ok = true;
				}
			});
		} else {
			if (preamConditionCheckField(field, condition_field, value)) {
				ok = true;
			}
		}
	});

	if (ok) {
		jQuery(field).removeClass('preamp-condition').addClass('preamp-condition-disabled');
	} else {
		jQuery(field).removeClass('preamp-condition-disabled').addClass('preamp-condition');
		if ( jQuery(field).find('select').length ) {
			console.log('select');
			jQuery(field).find('select').prop('selectedIndex',0).trigger('change');
		}
	}
}

function preamConditionCheckField(field, condition_field, value) {
	var ok = false;
	if ( jQuery(field).hasClass('preamp-condition-' + condition_field + '-' + value )) {
		ok = true;
	}

	return ok;
}

function preampTabsInit() {
	jQuery('.preamp-tabs-menu label').unbind('click');

	jQuery('.preamp-tabs-menu label').click(function(event){
		// event.preventDefault();
		jQuery(this).parent().children('label').removeClass('active');
		jQuery(this).parents('.preamp-tabs').eq(0).find('.preamp-tab').removeClass('active');
		jQuery(this).addClass('active');
		
		var id = jQuery(this).attr('data-id');
		jQuery('#' + id).addClass('active');
	});
}

//# sourceMappingURL=preamp.js.map
