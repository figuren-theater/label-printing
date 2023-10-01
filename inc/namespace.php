<?php
/**
 * Figuren_Theater project_name.
 *
 * @package figuren-theater/project_urlname
 */

namespace Figuren_Theater\project_name;

use Altis;

/**
 * Register module.
 *
 * @return void
 */
function register() :void {

	$default_settings = [
		'enabled' => true, // Needs to be set.
	];
	$options = [
		'defaults' => $default_settings,
	];

	Altis\register_module(
		'project_urlname',
		DIRECTORY,
		'project_name',
		$options,
		__NAMESPACE__ . '\\bootstrap'
	);
}

/**
 * Bootstrap module, when enabled.
 *
 * @return void
 */
function bootstrap() :void {

	/**
	 * Automatically load Plugins.
	 *
	 * @example NameSpace\bootstrap();
	 */

	/**
	 * Load 'Best practices'.
	 *
	 * @example NameSpace\bootstrap();
	 */
}
