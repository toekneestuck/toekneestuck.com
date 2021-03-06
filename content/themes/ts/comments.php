<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to toeknee_comment which is
 * located in the functions.php file.
 *
 * @package toekneestuck
 * @subpackage toekneestuck
 * @since toekneestuck 2.0
 */
?>

<section id="comments">
<?php if ( post_password_required() ) : ?>
	<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'toeknee' ); ?></p>
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;

if ( have_comments() ) : ?>
	<p class="social left">
		<a href="<?php echo get_post_comments_feed_link() ?>" class="rss" title="<?php _e('Subscribe to the Comments Feed', 'toeknee') ?>" data-twipsy="true"><span><?php _e('Subscribe to the Comments Feed', 'toeknee') ?></span></a>
	</p>
	<h3 class="comments-title"><?php
	printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'toeknee' ),
	number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
	?></h3>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
	<div class="navigation">
		<div class="nav-previous left"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'toeknee' ) ); ?></div>
		<div class="nav-next right"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'toeknee' ) ); ?></div>
	</div> <!-- /.navigation -->
	<?php endif; // check for comment navigation ?>

	<ol class="commentlist">
		<?php wp_list_comments( array( 'callback' => 'toeknee_comment' ) ); ?>
	</ol>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
	<div class="navigation">
		<div class="nav-previous left"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'toeknee' ) ); ?></div>
		<div class="nav-next right"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'toeknee' ) ); ?></div>
	</div><!-- /.navigation -->
	<?php endif; // check for comment navigation ?>

<?php else : // or, if we don't have comments: ?>
	<?php if ( ! comments_open() ) : ?><p class="nocomments"><?php _e( 'Comments are closed.', 'toeknee' ); ?></p><?php endif; // end ! comments_open() ?>
<?php endif; // end have_comments() ?>

<?php comment_form(); ?>
</section><!-- /#comments -->