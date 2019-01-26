<?php
/**
 * EDD images used in shortcodes
 *
 * @package Gridd
 */

?>
<?php if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( get_the_ID() ) && ! is_singular( 'download' ) ) : ?>
	<div class="edd_download_image">
		<a href="<?php the_permalink(); ?>">
			<?php echo get_the_post_thumbnail( get_the_ID(), 'large' ); ?>
		</a>
	</div>
<?php endif; ?>
