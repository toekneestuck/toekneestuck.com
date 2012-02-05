<?php 

/** INITIAL ACTIONS **/
add_action( 'admin_init', 'toeknee_init' );

/**
 * Admin Styles
 *
 * @uses wp_enqueue_style
 * @uses home_url
 * @uses get_bloginfo
 */
function toeknee_init(){
	
	/** ALL ADMIN ACTIONS **/
	add_action( 'right_now_content_table_end', 'add_project_counts' );
	add_action( 'restrict_manage_posts','filter_by_custom_taxonomies' );
	add_action( 'add_meta_boxes', 'toeknee_add_meta_boxes' );
	add_action( 'save_post', 'toeknee_save_post' );
	
	/** ALL ADMIN FILTERS **/
	add_filter( 'favorite_actions', 'custom_favorite_actions' );
	add_filter( 'attachment_fields_to_save', 'save_extra_attachment_fields', 10,2 );
	add_filter( 'attachment_fields_to_edit', 'add_extra_attachment_fields', 10, 2 );
	add_filter( 'manage_posts_custom_column', 'postsColumnsRow', 10, 2 );
	add_filter( 'manage_edit-projects_columns', 'typesColumnsHeader' );
	add_filter( 'parse_query', 'convert_restrict_manage_posts' );
	
	/** CUSTOM ADMIN STYLES **/
	wp_enqueue_style('toeknee_admin', home_url( get_bloginfo('template_url') . '/admin/admin-styles.css' ) );
	
	// Default to two years
	add_option( 'project_archive_threshold_date', date('m/d/Y', (time() - ( 2 * 52 * 7 * 24 * 60 * 60)) ) );
	add_option( 'project_subheadline', '' );
	add_option( 'project_archive_headline', '' );
	add_option( 'project_archive_subheadline', '' );

	add_settings_section('projects', _x('Project Options', 'toeknee'), 'toeknee_add_project_section', 'reading' );

	add_settings_field( 'project_archive_threshold_date', _x('Archive Threshold Date', 'toeknee'), 'toeknee_add_project_threshold_date', 'reading', 'projects' );
	add_settings_field( 'project_subheadline', _x('Sub-headline', 'toeknee'), 'toeknee_add_project_subheadline', 'reading', 'projects' );
	add_settings_field( 'project_archive_headline', _x('Archive Headline', 'toeknee'), 'toeknee_add_project_archive_headline', 'reading', 'projects' );
	add_settings_field( 'project_archive_subheadline', _x('Archive Sub-headline', 'toeknee'), 'toeknee_add_project_archive_subheadline', 'reading', 'projects' );
	
	register_setting('reading', 'project_archive_threshold_date');
	register_setting('reading', 'project_subheadline');
	register_setting('reading', 'project_archive_headline');
	register_setting('reading', 'project_archive_subheadline');
}

/*
 * Outputs settings fields
 *
 * @uses get_option
 */

function toeknee_add_project_section(){
	
}

function toeknee_add_project_threshold_date(){
	$text = get_option('project_archive_threshold_date');
	echo "<input type='text' name='project_archive_threshold_date' id='project_archive_threshold_date' size='40' value='$text' />";
	echo "<small class='nonessential'>Format: MM/DD/YYYY</small>";
}

function toeknee_add_project_archive_headline(){
	$headline = get_option('project_archive_headline');
	echo "<input type='text' class='widefat' name='project_archive_headline' id='project_archive_headline' size='100' value='$headline' />";
}

function toeknee_add_project_archive_subheadline(){
	$subheadline = get_option('project_archive_subheadline');
	echo "<input type='text' class='widefat' name='project_archive_subheadline' id='project_archive_subheadline' size='100' value='$subheadline' />";
}

function toeknee_add_project_subheadline(){
	$subheadline = get_option('project_subheadline');
	echo "<input type='text' class='widefat' name='project_subheadline' id='project_subheadline' size='100' value='$subheadline' />";
}


/**
 * Adds the number of published publications to the dashboard
 *
 * @uses post_type_exists
 * @uses number_format_i18n
 * @uses _n
 * @uses current_user_can
 */
function add_project_counts() {
	if( !post_type_exists('projects') )
		return;

	$num_posts = wp_count_posts( 'projects' );
	$num = number_format_i18n( $num_posts->publish );
	$text = _n( 'Projects', 'Projects', intval($num_posts->publish) );
	if ( current_user_can( 'edit_posts' ) ) {
		$num = "<a href='edit.php?post_type=projects'>$num</a>";
		$text = "<a href='edit.php?post_type=projects'>$text</a>";
	}
	echo '<td class="first b b-recipes">' . $num . '</td>';
	echo '<td class="t recipes">' . $text . '</td>';
	echo '</tr>';
	
	if ($num_posts->pending > 0) {
		$num = number_format_i18n( $num_posts->pending );
		$text = _n( 'Projects Pending', 'Projects Pending', intval($num_posts->pending) );
	if ( current_user_can( 'edit_posts' ) ) {
		$num = "<a href='edit.php?post_status=pending&post_type=projects'>$num</a>";
		$text = "<a href='edit.php?post_status=pending&post_type=projects'>$text</a>";
	}
	echo '<td class="first b b-recipes">' . $num . '</td>';
	echo '<td class="t recipes">' . $text . '</td>';
	echo '</tr>';
	}
}

/**
 * Adds meta boxes
 *
 * @uses add_meta_box
 */
function toeknee_add_meta_boxes(){
	
	add_meta_box('feature_options', __('Additional Project Options'), 'feature_options', 'projects', 'side', 'high');
}

