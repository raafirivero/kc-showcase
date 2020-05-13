<?php


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