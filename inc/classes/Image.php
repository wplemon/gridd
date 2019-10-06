<?php // phpcs:ignoreFile
/**
 * Image handling class.
 * Initially implemented for the Shoestrap theme
 * and then abstracted for general use.
 *
 * @package Ari_Image
 * @since 1.0.0
 */

namespace Gridd;

/**
 * The Image handling class
 */
class Image {

	/**
	 * The image ID.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @var int
	 */
	protected $id;

	/**
	 * The image URL.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @var string
	 */
	protected $url;

	/**
	 * The image width.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @var int
	 */
	protected $width;

	/**
	 * The image height.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @var int
	 */
	protected $height;

	/**
	 * An array of instances.
	 *
	 * @static
	 * @access private
	 * @since 1.0.0
	 * @var array
	 */
	private static $instances = array();

	/**
	 * Constructor.
	 *
	 * @access private
	 * @since 1.0.0
	 * @param int|string|array $image If numeric, assume image ID.
	 *                                If other string, assume URL.
	 */
	private function __construct( $image ) {

		$this->id     = (int) $image;
		$image_array  = wp_get_attachment_image_src( $this->id, 'full' );
		$this->url    = $image_array[0];
		$this->width  = $image_array[1];
		$this->height = $image_array[2];

	}

	/**
	 * Get an instance of this object.
	 *
	 * @static
	 * @access public
	 * @since 1.0.0
	 * @param int|string|array $image If numeric, assume image ID.
	 *                                If other string, assume URL.
	 * @return object
	 */
	public static function create( $image ) {

		$id = self::get_image_id( $image );
		if ( ! isset( self::$instances[ $id ] ) ) {
			self::$instances[ $id ] = new self( $id );
		}
		return self::$instances[ $id ];

	}

