<?php
/**
 * The Template for displaying all individual projects.
 *
 * @package toekneestuck
 * @since toekneestuck 2.0
 */

if( get_query_var('toeknee_real_title') ){

	function update_page_title( $title, $sep, $seplocation ){
		return sprintf('%s %s %s', get_query_var('toeknee_real_title'), $sep, $title);
	}
	add_filter('wp_title', 'update_page_title', 10, 3);
}

get_header();

if( have_posts() ) while ( have_posts() ) : the_post();

	$images = get_post_images( array('post_parent' => get_the_ID() ) );

	if( get_query_var('toeknee_attachment_id') ){
		$active = array_filter($images, 'search_for_active_attachment' );
		$active = !empty($active) ? array_shift($active) : $images[0];
		$show_intro = false;
	}else{
		$active = $images[0];
		$show_intro = get_post_meta( get_the_ID(), 'show_intro', true);
	}

	$current_image = get_image_details( $active, 'portfolio' );
	$url = get_post_meta( get_the_ID(), 'url', true );
?>
<article role="main" id="main" <?php post_class('project') ?>>

	<header class="container headline">
		<hgroup>
			<h1><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h1>
			<h6 class="meta">
				<?php the_date('F Y') ?>
				<?php $tax = get_the_term_list( get_the_ID(), 'project_type', null, ', '); if( $tax ): ?> &bull; filed under: <span class="cat-links"><?php echo $tax; ?></span><?php endif; ?></h6>
		</hgroup>
	</header>

	<?php if( count( $images ) > 1 ): ?>
	<section class="project-nav-container">
		<div class="container clearfix">
			<nav id="project-nav" class="project-nav" role="navigation">
			<?php foreach( $images as $key=>$thumb ):
				printf('<li data-id="%1$s"%2$s>%3$s</li>',
					$thumb->ID,
					($thumb->ID == $active->ID && !$show_intro) ? ' class="active"' : '',
					wp_get_attachment_link($thumb->ID, 'thumbnail', true)
				);
			 		endforeach; ?>
			</nav>
		</div><!-- /.row -->
	</section><!-- /.container -->
	<?php endif; ?>

	<div class="accent playpen-container clearfix">
		<div class="container clearfix">
			<section id="playpen" role="main" class="clearfix">
				<?php if( $show_intro ): ?>
				<div class="display intro" data-id="0"><?php the_content(); ?></div>
				<?php else: ?>
				<div class="display" data-id="<?php echo $current_image['id'] ?>">
					<?php echo $current_image['html']; ?>
				</div>
				<div class="details" data-id="<?php echo $current_image['id']; ?>">
					<h2 class="title"><?php echo $current_image['title'] ?></h2>
					<div class="description">
						<?php echo $current_image['desc']; ?>
						<?php if( !empty($url) ): ?><p><a href="<?php echo $url ?>" class="button" target="_blank">View the Site &raquo;</a></p><?php endif ?>
					</div>
				</div><!-- /#details -->
				<?php endif; ?>
			</section><!-- /#playpen -->
		</div><!-- /.container -->
	</div><!-- /.playpen-container -->

</article><!-- /.project -->

<script id="proj-tmpl" type="text/x-template">
	<div class="display" data-id="<%=id %>"><%=html %></div>
	<div class="details" data-id="<%=id %>">
		<h2 class="title"><%=title %></h2>
		<div class="description">
			<%=desc %>
			<?php if( !empty($url) ): ?><p><a href="<?php echo $url ?>" class="button" target="_blank">View the Site &raquo;</a></p><?php endif ?>
		</div>
	</div>
</script>

<?php endwhile; // end of the loop.

get_footer(); ?>