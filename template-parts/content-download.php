<?php
/**
 * Template part for displaying posts
 *
 * @package Gridd
 * @since 1.0
 */

if ( is_singular() ) {
	gridd_get_template_part( 'template-parts/content-download', 'single' );
} else {
	gridd_get_template_part( 'template-parts/content-download', 'grid' );
}
