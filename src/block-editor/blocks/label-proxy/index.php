<?php
/**
 * Figuren_Theater label_printing Blocks.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing\Blocks\Proxy;

/**
 * Render callback of the 'figuren-theater/label-proxy' block.
 *
 * Works like a 'local reusable block' as long as we have no native one.
 *
 * @todo #12 Create a local-only reusable-block or a ‚reusable-block light‘
 *
 * @return string
 */
function render(): string {

	$post = \get_post();

	if ( ! $post instanceof \WP_Post ) {
		return '';
	}

	$blocks = \parse_blocks( $post->post_content );

	foreach ( $blocks as $block ) {

		/* // Works*/
		if ( 'figuren-theater/label-printing' === $block['blockName'] ) {

			\ob_start();
			foreach ( $block['innerBlocks'] as $block ) {
				echo \wp_kses_post(
					\apply_filters(
						'the_content',
						\render_block( $block )
					)
				);
			}
			return (string) \ob_get_clean();
		}
	}

	// If not already exited.
	return '';
}
