<?php
/**
 * Utility functions for the theme.
 *
 * This file is for custom helper functions.
 * These should not be confused with WordPress template
 * tags. Template tags typically use prefixing, as opposed
 * to Namespaces.
 *
 * @link https://developer.wordpress.org/themes/basics/template-tags/
 * @package FloatWP
 */

namespace FloatWP\Utility;

/**
 * Get asset info from extracted asset files
 *
 * @param string $slug Asset slug as defined in build/webpack configuration
 * @param string $attribute Optional attribute to get. Can be version or dependencies
 * @return string|array
 */
function get_asset_info( $slug, $attribute = null ) {
	if ( file_exists( FLOATWP_PATH . 'dist/js/' . $slug . '.asset.php' ) ) {
		$asset = require FLOATWP_PATH . 'dist/js/' . $slug . '.asset.php';
	} elseif ( file_exists( FLOATWP_PATH . 'dist/css/' . $slug . '.asset.php' ) ) {
		$asset = require FLOATWP_PATH . 'dist/css/' . $slug . '.asset.php';
	} else {
		return null;
	}

	if ( ! empty( $attribute ) && isset( $asset[ $attribute ] ) ) {
		return $asset[ $attribute ];
	}

	return $asset;
}

/**
 * Gets the request parameter.
 *
 * @param string $key      The query parameter
 * @param string $default  The default value to return if not found
 * @return string the request parameter.
 */
function get_request_parameter( $key, $default = '' ) {
	// If not request set
	if ( ! isset( $_REQUEST[ $key ] ) || empty( $_REQUEST[ $key ] ) ) {
		return $default;
	}

	// Set so process it
	return strip_tags( (string) wp_unslash( $_REQUEST[ $key ] ) );
}
