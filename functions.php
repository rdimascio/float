<?php
/**
 * WP Theme constants and setup functions
 *
 * @package FloatWP
 */

// Useful global constants.
define( 'FLOATWP_VERSION', '0.1.0' );
define( 'FLOATWP_TEMPLATE_URL', get_template_directory_uri() );
define( 'FLOATWP_PATH', get_template_directory() . '/' );
define( 'FLOATWP_DIST_PATH', FLOATWP_PATH . 'dist/' );
define( 'FLOATWP_DIST_URL', FLOATWP_TEMPLATE_URL . '/dist/' );
define( 'FLOATWP_INC', FLOATWP_PATH . 'includes/' );
define( 'FLOATWP_BLOCK_DIR', FLOATWP_INC . 'blocks/' );

require_once FLOATWP_INC . 'core.php';
require_once FLOATWP_INC . 'overrides.php';
require_once FLOATWP_INC . 'template-tags.php';
require_once FLOATWP_INC . 'utility.php';
require_once FLOATWP_INC . 'blocks.php';

// Run the setup functions.
FloatWP\Core\setup();
FloatWP\Blocks\setup();

// Require Composer autoloader if it exists.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * Shim for the the new wp_body_open() function that was added in 5.2
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}