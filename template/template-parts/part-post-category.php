<?php
/**
 * Template part for displaying the post-category(ies).
 *
 * @package Gridd
 * @since 1.0
 */

/* translators: used between list items, there is a space after the comma. */
$categories_list = get_the_category_list( esc_html__( ', ', 'gridd' ) );
?>
<?php if ( $categories_list ) : ?>
	<div class="entry-category gridd-contain">
		<span class="cat-links">
			<?php
			/* translators: 1: list of categories. */
			printf( esc_html__( 'Posted in %1$s', 'gridd' ), $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</span>
	</div>
<?php endif; ?>
