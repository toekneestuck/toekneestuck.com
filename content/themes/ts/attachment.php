<?php
/**
 * The template for displaying attachments.
 *
 * @package toekneestuck
 * @subpackage toekneestuck
 * @since toekneestuck 2.0
 */

global $post;

/** If this is a project item, load the projects template **/
if( !empty($post->post_parent) && get_post_type( $post->post_parent ) == 'project' ):

	query_posts(array(
		'page_id' => $post->post_parent,
		'post_type' => 'project',
		'toeknee_attachment_id' => $post->ID,
		'toeknee_real_title' => get_the_title()
	));

	get_template_part('single', 'project');

else:

/** Otherwise, proceed as normal **/
get_header(); ?>
<div class="container" role="main">
<?php if( have_posts() ) while( have_posts() ): the_post(); ?>
	<?php get_template_part( 'content', 'attachment' ); ?>
<?php endwhile; ?>
</div>
<?php get_footer();

endif; ?>