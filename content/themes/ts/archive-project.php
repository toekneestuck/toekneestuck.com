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

	// If the post is older than two years
	if( strtotime($post->post_date) <= (time() - ( 2 * 52 * 7 * 24 * 60 * 60)) ){
		$old_posts[] = $post;
		unset($posts[$key]);
	}
}

?>
<div id="portfolio">
	<header class="container">
		<hgroup class="center">
			<h1><?php _e('Portfolio', 'toeknee'); ?></h1>
			<h2 class="h4 subheadline"><?php _e('These are the things I make.', 'toeknee'); ?></h2>
		</hgroup>
		<hr />
	</header>

	<section class="container clearfix" id="projects">
	<?php if( count($posts) > 0 ): ?>
		<ul id="latest_posts">
		<?php foreach( $posts as $key=>$post ): setup_postdata($post); ?>
			<?php get_template_part( 'content', 'project' ); ?>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>

		<?php if( count($posts) > 0 && count($old_posts) > 0 ): ?><hr /><?php endif; ?>

	<?php if( count($old_posts) > 0 ): ?>
		<hgroup class="center">
			<h3 class="headline"><?php _e("The Seriously Old Stuff", 'toeknee') ?></h3>
			<h6 class="subheadline"><?php _e("I decided not to let these projects die a silent, binary death,<br />so here are some of the older and less web-related things I've done in the distant past.", 'toeknee') ?></h6>
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