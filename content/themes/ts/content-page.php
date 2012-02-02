<?php
/**
 * The default template for displaying content
 *
 * @package toekneestuck
 * @subpackage toekneestuck 2.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'toeknee' ), 'after' => '</div>' ) ); ?>
	</div><!-- /.entry-content -->
</article>