<?php 
	
	$types = get_terms('project_type'); 
	
?>
<nav class="clearfix">
	<ul id="sort-type">
		<li><a data-type="all" href="/portfolio/"<?php if( is_post_type_archive('projects') ) echo ' class="active"'; ?>><?php _e('all projects') ?></a></li>
		<?php foreach($types as $type): ?>
		<li><?php printf('<a data-type="%1$s" href="%2$s" title="%3$s"%4$s>%5$s</a>',
			strtolower( sanitize_html_class($type->name) ),
			get_term_link($type, 'project_type'),
			ucwords($type->name),
			get_queried_object()->name == $type->name ? ' class="active"' : '',
			$type->name
		); ?></li>
		<?php endforeach; ?>
	</ul>
	<ul id="sort-date">
		<li><a data-type="date-desc" href="#" title="Show Newest First" class="active">newest first</a></li>
		<li><a data-type="date-asc" href="#" title="Show Oldest First">oldest first</a></li>
	</ul>
</nav>