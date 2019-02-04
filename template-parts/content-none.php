<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package Gridd
 * @since 1.0
 */

?>

<section class="no-results not-found">
	<header class="page-header container">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'gridd' ); ?></h1>
	</header>

	<div class="page-content container">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p>
				<?php
				printf(
					/* translators: Link to WP admin new post page ("Get started here" text). */
					esc_html__( 'Ready to publish your first post? %s.', 'gridd' ),
					'<a href="' . esc_url( admin_url( 'post-new.php' ) ) . '">' . esc_html__( 'Get started here', 'gridd' ) . '</a>'
				);
				?>
			</p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'gridd' ); ?></p>
			<?php get_search_form(); ?>

		<?php else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'gridd' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div>
</section>
