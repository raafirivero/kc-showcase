<?php

# When a videolink is entered - save its embed code via oEmbed:

function get_embed( $post_id, $post, $update ){
	
	if($post->post_type !== 'video') {
		return $post_id;
	}
 
 	$has_link = metadata_exists('post', $post->ID, 'videolink');
	$has_embed = metadata_exists('post', $post->ID, 'oembed');
	$has_thumb = metadata_exists('post', $post->ID, 'thumbnail');	

		
	if ($has_embed === false || $has_thumb === false) {
		
		if ($has_link) {
			# Now we're cooking. Start fetching stuff.

			require_once(ABSPATH.'wp-includes/class-wp-oembed.php');
			$oembed= new WP_oEmbed;
			
			$link_value = get_post_meta($post->ID, 'videolink', true);
			$var = $oembed->get_data($link_value);
			//error_log(print_r($var, true));
			
			$video = $var->html;
			$thumb = $var->thumbnail_url; 
			
			if ($has_embed === false) {
				update_post_meta($post->ID, 'oembed', $video);
			}
			
			if ($has_thumb === false) {
				update_post_meta($post->ID, 'thumbnail', $thumb);
			}

		}
		
	}	
	
}
add_action( 'save_post', 'get_embed', 10, 3 );