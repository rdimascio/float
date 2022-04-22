<?php
/**
 * Custom template tags for this theme.
 *
 * This file is for custom template tags only and it should not contain
 * functions that will be used for filtering or adding an action.
 *
 * All functions should be prefixed with FloatWP in order to prevent
 * pollution of the global namespace and potential conflicts with functions
 * from plugins.
 * Example: `float_function()`
 *
 * @package FloatWP\Template_Tags
 *
 */

// phpcs:ignoreFile

/**
 * Get page/post slug
 * Defaults to current page/post
 *
 * @param int|WP_Post|null
 * @return string|bool Slug|False
 */
function float_post_slug( $p = null ) {

	$slug = false;

	if ( empty( $p ) ) {
		$current = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
	} else {
		if ( is_numeric( $p ) ) {
			$p = intval( $p );
		}
		$current = get_post( $p );
	}

	if ( $current && $current instanceof WP_Post ) {
		$slug = $current->post_name;
	}

	return $slug;
}

/**
 * Displays Posts Pagination.
 *
 * @param string $class Class to apply to a pagination element.
 * @param array $args Args to apply to paginate_links.
 */
function float_posts_pagination( $class = '', $args = [] ) {
	$class = $class ? $class . ' pagination' : 'pagination';

	$args = wp_parse_args(
		$args,
		[
			'prev_text'          => '<span class="visually-hidden">' . __( 'Go to the Previous Page', 'floatwp' ) . '</span><span aria-hidden="true">' . __( 'Prev', 'floatwp' ) . '</span>',
			'next_text'          => '<span class="visually-hidden">' . __( 'Go to the Next Page', 'floatwp' ) . '</span><span aria-hidden="true">' . __( 'Next', 'floatwp' ) . '</span>',
			'before_page_number' => '<span class="visually-hidden">' . __( 'Go to Page ', 'floatwp' ) . '</span>',
			'echo'               => false,
		]
	);

	$pagination = paginate_links( $args );

	if ( ! $pagination ) {
		return;
	}

	// Add BEM style classes
	$pagination = str_replace(
		[
			'prev page-numbers',
			'next page-numbers',
			'page-numbers current',
			'page-numbers dots',
			'page-numbers',
		],
		[
			'pagination__item pagination__item--prev',
			'pagination__item pagination__item--next',
			'pagination__item pagination__item--current',
			'pagination__item pagination__item--dots',
			'pagination__item',
		],
		$pagination
	);

	printf(
		'<nav class="%s"><div class="pagination__inner">%s</div></nav>',
		esc_attr( $class ),
		wp_kses_post( $pagination ),
	);
}