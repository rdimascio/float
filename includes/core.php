<?php
/**
 * Core setup, site hooks and filters.
 *
 * @package FloatWP
 */

namespace FloatWP\Core;

use FloatWP\Utility;

/**
 * Set up theme defaults and register supported WordPress features.
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'after_setup_theme', $n( 'i18n' ) );
	add_action( 'after_setup_theme', $n( 'theme_setup' ) );
	add_action( 'wp_enqueue_scripts', $n( 'scripts' ) );
	add_action( 'wp_enqueue_scripts', $n( 'styles' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_styles' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_scripts' ) );
	add_action( 'enqueue_block_editor_assets', $n( 'core_block_overrides' ) );
	add_action( 'wp_head', $n( 'js_detection' ), 0 );
	add_action( 'wp_head', $n( 'module_detection' ), 0 );
	add_action( 'wp_head', $n( 'add_manifest' ), 10 );
	add_action( 'wp_head', $n( 'preload_post_thumbnail' ), 2 );
	add_action( 'wp_head', $n( 'link_preload_preconnect' ), 3 );

	add_action( 'get_header', $n( 'remove_admin_bar_layout_styles' ) );

	// add_action( 'wp_head', $n( 'critical_css' ), 1 );
	// add_action( 'wp_head', $n( 'fonts' ), 4 );

	add_filter( 'script_loader_tag', $n( 'script_loader_tag' ), 10, 2 );

	if ( ! is_admin() ) {
		add_filter( 'style_loader_tag', $n( 'style_loader_tag' ), 99, 2 );
	}
}

/**
 * Makes Theme available for translation.
 *
 * Translations can be added to the /languages directory.
 * If you're building a theme based on "floatwp", change the
 * filename of '/languages/FloatWP.pot' to the name of your project.
 *
 * @return void
 */
function i18n() {
	load_theme_textdomain( 'floatwp', FLOATWP_PATH . '/languages' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function theme_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'align-full' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'css/editor-style.css' );
	add_theme_support( 'disable-custom-colors' );
	add_theme_support( 'disable-custom-font-sizes' );
	add_theme_support( 'disable-custom-gradients' );
	add_theme_support( 'editor-font-sizes', [] );
	add_theme_support( 'editor-gradient-presets', [] );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		[
			'search-form',
			'gallery',
			'script',
			'style',
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		]
	);

	remove_theme_support( 'core-block-patterns' );

	// This theme uses wp_nav_menu() in three locations.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'floatwp' ),
		)
	);

	// Register image sizes
	add_image_size( 'placeholder', 40, 40, true );
}

/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function scripts() {

	wp_enqueue_script(
		'frontend',
		FLOATWP_TEMPLATE_URL . '/dist/js/frontend.js',
		Utility\get_asset_info( 'frontend', 'dependencies' ),
		Utility\get_asset_info( 'frontend', 'version' ),
		true
	);

	if ( is_page_template( 'templates/page-styleguide.php' ) ) {
		wp_enqueue_script(
			'styleguide',
			FLOATWP_TEMPLATE_URL . '/dist/js/styleguide.js',
			Utility\get_asset_info( 'styleguide', 'dependencies' ),
			Utility\get_asset_info( 'styleguide', 'version' ),
			true
		);
	}

	/*
	wp_enqueue_script(
		'shared',
		FLOATWP_TEMPLATE_URL . '/dist/js/shared.js',
		Utility\get_asset_info( 'shared', 'dependencies' ),
		Utility\get_asset_info( 'shared', 'version' ),
		true
	);
	*/
}

/**
 * Enqueue scripts for admin
 *
 * @return void
 */
function admin_scripts() {
	wp_enqueue_script(
		'admin',
		FLOATWP_TEMPLATE_URL . '/dist/js/admin.js',
		Utility\get_asset_info( 'admin', 'dependencies' ),
		Utility\get_asset_info( 'admin', 'version' ),
		true
	);

	/*
	wp_enqueue_script(
		'shared',
		FLOATWP_TEMPLATE_URL . '/dist/js/shared.js',
		Utility\get_asset_info( 'shared', 'dependencies' ),
		Utility\get_asset_info( 'shared', 'version' ),
		true
	);
	*/
}

