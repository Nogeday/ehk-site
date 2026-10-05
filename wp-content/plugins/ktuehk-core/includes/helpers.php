<?php
/**
 * Public helper API used by the theme. Every function here is safe to call
 * from templates; the theme ships fallbacks for when the plugin is inactive.
 *
 * @package KTUEHK_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Configured project post type key.
 *
 * @return string
 */
function ktuehk_core_project_type() {
	return (string) apply_filters( 'ktuehk_project_type', ktuehk_core_settings( 'project_type' ) );
}

/**
 * Configured event post type key.
 *
 * @return string
 */
function ktuehk_core_event_type() {
	return (string) apply_filters( 'ktuehk_event_type', ktuehk_core_settings( 'event_type' ) );
}

/**
 * Meta key for a field name.
 *
 * @param string $name Field name.
 * @return string
 */
function ktuehk_meta_key( $name ) {
	return '_ehk_' . $name;
}

/**
 * Raw field value.
 *
 * @param string           $name Field name (without prefix).
 * @param int|WP_Post|null $post Post.
 * @return string
 */
function ktuehk_core_field( $name, $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	$value = get_post_meta( $post->ID, ktuehk_meta_key( $name ), true );
	return is_scalar( $value ) ? (string) $value : '';
}

/**
 * Field value split into non-empty lines.
 *
 * @param string           $name Field name.
 * @param int|WP_Post|null $post Post.
 * @return string[]
 */
function ktuehk_field_lines( $name, $post = null ) {
	$lines = preg_split( '/\r\n|\r|\n/', ktuehk_field( $name, $post ) );
	return array_values( array_filter( array_map( 'trim', (array) $lines ), 'strlen' ) );
}

/**
 * Field value split by commas or new lines (for tag-like lists).
 *
 * @param string           $name Field name.
 * @param int|WP_Post|null $post Post.
 * @return string[]
 */
function ktuehk_field_list( $name, $post = null ) {
	$items = preg_split( '/[,\r\n]+/', ktuehk_field( $name, $post ) );
	return array_values( array_unique( array_filter( array_map( 'trim', (array) $items ), 'strlen' ) ) );
}

/**
 * Team member lines parsed as name / role ("Ad Soyad — Rol" or "Ad Soyad - Rol").
 *
 * @param int|WP_Post|null $post Post.
 * @return array<int,array{name:string,role:string}>
 */
function ktuehk_project_team( $post = null ) {
	$team = array();
	foreach ( ktuehk_field_lines( 'ekip', $post ) as $line ) {
		$parts  = preg_split( '/\s+[—–\-|]\s+/u', $line, 2 );
		$team[] = array(
			'name' => trim( $parts[0] ),
			'role' => isset( $parts[1] ) ? trim( $parts[1] ) : '',
		);
	}
	return $team;
}

/**
 * Attachment IDs stored in a gallery field.
 *
 * @param int|WP_Post|null $post Post.
 * @return int[]
 */
function ktuehk_gallery_ids( $post = null ) {
	$ids = array_filter( array_map( 'absint', explode( ',', ktuehk_field( 'galeri', $post ) ) ) );
	return array_values( array_filter( $ids, 'wp_attachment_is_image' ) );
}

/**
 * Project status choices.
 *
 * @return array<string,string>
 */
function ktuehk_project_statuses() {
	return (array) apply_filters(
		'ktuehk_project_statuses',
		array(
			'planlaniyor'  => __( 'Planlanıyor', 'ktuehk-core' ),
			'devam-ediyor' => __( 'Devam ediyor', 'ktuehk-core' ),
			'tamamlandi'   => __( 'Tamamlandı', 'ktuehk-core' ),
		)
	);
}

/**
 * Project status as key + label.
 *
 * @param int|WP_Post|null $post Post.
 * @return array{key:string,label:string}|null
 */
function ktuehk_project_status( $post = null ) {
	$key      = ktuehk_field( 'durum', $post );
	$statuses = ktuehk_project_statuses();
	if ( '' === $key || ! isset( $statuses[ $key ] ) ) {
		return null;
	}
	return array(
		'key'   => $key,
		'label' => $statuses[ $key ],
	);
}

/**
 * Distinct project years (newest first). Cached until a project is saved.
 *
 * @return int[]
 */
