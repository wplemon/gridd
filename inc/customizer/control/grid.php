<?php
/**
 * Gridd - the grid control.
 *
 * @package Gridd
 * @since 1.0
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd\Customizer\Control;

use Gridd\Grid_Parts;

/**
 * The grid control.
 *
 * @since 1.0
 */
class Grid extends \Kirki_Control_Base {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'gridd_grid';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {

		// Enqueue ColorPicker.
		wp_enqueue_script( 'wp-color-picker-alpha', get_template_directory_uri() . '/assets/js/vendor/wp-color-picker-alpha.js', [ 'wp-color-picker' ], '2.0', true );

		// Enqueue the script and style.
		wp_enqueue_script( 'gridd-grid-control', get_template_directory_uri() . '/assets/js/customizer/gridd-grid-control.js', [ 'jquery', 'customize-base', 'wp-color-picker-alpha' ], GRIDD_VERSION, false );

		wp_localize_script(
			'gridd-grid-control',
			'griddGridControl',
			[
				'l10n'        => [
					'add'    => esc_attr__( 'Add', 'gridd' ),
					'resize' => esc_attr__( 'Resize', 'gridd' ),
					'edit'   => esc_attr__( 'Edit', 'gridd' ),
					'delete' => esc_attr__( 'Delete', 'gridd' ),
					'whatis' => [
						/* translators: The Column number. */
						'columnWidth' => esc_attr__( 'Column %d Width', 'gridd' ),
						/* translators: The Row number. */
						'rowHeight'   => esc_attr__( 'Row %d Height', 'gridd' ),
					],
				],
				'nestedParts' => Grid_Parts::get_instance()->get_grids(),
			]
		);
		wp_enqueue_style( 'gridd-grid-control', get_template_directory_uri() . '/assets/css/customizer/gridd-grid-control.css', [ 'wp-color-picker' ], GRIDD_VERSION );
	}

	/**
	 * Render the control's content.
	 *
	 * @access protected
	 * @see WP_Customize_Control::render_content()
	 * @since 1.0
	 */
	protected function render_content() {
		$grid_parts = $this->choices['parts'];
		$value      = $this->value();
		// Sort parts alphabetically.
		usort( $grid_parts, function ($a, $b ) {
			return strcmp( $a['label'], $b['label'] );
		} );
		?>
		<!-- Label. -->
		<span class="customize-control-title">
			<?php echo $this->label; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</span>

		<!-- Description. -->
		<span class="description customize-control-description">
			<?php echo $this->description; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</span>

		<!-- Grid Builder. -->
		<div class="gridd-grid-builder">

			<!-- Exit edit mode button. -->
			<?php foreach ( $grid_parts as $part ) : ?>
				<div class="template-part-advanced-options hidden" data-template-part-id="<?php echo esc_attr( $part['id'] ); ?>">
					<button class="button button-primary edit-part-options-done" data-template-part-id="<?php echo esc_attr( $part['id'] ); ?>">
						<?php esc_html_e( 'Exit edit mode', 'gridd' ); ?>
					</button>
				</div>
			<?php endforeach; ?>

			<!-- Grid builder. -->
			<div class="grid-tab">
				<div class="gridd-grid-builder-grids-wrapper">
					<!-- Add action buttons. -->
					<div class="map-builder-actions">
						<button class="button button-secondary button add-column"><?php esc_html_e( '+ Column', 'gridd' ); ?></button>
						<button class="button button-secondary button remove-column"><?php esc_html_e( '- Column', 'gridd' ); ?></button>
						<button class="button button-secondary button add-row"><?php esc_html_e( '+ Row', 'gridd' ); ?></button>
						<button class="button button-secondary button remove-row"><?php esc_html_e( '- Row', 'gridd' ); ?></button>
					</div>

					<button class="grid-whatis"><span class="dashicons dashicons-editor-help"></span></button>
					<div class="gridd-grid-builder-columns"></div>
					<div class="gridd-grid-builder-rows"></div>

					<!-- Add a grid for each template part. -->
					<div class="gridd-grid-builder-grids">
						<div class="gridd-grid-selected"></div>
						<div id="gridd-grid-selectable-<?php echo esc_attr( $this->id ); ?>" class="gridd-grid-selectable"></div>
						<div class="gridd-grid-part-selector" style="display:none;">
							<h2><?php esc_html_e( 'Select Grid Part for this area.', 'gridd' ); ?></h2>
							<select class="grid-part-select">
								<option value="" selected="selected"><?php esc_html_e( 'Select a grid-part to add.', 'gridd' ); ?></option>
								<?php foreach ( $grid_parts as $part ) : ?>
									<?php if ( ! isset( $part['hidden'] ) || ! $part['hidden'] ) : ?>
										<option value="<?php echo esc_attr( $part['id'] ); ?>"><?php echo esc_html( $part['label'] ); ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
							<button class="gridd-grid-part-selector-cancel button"><?php esc_html_e( 'Cancel', 'gridd' ); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<input class="gridd-grid-hidden-value" type="hidden" value="<?php echo esc_attr( wp_json_encode( $value ) ); ?>" <?php $this->link(); ?>>
		<?php
	}
}
