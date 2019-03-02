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
						<button class="button add-column" title="<?php esc_attr_e( 'Append column', 'gridd' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"><path d="M15 0v25h10V0zm3.514 8.66h2.973v2.348h2.348v2.983h-2.348v2.349h-2.973V13.99h-2.349v-2.983h2.349z"/><path d="M10.154.774h.774v2.204H9.38v-1.43h-.774V0h1.548zm-3.095.774H3.963V0h3.096zm-4.644 0h-.867v.68H0V0h2.415zM1.548 6.87H0V3.776h1.548zm9.38.75H9.38V4.526h1.548zm-9.38 3.894H0V8.419h1.548zm9.38.75H9.38V9.168h1.548zm-9.38 3.893H0v-3.095h1.548zm9.38.752H9.38v-3.096h1.548zM1.548 20.8H0v-3.095h1.548zm9.38.752H9.38v-3.096h1.548zm-9.38 1.9h.445V25H0V22.35h1.548zm9.38.773V25H8.184v-1.548H9.38v-.351h1.548zM6.636 25H3.541v-1.548h3.095z" color="#000" font-family="sans-serif" font-weight="400" overflow="visible" style="line-height:normal;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000;text-transform:none;text-orientation:mixed;shape-padding:0;isolation:auto;mix-blend-mode:normal" white-space="normal"/></svg>
							<span class="screen-reader-text"><?php esc_html_e( 'Append column', 'gridd' ); ?></span>
						</button>
						<button class="button remove-column" title="<?php esc_attr_e( 'Remove last column', 'gridd' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"><path d="M23.835 11.008v2.983h-7.67v-2.983z"/><path d="M10.154.774h.774v2.204H9.38v-1.43h-.774V0h1.548zm-3.095.774H3.963V0h3.096zm-4.644 0h-.867v.68H0V0h2.415zM1.548 6.87H0V3.776h1.548zm9.38.75H9.38V4.526h1.548zm-9.38 3.894H0V8.419h1.548zm9.38.75H9.38V9.168h1.548zm-9.38 3.893H0v-3.095h1.548zm9.38.752H9.38v-3.096h1.548zM1.548 20.8H0v-3.095h1.548zm9.38.752H9.38v-3.096h1.548zm-9.38 1.9h.445V25H0V22.35h1.548zm9.38.773V25H8.184v-1.548H9.38v-.351h1.548zM6.636 25H3.541v-1.548h3.095z" color="#000" font-family="sans-serif" font-weight="400" overflow="visible" style="line-height:normal;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000;text-transform:none;text-orientation:mixed;shape-padding:0;isolation:auto;mix-blend-mode:normal" white-space="normal"/></svg>
							<span class="screen-reader-text"><?php esc_html_e( 'Remove last column', 'gridd' ); ?></span>
						</button>
						<button class="button add-row" title="<?php esc_html_e( 'Append Row', 'gridd' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"><path d="M25 15H0v10h25zm-8.66 3.514v2.973h-2.348v2.348h-2.983v-2.348H8.66v-2.973h2.349v-2.349h2.983v2.349z"/><path d="M24.226 10.154v.774h-2.204V9.38h1.43v-.774H25v1.548zm-.774-3.095V3.963H25v3.096zm0-4.644v-.867h-.68V0H25v2.415zm-5.323-.867V0h3.095v1.548zm-.75 9.38V9.38h3.095v1.548zm-3.894-9.38V0h3.096v1.548zm-.75 9.38V9.38h3.096v1.548zm-3.893-9.38V0h3.095v1.548zm-.752 9.38V9.38h3.096v1.548zM4.2 1.548V0h3.095v1.548zm-.752 9.38V9.38h3.096v1.548zm-1.9-9.38v.445H0V0H2.65v1.548zm-.773 9.38H0V8.184h1.548V9.38h.351v1.548zM0 6.636V3.541h1.548v3.095z" color="#000" font-family="sans-serif" font-weight="400" overflow="visible" white-space="normal" style="line-height:normal;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000;text-transform:none;text-orientation:mixed;shape-padding:0;isolation:auto;mix-blend-mode:normal"/></svg>
							<span class="screen-reader-text"><?php esc_html_e( 'Append Row', 'gridd' ); ?>
						</button>
						<button class="button remove-row" title="<?php esc_html_e( 'Remove last row', 'gridd' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"><path d="M16.34 18.514v2.973H8.66v-2.973z"/><path d="M24.226 10.154v.774h-2.204V9.38h1.43v-.774H25v1.548zm-.774-3.095V3.963H25v3.096zm0-4.644v-.867h-.68V0H25v2.415zm-5.324-.867V0h3.096v1.548zm-.75 9.38V9.38h3.096v1.548zm-3.893-9.38V0h3.096v1.548zm-.75 9.38V9.38h3.096v1.548zm-3.893-9.38V0h3.095v1.548zm-.752 9.38V9.38h3.096v1.548zm-3.892-9.38V0h3.096v1.548zm-.751 9.38V9.38h3.096v1.548zm-1.9-9.38v.445H0V0h2.649v1.548zm-.773 9.38H0V8.184h1.548V9.38h.351v1.548zM0 6.636V3.541h1.548v3.095z" color="#000" font-family="sans-serif" font-weight="400" overflow="visible" style="line-height:normal;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000;text-transform:none;text-orientation:mixed;shape-padding:0;isolation:auto;mix-blend-mode:normal" white-space="normal"/></svg>
							<span class="screen-reader-text"><?php esc_html_e( 'Remove last row', 'gridd' ); ?></span>
						</button>
						<button class="button gridd-grid-zoom-in" title="<?php esc_attr( 'Zoom In', 'gridd' ); ?>">
							<span class="screen-reader-text"><?php esc_html( 'Zoom In', 'gridd' ); ?></span>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6.426 10.668l-3.547-3.547-2.879 2.879v-10h10l-2.879 2.879 3.547 3.547-4.242 4.242zm11.148 2.664l3.547 3.547 2.879-2.879v10h-10l2.879-2.879-3.547-3.547 4.242-4.242zm-6.906 4.242l-3.547 3.547 2.879 2.879h-10v-10l2.879 2.879 3.547-3.547 4.242 4.242zm2.664-11.148l3.547-3.547-2.879-2.879h10v10l-2.879-2.879-3.547 3.547-4.242-4.242z"/></svg>
						</button>
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