function ktuehk_project_years() {
	$years = get_transient( 'ktuehk_project_years' );
	if ( false === $years ) {
		global $wpdb;
		$years = $wpdb->get_col( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->prepare(
				"SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id WHERE pm.meta_key = %s AND p.post_type = %s AND p.post_status = 'publish'",
				ktuehk_meta_key( 'yil' ),
				ktuehk_project_type()
			)
		);
		$years = array_values( array_unique( array_filter( array_map( 'absint', (array) $years ) ) ) );
		rsort( $years );
		set_transient( 'ktuehk_project_years', $years, DAY_IN_SECONDS );
	}
	return $years;
}

/**
 * Parse a stored local datetime ("Y-m-d H:i:s") in the site timezone.
 *
 * @param string $value Stored value.
 * @return DateTimeImmutable|null
 */
function ktuehk_parse_datetime( $value ) {
	if ( ! is_string( $value ) || '' === $value ) {
		return null;
	}
	$date = DateTimeImmutable::createFromFormat( 'Y-m-d H:i:s', $value, wp_timezone() );
	return $date ? $date : null;
}

/**
 * Event start date.
 *
 * @param int|WP_Post|null $post Post.
 * @return DateTimeImmutable|null
 */
function ktuehk_event_start( $post = null ) {
	return ktuehk_parse_datetime( ktuehk_field( 'baslangic', $post ) );
}

/**
 * Event end date (only if explicitly set).
 *
 * @param int|WP_Post|null $post Post.
 * @return DateTimeImmutable|null
 */
function ktuehk_event_end( $post = null ) {
	return ktuehk_parse_datetime( ktuehk_field( 'bitis', $post ) );
}

/**
 * The moment after which an event counts as "past": the end time if set,
 * otherwise the end of the start day.
 *
 * @param string $start Stored start (Y-m-d H:i:s).
 * @param string $end   Stored end (Y-m-d H:i:s) or ''.
 * @return string Stored format or '' when no start date.
 */
function ktuehk_event_effective_end( $start, $end ) {
	if ( '' !== $end && ktuehk_parse_datetime( $end ) ) {
		return $end;
	}
	$start_date = ktuehk_parse_datetime( $start );
	return $start_date ? $start_date->format( 'Y-m-d' ) . ' 23:59:59' : '';
}

/**
 * Whether an event is upcoming or still running.
 *
 * @param int|WP_Post|null $post Post.
 * @return bool
 */
function ktuehk_event_is_upcoming( $post = null ) {
	$until = ktuehk_field( 'son', $post );
	if ( '' === $until ) {
		$until = ktuehk_event_effective_end( ktuehk_field( 'baslangic', $post ), ktuehk_field( 'bitis', $post ) );
	}
	return '' !== $until && $until >= current_time( 'mysql' );
}

/**
 * WP_Query arguments for upcoming events (soonest first).
 *
 * @param array $args Extra arguments.
 * @return array
 */
function ktuehk_upcoming_events_args( $args = array() ) {
	return array_merge(
		array(
			'post_type'           => ktuehk_event_type(),
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'meta_query'          => ktuehk_event_meta_query( 'upcoming' ), // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'             => array( 'ehk_start' => 'ASC' ),
		),
		$args
	);
}

/**
 * WP_Query arguments for past events (most recent first). Events without a
 * date are listed last.
 *
 * @param array $args Extra arguments.
 * @return array
 */
function ktuehk_past_events_args( $args = array() ) {
	return array_merge(
		array(
			'post_type'           => ktuehk_event_type(),
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'meta_query'          => ktuehk_event_meta_query( 'past' ), // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'             => array(
				'ehk_past' => 'DESC',
				'date'     => 'DESC',
			),
		),
		$args
	);
}

/**
 * Meta query for upcoming / past events.
 *
 * Dates are stored as "Y-m-d H:i:s" so a string comparison is correct and
 * avoids database specific CAST() behaviour.
 *
 * @param string $period upcoming|past.
 * @return array
 */
function ktuehk_event_meta_query( $period ) {
	$now = current_time( 'mysql' );
	if ( 'upcoming' === $period ) {
		return array(
			'relation'     => 'AND',
			'ehk_upcoming' => array(
				'key'     => ktuehk_meta_key( 'son' ),
				'value'   => $now,
				'compare' => '>=',
			),
			'ehk_start'    => array(
				'key'     => ktuehk_meta_key( 'baslangic' ),
				'compare' => 'EXISTS',
			),
		);
	}
	return array(
		'relation'   => 'OR',
		'ehk_past'   => array(
			'key'     => ktuehk_meta_key( 'son' ),
			'value'   => $now,
			'compare' => '<',
		),
		'ehk_nodate' => array(
			'key'     => ktuehk_meta_key( 'son' ),
			'compare' => 'NOT EXISTS',
		),
	);
}

