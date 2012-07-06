<?php
/**
 * @package toekneestuck
 * @subpackage toekneestuck
 * @since toekneestuck 2.0
**/

define('TYPEKIT_URL', 'http://use.typekit.com/jkt5yng.js');
define('AJAX_PROJECT_ITEM_ACTION', 'project_item');

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 663;

/************* ALL ACTIONS *************/
add_action( 'login_head', 'add_custom_logo' );
add_action( 'after_setup_theme', 'toeknee_setup' );
add_action( 'widgets_init', 'toeknee_widgets_init' );
add_action( 'widgets_init', 'toeknee_remove_recent_comments_style' );
add_action( 'wp_print_styles', 'toeknee_add_styles' );
add_action( 'wp_enqueue_scripts', 'toeknee_add_scripts' );
//add_action( 'init', 'toeknee_remove_head_items');
add_action( 'init', 'toeknee_register_post_types' );
add_action( 'init', 'toeknee_register_taxonomies' );
add_action( 'init', 'toeknee_remove_head_items' );
add_action( 'wp_ajax_nopriv_' . AJAX_PROJECT_ITEM_ACTION, 'ajax_get_project_item' );
add_action( 'wp_ajax_' . AJAX_PROJECT_ITEM_ACTION, 'ajax_get_project_item' );

/************ ALL FILTERS **************/
add_filter( 'pre_get_posts', 'toeknee_remove_project_page_limit' );
add_filter( 'nav_menu_css_class', 'remove_menu_classes', 10, 3 );
add_filter( 'comment_form_default_fields', 'toeknee_comment_fields' );
add_filter( 'comment_form_defaults', 'toeknee_comment_default_args' );
add_filter( 'excerpt_length', 'toeknee_excerpt_length' );
add_filter( 'excerpt_more', 'toeknee_auto_excerpt_more' );
add_filter( 'get_the_excerpt', 'toeknee_custom_excerpt_more' );
add_filter( 'use_default_gallery_style', '__return_false' );
add_filter( 'body_class', 'toeknee_body_classes' );
add_filter( 'wp_get_attachment_link', 'toeknee_wp_get_attachment_link', 10, 6 );
add_filter( 'the_posts', 'check_for_prettify' );
add_filter( 'editor_max_image_size', 'allow_custom_image_sizes', 10, 2 );
add_filter( 'the_excerpt_rss', 'rss_post_thumbnail' );
add_filter( 'the_content_feed', 'rss_post_thumbnail' );
add_filter( 'post_thumbnail_html', 'post_thumbnail_picturefill', 10, 5 );


/**
 * Add Harboring Hearts Logo to the login screen
 *
 */
function add_custom_logo(){
	echo '<style type="text/css">h1 a{background:url(' . get_bloginfo('template_url') . '/img/logo-toekneestuck-login.png) top center no-repeat;height:94px;width:279px;margin:0 auto}</style>';
}

/**
 * *** INCLUDES ***
 *
 * @since toekneestuck 2.0
 */
require('functions-utilities.php');
require('functions-widgets.php');

/**
 * *** ADMIN CONFIGURATION ***
 *
 * @since toekneestuck 2.0
 */
if( is_blog_admin() )
	require('admin/admin-config.php');

/*
 * *** AFTER THEME SETUP ****
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_editor_style() To style the visual editor.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 */
function toeknee_setup(){
	global $content_width;

	show_admin_bar(false);
	add_editor_style();

	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'video' ) );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );

	register_nav_menus( array('primary' => __( 'Primary Navigation', 'toeknee' )) );

	set_post_thumbnail_size($content_width, 900);
	add_image_size('blog-thumb', $content_width, 300, true);
	add_image_size('portfolio-thumb', 250, 125, true);
	add_image_size('portfolio', 844, 1500);
}

/*
 * *** QUERY CONFIGURATION ***
 *
 * @since toekneestuck 2.0
 * @uses is_post_type_archive
 */
function toeknee_remove_project_page_limit( $query ){

	if( is_post_type_archive() && $query->query_vars['post_type'] == 'project' )
		$query->query_vars['posts_per_page'] = -1;

	return $query;
}

/*
 * *** ADD CSS STLYES ***
 *
 * @since toekneestuck 2.0
 * @uses wp_enqueue_style, home_url, get_bloginfo, get_stylesheet_uri
 */
function toeknee_add_styles(){
	if( !is_blog_admin() ){
		wp_enqueue_style('toeknee', home_url( get_bloginfo('template_url') . '/stylesheets/css/style.css' ), null, 1.16, 'screen,projection');

		//global $wp_styles;
		//$wp_styles->add_data('1140_ie', 'conditional', 'lte IE 9');
	}
}

