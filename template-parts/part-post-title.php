<?php
/**
 * Template part.
 *
 * @package Gridd
 * @since 1.0.3
 */

if ( is_singular() ) :
	?>
	<header class="entry-header">
		<div class="container">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</div>
	</header>
<?php else : ?>
	<header class="entry-header">
		<div class="container">
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		</div>
	</header>
	<?php
endif;

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
