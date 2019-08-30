<?php
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;
use Gridd\Style;

?>
<?php if ( has_post_thumbnail() ) : ?>
	<?php
	/**
	 * Add styles.
	 */
	$style = Style::get_instance( 'post-featured-image-header' );
	$style->add_file( get_theme_file_path( 'assets/css/core/featured-image-post-header.min.css' ) );
	$padding = get_theme_mod(
		'gridd_grid_content_padding',
		[
			'top'    => 0,
			'bottom' => 0,
			'left'   => '20px',
			'right'  => '20px',
		]
	);
	$style->add_vars(
		[
			'--c-pd-l' => $padding['left'],
			'--c-pd-r' => $padding['right'],
			'--c-mw'   => get_theme_mod( 'gridd_grid_content_max_width', '45em' ),
		]
	);
	$style->the_css( 'post-featured-image-header' );
	?>

	<div class="gridd-featured-image-post-header">
		<div class="gridd-featured-image-post-header-image-wrapper"><?php the_post_thumbnail(); ?></div>
		<div class="gridd-featured-image-post-header-overlay"></div>
		<div class="gridd-featured-image-post-header-content">
			<div class="inner">
<?php endif; ?>
				<?php Theme::get_template_part( 'template-parts/part-post-title', get_post_type( $post->ID ) ); ?>
				<?php Theme::get_template_part( 'template-parts/part-post-date-author', get_post_type( $post->ID ) ); ?>

<?php if ( has_post_thumbnail() ) : ?>
			</div>
		</div>
	</div>
<?php endif; ?>
