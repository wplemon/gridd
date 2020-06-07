<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Gridd
 */

use Gridd\Theme;

get_header(); ?>

<?php if ( have_posts() ) { ?>

	<header class="page-header container">
		<h1 class="page-title">
			<?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Search Results for: %s', 'gridd' ), '<span>' . get_search_query() . '</span>' );
			?>
		</h1>
	</header>

	<?php
	while ( have_posts() ) {
		the_post();

		/**
		 * Run the loop for the search to output the results.
		 * If you want to overload this in a child theme then include a file
		 * called content-search.php and that will be used instead.
		 */
		Theme::get_template_part( 'template-parts/content', 'search' );
	}

	the_posts_navigation();

} else {
	Theme::get_template_part( 'template-parts/content', 'none' );
}

get_sidebar();
get_footer();
