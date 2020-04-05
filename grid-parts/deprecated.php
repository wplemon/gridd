<?php
/**
 * Backwards-compatibility for pre-migration grid-parts.
 *
 * @since 3.0.0
 * @package gridd
 */

/**
 * Backwards-compatibility for grid-parts.
 *
 * @since 3.0.0
 */
add_action(
	'gridd_the_grid_part',
	/**
	 * Do the grid-part.
	 *
	 * @since 3.0.0
	 * @param string $part The grid-part ID.
	 * @return void
	 */
	function( $part ) {

		$class_name = false;

		switch ( $part ) {
			case 'header_search':
				$class_name = '\Gridd\Upgrades\Block\Header_Search';
				break;

			case 'footer_copyright':
				$class_name = '\Gridd\Upgrades\Block\Footer_Copyright';
				break;

			case 'header_contact_info':
				$class_name = '\Gridd\Upgrades\Block\Header_Contact_info';
				break;
		}

		if ( $class_name ) {
			$migrator = new \Gridd\Upgrades\Block\Header_Search();
			$content  = $migrator->get_content();
			echo do_blocks( $content ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}
);
