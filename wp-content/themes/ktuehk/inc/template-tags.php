<?php
/**
 * Template helpers: icons, brand, menus, meta lines, media, breadcrumbs,
 * pagination and event date formatting.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

/**
 * Inline SVG icon.
 *
 * @param string $name  Icon name (see inc/icon-set.php).
 * @param int    $size  Pixel size attribute (CSS may override).
 * @param string $class Extra classes.
 * @return string
 */
function ktuehk_icon( $name, $size = 20, $class = '' ) {
	static $set = null;
	if ( null === $set ) {
		$set = ktuehk_icon_set();
	}
	if ( ! isset( $set[ $name ] ) ) {
		return '';
	}
	list( $style, $inner ) = $set[ $name ];
	$paint                 = 'fill' === $style
		? 'fill="currentColor"'
		: 'fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"';
	return sprintf(
		'<svg class="icon icon-%1$s%2$s" width="%3$d" height="%3$d" viewBox="0 0 24 24" %4$s aria-hidden="true" focusable="false">%5$s</svg>',
		esc_attr( $name ),
		$class ? ' ' . esc_attr( $class ) : '',
		(int) $size,
		$paint,
		$inner
	);
}

/**
 * Site brand: custom logo if set, otherwise a text logo with a small mark.
 *
 * The blue header and the footer have dark backgrounds. There the optional
 * "logo for dark backgrounds" (Customizer) is used; without it, the regular
 * logo is placed on a small white tile so a dark logo stays visible.
 *
 * @param string $context header|footer.
 */
function ktuehk_brand( $context = 'header' ) {
	$name     = get_bloginfo( 'name' );
	$is_dark  = 'footer' === $context || 'blue' === ktuehk_header_style();
	$logo_id  = (int) get_theme_mod( 'custom_logo' );
	$dark_id  = (int) get_theme_mod( 'ktuehk_logo_dark' );
	$image_id = $logo_id;
	$class    = 'brand__logo';
	if ( $is_dark && $dark_id && wp_attachment_is_image( $dark_id ) ) {
		$image_id = $dark_id;
	} elseif ( $is_dark ) {
		$class .= ' brand__logo--tile';
	}

	echo '<a class="brand brand--' . esc_attr( $context ) . '" href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
	if ( $image_id && wp_attachment_is_image( $image_id ) ) {
		echo wp_get_attachment_image(
			$image_id,
			'full',
			false,
			array(
				'class'         => $class,
				'alt'           => $name,
				'loading'       => false,
				'fetchpriority' => 'header' === $context ? 'high' : 'auto',
				'decoding'      => 'async',
			)
		);
	} else {
		echo ktuehk_brand_mark(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG.
		echo '<span class="brand__text">';
		echo '<span class="brand__name">' . esc_html__( 'KTÜ EHK', 'ktuehk' ) . '</span>';
		echo '<span class="brand__sub">' . esc_html__( 'Elektronik ve Haberleşme Kulübü', 'ktuehk' ) . '</span>';
		echo '</span>';
	}
	echo '</a>';
}

/**
 * Brand mark: an open ring (radiation pattern) around an arch-shaped
 * antenna element. Drawn with currentColor so it adapts to light and dark
 * backgrounds.
 *
 * @return string
 */
function ktuehk_brand_mark() {
	return '<svg class="brand__mark" width="40" height="40" viewBox="0 0 40 40" fill="none" stroke="currentColor" aria-hidden="true" focusable="false">'
		. '<path d="M12.2 35.6A16.5 16.5 0 1 1 27.8 35.6" stroke-width="4.4" stroke-linecap="round"/>'
		. '<path d="M14.6 38V22.6a5.4 5.4 0 0 1 10.8 0V38" stroke-width="4.4"/>'
		. '</svg>';
}

/**
 * Page lookup by possible slugs (first published match).
 *
 * @param string[] $slugs Slugs.
 * @return WP_Post|null
 */
function ktuehk_find_page( $slugs ) {
	foreach ( $slugs as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page && 'publish' === $page->post_status ) {
			return $page;
		}
	}
	return null;
}

/**
 * The about page: slug "hakkimizda" or any page using the about template.
 *
 * @return WP_Post|null
 */
