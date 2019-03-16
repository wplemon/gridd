<?php
/**
 * Template part for displaying the post-tags.
 *
 * @package Gridd
 * @since 1.0
 */

$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'gridd' ) );
?>
<?php if ( $tags_list ) : ?>
	<div class="entry-tags gridd-contain">
		<span class="tags-links">
			<?php
			printf(
				/* translators: 1: list of tags. */
				esc_html__( 'Tagged %1$s', 'gridd' ),
				wp_kses_post( $tags_list )
			);
			?>
		</span>
	</div>
<?php endif; ?>
