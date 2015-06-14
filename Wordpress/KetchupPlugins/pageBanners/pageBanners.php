<?php
/*
Plugin Name: 	Page Banners
Plugin URI: 	http://www.ketchup-marketing.co.uk
Description: 	Adds ability to add in revSlider Banners on the page with an additional mobile banner
Version: 		1.0
Author: 		John Simpson
Author URI: 	http://www.ketchup-marketing.co.uk
License: 		Ketchup Marketing
Comments:		
*/


add_action('admin_init','page_banner_meta_init');
function page_banner_meta_init() {
	add_meta_box( 'banner', 'Banner', 'banner_fields', 'page', 'side', 'high' );
	add_action('save_post','page_banner_meta_save');	// Add callback function to save any data
}

function banner_fields() {
	global $post;
?>
	Desktop Banner
	<select name="_gallery" style="width:100%;">
		<option>Please Select a Gallery</option>
		<?
			global $wpdb;
			$table_name = $wpdb->prefix . 'revslider_sliders';
			$sliders = $wpdb->get_results("SELECT id, title, alias FROM $table_name");
			foreach ($sliders as $sliderID) {
		?>
				<option value="<?= $sliderID->alias; ?>" <? if (get_post_meta($post->ID,'_gallery',TRUE) ==  $sliderID->alias) { echo "selected=selected"; } ?>  ><?= $sliderID->title; ?></option>
		<?
			}
		?>
	</select>
	<br /><br />
	Mobile Banner
	<select name="_galleryMobile" style="width:100%;">
		<option>Please Select a Gallery</option>
		<?
			global $wpdb;
			$table_name = $wpdb->prefix . 'revslider_sliders';
			$sliders2 = $wpdb->get_results("SELECT id, title, alias FROM $table_name");
			foreach ($sliders2 as $sliderID2) {
		?>
				<option value="<?= $sliderID2->alias; ?>" <? if (get_post_meta($post->ID,'_galleryMobile',TRUE) ==  $sliderID2->alias) { echo "selected=selected"; } ?>  ><?= $sliderID2->title; ?></option>
		<?
			}
		?>
	</select>
<?
	echo '<input type="hidden" name="meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';		// create for validation
}

// Save everything
function page_banner_meta_save($post_id) {
	// Do Not Edit
	if (!isset($_POST['meta_noncename']) || !wp_verify_nonce($_POST['meta_noncename'], __FILE__)) { return $post_id; }
	if ('post' == $_POST['post_type']) { if (!current_user_can('edit_post', $post_id)) { return $post_id; } } elseif (!current_user_can('edit_page', $post_id)) { return $post_id; }
	if (defined('DOING_AUTOSAVE') == DOING_AUTOSAVE) { return $post_id; }
	// ------------------ //
	
	// Banner 
	if(isset($_POST['_gallery'])) 				{ update_post_meta($post_id, '_gallery', $_POST['_gallery']); } else { delete_post_meta($post_id, '_gallery'); }
	if(isset($_POST['_galleryMobile'])) 		{ update_post_meta($post_id, '_galleryMobile', $_POST['_galleryMobile']); } else { delete_post_meta($post_id, '_galleryMobile'); }
	// ------------------ //
}	

?>