function ktuehk_about_page() {
	static $page = false;
	if ( false === $page ) {
		$page = ktuehk_find_page( array( 'hakkimizda', 'hakkinda', 'about' ) );
		if ( ! $page ) {
			$found = get_posts(
				array(
					'post_type'      => 'page',
					'posts_per_page' => 1,
					'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
					'meta_value'     => 'page-templates/template-hakkimizda.php', // phpcs:ignore WordPress.DB.SlowDBQuery
					'no_found_rows'  => true,
				)
			);
			$page  = $found ? $found[0] : null;
		}
	}
	return $page;
}

/**
 * URL of the posts listing ("Yazılar").
 *
 * @return string
 */
function ktuehk_posts_url() {
	$page_id = (int) get_option( 'page_for_posts' );
	if ( $page_id && 'page' === get_option( 'show_on_front' ) ) {
		return (string) get_permalink( $page_id );
	}
	return home_url( '/' );
}

/**
 * Default navigation items (used when no menu is assigned): Ana Sayfa,
 * Yazılar, Projeler, Etkinlikler, Hakkımızda. A menu created under
 * Görünüm › Menüler (for example one that also links the contact page)
 * replaces these.
 *
 * @return array<int,array{label:string,url:string,current:bool}>
 */
function ktuehk_default_menu_items() {
	$project = ktuehk_project_type();
	$event   = ktuehk_event_type();
	$items   = array(
		array(
			'label'   => __( 'Ana Sayfa', 'ktuehk' ),
			'url'     => home_url( '/' ),
			'current' => is_front_page(),
		),
		array(
			'label'   => __( 'Yazılar', 'ktuehk' ),
			'url'     => ktuehk_posts_url(),
			'current' => ( is_home() && ! is_front_page() ) || is_singular( 'post' ) || is_category() || is_tag() || is_author() || is_date(),
		),
	);
	if ( post_type_exists( $project ) ) {
		$items[] = array(
			'label'   => __( 'Projeler', 'ktuehk' ),
			'url'     => (string) get_post_type_archive_link( $project ),
			'current' => is_post_type_archive( $project ) || is_singular( $project ) || is_tax( array( 'ehk_program', 'ehk_alan' ) ),
		);
	}
	if ( post_type_exists( $event ) ) {
		$items[] = array(
			'label'   => __( 'Etkinlikler', 'ktuehk' ),
			'url'     => (string) get_post_type_archive_link( $event ),
			'current' => is_post_type_archive( $event ) || is_singular( $event ) || is_tax( 'ehk_etkinlik_turu' ),
		);
	}
	$about = ktuehk_about_page();
	if ( $about ) {
		$items[] = array(
			'label'   => __( 'Hakkımızda', 'ktuehk' ),
			'url'     => (string) get_permalink( $about ),
			'current' => is_page( $about->ID ),
		);
	}
	$contact = function_exists( 'ktuehk_contact_page' ) ? ktuehk_contact_page() : null;
	if ( $contact ) {
		$items[] = array(
			'label'   => __( 'İletişim', 'ktuehk' ),
			'url'     => (string) get_permalink( $contact ),
			'current' => is_page( $contact->ID ),
		);
	}
	return (array) apply_filters( 'ktuehk_default_menu_items', $items );
}

/**
 * Fallback for wp_nav_menu() when no menu is assigned to a location.
 *
 * @param array $args wp_nav_menu() arguments.
 */
function ktuehk_menu_fallback( $args ) {
	$args = (array) $args;
	echo '<ul class="' . esc_attr( isset( $args['menu_class'] ) ? $args['menu_class'] : 'menu' ) . '">';
	foreach ( ktuehk_default_menu_items() as $item ) {
		printf(
			'<li class="menu-item%1$s"><a href="%2$s"%3$s>%4$s</a></li>',
			$item['current'] ? ' current-menu-item' : '',
			esc_url( $item['url'] ),
			$item['current'] ? ' aria-current="page"' : '',
			esc_html( $item['label'] )
		);
	}
	echo '</ul>';
}

/**
 * Footer quick links fallback (same items as the main menu).
 *
 * @param array $args wp_nav_menu() arguments.
 */
