<?php
/**
 * The Template for displaying all single posts.
 *
 * @package toekneestuck
 * @since toekneestuck 2.0
 */

get_header(); ?>
<div class="main clearfix">
<?php while ( have_posts() ) : the_post(); ?>
	<div class="primary" role="main">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header>
				<h2 class="title">
					<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'toeknee' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" tabindex="<?php next_tabindex(); ?>">
						<?php the_title(); ?>
					</a>
				</h2>
				<div class="entry-meta">
					<?php toeknee_the_date(); ?> in <span class="entry-category"><?php echo get_the_category_list( ', ' ); ?></span> &bull;
					<span class="comments-link"><?php 
						comments_popup_link( 
							__( 'Leave a comment', 'toeknee' ), 
							__( '1 Comment', 'toeknee' ), 
							__( '% Comments', 'toeknee' ) 
						); ?></span>
					<?php edit_post_link( __( 'Edit', 'toeknee' ), '&bull; <span class="edit-link">', '</span>' ); ?>
				</div>
			</header>

			<div class="entry-content">
				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'toeknee' ), 'after' => '</div>' ) ); ?>
			</div><!-- /.entry-content -->

			<?php the_tags('<p class="tag-list">Tagged: ', '<span class="screen-reader-text">,</span> ', '</p>'); ?>
		</article>

		<nav class="navigation nav-below clearfix">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'toeknee' ); ?></h3>
			<span class="item nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'toeknee' ) ); ?></span>
			<span class="item nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'toeknee' ) ); ?></span>
		</nav><!-- #nav-single -->

		<?php comments_template( null, true ); ?>
	</div><!-- /.primary -->

<?php endwhile; // end of the loop. ?>

	<aside class="sidebar">
		<?php get_sidebar() ?>
	</aside>
</div><!-- /.main -->
<?php get_footer(); ?>