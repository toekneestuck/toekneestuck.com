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
</div><!-- /.content-wrapper -->

<?php if( !wp_script_is('jquery', 'done') ): wp_deregister_script('jquery'); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<?php endif; ?>
<script type="text/javascript">if( !jQuery ){document.write(unescape("%3Cscript type='text/javascript' src='<?php echo home_url( get_bloginfo('template_url') . '/js/libs/jquery-1.7.1.min.js' ); ?>'%3E%3C/script%3E"));}</script>
<script defer type="text/javascript" src="<?php echo home_url( get_bloginfo('template_url') . '/js/min/main.min.js' ) ?>"></script>
<?php wp_footer(); ?>
<!--[if lt IE 9]>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
<script type="text/javascript">try{ CFInstall.check({mode:'overlay'}) }catch(e){}</script>
<![endif]-->
</body>
</html>