function ktuehk_footer_menu_fallback( $args ) {
	ktuehk_menu_fallback( $args );
}

/**
 * Estimated reading time in minutes (≈200 words per minute).
 *
 * @param int|WP_Post|null $post Post.
 * @return int
 */
function ktuehk_reading_time( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return 1;
	}
	$text  = wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
	$words = count( preg_split( '/\s+/u', trim( $text ), -1, PREG_SPLIT_NO_EMPTY ) );
	return max( 1, (int) ceil( $words / (int) apply_filters( 'ktuehk_words_per_minute', 200 ) ) );
}

/**
 * Meta line for posts: author, date, reading time.
 *
 * @param array            $parts Which parts to show.
 * @param int|WP_Post|null $post  Post.
 */
function ktuehk_post_meta( $parts = array( 'author', 'date', 'reading' ), $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}
	echo '<ul class="meta">';
	foreach ( $parts as $part ) {
		switch ( $part ) {
			case 'author':
				$author = ktuehk_display_author( $post );
				if ( $author['name'] ) {
					echo '<li class="meta__item">' . ktuehk_icon( 'user', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput
					echo '<span class="screen-reader-text">' . esc_html__( 'Yazar:', 'ktuehk' ) . ' </span>';
					echo esc_html( $author['name'] ) . '</li>';
				}
				break;
			case 'date':
				printf(
					'<li class="meta__item">%1$s<span class="screen-reader-text">%2$s </span><time datetime="%3$s">%4$s</time></li>',
					ktuehk_icon( 'calendar', 16 ), // phpcs:ignore WordPress.Security.EscapeOutput
					esc_html__( 'Yayın tarihi:', 'ktuehk' ),
					esc_attr( get_the_date( 'c', $post ) ),
					esc_html( get_the_date( '', $post ) )
				);
				break;
			case 'reading':
				printf(
					'<li class="meta__item">%1$s%2$s</li>',
					ktuehk_icon( 'clock', 16 ), // phpcs:ignore WordPress.Security.EscapeOutput
					/* translators: %d: minutes */
					esc_html( sprintf( __( '%d dk okuma', 'ktuehk' ), ktuehk_reading_time( $post ) ) )
				);
				break;
			case 'reading_short':
				printf(
					'<li class="meta__item">%1$s<span class="screen-reader-text">%2$s </span>%3$s</li>',
					ktuehk_icon( 'clock', 16 ), // phpcs:ignore WordPress.Security.EscapeOutput
					esc_html__( 'Tahmini okuma süresi:', 'ktuehk' ),
					/* translators: %d: minutes */
					esc_html( sprintf( __( '%d dk', 'ktuehk' ), ktuehk_reading_time( $post ) ) )
				);
				break;
		}
	}
	echo '</ul>';
}

/**
 * Image for a card: the featured image or, when none is set, the first
 * image in the content (media library image, attached image or plain URL).
 *
 * @param int|WP_Post|null $post Post.
 * @return array{id:int,url:string}
 */
