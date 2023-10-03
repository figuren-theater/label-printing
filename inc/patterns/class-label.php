<?php
/**
 * Figuren_Theater label_printing Patterns.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing\Patterns;

/**
 * Printing-Label definition for real-world printing.
 */
class Label {
	/**
	 * Human readable name
	 *
	 * @var string
	 */
	public string $name;

	/**
	 * Machine readable name
	 *
	 * @var string
	 */
	public string $slug;

	/**
	 * Width in mm
	 *
	 * @var float
	 */
	public float $width;

	/**
	 * Height in mm
	 *
	 * @var float
	 */
	public float $height;

	/**
	 * Top & Bottom border in mm
	 * (on A4 label-printing sheet)
	 *
	 * @var float
	 */
	public float $a4_border_tb = 0;

	/**
	 * Left & Right border in mm
	 * (on A4 label-printing sheet)
	 *
	 * @var float
	 */
	public float $a4_border_lr = 0;

	/**
	 * Paper orientation off A4 label-printing sheet
	 *
	 * @var string
	 */
	public string $orientation = 'landscape';

	/**
	 * Synced Pattern 'wp_block' post ID
	 *
	 * @var int
	 */
	public int $post_ID;

	/**
	 * Setup and instantiate a new Label.
	 *
	 * @param  string $name   Human readable name
	 * @param  float  $width  Width in mm
	 * @param  float  $height Height in mm
	 */
	public function __construct( string $name, float $width, float $height ) {
		$this->name   = $name;
		$this->slug   = \sanitize_title_with_dashes( $name, '', 'save' );
		$this->width  = $width;
		$this->height = $height;
	}

	/**
	 * Insert a new Label into the database.
	 *
	 * @return void
	 */
	public function insert() : void {
		$post_ID = \wp_insert_post(  \wp_slash(
			[
				'post_content' => static::get_insert_markup(),
				'post_title'   => $this->name,
				'post_type'    => 'wp_block',
				'post_status'  => 'publish',
				'meta_input'   => [
					META_KEY => [
						'width'        => $this->width,
						'height'       => $this->height,
						'a4_border_tb' => $this->a4_border_tb,
						'a4_border_lr' => $this->a4_border_lr,
						'orientation'  => $this->orientation,
					],
				],
				'tax_input'    => [
					WP_CORE_PATTERN_TAX => PATTERN_TAX_TERM,
				],
			]
		) );

		if ( empty( $post_ID ) ) {
			return;
		}

		// phpstan Error
		//
		// Instanceof between int<1, max> and WP_Error will always evaluate to false.
		// Because the type is coming from a PHPDoc, you can turn off this check by setting treatPhpDocTypesAsCertain: false in your phpstan.neon.

		// @phpstan-ignore-next-line
		if ( $post_ID instanceof \WP_Error ) {
			return;
		}

		$this->post_ID = $post_ID;

		/**
		 * Run after a Label was saved successfully as 'wp_block' post.
		 *
		 * @param int $post_ID ID of the new inserted 'wp_block' post.
		 */
		\do_action( __NAMESPACE__ . '\\save_post_wp_block_label', $post_ID );
	}

	/**
	 * Default block-markup for 'synced-pattern' labels.
	 *
	 * @return string
	 */
	protected static function get_insert_markup() : string {
		return '<!-- wp:paragraph -->
		<p>MEIN TOLLES DBINSERTED LABEL</p>
		<!-- /wp:paragraph -->';
	}
}
