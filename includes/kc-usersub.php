<?php

add_action('cf7_2_post_form_submitted_to_video', 'new_video_mapped',10,3);
/**
* Function to take further action once form has been submitted and saved as a post.  
* Note this action is only fired for submission which has been submitted as opposed 
* to saved as drafts.
* @param string $post_id new post ID to which submission was saved.
* @param array $cf7_form_data complete set of data submitted in the form as an array of field-name=>value pairs.
* @param string $cf7form_key unique key to identify your form.
*/
function new_video_mapped($post_id, $cf7_form_data, $cf7form_key){
  /** 
  *  put the post in the Showcase category and publish it.
  */
  $dir = plugins_url();
  $dir = $dir.'/post-my-contact-form-7/cf7-2-post.php';

  $category_id = get_cat_ID('Showcase');
  wp_set_post_categories($post_id, $category_id);

  $my_post = array(
    'ID'           => $post_id
  );

  // Update the post in the database
  // important because this gets the iframe via oEmbed 
  wp_update_post( $my_post );
  // Processing $wp_error
}

add_action( 'cf7_2_post_status_video', 'publish_new_video',10,3);
/**
* Function to change the post status of saved/submitted posts.
* @param string $status the post status, default is 'draft'.
* @param string $ckf7_key unique key to identify your form.
* @param array $submitted_data complete set of data submitted in the form as an array of field-name=>value pairs.
* @return string a valid post status ('publish'|'draft'|'pending'|'trash')
*/
function publish_new_video($status, $ckf7_key, $submitted_data){
  /*The default behaviour is to save post to 'draft' status.  If you wish to change this, you can use this filter and return a valid post status: 'publish'|'draft'|'pending'|'trash'*/
  return 'publish';
}