function ktuehk_card_image( $post = null ) {
	static $cache = array();
	$post  = get_post( $post );
	$found = array(
		'id'  => 0,
		'url' => '',
	);
	if ( ! $post ) {
		return $found;
	}
	if ( isset( $cache[ $post->ID ] ) ) {
		return $cache[ $post->ID ];
	}

	$found['id'] = (int) get_post_thumbnail_id( $post );
	if ( ! $found['id'] ) {
		$content = (string) $post->post_content;
		if ( preg_match( '/wp-image-(\d+)|<!-- wp:image \{[^}]*"id":(\d+)/', $content, $m ) ) {
			$found['id'] = (int) ( ! empty( $m[1] ) ? $m[1] : $m[2] );
		}
		if ( ! $found['id'] ) {
			$attached = get_children(
				array(
					'post_parent'    => $post->ID,
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'numberposts'    => 1,
					'orderby'        => 'menu_order ID',
					'order'          => 'ASC',
					'fields'         => 'ids',
				)
			);
			$found['id'] = $attached ? (int) reset( $attached ) : 0;
		}
		if ( $found['id'] && ! wp_attachment_is_image( $found['id'] ) ) {
			$found['id'] = 0;
		}
		if ( ! $found['id'] && preg_match( '/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $m ) ) {
			$found['url'] = $m[1];
		}
	}

	$cache[ $post->ID ] = (array) apply_filters( 'ktuehk_card_image', $found, $post );
	return $cache[ $post->ID ];
}

/**
 * Image or a neutral technical placeholder for cards and covers.
 *
 * @param array            $args {
 *     @type int    $image_id      Explicit attachment ID (defaults to the featured image).
 *     @type string $size          Image size.
 *     @type string $sizes         sizes attribute.
 *     @type string $class         Wrapper class.
 *     @type string $icon          Placeholder icon.
 *     @type bool   $priority      Load eagerly with high priority (above the fold).
 *     @type bool   $contain       Show the whole image (posters).
 * }
 * @param int|WP_Post|null $post Post.
 * @return string
 */
function ktuehk_media( $args = array(), $post = null ) {
	$post  = get_post( $post );
	$image = ktuehk_card_image( $post );
	$args  = wp_parse_args(
		$args,
		array(
			'image_id' => $image['id'],
			'size'     => 'medium_large',
			'sizes'    => '(min-width: 1200px) 380px, (min-width: 640px) 50vw, 100vw',
			'class'    => 'card__media',
			'icon'     => 'file-text',
			'priority' => false,
			'contain'  => false,
		)
	);
	$class = $args['class'] . ( $args['contain'] ? ' is-contain' : '' );

	if ( $args['image_id'] && wp_attachment_is_image( $args['image_id'] ) ) {
		$alt   = trim( (string) get_post_meta( $args['image_id'], '_wp_attachment_image_alt', true ) );
		$attrs = array(
			'alt'      => '' !== $alt ? $alt : ( $post ? get_the_title( $post ) : '' ),
			'sizes'    => $args['sizes'],
			'decoding' => 'async',
		);
		if ( $args['priority'] ) {
			$attrs['loading']       = false;
			$attrs['fetchpriority'] = 'high';
		} else {
			$attrs['loading'] = 'lazy';
		}
		return '<div class="' . esc_attr( $class ) . '">' . wp_get_attachment_image( $args['image_id'], $args['size'], false, $attrs ) . '</div>';
	}

	// Image inserted into the content by URL only (not in the media library).
	if ( ! $args['image_id'] && $image['url'] ) {
		return sprintf(
			'<div class="%1$s"><img src="%2$s" alt="%3$s" %4$s decoding="async"></div>',
			esc_attr( $class ),
			esc_url( $image['url'] ),
			esc_attr( $post ? get_the_title( $post ) : '' ),
			$args['priority'] ? 'fetchpriority="high"' : 'loading="lazy"'
		);
	}

	return '<div class="' . esc_attr( $class ) . ' media-ph" aria-hidden="true">' . ktuehk_icon( $args['icon'], 32 ) . '</div>';
}

/**
 * First term of a taxonomy (categories: skip the default "Genel" when the
 * post has others).
 *
 * @param string           $taxonomy Taxonomy.
 * @param int|WP_Post|null $post     Post.
 * @return WP_Term|null
 */
function ktuehk_primary_term( $taxonomy, $post = null ) {
	$terms = get_the_terms( get_post( $post ), $taxonomy );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return null;
	}
	if ( 'category' === $taxonomy && count( $terms ) > 1 ) {
		$default = (int) get_option( 'default_category' );
		$terms   = array_values(
			array_filter(
				$terms,
				static function ( $term ) use ( $default ) {
					return (int) $term->term_id !== $default;
				}
			)
		);
	}
	return $terms ? $terms[0] : null;
}

/**
 * Breadcrumb navigation.
 */
function ktuehk_breadcrumbs() {
	$items = ktuehk_breadcrumb_items();
	if ( count( $items ) < 2 ) {
		return;
	}
	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Sayfa konumu', 'ktuehk' ) . '"><ol>';
	$last = count( $items ) - 1;
	foreach ( $items as $i => $item ) {
		echo '<li>';
		if ( $i < $last && $item['url'] ) {
			echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['name'] ) . '</a>';
		} else {
			echo '<span' . ( $i === $last ? ' aria-current="page"' : '' ) . '>' . esc_html( $item['name'] ) . '</span>';
		}
		echo '</li>';
	}
	echo '</ol></nav>';
}

