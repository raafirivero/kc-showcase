<?php
/*
Plugin Name: KineCommunity Video Showcase
Plugin URI: https://github.com/raafirivero/kc-showcase
Author: Raafi Rivero
Author URI: http://raafirivero.com
Description: Custom post type for video showcase on KineCommunity
Version: 1.2
Textdomain: kinecommunity
License: GPLv2
*/


# top part forked from Dave Rupert 
# credit: https://gist.github.com/davatron5000/848232

# middle parts by me
# on saving posts: https://toolset.com/forums/topic/imposible-to-hook-on-custom-post-type-save-or-update/
# bottom parts found online somewhere else

new VideoPostType;		// Initial call

class VideoPostType
{

	var $single = "Video"; 	// this represents the singular name of the post type
	var $plural = "Videos"; 	// this represents the plural name of the post type
	var $type 	= "video"; 	// this is the actual type

	function VideoPostType()
	{
		$this->__construct();
	}

	function __construct()
	{
		# Place your add_actions and add_filters here
		add_action('init', array(&$this, 'init'));
		add_action('init', array(&$this, 'add_post_type'));

		# Add image support 
		add_theme_support('post-thumbnails', array($this->type));
		add_image_size(strtolower($this->plural) . '-thumb-s', 220, 160, true);
		add_image_size(strtolower($this->plural) . '-thumb-m', 300, 180, true);

		# Add Post Type to Search 
		add_filter('pre_get_posts', array(&$this, 'query_post_type'));
		
	}

	function init($options = null)
	{
		if ($options) {
			foreach ($options as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	function add_post_type()
	{
		$labels = array(
			'name' => _x($this->plural, 'post type general name'),
			'singular_name' => _x($this->single, 'post type singular name'),
			'add_new' => _x('Add ' . $this->single, $this->single),
			'add_new_item' => __('Add New ' . $this->single),
			'edit_item' => __('Edit ' . $this->single),
			'new_item' => __('New ' . $this->single),
			'view_item' => __('View ' . $this->single),
			'search_items' => __('Search ' . $this->plural),
			'not_found' =>  __('No ' . $this->plural . ' Found'),
			'not_found_in_trash' => __('No ' . $this->plural . ' found in Trash'),
			'parent_item_colon' => ''
		);
		$options = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array('slug' => strtolower($this->plural)),
			'capability_type' => 'post',
			'hierarchical' => false,
			'has_archive' => true,
			'menu_position' => 5,
			'menu_icon'     => 'dashicons-video-alt',
			'show_in_rest' => true,
			'taxonomies' => array(
				'category',
				'camera'
			),
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'comments',
				'custom-fields'
			)

		);
		register_post_type($this->type, $options);
	}


	function query_post_type($query)
	{
		if (is_category() || is_tag()) {
			$post_type = get_query_var('post_type');
			if ($post_type) {
				$post_type = $post_type;
			} else {
				$post_type = array($this->type); // replace cpt to your custom post type
			}
			$query->set('post_type', $post_type);
			return $query;
		}
	}
	
}


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
			// error_log(print_r($var->{'html'}, true));
			
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





# Register Taxonomy for Camera Type

# Hook into the init action and call create_book_taxonomies when it fires
add_action('init', 'create_camera_taxonomy', 0);

function create_camera_taxonomy()
{
	# Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name'                       => _x('Cameras', 'taxonomy general name', 'textdomain'),
		'singular_name'              => _x('Camera', 'taxonomy singular name', 'textdomain'),
		'search_items'               => __('Search Cameras', 'textdomain'),
		'popular_items'              => __('Popular Cameras', 'textdomain'),
		'all_items'                  => __('All Cameras', 'textdomain'),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __('Edit Camera', 'textdomain'),
		'update_item'                => __('Update Camera', 'textdomain'),
		'add_new_item'               => __('Add New Camera', 'textdomain'),
		'new_item_name'              => __('New Camera Name', 'textdomain'),
		'separate_items_with_commas' => __('Separate cameras with commas', 'textdomain'),
		'add_or_remove_items'        => __('Add or remove cameras', 'textdomain'),
		'choose_from_most_used'      => __('Choose from the most used cameras', 'textdomain'),
		'not_found'                  => __('No cameras found.', 'textdomain'),
		'menu_name'                  => __('Cameras', 'textdomain'),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array('slug' => 'camera'),
	);

	register_taxonomy('camera', 'video', $args);
}


function json_custom_fields($data, $post, $request)
{
	$_data = $data->data;
	$_data['videolink'] = get_post_meta($post->ID, 'videolink', true);
	$_data['director'] = get_post_meta($post->ID, 'director', true);
	$_data['dp'] = get_post_meta($post->ID, 'dp', true);
	$_data['editor'] = get_post_meta($post->ID, 'editor', true);
	$_data['kinecamera'] = get_post_meta($post->ID, 'kinecamera', true);
	$_data['upvotes'] = get_post_meta($post->ID, 'upvotes', true);
	$_data['downvotes'] = get_post_meta($post->ID, 'downvotes', true);
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

$vote_args = array(
	// these ones use integer for the data-type
	'type'         => 'integer',
	// Shown in the schema for the meta key.
	'description'  => 'A meta key associated with a string meta value.',
	// Return a single value of the type.
	'single'       => true,
	// Show in the WP REST API response. Default: false.
	'show_in_rest' => true,
);

register_meta($object_type, 'upvotes', $vote_args);
register_meta($object_type, 'downvotes', $vote_args);


