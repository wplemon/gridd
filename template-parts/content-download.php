<?php
/**
 * Template part for displaying posts
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;

if ( is_singular() ) {
	Theme::get_template_part( 'template-parts/content-download', 'single' );
} else {
	Theme::get_template_part( 'template-parts/content-download', 'grid' );
}