/*
 * *** Enqueue Prettify Styles & Scripts on Demand ***
 *
 * @since toekneestuck 2.0
 * @uses wp_enqueue_style, home_url, get_bloginfo, get_stylesheet_uri
 */
function check_for_prettify( $posts ){

	if( ! is_single() )
		return $posts;

	if( count($posts) == 1 && strpos( $posts[0]->post_content, 'class="prettyprint') !== false ){

		wp_enqueue_style('prettify', home_url( get_bloginfo('template_url') . '/stylesheets/css/prettify.css' ), null, false, 'screen,projection');
		wp_enqueue_script('prettify', home_url( get_bloginfo('template_url') . '/js/libs/prettify.js'), null, '', true);

	}

	return $posts;
}

/*
 * *** Don't set a max width on portfolio items ***
 *
 * @since toekneestuck 2.0
 */
function allow_custom_image_sizes( $max_size, $original_size ){
	global $_wp_additional_image_sizes;

	if( is_string($original_size) &&  isset($wp_additional_image_sizes[$original_size]) )
		return $wp_additional_image_sizes[$original_size];
}

/*
 * *** ADD JAVASCRIPTS ***
 *
 * @since toekneestuck 2.0
 * @uses wp_enqueue_script
 */
function toeknee_add_scripts(){
	if( !is_blog_admin() ){
		wp_deregister_script('jquery');
		wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', '1.7.1');

		wp_enqueue_script('typekit', TYPEKIT_URL, null); // We can't add defer or async to this, so might as well load it through here

		if ( is_single() ){
			wp_enqueue_script('addthis', 'http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f2ede3b3d2c13ae', null, null, true);

			// Required for nested reply function that moves reply inline with JS
			if( comments_open() && get_option('thread_comments') == 1 )
				wp_enqueue_script( 'comment-reply' );
		}

		// Get rid of this stupid thing
		wp_deregister_script('l10n');
	}
}

/*
 * *** Remove Extra <head> items ***
 *
 * @since toekneestuck 2.0
 * @uses remove_action
 */
function toeknee_remove_head_items(){
	remove_action('wp_head', 'wp_generator');
}

/**
 * Ajax Request for a Project Item
 *
 */
function ajax_get_project_item(){

	$id = isset($_GET['id']) ? $_GET['id'] : null;
	$url = isset($_GET['url']) ? $_GET['url'] : '';

	$image = get_image_details( $id, 'portfolio' );

	// Return a JSON object for parsing
	if( ! $image ){
		echo json_encode( array('error' => "It doesn't appear that this item exists."));
	}else{
		echo json_encode( $image );
	}
	die();
}

/*
 * *** WIDGETS INIT ***
 *
 * @since toekneestuck 2.0
 * @uses register_sidebar
 */
function toeknee_widgets_init() {
	// Right Sidebar Widget, for blog pages
	register_sidebar( array(
		'name' => __( 'Right Sidebar', 'toeknee' ),
		'id' => 'right-sidebar-area',
		'description' => __( 'The right sidebar for blog content.', 'toeknee' ),
		'before_widget' => '<section id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>',
	) );

	// Homepage Widget Area
	register_sidebar( array(
		'name' => __( 'Homepage Widget Area', 'toeknee' ),
		'id' => 'homepage-widget-area',
		'description' => __( 'The homepage widget area', 'toeknee' ),
		'before_widget' => '<section id="%1$s" class="clearfix %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h4 class="headline">',
		'after_title' => '</h4>',
	) );

	wp_register_sidebar_widget('search', 'Search', 'toeknee_widget_search', array(
		'classname' => 'widget_search',
		'description' => 'A search form for your blog.'
	));

	register_widget( 'Social_Widget' );
	register_widget( 'Featured_Projects_Widget' );
}


/*
 * Custom search box for sidebar
 *
 * @uses home_url
 */
function toeknee_widget_search($args){
	extract($args);
	echo $before_widget;
	if( !empty($title)){
		echo $before_title;
		echo $title;
		echo $after_title;
	}
	echo '<form role="search" method="get" id="searchform" action="'. home_url('/') .'">';
	echo '<fieldset><label class="screen-reader-text" for="s">Search for:</label>';
	echo '<div class="box">';
	echo '<div><input type="text" value="" name="s" id="s" placeholder="search this blog" tabindex="1"></div>';
	echo '<button type="submit" id="searchsubmit" class="button">Search</button>';
	echo '</div>';
	echo '</fieldset>';
	echo '</form>';
	echo $after_widget;
}

/*
 * *** REGISTER POST TYPES ***
 *
 * @since toekneestuck 2.0
 * @uses register_post_type
 */