/**
 * Author shown for a post. Supports a "display author" override so an admin
 * can publish an article on behalf of a student.
 *
 * @param int|WP_Post|null $post Post.
 * @return array{name:string,description:string,url:string,user_id:int,is_override:bool}
 */
function ktuehk_core_display_author( $post = null ) {
	$post     = get_post( $post );
	$override = $post ? ktuehk_field( 'yazar_adi', $post ) : '';
	if ( '' !== $override ) {
		return array(
			'name'        => $override,
			'description' => ktuehk_field( 'yazar_bilgi', $post ),
			'url'         => '',
			'user_id'     => 0,
			'is_override' => true,
		);
	}
	$user_id = $post ? (int) $post->post_author : 0;
	return array(
		'name'        => $user_id ? get_the_author_meta( 'display_name', $user_id ) : '',
		'description' => $user_id ? get_the_author_meta( 'description', $user_id ) : '',
		'url'         => $user_id ? get_author_posts_url( $user_id ) : '',
		'user_id'     => $user_id,
		'is_override' => false,
	);
}

/**
 * Whether a dedicated SEO plugin handles meta tags / schema.
 *
 * @return bool
 */
function ktuehk_core_seo_plugin_active() {
	$active = defined( 'WPSEO_VERSION' )
		|| class_exists( 'RankMath' )
		|| defined( 'AIOSEO_VERSION' )
		|| defined( 'SEOPRESS_VERSION' )
		|| defined( 'THE_SEO_FRAMEWORK_VERSION' )
		|| defined( 'SLIM_SEO_VER' )
		|| defined( 'SQ_VERSION' );
	return (bool) apply_filters( 'ktuehk_seo_plugin_active', $active );
}

/**
 * Label + URL of the posts listing ("Yazılar").
 *
 * @return array{name:string,url:string}
 */
function ktuehk_posts_listing() {
	$page_id = (int) get_option( 'page_for_posts' );
	if ( $page_id && 'page' === get_option( 'show_on_front' ) ) {
		return array(
			'name' => get_the_title( $page_id ),
			'url'  => get_permalink( $page_id ),
		);
	}
	return array(
		'name' => __( 'Yazılar', 'ktuehk-core' ),
		'url'  => 'posts' === get_option( 'show_on_front' ) ? '' : home_url( '/' ),
	);
}

/**
 * Breadcrumb trail for the current request.
 *
 * @return array<int,array{name:string,url:string}>
 */
