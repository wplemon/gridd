<?php
/**
 * Template part for the Header Search
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid_Part\Header;
use Gridd\Style;
use Gridd\Theme;

$header_search_mode  = get_theme_mod( 'header_search_mode', 'form' );
$header_search_class = 'gridd-tp gridd-tp-header_search ' . $header_search_mode;
$padding             = get_theme_mod(
	'header_search_padding',
	[
		'left'  => '1em',
		'right' => '1em',
	]
);
?>

<div <?php Theme::print_attributes( [ 'class' => $header_search_class ], 'wrapper-header_search' ); ?>>
	<?php
	/**
	 * Print styles.
	 */
	Style::get_instance( 'grid-part/header/search' )
		->add_file( get_theme_file_path( 'grid-parts/styles/header/styles-header-search.min.css' ) )
		->add_file( get_theme_file_path( "grid-parts/styles/header/styles-header-search-$header_search_mode.min.css" ) )
		->the_css( 'gridd-inline-css-header-search' );
	?>

	<div class="gridd-header-search inner" style="display:flex;align-items:center;width:100%;">
		<?php if ( 'slide-up' === $header_search_mode ) : ?>
			<?php
			/**
			 * Prints the button.
			 * No need to escape this, there's zero user input.
			 * Everything is hardcoded and things that need escaping
			 * are properly escaped in the function itself.
			 */
			echo Theme::get_toggle_button( // phpcs:ignore WordPress.Security.EscapeOutput
				[
					'context'                      => [ 'header-search' ],
					'expanded_state_id'            => 'griddHeaderSearch',
					'expanded'                     => 'false',
					'screen_reader_label_collapse' => __( 'Collapse Search', 'gridd' ),
					'screen_reader_label_expand'   => __( 'Expand Search', 'gridd' ),
					'screen_reader_label_toggle'   => __( 'Toggle Search', 'gridd' ),
					'label'                        => '<svg class="gridd-search-icon" style="width:1em;height:1em;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.809 21.646l-6.205-6.205c1.167-1.605 1.857-3.579 1.857-5.711 0-5.365-4.365-9.73-9.731-9.73-5.365 0-9.73 4.365-9.73 9.73 0 5.366 4.365 9.73 9.73 9.73 2.034 0 3.923-.627 5.487-1.698l6.238 6.238 2.354-2.354zm-20.955-11.916c0-3.792 3.085-6.877 6.877-6.877s6.877 3.085 6.877 6.877-3.085 6.877-6.877 6.877c-3.793 0-6.877-3.085-6.877-6.877z"/></svg>',
				]
			);
			?>
			<div class="header-searchform-wrapper">
				<?php get_search_form( true ); ?>
			</div>
		<?php else : ?>
			<?php get_search_form( true ); ?>
		<?php endif; ?>
	</div>
</div>
