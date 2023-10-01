<?php
/**
 * Register the label-printing module
 *
 * @package           figuren-theater/label-printing
 * @author            figuren.theater
 * @copyright         2023 figuren.theater
 * @license           GPL-3.0+
 *
 * @wordpress-plugin
 * Plugin Name:       figuren.theater | Label Printing
 * Plugin URI:        https://github.com/figuren-theater/label-printing
 * Description:       Create printable labels with blocks.
 * Version:           0.1.0-alpha
 * Requires at least: 6.0
 * Requires PHP:      7.1
 * Author:            figuren.theater
 * Author URI:        https://figuren.theater
 * Text Domain:       label-printing
 * Domain Path:       /languages
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Update URI:        https://github.com/figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing;

const DIRECTORY = __DIR__;

// TEMP needed
require_once DIRECTORY . '/inc/patterns/namespace.php';
require_once DIRECTORY . '/inc/namespace.php';

add_action( 'init', __NAMESPACE__ . '\\register', 0 );
