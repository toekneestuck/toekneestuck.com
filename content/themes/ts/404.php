<?php
/**
 * The template for displaying a 404.
 *
 * @package toekneestuck
 * @subpackage toekneestuck
 * @since toekneestuck 2.0
 */

get_header(); ?>
<article class="main center clearfix" role="main">
	<h1><?php _e('This page was unintentionally left blank.', 'toeknee') ?></h1>
	<p><?php _e('Sowwy. Try going back', 'toeknee') ?> <a href="<?php echo home_url() ?>"><?php _e('home', 'toeknee') ?> &raquo;</a></p>
</article><!-- /.main -->
<?php get_footer(); ?>