	/**
	 * Get an image ID from its URL.
	 *
	 * @static
	 * @access private
	 * @since 1.0.0
	 * @param int|string|array $image If numeric, assume image ID.
	 *                                If other string, assume URL.
	 * @return int
	 */
	private static function get_image_id( $image ) {

		if ( is_numeric( $image ) ) {
			return (int) $image;
		}

		// If we got this far then the $image is a URL.
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image ) );
		if ( $attachment && is_array( $attachment ) && isset( $attachment[0] ) ) {
			return (int) $attachment[0];
		}
	}

	/**
	 * return the image ID.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * return the image url.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}

	/**
	 * return the image width.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return int
	 */
	public function get_width() {
		return $this->width;
	}

	/**
	 * return the image height.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return int
	 */
	public function get_height() {
		return $this->height;
	}

	public function resize( $data ) {

		$defaults = array(
			'url'    => $this->url,
			'width'  => '',
			'height' => '',
			'crop'   => true,
			'retina' => false,
			'resize' => true,
		);

		$settings = wp_parse_args( $data, $defaults );

		if ( empty( $settings['url'] ) ) {
			return;
		}

		if ( false !== $settings['retina'] ) {
			// Default retina multiplier to 2.
			if ( true === $settings['retina'] ) {
				$settings['retina'] = 2;
			}
			// If (int) 1 is used, then assume we want the multiplier to be 1
			// therefore no retina image should be created.
			if ( 1 === $settings['retina'] || '1' === $settings['retina'] ) {
				$settings['retina'] = false;
			}
			// If not a boolean, make sure value is an integer.
			if ( ! is_bool( $settings['retina'] ) ) {
				$settings['retina'] = absint( $settings['retina'] );
			}
		}

		// If width or height are not specified, auto-calculate.
		if ( empty( $settings['width'] ) || empty( $settings['height'] ) ) {
			if ( ! empty( $settings['height'] ) ) {
				$settings['width'] = $settings['height'] * $this->width / $this->height;
				$settings['width'] = (int) $settings['width'];
				$settings['width'] = ( 0 >= $settings['width'] ) ? '' : $settings['width'];
			} elseif ( ! empty( $settings['width'] ) ) {
				$settings['height'] = $settings['width'] * $this->height / $this->width;
				$settings['height'] = (int) $settings['height'];
				$settings['height'] = ( 0 >= $settings['height'] ) ? '' : $settings['height'];
			}
		}

		// Generate the @2x file if retina is enabled
		if ( false !== $settings['retina'] ) {
			$results['retina'] = self::_resize( $settings['url'], $settings['width'], $settings['height'], $settings['crop'], $settings['retina'] );
		}

		return self::_resize( $settings['url'], $settings['width'], $settings['height'], $settings['crop'], false );
	}

	/**
	 * Resizes an image and returns an array containing the resized URL, width, height and file type.
	 * Uses native WordPress functionality.
	 * This is a slightly modified version of http://goo.gl/9iS0CO
	 *
	 * @access private
	 * @since 1.0.0
	 * @param string   $url    The image URL.
	 * @param int|null $width  The image width.
	 * @param int|null $height The image height.
	 * @param bool     $crop   If we want to crop the image or not.
	 * @param bool|int $retina If we want to generate a retina image or not.
	 *                         If an integer is used then it's used as a multiplier (@2x, @3x etc).
	 * @return array   An array containing the resized image URL, width, height and file type.
	 */
	public static function _resize( $url, $width = null, $height = null, $crop = true, $retina = false ) {
		global $wpdb;

		if ( empty( $url ) ) {
			return new WP_Error( 'no_image_url', esc_html__( 'No image URL has been entered.', 'gridd' ), $url );
		}

		// Get default size from database.
		$width  = ( $width ) ? $width : get_option( 'thumbnail_size_w' );
		$height = ( $height ) ? $height : get_option( 'thumbnail_size_h' );

		// Allow for different retina sizes
		$retina = ( false === $retina ) ? 1 : $retina;
		$retina = ( true === $retina ) ? 2 : $retina;
		$retina = (int) $retina;

		// Get the image file path
		$file_path = parse_url( $url );
		$file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];

		// Destination width and height variables.
		// Multiplied by the $retina int.
		$dest_width  = $width * $retina;
		$dest_height = $height * $retina;

		// File name suffix (appended to original file name)
		$suffix_width  = ( $dest_width / $retina );
		$suffix_height = ( $dest_height / $retina );
		$suffix_retina = ( $retina != 1 ) ? '@' . $retina . 'x' : null;
		$suffix        = "{$suffix_width}x{$suffix_height}{$suffix_retina}";

		// Some additional info about the image
		$info = pathinfo( $file_path );
		$dir  = $info['dirname'];
		$ext  = '';

		if ( ! empty( $info['extension'] ) ) {
			$ext = $info['extension'];
		}

		$name = wp_basename( $file_path, ".$ext" );

		// Suffix applied to filename
		$suffix_width  = ( $dest_width / $retina );
		$suffix_height = ( $dest_height / $retina );
		$suffix_retina = ( $retina != 1 ) ? '@' . $retina . 'x' : null;
		$suffix        = $suffix_width . 'x' . $suffix_height . $suffix_retina;

		// Get the destination file name
		$dest_file_name = "{$dir}/{$name}-{$suffix}.{$ext}";

		if ( ! file_exists( $dest_file_name ) ) {
			/*
				*  Bail if this image isn't in the Media Library.
				*  We only want to resize Media Library images, so we can be sure they get deleted correctly when appropriate.
				*/
			$query          = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE guid='%s'", $url );
			$get_attachment = $wpdb->get_results( $query );

			if ( ! $get_attachment ) {
				return array(
					'url'    => $url,
					'width'  => $width,
					'height' => $height,
				);
			}

			// Load WordPress Image Editor
			$editor = wp_get_image_editor( $file_path );
			if ( is_wp_error( $editor ) ) {
				return array(
					'url'    => $url,
					'width'  => $width,
					'height' => $height,
				);
			}

			// Get the original image size
			$size        = $editor->get_size();
			$orig_width  = $size['width'];
			$orig_height = $size['height'];

			$src_x = 0;
			$src_y = 0;
			$src_w = $orig_width;
			$src_h = $orig_height;

			if ( $crop ) {

				$cmp_x = $orig_width / $dest_width;
				$cmp_y = $orig_height / $dest_height;

				// Calculate x or y coordinate, and width or height of source
				if ( $cmp_x > $cmp_y ) {
					$src_w = round( $orig_width / $cmp_x * $cmp_y );
					$src_x = round( ( $orig_width - ( $orig_width / $cmp_x * $cmp_y ) ) / 2 );
				} elseif ( $cmp_y > $cmp_x ) {
					$src_h = round( $orig_height / $cmp_y * $cmp_x );
					$src_y = round( ( $orig_height - ( $orig_height / $cmp_y * $cmp_x ) ) / 2 );
				}
			}

			// Time to crop the image!
			$editor->crop( $src_x, $src_y, $src_w, $src_h, $dest_width, $dest_height );

			// Now let's save the image.
			$saved = $editor->save( $dest_file_name );

			// Get resized image information.
			$resized_url    = str_replace( basename( $url ), basename( $saved['path'] ), $url );
			$resized_width  = $saved['width'];
			$resized_height = $saved['height'];
			$resized_type   = $saved['mime-type'];

			// Add the resized dimensions to original image metadata
			// so we can delete our resized images when the original image is deleted
			// from the Media Library.
			$metadata = wp_get_attachment_metadata( $get_attachment[0]->ID );
			if ( isset( $metadata['image_meta'] ) ) {
				$metadata['image_meta']['resized_images'][] = $resized_width . 'x' . $resized_height;
				wp_update_attachment_metadata( $get_attachment[0]->ID, $metadata );
			}

			// Create the image array
			$image_array = array(
				'url'    => $resized_url,
				'width'  => $resized_width,
				'height' => $resized_height,
				'type'   => $resized_type,
			);
		} else {
			$image_array = array(
				'url'    => str_replace( basename( $url ), basename( $dest_file_name ), $url ),
				'width'  => $dest_width,
				'height' => $dest_height,
				'type'   => $ext,
			);
		}

		// Return image array
		return $image_array;
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