function toeknee_register_post_types(){

	register_post_type( 'project', array(
		'labels' => array(
			'name' => 'Projects',
			'singular_name' => 'Project',
			'add_new_item' => 'Add New Project',
			'edit_item' => 'Edit Project',
			'new_item' => 'New Project',
			'view_item' => 'View Project',
			'search_items' => 'Search Projects',
			'not_found' => 'No projects found',
			'not_found_in_trash' => 'No projects found in the trash',
			'menu_name' => 'Projects'
		),
		'description' => 'A collection of projects by you!',
		'public' => true,
		'menu_position' => 5,
		'supports' => array('title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments'),
		'register_meta_cb' => 'toeknee_project_meta_boxes',
		'taxonomies' => array('post_tag','project_type'),
		'has_archive' => 'portfolio',
		'rewrite' => array(
			'slug' => 'portfolio',
			'with_front' => false,
			'can_export' => true
		)
	));
}

/*
 * *** REGISTER TAXONOMIES ***
 *
 * @since toekneestuck 2.0
 * @uses register_taxonomy
 */
function toeknee_register_taxonomies(){

	register_taxonomy('project_type', 'project', array(
		'labels' => array(
			'name' => 'Project Types',
			'singular_name' => 'Project Type',
			'search_items' => 'Search Project Types',
			'popular_items' => 'Popular Types',
			'all_items' => 'Every Type',
			'edit_item' => 'Edit Project Type',
			'update_item' => 'Update Project Type',
			'add_new_item' => 'Add New Type',
			'new_item_name' => 'New Type Name',
			'separate_items_with_commas' => 'Separate types with commas',
			'add_or_remove_items' => 'Add or remove types',
			'choose_from_most_used' => 'Choose from most used types',
			'menu_name' => 'Types'
		),
		'public' => true,
		'hierarchical' => false,
		'rewrite' => array(
			'slug' => 'project/type',
			'with_front' => false,
			'hierarchical' => true
		)
	));
}


/**
 * Sets the post excerpt length to 40 characters.
 *
 * @since toekneestuck 2.0
 * @return int
 */
function toeknee_excerpt_length( $length ){ if( is_front_page() ) return 20; else return 40; }

/**
 * @since toekneestuck 2.0
 * @return string "Continue Reading" link
 */
function toeknee_continue_reading_link() {
	return ' <a href="'. get_permalink() . '" class="more">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'toeknee' ) . '</a>';
}

/**
 * @since toekneestuck 2.0
 * @return string An ellipsis
 */
function toeknee_auto_excerpt_more( $more ) { return ' &hellip;' /* . toeknee_continue_reading_link() */; }

/**
 * @since toekneestuck 2.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function toeknee_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= toeknee_continue_reading_link();
	}
	return $output;
}

/**
 * Add HTML5 data attributes for the attachment links
 *
 * @uses get_post_type
 */
function toeknee_wp_get_attachment_link( $link, $id, $size, $permalink, $icon, $text ){

	if( get_post_type() == 'project' ){
		$action = defined('AJAX_PROJECT_ITEM_ACTION') ? AJAX_PROJECT_ITEM_ACTION : 'project_item';
		$link = str_replace("<a", "<a data-id='$id' data-action='$action'", $link);
	}
	return $link;
}

/**
 * Add Post Thumbnail to RSS Feed
 *
 * @uses has_post_thumbnail
 * @uses get_the_post_thumbnail
 * @uses get_the_content
 */
function rss_post_thumbnail( $content ){
	global $post;

	if( has_post_thumbnail( $post->ID ) ){
		$content = '<p>' . get_the_post_thumbnail($post->ID) . '</p>' . $content;
	}

	return $content;
}

/**
 * Replace normal <img> tag with custom <picture> polyfill element for responsive images
 *
 * @uses wp_get_attachment_image_src
 */
function post_thumbnail_picturefill( $html, $post_id, $post_thumbnail_id, $size, $attr ){

	$alt = preg_match('/alt="(.*)"/', $html, $matches);
	$picture_fill_html = '<picture alt="'. $matches[1] .'">';

	$small = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail' );
	$picture_fill_html .= '<!-- <source src="' . $small[0] . '"> -->';
	$picture_fill_html .= '<source src="' . $small[0] . '">';

	$normal = wp_get_attachment_image_src( $post_thumbnail_id, $size );
	$picture_fill_html .= '<!-- <source src="' . $normal[0] . '" media="(min-width:480px)"> -->';
	$picture_fill_html .= '<source src="' . $normal[0] . '" media="(min-width:480px)">';

	$large = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
	$picture_fill_html .= '<!-- <source src="' . $large[0] . '" media="(-webkit-min-device-pixel-ratio:2 and min-width:480px)"> -->';
	$picture_fill_html .= '<source src="' . $large[0] . '" media="(-webkit-min-device-pixel-ratio:2 and min-width:480px)">';

	$picture_fill_html .= '<noscript>' . $html . '</noscript>';
	$picture_fill_html .= '</picture>';

	return $picture_fill_html;
}