/**
 * Option for images to be part of the photo gallery
 *
 * @uses get_post_meta
 */
function add_extra_attachment_fields($form_fields, $post){

	if($post->post_mime_type == "image/jpeg"){
		$video_url = get_post_meta($post->ID, 'toeknee_video_url', true);
				
		
		$form_fields['toeknee_video_url'] = array(
			'label'      => __('Video URL'),
			'html'      => '<input type="text" name="toeknee_video_url" id="toeknee_video_url" value="' . $video_url . '" />',
			'input'      => 'html'
		);
	}
	return $form_fields;
}

/**
 * Saving extra attachment field values
 *
 * @uses update_post_meta
 */
function save_extra_attachment_fields($post, $attachment){
	
	$video_url = $_POST['toeknee_video_url'] ? $_POST['toeknee_video_url'] : '';
	
	if( $post['post_type'] != 'attachment' || $post['post_mime_type'] != 'image/jpeg' || empty( $video_url ) ){
		return $post;
	
	}elseif( !empty( $video_url ) ){
		update_post_meta( $post['ID'], 'toeknee_video_url', $video_url );
	}
	
	return $post;
}

/**
 * Custom filtering dropdowns on the admin pages
 *
 * @uses wp_dropdown_categories
 * @uses get_taxonomy
 */
function filter_by_custom_taxonomies(){
    global $typenow;
    global $wp_query;
    
    if( $typenow == 'projects' ){
        $taxonomy = get_taxonomy('project_type');

        wp_dropdown_categories(array(
            'show_option_all' =>  __("Show all {$taxonomy->label}"),
            'taxonomy'        =>  'project_type',
            'name'            =>  'project_type',
            'orderby'         =>  'name',
            'selected'        =>  $wp_query->query['project_type'],
            'show_count'      =>  true, 
            'hide_empty'      =>  true
        ));
    }
}

/**
 * Converts the filtering by taxonomies to a functioning query
 *
 * @uses get_object_taxonomies
 * @uses get_term_by
 */
function convert_restrict_manage_posts( $query ){
	global $pagenow;
	global $typenow;
	
	if ($pagenow=='edit.php') {
		$filters = get_object_taxonomies( $typenow );
		foreach ($filters as $tax_slug) {
			$var = &$query->query_vars[$tax_slug];
			if ( isset($var) ) {
				$term = get_term_by( 'id', $var, $tax_slug );
				$var = $term->slug;
			}
		}
	}
}

/**
 * Adds project type column to projects edit page
 *
 * @uses get_the_terms
 * @uses get_post_type
 * @uses esc_html
 * @uses sanitize_term_field
 */
function postsColumnsRow( $columnTitle, $postID ){
	
	if($columnTitle == 'project_type'){
		$cats = get_the_terms($postID, $columnTitle);
		if ( !empty($cats) ){
			$post_type = (get_post_type($postID) == 'projects') ? 'post_type=projects' : '';
			$out = array();
			foreach($cats as $c) 
				$out[] = "<a href='edit.php?$post_type&$columnTitle=$c->slug'> " . esc_html(sanitize_term_field('name', $c->name, $c->term_id, $columnTitle, 'display')) . "</a>";
			echo join( ', ', $out );
		}
		
	}elseif($columnTitle == 'featured'){
		$featured = ( get_post_meta($postID, 'featured', true) ) ? true : false;
		if( $featured )
			echo "<p><img src='" . admin_url('images/yes.png') . "' height='16' width='16' alt='Yes' /></p>";
	}
}

/**
 * Order the project columns
 *
 * @uses admin_url
 */
function typesColumnsHeader( $columns ){
	$new_columns['cb'] = $columns['cb'];
	$new_columns['title'] = $columns['title'];
	$new_columns['featured'] = _x('Featured', 'toeknee');
	$new_columns['project_type'] = _x('Project Type', 'toeknee');
	$new_columns['tags'] = $columns['tags'];
	$new_columns['comments'] = $columns['comments'];
	$new_columns['date'] = $columns['date'];
	return $new_columns;
}

/**
 * The meta box for featured options
 *
 * @uses get_post_meta
 * @uses wp_nonce_field
 * @uses plugin_basename
 */
function feature_options( $post ){
	
	// Use nonce for verification
	wp_nonce_field( plugin_basename(__FILE__), 'toeknee' );
	
	$featured = get_post_meta($post->ID, 'featured', true);
	$post_type = ucwords( get_post_type($post->ID) );
	
	if( $featured )
		echo '<div class="updated"><p>' . __("This $post_type is featured.", 'toeknee') . '</p></div>';
	
	echo '<p><label for="featured"><input type="checkbox" name="featured" id="featured"' . checked($featured, 1, false) . ' /> ' . __("Feature this $post_type") . '</label></p>';
}

/*
 * Saves all of our post data form all meta boxes
 *
 * @uses update_post_meta
 * @uses delete_post_meta
 * @uses wp_verify_none
 * @uses plugin_basename
 */
function toeknee_save_post( $post_id ){
	// 1. Don't do anything if we don't have custom stuff on the page
	// 2. verify this came from the our screen and with proper authorization, because save_post can be triggered at other times
	// 3. verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
	if( !isset( $_POST['toeknee']) || !wp_verify_nonce( $_POST['toeknee'], plugin_basename(__FILE__)) || ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) )
		return $post_id;

	// Featured Post
	if( isset($_POST['featured']) ){
		update_post_meta($post_id, 'featured', 1);
	}else{
		delete_post_meta($post_id, 'featured');
	}
}

 ?>