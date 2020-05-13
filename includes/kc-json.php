<?php

function json_custom_fields($data, $post, $request)
{
	$_data = $data->data;
	$_data['videolink'] = get_post_meta($post->ID, 'videolink', true);
	$_data['director'] = get_post_meta($post->ID, 'director', true);
	$_data['dp'] = get_post_meta($post->ID, 'dp', true);
	$_data['editor'] = get_post_meta($post->ID, 'editor', true);
	$_data['kinecamera'] = get_post_meta($post->ID, 'kinecamera', true);
	$_data['upvotes'] = get_post_meta($post->ID, 'upvotes', true);
	$_data['sponsored'] = get_post_meta($post->ID, 'sponsored', true);
	$_data['sponsorname'] = get_post_meta($post->ID, 'sponsorname', true);
	$_data['oembed'] = get_post_meta($post->ID, 'oembed', true);
	$_data['thumbnail'] = get_post_meta($post->ID, 'thumbnail', true);
	$data->data = $_data;
	return $data;
}
add_filter('rest_prepare_post', 'json_custom_fields', 10, 3);


# Add REST API support to an already registered post type.

add_filter('register_post_type_args', 'rest_args', 10, 2);

function rest_args($args, $post_type)
{

	if ('video' === $post_type) {
		$args['show_in_rest'] = true;

		// Optionally customize the rest_base or rest_controller_class
		$args['rest_base']             = 'videos';
		$args['rest_controller_class'] = 'WP_REST_Posts_Controller';
	}

	return $args;
}


# Adding REST API support to custom fields.

# The object type. For custom post types, this is 'post';
# for custom comment types, this is 'comment'. For user meta,
# this is 'user'.
$object_type = 'post';
$meta_args = array( // Validate and sanitize the meta value.
	// Note: currently (4.7) one of 'string', 'boolean', 'integer',
	// 'number' must be used as 'type'. The default is 'string'.
	'type'         => 'string',
	// Shown in the schema for the meta key.
	'description'  => 'A meta key associated with a string meta value.',
	// Return a single value of the type.
	'single'       => true,
	// Show in the WP REST API response. Default: false.
	'show_in_rest' => true,
);

register_meta($object_type, 'videolink', $meta_args);
register_meta($object_type, 'director', $meta_args);
register_meta($object_type, 'dp', $meta_args);
register_meta($object_type, 'editor', $meta_args);
register_meta($object_type, 'kinecamera', $meta_args);
register_meta($object_type, 'oembed', $meta_args);
register_meta($object_type, 'thumbnail', $meta_args);
register_meta($object_type, 'sponsorname', $meta_args);

$vote_args = array(
	// these ones use integer for the data-type
	'type'         => 'integer',
	// Shown in the schema for the meta key.
	'description'  => 'Upvotes or fire emojis.',
	// Return a single value of the type.
	'single'       => true,
	// Show in the WP REST API response. Default: false.
	'show_in_rest' => true,
);

register_meta($object_type, 'upvotes', $vote_args);

$spon_args = array(
	//  'string', 'boolean', 'integer',
	// 'number' must be used as 'type'. The default is 'string'.
	'type'         => 'boolean',
	// Shown in the schema for the meta key.
	'description'  => 'Is this video sponsored?',
	// Return a single value of the type.
	'single'       => true,
	// Show in the WP REST API response. Default: false.
	'show_in_rest' => true,
);

register_meta($object_type, 'sponsored', $spon_args);