/**
 * Template for comments and pingbacks.
 *
 * @since toekneestuck 2.0
 */
function toeknee_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class('clearfix'); ?> id="li-comment-<?php comment_ID(); ?>">
		<?php if ( $comment->comment_approved == '0' ) : ?><em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'toeknee' ); ?></em><?php endif; ?>
		<div class="left badge">
			<?php echo get_avatar( $comment, 70 ); ?>
		</div>
		<div id="comment-<?php comment_ID(); ?>" class="comment-details">
			<div class="comment-author">
				<?php printf( __( '%s <span class="says">says:</span>', 'toeknee' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
			</div>
			<div class="comment-body"><?php comment_text(); ?></div>
			<div class="comment-meta"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
				<?php printf( __( '%1$s at %2$s', 'toeknee' ), get_comment_date(),  get_comment_time() ); ?></a>
				&bull; <?php comment_reply_link( array_merge( $args, array( 'class' => 'btn', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				<?php edit_comment_link( __( ' &bull; (Edit)', 'toeknee' ), ' ' ); ?>
			</div><!-- /.comment-meta -->
		</div><!-- /#comment  -->
	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'toeknee' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'toeknee' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}

/**
 * Custom Comment Field Layout
 *
 */
function toeknee_comment_fields($fields){
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	$fields = array(
		'author' => '<div class="columns two clearfix"><div class="column"><div class="comment-form-author">' . '<label for="author">' . __( 'Name' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>' .
		            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' placeholder="Name' . ( $req ? '*' : '' ) . '" /></div>',
		'email'  => '<div class="comment-form-email"><label for="email">' . __( 'Email' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' placeholder="Email' . ( $req ? '*' : '' ) . '" /></div>',
		'url'    => '<div class="comment-form-url"><label for="url">' . __( 'Website' ) . '</label>' .
		            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" placeholder="Website" /></div></div><!-- /.column -->',
	);
	return $fields;
}

function toeknee_comment_default_args( $defaults ){
	$defaults['comment_field'] = '<div class="comment-form-comment column"><label for="comment">'. __('Comment') .' <span class="required">*</span></label><textarea id="comment" name="comment" cols="48" rows="9" aria-required="true" placeholder="Comment..."></textarea></div>';
	$defaults['comment_notes_after'] = null;

	if( !is_user_logged_in() )
		$defaults['comment_field'] .= '</div><!-- /.columns -->';

	return $defaults;
}

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * @since toekneestuck 2.0
 */
function toeknee_remove_recent_comments_style() {
	add_filter( 'show_recent_comments_widget_style', '__return_false' );
}

/**
 * Print out the HTML5 datetime tag
 *
 * @since toekneestuck 2.0
 */
function toeknee_the_date($dateonly=false) {
	if( $dateonly )
		printf ('<span data-timedate="%1$s" data-pubdate>%2$s</span>', get_the_date('c'), get_the_date() );
	else
		printf ('Posted on <span data-timedate="%1$s" data-pubdate>%2$s</span>', get_the_date('c'), get_the_date() );
}

/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since toekneestuck 2.0
 */
function toeknee_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'toeknee' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'toeknee' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'toeknee' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}

/**
 *
 * Used to remove any classname that contains 'menu-item'
 *
 */
function remove_menu_classes($classes, $item, $args){

	if( (get_query_var('post_type') == 'project' || get_query_var('project_type') ) && $item->post_name == 'portfolio' )
		$classes[] = 'active';

	foreach( $classes as $key=>$class ){
		if( keep_menu_class($class) ){
			if( strpos($class, 'current') !== false
				&& !((get_query_var('post_type') == 'project' || get_query_var('project_type')) && strtolower($item->title) == 'blog') )
				$classes[$key] = 'active';
		}else{
			unset($classes[$key]);
		}
	}
	return array_unique($classes);
}

function keep_menu_class($item){
	if( strpos($item, 'current') !== false)
		return true;
	elseif( strpos($item, 'menu-item') !== false)
		return false;
	elseif( strpos($item, 'page-item') !== false)
		return false;

	return true;
}

/**
 * Remove unnecessary body classes
 *
 */
 function toeknee_body_classes($classes){

 	foreach( $classes as $key=>$class ){
 		if( strpos($class, 'page-') !== false
 			|| strpos($class, 'post-type') !== false
 			|| strpos($class, 'single-') !== false )
 			unset($classes[$key]);
 	}
 	return $classes;
 }
