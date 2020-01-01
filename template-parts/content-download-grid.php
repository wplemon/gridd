<?php
/**
 * Template part for displaying posts
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> aria-label="<?php echo esc_attr( get_the_title() ); ?>">
	<header class="entry-header">
		<?php Theme::get_template_part( 'template-parts/thumbnail-download' ); ?>
		<?php the_title( '<h2 class="entry-title h4"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
	</header>
	<div class="entry-content">
		<?php
		echo '<div class="gridd-excerpt-container">';
		the_excerpt();
		echo '</div>';
		?>
		<?php if ( edd_has_variable_prices( $post->ID ) ) : ?>
			<a class="edd-add-to-cart button" href="<?php the_permalink( $post->ID ); ?>">
				<?php esc_html_e( 'View Product Details', 'gridd' ); ?>
			</a>
		<?php else : ?>
			<?php echo do_shortcode( '[purchase_link id="' . $post->ID . '" style="button"]' ); ?>
		<?php endif; ?>
	</div>
</article>
