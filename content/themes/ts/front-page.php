<?php
/**
 * The template for displaying the home page.
 *
 * @package toekneestuck
 * @subpackage toekneestuck
 * @since toekneestuck 2.0
 */

get_header(); 

if( have_posts() ) while( have_posts() ): the_post(); ?>

<section class="container greeting center">
	<header>
		<?php the_content(); ?>
	</header>
</section>

<?php endwhile; ?>

<section class="highlight">
	<div class="container">
		<h5 class="headline"><?php _e( get_post_meta( get_the_ID(), 'home_blog_headline', true) , 'toeknee'); ?></h5>
		<ul class="articles clearfix">
			<?php
				$total = 4;
				$latest_posts = get_posts( array('numberposts' => $total) );
				foreach( $latest_posts as $key=>$post ): 
					setup_postdata($post);
			?>
			<li>
				<article <?php post_class() ?>>
					<header>
						<h2 class="title"><?php  
							printf('<a href="%1$s" title="%2$s">%3$s</a>',
								get_permalink(),
								sprintf( esc_attr__( 'Permalink to %s', 'toeknee' ), the_title_attribute( 'echo=0' )),
								get_the_title()
							);
						?></h2>
					</header>
					<div class="entry-summary">
						<?php the_excerpt() ?>
					</div>
					<footer>
						<span class="entry-date"><?php toeknee_the_date();?></span> in <span class="entry-category"><?php 
							$cats = get_the_category(); 
							echo '<a href="'.get_category_link( $cats[0]->term_id ).'">' . $cats[0]->cat_name . '</a>';
						?></span> &bull; 
						<span class="comments-link"><?php 
							comments_popup_link( 
								__( '0', 'toeknee' ), 
								__( '1', 'toeknee' ), 
								__( '%', 'toeknee' ) 
							); ?></span>
					</footer>
				</article>
			</li>
			<?php endforeach; wp_reset_postdata(); ?>
		</ul>
	</div>
</section>

<?php get_footer(); ?>