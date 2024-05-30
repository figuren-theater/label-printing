<?php
/**
 * Register the label-printing plugin
 *
 * @package           figuren-theater/label-printing
 * @author            Carsten Bach
 * @copyright         2023 figuren.theater
 * @license           GPL-3.0+
 *
 * @wordpress-plugin
 * Plugin Name:       Label Printing
 * Plugin URI:        https://github.com/figuren-theater/label-printing
 * Description:       Create printable labels with blocks.
 * Version:           0.3.19
 * Requires at least: 6.2
 * Requires PHP:      8.1
 * Author:            Carsten Bach
 * Author URI:        https://figuren.theater/crew
 * Text Domain:       label-printing
 * Domain Path:       /languages
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Figuren_Theater\Label_Printing;

const DIRECTORY = __DIR__;

/**
 * REMOVE
 *
 * @todo Add composer autoloading strategy https://github.com/figuren-theater/label-printing/issues/15
 */
require_once DIRECTORY . '/inc/blocks/namespace.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once DIRECTORY . '/inc/block-styles/namespace.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once DIRECTORY . '/inc/block-variations/namespace.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once DIRECTORY . '/inc/patterns/class-generator.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once DIRECTORY . '/inc/patterns/class-label.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once DIRECTORY . '/inc/patterns/class-label-store.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once DIRECTORY . '/inc/patterns/namespace.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once DIRECTORY . '/inc/namespace.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

\add_action( 'init', __NAMESPACE__ . '\\register', 0 ); // 0 is important
