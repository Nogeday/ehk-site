<?php
/**
 * Front-end query handling: archive filters for projects and events.
 *
 * Only the main query of our own archives is touched; regular posts, pages,
 * admin screens and other plugins' queries are left alone.
 *
 * Filter parameters (all optional, combinable):
 *   Projects: ?yil=2025 &durum=tamamlandi &program=teknofest &alan=iot
 *   Events:   ?tur=workshop &donem=yaklasan|gecmis
 *
 * @package KTUEHK_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register custom query vars.
 *
 * @param string[] $vars Query vars.
 * @return string[]
 */
function ktuehk_core_query_vars( $vars ) {
	$vars[] = 'yil';
	$vars[] = 'durum';
	$vars[] = 'donem';
	return $vars;
}
add_filter( 'query_vars', 'ktuehk_core_query_vars' );

/**
 * Whether the main query is one of our project listings.
 *
 * @param WP_Query $query Query.
 * @return bool
 */
function ktuehk_core_is_project_listing( $query ) {
	return $query->is_post_type_archive( ktuehk_project_type() ) || $query->is_tax( array( KTUEHK_TAX_PROGRAM, KTUEHK_TAX_AREA ) );
}

/**
 * Whether the main query is one of our event listings.
 *
 * @param WP_Query $query Query.
 * @return bool
 */
function ktuehk_core_is_event_listing( $query ) {
	return $query->is_post_type_archive( ktuehk_event_type() ) || $query->is_tax( KTUEHK_TAX_EVENT );
}

/**
 * Adjust archive main queries.
 *
 * @param WP_Query $query Query.
 */
function ktuehk_core_pre_get_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() || $query->is_search() || $query->is_feed() ) {
		return;
	}

	if ( ktuehk_core_is_project_listing( $query ) ) {
		$query->set( 'post_type', ktuehk_project_type() );
		$query->set( 'posts_per_page', (int) apply_filters( 'ktuehk_projects_per_page', 12 ) );

		$meta_query = array();
		$year       = absint( $query->get( 'yil' ) );
		if ( $year ) {
			$meta_query[] = array(
				'key'   => ktuehk_meta_key( 'yil' ),
				'value' => $year,
			);
		}
		$status = sanitize_key( (string) $query->get( 'durum' ) );
		if ( $status && array_key_exists( $status, ktuehk_project_statuses() ) ) {
			$meta_query[] = array(
				'key'   => ktuehk_meta_key( 'durum' ),
				'value' => $status,
			);
		}
		if ( $meta_query ) {
			$query->set( 'meta_query', $meta_query ); // phpcs:ignore WordPress.DB.SlowDBQuery
		}
		return;
	}

	if ( ktuehk_core_is_event_listing( $query ) ) {
		$query->set( 'post_type', ktuehk_event_type() );
		$period = sanitize_key( (string) $query->get( 'donem' ) );

		if ( 'yaklasan' === $period ) {
			$args = ktuehk_upcoming_events_args();
			$query->set( 'posts_per_page', (int) apply_filters( 'ktuehk_events_per_page', 9 ) );
		} else {
			// Default listing (and "gecmis"): past events. Upcoming ones are
			// shown separately above the archive grid by the theme.
			$args = ktuehk_past_events_args();
			$query->set( 'posts_per_page', (int) apply_filters( 'ktuehk_events_per_page', 9 ) );
		}
		$query->set( 'meta_query', $args['meta_query'] ); // phpcs:ignore WordPress.DB.SlowDBQuery
		$query->set( 'orderby', $args['orderby'] );
	}
}
add_action( 'pre_get_posts', 'ktuehk_core_pre_get_posts' );

/**
 * Filtered listings are variations of the same page: keep them out of the
 * search index but let crawlers follow links.
 *
 * @param array $robots Robots directives.
 * @return array
 */
function ktuehk_core_robots( $robots ) {
	if ( is_admin() ) {
		return $robots;
	}
	$filtered = false;
	foreach ( array( 'yil', 'durum', 'donem' ) as $var ) {
		if ( '' !== (string) get_query_var( $var ) ) {
			$filtered = true;
		}
	}
	// Taxonomy filters given as query parameters on the archive URL.
	if ( ( is_post_type_archive( ktuehk_project_type() ) || is_post_type_archive( ktuehk_event_type() ) ) && is_tax() ) {
		$filtered = true;
	}
	if ( $filtered ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'ktuehk_core_robots' );
