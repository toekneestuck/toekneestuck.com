<?php
/**
 * The category template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package toekneestuck
 * @subpackage toekneestuck 2.0
 */

get_header(); ?>
<div class="main clearfix">
<?php if ( have_posts() ) : ?>
	<section class="primary search collection" role="main">
		<header class="headline">
			<h1><?php printf( __( 'Search Results for: %s', 'toeknee' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
		</header>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content', get_post_format() ); ?>
	<?php endwhile; ?>
		<?php get_template_part( 'nav', 'below' ); ?>
	</section>

	<aside class="sidebar">
		<?php get_sidebar(); ?>
	</aside>

<?php else : ?>

	<article id="post-0" class="post no-results not-found">
		<header>
			<h1 class="title"><?php _e( 'Nothing Found', 'toekneestuck' ); ?></h1>
		</header>

		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'toekneestuck' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</article>

<?php endif; ?>
</div><!-- /.main -->

<?php get_footer(); ?>