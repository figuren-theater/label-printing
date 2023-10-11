<?php
/**
 * Register the label-printing plugin
 *
 * @package           figuren-theater/label-printing
 * @author            figuren.theater Crew
 * @copyright         2023 figuren.theater
 * @license           GPL-3.0+
 *
 * @wordpress-plugin
 * Plugin Name:       figuren.theater | Label Printing
 * Plugin URI:        https://github.com/figuren-theater/label-printing
 * Description:       Create printable labels with blocks.
 * Version:           0.1.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            figuren.theater Crew
 * Author URI:        https://figuren.theater/crew
 * Text Domain:       label-printing
 * Domain Path:       /languages
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Update URI:        https://github.com/figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing;

const DIRECTORY = __DIR__;

/**
 * REMOVE
 *
 * @todo #15 Add composer autoloading strategy
 */
require_once DIRECTORY . '/inc/blocks/namespace.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once DIRECTORY . '/inc/block-styles/namespace.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once DIRECTORY . '/inc/block-variations/namespace.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once DIRECTORY . '/inc/patterns/class-generator.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once DIRECTORY . '/inc/patterns/class-label.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once DIRECTORY . '/inc/patterns/class-label-store.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once DIRECTORY . '/inc/patterns/namespace.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once DIRECTORY . '/inc/namespace.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

\add_action( 'init', __NAMESPACE__ . '\\register', 0 );