/**
 * Numbered pagination for archives.
 */
function ktuehk_pagination() {
	the_posts_pagination(
		array(
			'mid_size'           => 1,
			'prev_text'          => ktuehk_icon( 'arrow-left', 18 ) . '<span class="screen-reader-text">' . esc_html__( 'Önceki sayfa', 'ktuehk' ) . '</span>',
			'next_text'          => '<span class="screen-reader-text">' . esc_html__( 'Sonraki sayfa', 'ktuehk' ) . '</span>' . ktuehk_icon( 'arrow-right', 18 ),
			'screen_reader_text' => __( 'Sayfalar', 'ktuehk' ),
			'aria_label'         => __( 'Sayfalar', 'ktuehk' ),
			'class'              => 'pagination',
		)
	);
}

/**
 * Section heading with optional "see all" link.
 *
 * @param array $args eyebrow, title, desc, id, link, link_text.
 */
function ktuehk_section_head( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'eyebrow'   => '',
			'title'     => '',
			'desc'      => '',
			'id'        => '',
			'link'      => '',
			'link_text' => '',
		)
	);
	echo '<div class="section-head">';
	echo '<div class="section-head__text">';
	if ( $args['eyebrow'] ) {
		echo '<p class="eyebrow">' . esc_html( $args['eyebrow'] ) . '</p>';
	}
	echo '<h2 class="section-head__title"' . ( $args['id'] ? ' id="' . esc_attr( $args['id'] ) . '"' : '' ) . '>' . esc_html( $args['title'] ) . '</h2>';
	if ( $args['desc'] ) {
		echo '<p class="section-head__desc">' . esc_html( $args['desc'] ) . '</p>';
	}
	echo '</div>';
	if ( $args['link'] ) {
		echo '<a class="link-arrow" href="' . esc_url( $args['link'] ) . '">' . esc_html( $args['link_text'] ) . ktuehk_icon( 'arrow-right', 18 ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	echo '</div>';
}

/**
 * Uppercase that respects Turkish dotted/dotless i ("Eki" → "EKİ") and
 * works without the mbstring extension.
 *
 * @param string $text Text.
 * @return string
 */
function ktuehk_upper( $text ) {
	if ( 0 === strpos( determine_locale(), 'tr' ) ) {
		$text = strtr( $text, array( 'i' => 'İ', 'ı' => 'I' ) );
	}
	if ( function_exists( 'mb_strtoupper' ) ) {
		return mb_strtoupper( $text, 'UTF-8' );
	}
	return strtoupper( strtr( $text, array( 'ç' => 'Ç', 'ğ' => 'Ğ', 'ö' => 'Ö', 'ş' => 'Ş', 'ü' => 'Ü' ) ) );
}

/**
 * Event date pieces in the site timezone and language.
 *
 * @param int|WP_Post|null $post Post.
 * @return array|null
 */
function ktuehk_event_date( $post = null ) {
	if ( ! function_exists( 'ktuehk_event_start' ) ) {
		return null;
	}
	$start = ktuehk_event_start( $post );
	if ( ! $start ) {
		return null;
	}
	$end     = ktuehk_event_end( $post );
	$ts      = $start->getTimestamp();
	$all_day = '00:00' === $start->format( 'H:i' ) && ( ! $end || '00:00' === $end->format( 'H:i' ) );
	return array(
		'start'   => $start,
		'end'     => $end,
		'day'     => wp_date( 'j', $ts ),
		'month'   => ktuehk_upper( wp_date( 'M', $ts ) ),
		'year'    => wp_date( 'Y', $ts ),
		'weekday' => wp_date( 'l', $ts ),
		'time'    => $all_day ? '' : wp_date( 'H:i', $ts ),
		'iso'     => $start->format( 'c' ),
		'all_day' => $all_day,
	);
}

/**
 * Human readable event date/time, e.g. "14 Ekim 2026, Çarşamba · 14:00–16:00".
 *
 * @param int|WP_Post|null $post Post.
 * @return string
 */
function ktuehk_event_when( $post = null ) {
	$date = ktuehk_event_date( $post );
	if ( ! $date ) {
		return '';
	}
	$ts   = $date['start']->getTimestamp();
	$text = wp_date( get_option( 'date_format', 'j F Y' ), $ts ) . ', ' . $date['weekday'];
	$end  = $date['end'];

	if ( $end && $end->format( 'Y-m-d' ) !== $date['start']->format( 'Y-m-d' ) ) {
		$text .= ' – ' . wp_date( get_option( 'date_format', 'j F Y' ), $end->getTimestamp() );
		if ( ! $date['all_day'] ) {
			$text .= ' · ' . $date['time'];
		}
		return $text;
	}
	if ( ! $date['all_day'] ) {
		$text .= ' · ' . $date['time'];
		if ( $end ) {
			$text .= '–' . wp_date( 'H:i', $end->getTimestamp() );
		}
	}
	return $text;
}

/**
 * Event time range, e.g. "14:00–17:00" (empty for all-day events).
 *
 * @param int|WP_Post|null $post Post.
 * @return string
 */
function ktuehk_event_time_range( $post = null ) {
	$date = ktuehk_event_date( $post );
	if ( ! $date || $date['all_day'] ) {
		return '';
	}
	$range = $date['time'];
	if ( $date['end'] && $date['end']->format( 'Y-m-d' ) === $date['start']->format( 'Y-m-d' ) ) {
		$range .= '–' . wp_date( 'H:i', $date['end']->getTimestamp() );
	}
	return $range;
}

/**
 * Whether an event is upcoming (false when the core plugin is missing).
 *
 * @param int|WP_Post|null $post Post.
 * @return bool
 */
function ktuehk_is_upcoming( $post = null ) {
	return function_exists( 'ktuehk_event_is_upcoming' ) && ktuehk_event_is_upcoming( $post );
}

/**
 * Published count for a post type.
 *
 * @param string $type Post type.
 * @return int
 */
function ktuehk_count( $type ) {
	if ( ! post_type_exists( $type ) ) {
		return 0;
	}
	$counts = wp_count_posts( $type );
	return isset( $counts->publish ) ? (int) $counts->publish : 0;
}

/**
 * Wrap search terms in <mark>.
 *
 * @param string $text  Plain text.
 * @param string $query Search query.
 * @return string Escaped HTML.
 */
function ktuehk_highlight( $text, $query ) {
	$text  = esc_html( $text );
	$terms = array_filter(
		preg_split( '/\s+/u', trim( (string) $query ) ),
		static function ( $term ) {
			return mb_strlen( $term ) >= 2;
		}
	);
	if ( ! $terms ) {
		return $text;
	}
	$pattern = implode(
		'|',
		array_map(
			static function ( $term ) {
				return preg_quote( esc_html( $term ), '/' );
			},
			$terms
		)
	);
	$marked  = preg_replace( '/(' . $pattern . ')/iu', '<mark>$1</mark>', $text );
	return null === $marked ? $text : $marked;
}

/**
 * Text snippet around the first search match.
 *
 * @param WP_Post $post  Post.
 * @param string  $query Search query.
 * @return string Plain text.
 */
function ktuehk_search_snippet( $post, $query ) {
	$text  = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( strip_shortcodes( excerpt_remove_blocks( $post->post_content ) ) ) ) );
	$terms = preg_split( '/\s+/u', trim( (string) $query ) );
	$pos   = false;
	foreach ( $terms as $term ) {
		if ( mb_strlen( $term ) < 2 ) {
			continue;
		}
		$pos = function_exists( 'mb_stripos' ) ? mb_stripos( $text, $term ) : stripos( $text, $term );
		if ( false !== $pos ) {
			break;
		}
	}
	if ( false === $pos ) {
		return has_excerpt( $post ) ? $post->post_excerpt : wp_html_excerpt( $text, 200, '…' );
	}
	$start   = max( 0, $pos - 80 );
	$snippet = mb_substr( $text, $start, 220 );
	return ( $start > 0 ? '…' : '' ) . trim( $snippet ) . ( mb_strlen( $text ) > $start + 220 ? '…' : '' );
}