/**
 * Enqueue core block filters, styles and variations.
 *
 * @return void
 */
function core_block_overrides() {
	$overrides = FLOATWP_DIST_PATH . 'js/core-block-overrides.asset.php';
	if ( file_exists( $overrides ) ) {
		$dep = require_once $overrides;
		wp_enqueue_script(
			'core-block-overrides',
			FLOATWP_DIST_URL . 'js/core-block-overrides.js',
			$dep['dependencies'],
			$dep['version'],
			true
		);
	}
}

/**
 * Enqueue styles for admin
 *
 * @return void
 */
function admin_styles() {

	wp_enqueue_style(
		'admin-style',
		FLOATWP_TEMPLATE_URL . '/dist/css/admin-style.css',
		[],
		Utility\get_asset_info( 'admin-style', 'version' )
	);

	/*
	wp_enqueue_style(
		'shared-style',
		FLOATWP_TEMPLATE_URL . '/dist/css/shared-style.css',
		[],
		Utility\get_asset_info( 'shared-style', 'version' )
	);
	*/
}

/**
 * Enqueue styles for front-end.
 *
 * @return void
 */
function styles() {
	wp_enqueue_style(
		'styles',
		FLOATWP_TEMPLATE_URL . '/dist/css/style.css',
		[],
		Utility\get_asset_info( 'style', 'version' )
	);

	if ( is_page_template( 'templates/page-styleguide.php' ) ) {
		wp_enqueue_style(
			'styleguide',
			FLOATWP_TEMPLATE_URL . '/dist/css/styleguide-style.css',
			[],
			Utility\get_asset_info( 'styleguide-style', 'version' )
		);
	}
}

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @return void
 */
function js_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}

/**
 * Safari 10.1 supports modules, but does not support the `nomodule` attribute - it will
 * load <script nomodule> anyway.
 *
 * @link https://gist.github.com/samthor/64b114e4a4f539915a95b91ffd340acc
 */
function module_detection() {
	echo "<script>
	(function(d) {
		var js = d.createElement('script');
		if (!('noModule' in js) && 'onbeforeload' in js) {
		  var support = false;
		  d.addEventListener('beforeload', function(e) {
			if (e.target === js) {
			  support = true;
			} else if (!e.target.hasAttribute('nomodule') || !support) {
			  return;
			}
			e.preventDefault();
		  }, true);

		  js.type = 'module';
		  js.src = '.';
		  d.head.appendChild(js);
		  js.remove();
		}
	  })(document);
	</script>";
}

/**
 * Removes hardcoded styles for admin bar placemnet.
 */
function remove_admin_bar_layout_styles() {
	remove_action( 'wp_head', '_admin_bar_bump_cb' );
}

/**
 * Add async/defer attributes to enqueued scripts that have the specified script_execution flag.
 *
 * @link https://core.trac.wordpress.org/ticket/12009
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string
 */
function script_loader_tag( $tag, $handle ) {
	$new_tag = $tag;
	$attributes = wp_scripts()->get_data( $handle, 'attributes' );

	if ( empty( $attributes ) || ! is_array( $attributes ) ) {
		return $new_tag;
	}

	foreach ( $attributes as $attribute => $value ) {
		if ( ! $value ) {
			break;
		}

		// Abort adding async/defer for scripts that have this script as a dependency. _doing_it_wrong()?
		if ( 'async' === $attribute || 'defer' === $attribute ) {
			foreach ( wp_scripts()->registered as $script ) {
				if ( in_array( $handle, $script->deps, true ) ) {
					break;
				}
			}
		}

		// Add the attribute if it hasn't already been added.
		if ( ! preg_match( ":\s$attribute(=|>|\s):", $new_tag ) ) {

			if ( is_string( $value ) ) {
				$new_tag = preg_replace( ':(?=></script>):', " $attribute" . '="' . $value . '"', $new_tag, 1 );
			} else {
				$new_tag = preg_replace( ':(?=></script>):', " $attribute", $new_tag, 1 );
			}
		}
	}

	return $new_tag;
}

/**
 * Add async/defer attributes to enqueued scripts that have the specified script_execution flag.
 *
 * @link https://developer.wordpress.org/reference/hooks/style_loader_tag/
 * @param string $html   The style html output.
 * @param string $handle The style handle.
 * @return string
 */
