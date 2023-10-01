<?php
/**
 * Tests for plugin.php.
 *
 * @package project_name
 */

use Yoast\WPTestUtils\WPIntegration\TestCase;

/**
 * Tests for plugin.php.
 */
class Test_Plugin extends TestCase {

	/**
	 * Test bootstrap.
	 */
	public function test_bootstrap() {
		$this->assertTrue( defined( 'project_name_VERSION' ) );
		$this->assertTrue( defined( 'project_name_PLUGIN_FILE' ) );
		$this->assertTrue( defined( 'project_name_PLUGIN_DIR' ) );

		// $this->assertTrue( class_exists( 'WP_Web_App_Manifest' ) );
		// $this->assertTrue( class_exists( 'WP_Service_Workers' ) );
	}
}
