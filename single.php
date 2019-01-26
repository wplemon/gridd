<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Gridd
 */

get_header();

while ( have_posts() ) {
	the_post();

	get_template_part( 'template-parts/content', get_post_type() );
	if ( get_theme_mod( 'gridd_show_next_prev', true ) ) {
		the_post_navigation();
	}

	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}

get_sidebar();
get_footer();
