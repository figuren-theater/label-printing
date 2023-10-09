<?php
/**
 * Figuren_Theater label_printing Blocks.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing\Blocks\Printing;

use Figuren_Theater\Label_Printing\Patterns;

/**
 * Render callback of the 'figuren-theater/label-printing' block.
 *
 * Shows a printable sheet with multiple same instances of the coosen label.
 *
 * @param  array<string, int> $attributes The blocks attributes.
 *
 * @return string
 */
function render( array $attributes ) : string {

	// Get the label post object based on the provided attribute.
	$label = \get_post( $attributes['wpLabelPost'] );

	if ( ! $label instanceof \WP_Post ) {
		return '';
	}

	// Get the labels measurements.
	// Yes, indeed - we already have 'width' and 'height' as block-attributes,
	// but we still need the rest of the post_meta, so we get all.
	$meta = \get_post_meta( $label->ID, Patterns\META_KEY, true );

	// Check if the metadata is valid.
	if ( ! \is_array( $meta ) || ! isset( $meta['height'] ) || ! isset( $meta['width'] ) ) {
		return '';
	}
	$attributes['labelHeight']  = $meta['height'];
	$attributes['labelWidth']   = $meta['width'];

	$attributes['a4_border_tb'] = $meta['a4_border_tb'];
	$attributes['a4_border_lr'] = $meta['a4_border_lr'];
	$attributes['orientation']  = $meta['orientation'];

	// Generate Inline-CSS using a custom-property to set the labels printing dimensions.
	$css = \sprintf(
		'<style>:root { --label-printing-height:%smm;--label-printing-width:%smm;--label-printing-a4-border-tb:%smm;--label-printing-a4-border-lr:%smm;--label-printing-orientation:%s; }</style>',
		$attributes['labelHeight'],
		$attributes['labelWidth'],
		$attributes['a4_border_tb'],
		$attributes['a4_border_lr'],
		$attributes['orientation']
	);

	// Generate the pattern slug from label.
	$pattern_slug = 'figuren-theater/label-view-a4-' . $label->ID;

	// Prepare block-pattern with the specified pattern slug for rendering.
	$pattern = \do_blocks( '<!-- wp:pattern {"slug":"' . $pattern_slug . '"} -->' );

	// Render the Inline-CSS and the block pattern.
	return $css . $pattern;
}
