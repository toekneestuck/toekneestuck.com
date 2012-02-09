<?php
/**
 * The default template for displaying content
 *
 * @package toekneestuck
 * @subpackage toekneestuck 2.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header>
		<h1><?php the_title() ?></h1>
	</header>
	<?php if( has_post_thumbnail() ): ?>
	<div class="featured-image">
		<?php the_post_thumbnail('large'); ?>
	</div>
	<?php endif; ?>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'toeknee' ), 'after' => '</div>' ) ); ?>
	</div><!-- /.entry-content -->
</article>