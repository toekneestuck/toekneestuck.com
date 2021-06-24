<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package toekneestuck
 * @subpackage toekneestuck
 * @since toekneestuck 2.0
 */

get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<article role="main" id="post-<?php the_ID(); ?>" <?php post_class('about'); ?>>
		<div class="container">
			<header class="center headline">
				<?php the_content(); ?>
			</header><!-- /.entry-content -->
	
			<hr />
	
			<?php if( get_field('circle') ): ?>
			<ul class="flip-it circles clearfix">
				<?php while( the_repeater_field('circle') ): ?>
				<li class="flipper circle <?php echo implode(' ', array( 
					esc_attr( strtolower( get_sub_field('class_name') ), 
					esc_attr( strtolower( get_sub_field('title') ) ) ) 
				) ) ?>">
					<p class="front face label"><?php echo esc_attr( _x( get_sub_field('title'), 'toeknee' ) ) ?></p>
					<div class="back face details">
						<?php echo wptexturize( _x( get_sub_field('buzz_words'), 'toeknee' ) ) ?>
						<?php echo wptexturize( _x( get_sub_field('description'), 'toeknee' ) ) ?>
					</div>
				</li>
				<?php endwhile; ?>
			</ul>
			<hr />
			<?php endif; ?>
		</div>

		<section class="who-i-am accent clearfix">
			<div class="container">

				<header class="center">
					<hgroup>
						<h2>
							<?php _e('Work', 'toeknee') ?> <span class="separator"><?php _e('SOFT', 'toeknee') ?></span> <?php _e('Play', 'toeknee') ?> <span class="separator"><?php _e('SOFTer', 'toeknee') ?></span></h2>
						<h6 class="subheadline"><?php echo esc_attr( get_field('subheadline') ) ?></h6>
					</hgroup>
				</header>

				<?php if( get_field('column') ): ?>
				<div class="columns three">
					<?php while( the_repeater_field('column') ): ?>
					<div class="column">
						<h5 class="headline"><?php echo esc_attr( _x( get_sub_field('headline'), 'toeknee') ) ?></h5>
						<?php echo add_spans_to_list_items( get_sub_field('content') ) ?>
					</div>
					<?php endwhile; ?>
				</div>
				<?php endif; ?>

			</div><!-- /.container -->
		</section>

		<?php if( get_field('goal') ): ?>
		<section class="container">
			<hr />

			<div class="goals-table">
				<div class="legend">
					<span class="little"></span>
					<span class="big" data-content="<?php _e('Goals', 'toeknee') ?>"><?php _e('Goals', 'toeknee') ?></span>
					<span class="little" data-content="<?php _e('in life', 'toeknee') ?>"><span><?php _e('in life', 'toeknee') ?></span></span>
				</div>
				<div class="goals">
					<div class="goal yes-please">
						<?php the_field('goal') ?>
					</div>
				</div><!-- /.goals -->
			</div><!-- /.goals-table -->
		</section>
		<?php endif; ?>

	</article>
<?php endwhile; ?>
<?php get_footer(); ?>