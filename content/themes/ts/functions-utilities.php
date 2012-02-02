<?php
/**
 * toekneestuck utility functions and definitions
 *
 * @package toekneestuck
 * @subpackage toekneestuck
 * @since toekneestuck 1.0
 */

// Start at 2 because Search is always #1
$tabindex = 2;

/** Easy Debugger **/
function wp_debug($variable,$die=false){
	echo '<pre>'.print_r($variable, true).'</pre>';
	if($die) die();
}


/** Get the next tabindex value **/
function next_tabindex($echo=true){
	global $tabindex;
	if($echo) echo $tabindex++;
	else return $tabindex++;
}

/** Get the tax term name **/
function toeknee_get_term_name($item){
	if( isset($item->name) )
		return $item->name;
	return false;
}

/** Add <span> tags around <li> content */
function add_spans_to_list_items( $content ){
	return preg_replace('/(<li>)(.*)(<\/li>)/i', '${1}<span>${2}</span>${3}', $content);
}

/** 
 * Gets attachements of the current post 
 *
 * @uses get_children
 */
function get_post_attachments( $args=array() ){
	
	$args = array_merge(array(
		'post_type' => 'attachment',
		'post_parent' => null,
		'order' => 'ASC',
		'posts_per_page' => -1
	), $args);
	
	return get_posts($args);
}

/** 
 * Gets all images associated with the post
 *
 * @param $args (array) :  to be passed to get_post_attachments - all parameters are the same as get_posts or query_posts
 * @param $size (string) : size of the image to return
 *
 * @uses get_post_attachments
 * @uses wp_get_attachment_image
 * @uses wp_get_attachment_image_src
 */
function get_post_images( $args=null, $size='large' ){
	if( is_null($args) ) 
		return false;
	
	$images = array();
	$defaults = array(
		'post_parent' => null,
		'post_mime_type' => 'image',
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);
	$args = array_merge($defaults, $args);
	$attachments = get_post_attachments($args);

	/*
if( $attachments ){
		foreach( $attachments as $image ){
			$images[] = array(
				'html' => wp_get_attachment_image( $image->ID, $size ),
				'src' => wp_get_attachment_image_src( $image->ID, $size )
			);
		}
	}
*/
	
	return $attachments;
}

/** 
 * Return the image details for a current project item
 *
 * @param $image = image object or post ID
 * @param $size = size of the image
 */
function get_image_details( $image, $size='large' ){
	
	// If a post object isn't past, get it
	if( !is_object($image) )
		$image = get_post($image);
	
	// If the image doesn't exist, return falsee
	if( ! $image )
		return false;
	
	$video_url = get_post_meta( $image->ID, 'toeknee_video_url', true);
	
	$arr['id'] = $image->ID;
	
	$arr['html'] = !empty($video_url) ? 
		wp_oembed_get( $video_url, array('width' => 844) ) : 
		wp_get_attachment_image( $image->ID,  $size, false, array('data-id' => $image->ID) );

	$arr['title'] = wptexturize( !empty( $image->post_excerpt ) ? $image->post_excerpt : $image->post_title );
	$arr['desc'] = wpautop( wptexturize( !empty( $image->post_content ) ? $image->post_content : '' ) );
	return $arr;
}

/**
 * Find out if the current item is the active attachment. Used in single-projects.php
 *
 * @uses get_query_var
 */
function search_for_active_attachment( $item ){
	if( $item->ID == get_query_var('toeknee_attachment_id') )
		return true;
	return false;
}

?>