<?php
/**
 * The template for displaying video
 *
 * @package toekneestuck
 * @subpackage toekneestuck 2.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header>
		<h2 class="title">
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'toeknee' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" tabindex="<?php next_tabindex(); ?>">
				<?php the_title(); ?>
			</a>
		</h2>
	</header>

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- /.entry-content -->

	<footer class="entry-meta">
		<?php toeknee_the_date(); ?> in <span class="entry-category"><?php echo get_the_category_list( ', ' ); ?></span> &bull;
		<span class="comments-link"><?php 
			comments_popup_link( 
				__( 'Leave a comment', 'toeknee' ), 
				__( '1 Comment', 'toeknee' ), 
				__( '% Comments', 'toeknee' ) 
			); ?></span>
		<?php edit_post_link( __( 'Edit', 'toeknee' ), '&bull; <span class="edit-link">', '</span>' ); ?>
	</footer>
</article>