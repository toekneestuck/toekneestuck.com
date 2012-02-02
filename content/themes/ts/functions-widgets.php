<?php 
/**
 * Custom Widgets
 *
 * @package toekneestuck
 * @subpackage toekneestuck
 * @since toekneestuck 2.0
 */

/********************
 * Social Widget
 *
 */
class Social_Widget extends WP_Widget {
	
	function Social_Widget() {
		$ops = array('description' => 'Displays a list of your social networks' );
		parent::WP_Widget(false, 'Social Networks', $ops);	
	}
	
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Social Networks', 'show_title' => true, 'networks' => '' ) );
		$title = strip_tags($instance['title']);
		$show_title = empty($instance['show_title']) ? false : true;
		$social_links = get_bookmarks(array('category_name' => 'social'));
		$wanted_networks = (array) unserialize($instance['networks']);
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('show_title') ?>"><input type="checkbox" id="<?php echo $this->get_field_id('show_title') ?>" name="<?php echo $this->get_field_name('show_title') ?>" <?php checked( $show_title, true ) ?> /> Show Title</label>
		</p>
		<hr />
		<p><label for="<?php echo $this->get_field_id('networks'); ?>">Networks:</label><br />
	<?php foreach( $social_links as $link ): ?>
		<?php printf('<input id="%1$s[%3$s]" name="%2$s[%3$s]" type="checkbox" %4$s /> %3$s',
			$this->get_field_id('networks'),
			$this->get_field_name('networks'),
			$link->link_name,
			checked( array_key_exists( $link->link_name, $wanted_networks ), true, false ) 
		); ?><br />
	<?php endforeach; ?>
		</p>
		<?php
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['show_title'] = empty($new_instance['show_title']) ? false : true;
		$instance['networks'] = serialize( $new_instance['networks'] );
		return $instance;
	}
	
	function widget($args, $instance){
		extract($args);
		
		$title = empty($instance['title']) ? _x('Social Networks', 'toeknee') : apply_filters('social_networks_widget_title', $instance['title']);
		$show_title = empty($instance['show_title']) ? false : true;
		$social_links = get_bookmarks(array('category_name' => 'social', 'orderby' => 'rating'));
		$networks = isset($instance['networks']) ? unserialize($instance['networks']) : array();
		$nested = empty($instance['nested']) ? false : true;
		
		echo $before_widget;
		if( $show_title )
			echo $before_title . $title . $after_title;
		echo "<ul class='social'>";
		foreach( $social_links as $key=>$link ){
			if( array_key_exists( $link->link_name, $networks ? $networks : array() ) || $nested ){
				if( $nested && $key > 4 ){
					$more_networks[] = $link;
				}else{
					printf('<li><a href="%1$s" class="%2$s" title="%3$s %4$s"><span>%3$s</span> %4$s</a></li>',
						$link->link_url,
						sanitize_html_class( strtolower($link->link_name) ),
						$link->link_description,
						$link->link_name
					);
				}
			}
		}
		
		if( isset($more_networks) && !empty($more_networks) ){
			echo '<li><a href="#" class="more"><span>More of My Networks</span></a><ul>';
			
			foreach( $more_networks as $link ){
				printf('<li><a href="%1$s" class="%2$s" title="%3$s %4$s"><span>%3$s</span> %4$s</a></li>',
					$link->link_url,
					sanitize_html_class( strtolower($link->link_name) ),
					$link->link_description,
					$link->link_name
				);
			}
			
			echo '</ul></li>';
		}
		
		echo "</ul>";
		echo $after_widget;
	}
}


/********************
 * Featured Projects Widget
 *
 */
class Featured_Projects_Widget extends WP_Widget {
	
	function Featured_Projects_Widget() {
		$ops = array('description' => _x('Displays your featured projects.', 'toeknee') );
		parent::WP_Widget(false, _x('Featured Projects', 'toeknee'), $ops);	
	}
	
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => _x('Featured Projects', 'toeknee'), 'show_title' => true, 'numposts' => 4 ) );
		$title = strip_tags($instance['title']);
		$show_title = empty($instance['show_title']) ? false : true;
		$num = strip_tags($instance['numposts']);
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('show_title') ?>"><input type="checkbox" id="<?php echo $this->get_field_id('show_title') ?>" name="<?php echo $this->get_field_name('show_title') ?>" <?php checked( $show_title, true ) ?> /> Show Title</label>
		</p>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Number of Posts to Show: <input class="widefat" id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo esc_attr($num); ?>" /></label></p>
		<?php
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['show_title'] = empty($new_instance['show_title']) ? false : true;
		$instance['numposts'] = strip_tags( $new_instance['numposts'] );
		return $instance;
	}
	
	function widget($args, $instance){
		global $post;
		
		extract($args);
		
		$title = empty($instance['title']) ? _x('Featured Projects', 'toeknee') : _x($instance['title'], 'toeknee');
		$show_title = empty($instance['show_title']) ? false : true;
		$num = empty($instance['numposts']) ? 4 : $instance['numposts'];
		
		$projects = get_posts( array(
			'post_type' => 'projects',
			'numposts' => $num,
			'meta_key' => 'featured',
			'meta_value' => 1
		) );
		
		echo $before_widget;
		if( $show_title )
			echo $before_title . $title . $after_title;
		echo "<ul class='featured-projects clearfix'>";
		foreach( $projects as $post ){ 
			setup_postdata( $post );
			
			if( has_post_thumbnail() ){
				printf('<li><a href="%s" title="%s">%s</a></li>', 
					get_permalink(),
					esc_attr( get_the_title() ),
					get_the_post_thumbnail( get_the_ID(), 'portfolio-thumb' )
				);
			}
		}
		echo "</ul>";
		echo "<a href='/portfolio/' class='btn'>" . _x('View them All', 'toeknee') . "</a>";
		echo $after_widget;
		
		wp_reset_postdata();
	}
}

 ?>