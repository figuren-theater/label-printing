<?php
/**
 * Figuren_Theater label_printing Patterns.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing\Patterns;

/**
 * This class generates the HTML markup for the label sheet pattern based on a given label.
 *
 * It calculates the layout based on label dimensions, A4 sheet size, and borders,
 * producing a printable pattern.
 */
class Generator {

	/**
	 * Label (which leads the generated sizes)
	 *
	 * @var Label
	 */
	public Label $label;

	/**
	 * Number of columns we need on A4.
	 *
	 * @var int
	 */
	protected int $columns;

	/**
	 * Number of rows we need on A4.
	 *
	 * @var int
	 */
	protected int $rows;

	/**
	 * Setup a new Pattern Generator with a pre-defined Label.
	 *
	 * @param  Label $label Printing-Label
	 */
	public function __construct( Label $label ) {
		$this->label = $label;
	}

	/**
	 * Generate the pattern slug from the label post_ID.
	 *
	 * @param  int    $id Post ID of the wp_block post.
	 *
	 * @return string Namespaced pattern slug|name including the labels post_ID.
	 */
	public static function get_pattern_name( int $id ) : string {
		return "figuren-theater/label-view-a4-$id";
	}

	/**
	 * Get human readable title of block pattern
	 * (currently invisible in the UI)
	 *
	 * @param  string $label_name Human readable name of the label.
	 *
	 * @return string             Human readable name of the block-pattern.
	 */
	public static function get_pattern_title( string $label_name ) : string {
		return "A4 view of $label_name Label";
	}

	/**
	 * Get full html of customized printing-label-sheet pattern,
	 * using a 'core/group' block with one of two custom block-styles.
	 *
	 * @return string
	 */
	public function get_markup() : string {

		$this->calculate();

		return '<!-- wp:group {"templateLock":"contentOnly","align":"wide","style":{"color":{"background":"#ffffff"},"dimensions":{"minHeight":"var(--label-printing-doc-height)"},"spacing":{"padding":{"top":"' . $this->label->a4_border_tb . 'mm","bottom":"' . $this->label->a4_border_tb . 'mm","left":"' . $this->label->a4_border_lr . 'mm","right":"' . $this->label->a4_border_lr . 'mm"},"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"className":"is-style-label-overview-' . $this->label->orientation . '","layout":{"type":"constrained","contentSize":"var(--label-printing-doc-width)","wideSize":"var(--label-printing-doc-width)","justifyContent":"left"}} -->
		<div class="wp-block-group alignwide is-style-label-overview-' . $this->label->orientation . ' has-background" style="background-color:#ffffff;min-height:var(--label-printing-doc-height);margin-top:0;margin-bottom:0;padding-top:' . $this->label->a4_border_tb . 'mm;padding-right:' . $this->label->a4_border_lr . 'mm;padding-bottom:' . $this->label->a4_border_tb . 'mm;padding-left:' . $this->label->a4_border_lr . 'mm">

		' . \str_repeat( $this->get_row_markup(), $this->rows ) . '
		</div>
		<!-- /wp:group -->';
	}

	/**
	 * Get HTML of 'core/group' row.
	 *
	 * @return string
	 */
	protected function get_row_markup() : string {
		return '<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"},"blockGap":"0"},"dimensions":{"minHeight":"' . $this->label->height . 'mm"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"stretch","justifyContent":"left"}} -->
		<div class="wp-block-group alignwide" style="min-height:' . $this->label->height . 'mm;margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">

		' . \str_repeat( $this->get_columns_markup(), $this->columns ) . '
		</div>
		<!-- /wp:group -->';
	}

	/**
	 * Get HTML of 'core/group' column and include 'synced-pattern' for current label.
	 *
	 * @return string
	 */
	protected function get_columns_markup() : string {
		return '<!-- wp:group {"style":{"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">

			<!-- wp:block {"ref":' . $this->label->post_ID . '} /-->

		</div>
		<!-- /wp:group -->';
	}

	/**
	 * Calculate amount of Labels per row and column,
	 * taking label-size, sheet-dimensions and sheet-borders into account.
	 *
	 * @return void
	 */
	protected function calculate() : void {

		// Determine label dimensions based on orientation.
		switch ( $this->label->orientation ) {
			case 'portrait':
				$width  = $this->label->width;
				$height = $this->label->height;
				break;

			case 'landscape':
			default:
				$width  = $this->label->height;
				$height = $this->label->width;
				break;
		}

		// Calculate the number of rows and columns that fit on an A4 sheet.
		$a4_height = 297 - ( 2 * $this->label->a4_border_tb );
		$a4_width  = 210 - ( 2 * $this->label->a4_border_lr );

		$this->rows    = (int) \round( $a4_height / $height );
		$this->columns = (int) \round( $a4_width / $width );
	}

}
