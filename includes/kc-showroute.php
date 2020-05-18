<?php
/**
 * Kine showcase fire emoji.
 *
 * Register a custom Wp Rest Api end-point,
 *
 * @see https://since1979.dev/snippet-005-simple-custom-rest-api-route/
 *
 * @uses register_rest_route() https://developer.wordpress.org/reference/functions/register_rest_route/
 * @uses array() https://www.php.net/manual/en/function.array.php
 */

function kc_register_custom_routes()
{                                  
    register_rest_route( 'kinecom', '/showcase/(?P<id>\d+)', array(
        'methods'  => 'GET, PUT',
        'callback' => 'kc_upvote_callback',
    ));
}


/**
 * Hook: rest_api_init.
 *
 * @uses add_action() https://developer.wordpress.org/reference/functions/add_action/
 * @uses rest_api_init https://developer.wordpress.org/reference/hooks/rest_api_init/
 */

add_action( 'rest_api_init', 'kc_register_custom_routes' );


/**
 * KC Showcase Endpoint
 *
 * Handle calls to the Wp Rest Api /showcase end-point,
 *
 * @uses get_post_meta() https://developer.wordpress.org/reference/functions/get_post_meta/
 * @uses WP_Error() https://developer.wordpress.org/reference/classes/wp_error/
 * @uses rest_ensure_response() https://developer.wordpress.org/reference/functions/rest_ensure_response/
 */


function kc_upvote_callback($request)
{
    /* ////////// 
    in order for this to work
    /wp-json/kinecom/showcase/{post-ID-number}/?upvotes={number I'm sending}
    
    the value we want is in a variable called upvotes
    //////////// */
    $postnum = $request['id'];
    $newnum = $request['upvotes'];

    // let us sanitize

    if (!filter_var($newnum, FILTER_VALIDATE_INT) === false) {
        // update if we're gucci
        update_post_meta($postnum, 'upvotes', $newnum);
        
        $totalvotes = get_post_meta($postnum, 'totalvotes');
        //error_log(print_r($totalvotes, true));

        if( isset( $totalvotes ) ) { 
            $addone = $totalvotes[0] + 1;
            update_post_meta($postnum, 'totalvotes', $addone);
        } else {
            update_post_meta($postnum, 'totalvotes', 1);
        }
    } else {
        echo("Integer is not valid");
        $newnum = false;
    }

    if (!$newnum)
        return new WP_Error('Meta value not found', 'Invalid meta key', array('status' => 404));
    return rest_ensure_response(array('upvotes count updated to:' => $newnum));
}