function style_loader_tag( $html, $handle ) {
	// Get previously defined stylesheets.
	$known_handles = get_known_handles();

	// Loop over stylesheets and replace media attribute
	foreach ( $known_handles as $known_style ) {
		if ( $known_style === $handle ) {
			$print_html = str_replace( "media='all'", "media='print' onload=\"this.media='all'\"", $html );
		}
	}

	if ( ! empty( $print_html ) ) {
		$html = $print_html . '<noscript>' . $html . '</noscript>';
	}

	return $html;
}

/**
 * Appends a link tag used to add a manifest.json to the head
 *
 * @return void
 */
function add_manifest() {
	echo "<link rel='manifest' href='" . esc_url( FLOATWP_TEMPLATE_URL . '/manifest.json' ) . "' />";
}

/**
 * Asynchronous stylesheet definitions
 *
 * Determines which stylesheets should behave
 * asynchronously on the page by storing their
 * unique handle in an array.
 *
 * @return array
 */
function get_known_handles() {
	$async_styles = [
		'admin-bar',
		'dashicons',
		'single',
		'archive',
		'home',
		'front-page',
		'blocks',
	];

	return $async_styles;
}

/**
 * Preload attachment image, defaults to post thumbnail
 *
 * @return void
 */
function preload_post_thumbnail() {
	global $post;

	/** Adjust image size based on post type or other factor. */
	$image_size = 'full';
	$image_size = apply_filters( 'preload_post_thumbnail_image_size', $image_size, $post );

	/** Get post thumbnail if an attachment ID isn't specified. */
	$thumbnail_id = apply_filters( 'preload_post_thumbnail_id', get_post_thumbnail_id(), $post );

	/** Get the image */
	$image = wp_get_attachment_image_src( $thumbnail_id, $image_size );
	$src = '';
	$attrs = [];
	$attr = '';

	/* @TODO: Preload the first featured blog post featured image on the posts page */
	if ( $image && ( is_single() || is_page() ) ) {
		list( $src, $width, $height ) = $image;

		/**
		 * The following code which generates the srcset is plucked straight
		 * out of wp_get_attachment_image() for consistency as it's important
		 * that the output matches otherwise the preloading could become ineffective.
		 *
		 * @see (https://core.trac.wordpress.org/browser/tags/5.7.1/src/wp-includes/media.php#L1066)
		 */
		$image_meta = wp_get_attachment_metadata( $thumbnail_id );

		if ( is_array( $image_meta ) ) {
			$size_array = array( absint( $width ), absint( $height ) );
			$srcset     = wp_calculate_image_srcset( $size_array, $src, $image_meta, $thumbnail_id );

			if ( $srcset ) {
				$attrs['imagesrcset'] = $srcset;
				$attrs['imagesizes'] = '100vw';
			}
		}

		foreach ( $attrs as $name => $value ) {
			$attr .= "$name=" . '"' . $value . '" ';
		}
	} else {
		/** Early exit if no image is found. */
		return;
	}

	/** Output the link HTML tag */
	printf( '<link rel="preload" as="image" href="%s" %s/>', esc_url( $src ), $attr ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Appends link tag for preloading and preconnecting. Booster for performance.
 *
 * @return void
 */
function link_preload_preconnect() {
	$preconnect_hrefs = [];
	$preload_hrefs    = [
		'font' => [],
	];

	$allowed_tags = [
		'link' => [
			'rel'         => true,
			'href'        => true,
			'as'          => true,
			'type'        => true,
			'crossorigin' => true,
		],
	];

	foreach ( $preconnect_hrefs as $href ) {
		echo "<link rel='preconnect' href='" . esc_url( $href ) . "' crossorigin>";
	}

	foreach ( $preload_hrefs as $type => $assets ) {
		foreach ( $assets as $asset ) {
			$attrs = 'font' === $type ? 'type=font/woff2 crossorigin' : '';
			$font_tag = "<link rel='preload' as='" . esc_attr( $type ) . "' href='" . esc_url( $asset ) . "'" . esc_attr( $attrs ) . ">\n";

			echo $font_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}
