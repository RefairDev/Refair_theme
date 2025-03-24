<?php
/**
 * Scripts use on back-office to interact with images controls.
 *
 * @package Pixelscodex
 *
 * @todo Move all this to js file
 */

$theme_name = 'refair';
?>
<script>

jQuery(document).ready(function ($) {
	// Instantiates the variable that holds the media library frame.
	var meta_image_frame;
	// Runs when the image button is clicked.
	setBrowseImg<?php echo esc_attr( $theme_name ); ?>Handling();
});

function setBrowseImg<?php echo esc_attr( $theme_name ); ?>Handling(){
	jQuery('.browse-image').off("click");
	jQuery('.browse-image').click(handleBrowseMetaImageEvent);
  
	jQuery('.remove-media').off("click");
	jQuery('.remove-media').click(function (e) {
	let nodeAncestors          = jQuery(this).parentsUntil('.meta-content');
	let inputsRoot             = nodeAncestors[nodeAncestors.length-1].parentElement;
	let meta_image_url         = inputsRoot.querySelector('.media-url');
	let meta_image_id          = inputsRoot.querySelector('.media-id');
	let meta_image_url_display = inputsRoot.querySelector('.media-url-display');
	meta_image_url_display.innerHTML = "";
	meta_image_url.value = "#";
	meta_image_id.value = "0";
	inputsRoot.querySelector('img').setAttribute('src',"https://via.placeholder.com/250x150.jpg?text=Aucune+image");
	e.target.classList.add("hidden");
	
	});
}
function handleBrowseMetaImageEvent(e){
	e.preventDefault();
	let nodeAncestors          = jQuery(this).parentsUntil('.meta-content');
	let inputsRoot             = nodeAncestors[nodeAncestors.length-1].parentElement;
	browseMetaImage(inputsRoot);
}

function handleOnClickBrowseMetaImage(browseBtnId){
	let nodeAncestors          = jQuery('#' + browseBtnId).parentsUntil('.meta-content');
	let inputsRoot             = nodeAncestors[nodeAncestors.length-1].parentElement;
	browseMetaImage(inputsRoot);
}

function browseMetaImage(inputsRoot){
	// Get preview pane
	var meta_image_preview     = jQuery(inputsRoot.querySelector('.image-preview'));            
	var meta_image             = jQuery(inputsRoot.querySelector('.media-url'));
	var meta_image_url_display = jQuery(inputsRoot.querySelector('.media-url-display'));
	var meta_id                = jQuery(inputsRoot.querySelector('.media-id'));
	var meta_image_url_reduced = jQuery(inputsRoot.parentNode.querySelector('.reduced-value'));
	var meta_image_remove      = jQuery(inputsRoot.querySelector('.remove-media'));
  
	// Sets up the media library frame
	var meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
	title: meta_image.title,
	
	});
	// Runs when an image is selected.
	meta_image_frame.on('select', function () {
	// Grabs the attachment selection and creates a JSON representation of the model.
	var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
	// Sends the attachment URL to our custom image input field.
	meta_image.val(media_attachment.url);
	meta_image_url_display.text(media_attachment.url);
	meta_id.val(media_attachment.id);
	
	meta_image_preview.html('<img style="max-width: 250px; box-shadow: 1px 2px 4px 0 #324664" />');
	
	meta_image_preview.children('img').attr('src', media_attachment.url);
	meta_image_remove.removeClass("hidden");
	//meta_image_preview.children('video')[0].load();
	});
	// Opens the media library frame.
	meta_image_frame.open();
}

function handleRemoveMetaImageEvent(e){
	e.preventDefault();
	let nodeAncestors          = jQuery(this).parentsUntil('.meta-content');
	let inputsRoot             = nodeAncestors[nodeAncestors.length-1].parentElement;
	let btnNode = e.target;
	removeMetaImage(inputsRoot, btnNode);
}

function handleOnClickRemoveMetaImage(removeBtnId){
	let nodeAncestors          = jQuery('#' + removeBtnId).parentsUntil('.meta-content');
	let inputsRoot             = nodeAncestors[nodeAncestors.length-1].parentElement;
	let btnNode = document.querySelector('#' + removeBtnId);
	removeMetaImage(inputsRoot, btnNode);
}

function removeMetaImage(inputsRoot, btnNode){
	let meta_image_url         = inputsRoot.querySelector('.media-url');
	let meta_image_id          = inputsRoot.querySelector('.media-id');
	let meta_image_url_display = inputsRoot.querySelector('.media-url-display');
	meta_image_url_display.innerHTML = "";
	meta_image_url.value = "#";
	meta_image_id.value = "0";
	inputsRoot.querySelector('img').setAttribute('src',"https://via.placeholder.com/250x150.jpg?text=Aucune+image");
	btnNode.classList.add("hidden");
}

</script>