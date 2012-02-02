<?php
/**
 * The template for displaying the footer.
 *
 * @package toekneestuck
 * @subpackage toekneestuck
 * @since toekneestuck 2.0
 */
?>

<footer id="footer">
	<div class="container">
		<?php wp_nav_menu(array(
			'theme_location' => 'primary',
			'container' => 'nav',
			'container_class' => null,
			'container_id' => null
		)); ?>
		<p class="copyright">&copy; <?php echo date('Y') ?> Copyright Tony Stuck. <?php bloginfo('description'); ?></p>
	</div>
</footer>

<?php if( !wp_script_is('jquery', 'done') ): ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<?php endif; ?>

<script type="text/javascript">if( !jQuery ){document.write(unescape("%3Cscript type='text/javascript' src='<?php echo home_url( get_bloginfo('template_url') . '/js/libs/jquery-1.7.1.min.js' ); ?>'%3E%3C/script%3E"));}</script>

<?php wp_footer(); ?>
</body>
</html>
