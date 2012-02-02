<?php
/**
 * @package toekneestuck
 * @subpackage toekneestuck
 */
?><!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="no-js oldie ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js oldie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js oldie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
<meta http-equiv="Content-Language" content="<?php bloginfo('charset') ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />

<title><?php wp_title( '|', true, 'right' ); bloginfo('blogname') ?></title>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="shortcut icon" href="<?php echo home_url( get_bloginfo('template_url') ); ?>/assets/favicon.png" />
<!--
<link rel="apple-touch-icon" href="<?php echo home_url(get_bloginfo('template_url')); ?>/img/apple-touch-icon-iphone.png" />
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo home_url(get_bloginfo('template_url')); ?>/img/apple-touch-icon-ipad.png" />
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo home_url(get_bloginfo('template_url')); ?>/img/apple-touch-icon-iphone4.png" />
-->
<?php wp_head(); ?>
<!--[if lt IE 9]><script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script><![endif]-->
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
</head>

<body <?php body_class( is_single() ? get_post_type() : '' ); ?>>

<header id="header">
	<div class="bar">
		<div class="container clearfix">
			<?php wp_nav_menu(array(
				'theme_location' => 'primary',
				'container' => false,
				'depth' => 1,
				'items_wrap' => '<nav id="%1$s" class="%2$s">%3$s</nav>'
			)); ?>

			<?php the_widget('Social_Widget', array(
				'nested' => true,
				'show_title' => false
			), array(
				'before_widget' => '',
				'after_widget' => ''
			)); ?>
		</div>
	</div>
	<div class="logo">
		<p class="container" id="logo">
			<a href="<?php echo home_url('/') ?>">Tony Stuck</a>
		</p>
	</div>
</header>