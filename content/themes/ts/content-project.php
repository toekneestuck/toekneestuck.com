<?php
/**
 * The default template for displaying content
 *
 * @package toekneestuck
 * @subpackage toekneestuck 2.0
 */

$terms = (array) get_the_terms( get_the_ID(), 'project_type');
$data_id = sanitize_html_class( 'id-' . get_the_ID() );
$data_cats = strtolower( implode(' ', sanitize_html_class( array_map( 'toeknee_get_term_name', $terms) ) ) );
$data_date = strtotime( get_the_date(DATE_RFC822) );
 
?>

<li data-id="<?php echo $data_id ?>" data-cats="<?php echo $data_cats ?>" data-date="<?php echo $data_date ?>">
	<div class="project-item-container">
		<?php printf( '<a class="thumbnail" href="%1$s" title="View Details of %2$s">%3$s</a>', 
			get_permalink(),
			get_the_title(),
			get_the_post_thumbnail( get_the_ID(), 'portfolio-thumb' ) ? 
				get_the_post_thumbnail( get_the_ID(), 'portfolio-thumb', array('title' => the_title_attribute(array('echo'=>false)) ) ) : 
				sprintf('<img src="$s" height="125" width="250" alt="No image available." />', home_url( get_bloginfo('template_url') .  '/assets/no-image.png'))
		); ?>
		<h4 class="h6 title"><a href="<?php the_permalink() ?>" title="View Details of <?php the_title() ?>"><?php the_title(); ?></a></h4>
		<div class="details">
			<p><?php the_date('F Y') ?></p>
		</div>
	</div><!-- /.project-item-container -->
</li>