function ktuehk_core_breadcrumb_items() {
	$items   = array(
		array(
			'name' => __( 'Ana Sayfa', 'ktuehk-core' ),
			'url'  => home_url( '/' ),
		),
	);
	$project = ktuehk_project_type();
	$event   = ktuehk_event_type();
	$posts   = ktuehk_posts_listing();

	if ( is_front_page() ) {
		return array();
	}

	if ( is_home() ) {
		$items[] = array(
			'name' => $posts['name'],
			'url'  => '',
		);
	} elseif ( is_singular( 'post' ) ) {
		$items[] = $posts;
		$cats    = get_the_category();
		if ( $cats ) {
			$items[] = array(
				'name' => $cats[0]->name,
				'url'  => get_category_link( $cats[0] ),
			);
		}
		$items[] = array(
			'name' => single_post_title( '', false ),
			'url'  => '',
		);
	} elseif ( is_singular( array( $project, $event ) ) ) {
		$type    = get_post_type();
		$items[] = array(
			'name' => get_post_type_object( $type )->labels->name,
			'url'  => (string) get_post_type_archive_link( $type ),
		);
		$items[] = array(
			'name' => single_post_title( '', false ),
			'url'  => '',
		);
	} elseif ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_queried_object_id() ) ) as $ancestor ) {
			$items[] = array(
				'name' => get_the_title( $ancestor ),
				'url'  => get_permalink( $ancestor ),
			);
		}
		$items[] = array(
			'name' => single_post_title( '', false ),
			'url'  => '',
		);
	} elseif ( is_singular() ) {
		$items[] = array(
			'name' => single_post_title( '', false ),
			'url'  => '',
		);
	} elseif ( is_category() || is_tag() || is_author() || is_date() ) {
		$items[] = $posts;
		if ( is_category() ) {
			foreach ( array_reverse( get_ancestors( get_queried_object_id(), 'category' ) ) as $ancestor ) {
				$items[] = array(
					'name' => get_cat_name( $ancestor ),
					'url'  => get_category_link( $ancestor ),
				);
			}
		}
		$items[] = array(
			'name' => wp_strip_all_tags( get_the_archive_title() ),
			'url'  => '',
		);
	} elseif ( is_tax( array( KTUEHK_TAX_PROGRAM, KTUEHK_TAX_AREA ) ) || is_post_type_archive( $project ) ) {
		$items[] = array(
			'name' => __( 'Projeler', 'ktuehk-core' ),
			'url'  => is_tax() ? (string) get_post_type_archive_link( $project ) : '',
		);
		if ( is_tax() ) {
			$items[] = array(
				'name' => single_term_title( '', false ),
				'url'  => '',
			);
		}
	} elseif ( is_tax( KTUEHK_TAX_EVENT ) || is_post_type_archive( $event ) ) {
		$items[] = array(
			'name' => __( 'Etkinlikler', 'ktuehk-core' ),
			'url'  => is_tax() ? (string) get_post_type_archive_link( $event ) : '',
		);
		if ( is_tax() ) {
			$items[] = array(
				'name' => single_term_title( '', false ),
				'url'  => '',
			);
		}
	} elseif ( is_search() ) {
		$items[] = array(
			/* translators: %s: search query */
			'name' => sprintf( __( 'Arama: %s', 'ktuehk-core' ), get_search_query( false ) ),
			'url'  => '',
		);
	} elseif ( is_404() ) {
		$items[] = array(
			'name' => __( 'Sayfa bulunamadı', 'ktuehk-core' ),
			'url'  => '',
		);
	} elseif ( is_archive() ) {
		$items[] = array(
			'name' => wp_strip_all_tags( get_the_archive_title() ),
			'url'  => '',
		);
	}

	return (array) apply_filters( 'ktuehk_breadcrumb_items', array_values( array_filter( $items, static function ( $item ) {
		return '' !== $item['name'];
	} ) ) );
}

/*
 * Public API aliases.
 *
 * The theme ships fallbacks with the same names (for when this plugin is
 * inactive) that delegate to the ktuehk_core_* implementations above. When
 * the plugin is activated while the theme is already loaded, those fallbacks
 * exist first, so the aliases below are only declared if missing. Either
 * way the behaviour is identical.
 */
if ( ! function_exists( 'ktuehk_project_type' ) ) {
	/**
	 * @see ktuehk_core_project_type()
	 * @return string
	 */
	function ktuehk_project_type() {
		return ktuehk_core_project_type();
	}
}
if ( ! function_exists( 'ktuehk_event_type' ) ) {
	/**
	 * @see ktuehk_core_event_type()
	 * @return string
	 */
	function ktuehk_event_type() {
		return ktuehk_core_event_type();
	}
}
if ( ! function_exists( 'ktuehk_field' ) ) {
	/**
	 * @see ktuehk_core_field()
	 * @param string           $name Field name.
	 * @param int|WP_Post|null $post Post.
	 * @return string
	 */
	function ktuehk_field( $name, $post = null ) {
		return ktuehk_core_field( $name, $post );
	}
}
if ( ! function_exists( 'ktuehk_display_author' ) ) {
	/**
	 * @see ktuehk_core_display_author()
	 * @param int|WP_Post|null $post Post.
	 * @return array
	 */
	function ktuehk_display_author( $post = null ) {
		return ktuehk_core_display_author( $post );
	}
}
if ( ! function_exists( 'ktuehk_seo_plugin_active' ) ) {
	/**
	 * @see ktuehk_core_seo_plugin_active()
	 * @return bool
	 */
	function ktuehk_seo_plugin_active() {
		return ktuehk_core_seo_plugin_active();
	}
}
if ( ! function_exists( 'ktuehk_breadcrumb_items' ) ) {
	/**
	 * @see ktuehk_core_breadcrumb_items()
	 * @return array
	 */
	function ktuehk_breadcrumb_items() {
		return ktuehk_core_breadcrumb_items();
	}
}
