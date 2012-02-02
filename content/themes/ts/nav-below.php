<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
<div id="nav-below" class="navigation">
	<p><?php 
	
	global $wp_rewrite;
	
	$pagination = array(
		'base' => @add_query_arg('page','%#%'),
		'type' => 'plain',
		'total' => $wp_query->max_num_pages,
		'current' => get_query_var('paged') ? get_query_var('paged') : 1,
		'mid_size' => 5
	);
	
	if( $wp_rewrite->using_permalinks() )
		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
	
	// Check if we're searching
	if( get_query_var('s') )
		$pagination['add_args'] = array( 's' => get_query_var( 's' ) );
	
	echo paginate_links( $pagination ); 
	
	?></p>	
</div><!-- #nav-below -->
<?php endif; ?>