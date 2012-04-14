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
		<h2 class="title">
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'toeknee' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" tabindex="<?php next_tabindex(); ?>">
				<?php the_title(); ?>
			</a>
		</h2>
	</header>

<?php if ( ! is_single() ) : // Display excerpts for everything except the full page. ?>
	<?php if( has_post_thumbnail() ): ?>
	<div class="featured-image">
		<a href="<?php the_permalink() ?>"><?php the_post_thumbnail('blog-thumb'); ?></a>
	</div>
	<?php endif; ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- /.entry-summary -->
<?php else : ?>
	<?php if( has_post_thumbnail() ): ?>
	<div class="featured-image">
		<?php the_post_thumbnail(); ?>
	</div>
	<?php endif; ?>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'toeknee' ), 'after' => '</div>' ) ); ?>
	</div><!-- /.entry-content -->
<?php endif; ?>

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