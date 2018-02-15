jQuery(document).ready(function($){
	// preampInitUploadButton($);
	// preampInitSortable($);
	// preampInitRemoveButton($);
	preampInitGroups();
	preampPageTemplateInit();
	preampConditionInit();
	preampTabsInit();
	preampOptionTabsInit();
	preampSettingsFormInit();
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

		var dataGroupItem = newItem.attr('data-groupitem');
		var newDataGroupItem = dataGroupItem.replace('%%', nextKey);
		newItem.attr('data-groupitem', newDataGroupItem);

		newItem.find('input, select, textarea, .preamp-tab, label').each(function(index, el) {
			var id = jQuery(this).attr('id');
			if ( typeof(id) != 'undefined' ) {
				var newId = id.replace('%%', nextKey);
				jQuery(this).attr('id', newId);
			}
			
			var name = jQuery(this).attr('name');
			if (name) {
				var newName = name.replace('preamp_new_','').replace('%%', nextKey);
				jQuery(this).attr('name', newName);
			}

			var data = jQuery(this).attr('data-id');
			if (data) {
				var newData = data.replace('preamp_new_','').replace('%%', nextKey);
				jQuery(this).attr('data-id', newData);
			}

			var oldFor = jQuery(this).attr('for');
			if (oldFor) {
				var newFor = oldFor.replace('preamp_new_','').replace('%%', nextKey);
				jQuery(this).attr('for', newFor);
			}
		});
		preampConditionBind();
		preampInitGroups();
		preampTabsInit();
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
	preampConditionBind();
	preampConditionCheckFields();
}

function preampConditionBind() {
	jQuery('.preamp-select, .preamp-checkbox').unbind('change');

	jQuery('.preamp-select, .preamp-checkbox').change(function(event) {
		preampConditionCheckFields();
	});
}

function preampConditionCheckFields() {
	// for all conditional fields
	jQuery('.preamp-condition').each(function(index, el) {
		// check all selectboxes
		var ok = preampConditionCheckSelectboxes( el );
		if ( ! ok ) {
			// check all checkboxes
			var ok = preampConditionCheckCheckboxes( el );
		}

		if (ok) {
			jQuery(el).removeClass('preamp-condition-active').addClass('preamp-condition-disabled');
		} else {
			jQuery(el).removeClass('preamp-condition-disabled').addClass('preamp-condition-active');
			if ( jQuery(el).find('select').length ) {
				jQuery(el).find('select').prop('selectedIndex',0);
			}
		}
	});
}

function preampConditionCheckSelectboxes( field ) {
	var ok = false;
	jQuery('.preamp-select').each(function(index, el) {
		var id = jQuery(this).attr('id');
		if ( id.indexOf('%%') === -1 ) {

			// if select box is part of a group
			var parent_li = jQuery(this).parents('li');
			if ( jQuery(parent_li).hasClass('wpep-group-item') ) {
				var attr = jQuery(this).parents('.wpep-group-item').attr('data-groupitem');
				id = id.replace( attr + '_', '' );
				jQuery(field).addClass('class_name');
				// check if this selectbox is in the same parent element as field
				if ( jQuery(field).parents('.wpep-group-item').attr('data-groupitem') == attr ) {
					var value = jQuery(this).val();
				} else {
					var value = '';
				}
			} else {
				var value = jQuery(this).val();
			}
			if (Array.isArray(value)) {
				jQuery(value).each(function(index, el) {
					if (preamConditionFieldHasClass(field, 'preamp-condition-' + id + '-' + el )) {
						ok = true;
					}
				});
			} else {
				if (preamConditionFieldHasClass(field, 'preamp-condition-' + id + '-' + value )) {
					ok = true;
				}
			}
		}
	});

	return ok;
}

function preampConditionCheckCheckboxes( field ) {
	var ok = false;
	jQuery('.preamp-checkbox').each(function(index, el) {
		var id = jQuery(this).attr('id');

		if ( id.indexOf('%%') === -1 && jQuery(this).is(':checked')) {
			if (preamConditionFieldHasClass(field, 'preamp-condition-' + id )) {
				ok = true;
			}
		}
	});

	return ok;
}

function preamConditionFieldHasClass( field, class_name ) {
	if ( jQuery(field).hasClass(class_name) ) {
		return true;
	}
	
	return false;
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

function preampOptionTabsInit() {
	jQuery('.preamp-option-tabs a').unbind('click');

	jQuery('.preamp-option-tabs a').click(function(event){
		event.preventDefault();
		this.blur();
		jQuery(this).parent().children('a').removeClass('nav-tab-active');
		jQuery(this).addClass('nav-tab-active');
		jQuery('.preamp-option-tab').removeClass('active');
		
		var id = jQuery(this).attr('href');
		jQuery(id).addClass('active');
	});
}

function preampSettingsFormInit() {
	jQuery('.preamp-button').each(function(index, el) {
		
		var text = jQuery(this).text();
		console.log(text);
		jQuery(this).html('<span>' + text + '</span><div class="lds-spin" style="100%;height:100%"><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div></div>');
	});
	jQuery('.preamp-button').click(function(event) {
		this.blur();
	});
	jQuery('.preamp-settings-form').submit(function(event) {
		event.preventDefault();
		var button = jQuery(this).find('.preamp-button');
		button.addClass('loading');

		var formAction = jQuery(this).attr('action');
		var formValues = jQuery(this).serialize();

		jQuery.ajax({
            type: "POST",
            url: formAction,
            data: formValues,

            success: function(response) {
				console.log(response);
				button.removeClass('loading');
				jQuery('.preamp-form-message').text(response.message).fadeTo(400, 1);
				window.setTimeout(function(){
					console.log('go');
					jQuery('.preamp-form-message').fadeTo(400, 0, function(){
						jQuery('.preamp-form-message').text('');
					});
				}, 3000);
            },
            error: function() {
            	console.log('error');
            }
        });
	});
}

//# sourceMappingURL=preamp.js.map
