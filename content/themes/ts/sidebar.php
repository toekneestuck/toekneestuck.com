<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package toekneestuck
 * @subpackage toekneestuck
 * @since toekneestuck 2.0
 */
?>
<?php if( ! dynamic_sidebar( 'right-sidebar' ) ): ?>

	<section id="search" class="widget-container widget_search">
		<?php get_search_form(); ?>
	</section>

	<section id="archives" class="widget-container">
		<h3 class="widget-title"><?php _e( 'Archives', 'toeknee' ); ?></h3>
		<ul>
			<?php wp_get_archives( 'type=monthly' ); ?>
		</ul>
	</section>
<?php endif; // end primary widget area ?>