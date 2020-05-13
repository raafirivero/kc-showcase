<?php

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
