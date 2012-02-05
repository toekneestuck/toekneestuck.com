<?php
/**
 * The template for displaying the project archive.
 *
 * @package toekneestuck
 * @subpackage toekneestuck
 * @since toekneestuck 2.0
 */

get_header();

$old_posts = array();
global $posts;
foreach( $posts as $key=>$post ){

	// If the post is older than the threshold, "archive" it
	if( strtotime($post->post_date) <= strtotime( get_option('project_archive_threshold_date') ) ){
		$old_posts[] = $post;
		unset($posts[$key]);
	}
}

?>
<div id="portfolio">
	<header class="container">
		<hgroup class="center">
			<h1><?php _e('Portfolio', 'toeknee'); ?></h1>
		<?php if( $subheadline = get_option('project_subheadline') ): ?>
			<h2 class="h4 subheadline"><?php _e($subheadline, 'toeknee'); ?></h2>
		<?php endif; ?>
		</hgroup>
		<hr />
	</header>

	<section class="container clearfix" id="projects">
	<?php if( count($posts) > 0 ): ?>
		<ul id="latest_posts" class="clearfix">
		<?php foreach( $posts as $key=>$post ): setup_postdata($post); ?>
			<?php get_template_part( 'content', 'project' ); ?>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>

		<?php if( count($posts) > 0 && count($old_posts) > 0 ): ?><hr /><?php endif; ?>

	<?php if( count($old_posts) > 0 ): ?>
		<hgroup class="center">
		<?php if( $archive_headline = get_option('project_archive_headline') ): ?>
			<h3 class="headline"><?php _e($archive_headline, 'toeknee') ?></h3>
		<?php endif; ?>
		<?php if( $archive_subheadline = get_option('project_archive_subheadline') ): ?>
			<h6 class="subheadline"><?php _e($archive_subheadline, 'toeknee') ?></h6>
		<?php endif; ?>
		</hgroup>

		<ul id="old_posts">
		<?php foreach( $old_posts as $key=>$post ): setup_postdata($post); ?>
			<?php get_template_part( 'content', 'project' ); ?>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	
	<?php if( ! have_posts() ): ?>
		<h4 class="center"><?php _e("I'm sorry. I don't seem to have any projects here.", 'toeknee') ?></h4>
	<?php endif; ?>
	</section>
</div><!-- /#portfolio -->
<?php get_footer(); ?>