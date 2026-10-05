<?php
/**
 * Theme setup, menus, template routing and small front-end adjustments.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme supports and menus.
 */
function ktuehk_setup() {
	load_theme_textdomain( 'ktuehk', KTUEHK_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'               => 96,
			'width'                => 320,
			'flex-height'          => true,
			'flex-width'           => true,
			'unlink-homepage-logo' => false,
		)
	);

	// Block editor: match the front-end look while writing.
	add_theme_support( 'editor-styles' );
	add_editor_style( array( 'assets/css/editor.css' ) );
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'KTÜ mavisi', 'ktuehk' ),
				'slug'  => 'ktu-blue',
				'color' => '#0b4a96',
			),
			array(
				'name'  => __( 'Lacivert', 'ktuehk' ),
				'slug'  => 'navy',
				'color' => '#0a1f3d',
			),
			array(
				'name'  => __( 'Açık mavi', 'ktuehk' ),
				'slug'  => 'blue-tint',
				'color' => '#e8f0fb',
			),
			array(
				'name'  => __( 'Açık gri', 'ktuehk' ),
				'slug'  => 'gray-50',
				'color' => '#f4f6f9',
			),
			array(
				'name'  => __( 'Gri', 'ktuehk' ),
				'slug'  => 'gray-600',
				'color' => '#4f5b6b',
			),
			array(
				'name'  => __( 'Beyaz', 'ktuehk' ),
				'slug'  => 'white',
				'color' => '#ffffff',
			),
		)
	);
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name' => __( 'Küçük', 'ktuehk' ),
				'slug' => 'small',
				'size' => 15,
			),
			array(
				'name' => __( 'Normal', 'ktuehk' ),
				'slug' => 'normal',
				'size' => 18,
			),
			array(
				'name' => __( 'Büyük', 'ktuehk' ),
				'slug' => 'large',
				'size' => 22,
			),
		)
	);

	// Page excerpts are used as the page header description.
	add_post_type_support( 'page', 'excerpt' );

	register_nav_menus(
		array(
			'primary' => __( 'Ana menü (üst)', 'ktuehk' ),
			'footer'  => __( 'Alt bilgi — Hızlı bağlantılar', 'ktuehk' ),
		)
	);
}
add_action( 'after_setup_theme', 'ktuehk_setup' );

/**
 * Content width for embeds / images in the editor.
 */
function ktuehk_content_width() {
	$GLOBALS['content_width'] = (int) apply_filters( 'ktuehk_content_width', 760 );
}
add_action( 'after_setup_theme', 'ktuehk_content_width', 0 );

/**
 * When switching from the previous theme, carry over its logo and social
 * media links (theme settings are stored per theme in WordPress).
 * Nothing is removed from the previous theme's settings.
 *
 * @param string        $old_name  Previous theme name.
 * @param WP_Theme|bool $old_theme Previous theme.
 */
function ktuehk_after_switch_theme( $old_name, $old_theme = false ) {
	$old_stylesheet = $old_theme instanceof WP_Theme ? $old_theme->get_stylesheet() : (string) get_option( 'theme_switched' );
	$old_mods       = $old_stylesheet ? get_option( 'theme_mods_' . $old_stylesheet ) : array();

	if ( is_array( $old_mods ) ) {
		if ( ! get_theme_mod( 'custom_logo' ) && ! empty( $old_mods['custom_logo'] ) && wp_attachment_is_image( (int) $old_mods['custom_logo'] ) ) {
			set_theme_mod( 'custom_logo', (int) $old_mods['custom_logo'] );
		}

		$hosts = array(
			'instagram' => array( 'instagram.com' ),
			'linkedin'  => array( 'linkedin.com' ),
			'github'    => array( 'github.com' ),
			'youtube'   => array( 'youtube.com', 'youtu.be' ),
			'x'         => array( 'twitter.com', 'x.com' ),
			'discord'   => array( 'discord.gg', 'discord.com' ),
			'telegram'  => array( 't.me', 'telegram.me' ),
			'medium'    => array( 'medium.com' ),
		);
		$found = array();
		array_walk_recursive(
			$old_mods,
			static function ( $value ) use ( $hosts, &$found ) {
				if ( ! is_string( $value ) || ! preg_match( '#^https?://#i', $value ) ) {
					return;
				}
				$host = strtolower( (string) wp_parse_url( $value, PHP_URL_HOST ) );
				$host = preg_replace( '/^www\./', '', $host );
				foreach ( $hosts as $network => $domains ) {
					if ( empty( $found[ $network ] ) && in_array( $host, $domains, true ) ) {
						$found[ $network ] = esc_url_raw( $value );
					}
				}
			}
		);
		foreach ( $found as $network => $url ) {
			if ( ! get_theme_mod( 'ktuehk_social_' . $network ) ) {
				set_theme_mod( 'ktuehk_social_' . $network, $url );
			}
		}
	}

	update_option( 'ktuehk_setup_notice', 1, false );
}
add_action( 'after_switch_theme', 'ktuehk_after_switch_theme', 10, 2 );

/**
 * Route configurable content types to the theme templates, so the design
 * also applies when the project/event post type key is not the default.
 *
 * @param string[] $templates Template candidates.
 * @return string[]
 */
function ktuehk_route_archive_templates( $templates ) {
	if ( is_post_type_archive( ktuehk_project_type() ) || is_tax( array( 'ehk_program', 'ehk_alan' ) ) ) {
		array_unshift( $templates, 'archive-proje.php' );
	} elseif ( is_post_type_archive( ktuehk_event_type() ) || is_tax( 'ehk_etkinlik_turu' ) ) {
		array_unshift( $templates, 'archive-etkinlik.php' );
	}
	return $templates;
}
add_filter( 'archive_template_hierarchy', 'ktuehk_route_archive_templates' );
add_filter( 'taxonomy_template_hierarchy', 'ktuehk_route_archive_templates' );

/**
 * Route single project / event views.
 *
 * @param string[] $templates Template candidates.
 * @return string[]
 */
function ktuehk_route_single_templates( $templates ) {
	if ( is_singular( ktuehk_project_type() ) ) {
		array_unshift( $templates, 'single-proje.php' );
	} elseif ( is_singular( ktuehk_event_type() ) ) {
		array_unshift( $templates, 'single-etkinlik.php' );
	}
	return $templates;
}
add_filter( 'single_template_hierarchy', 'ktuehk_route_single_templates' );

/**
 * Search: limit results to content people look for and respect the
 * optional ?post_type= filter used by the result tabs.
 *
 * @param WP_Query $query Query.
 */
function ktuehk_search_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}
	$allowed   = ktuehk_search_types();
	$requested = $query->get( 'post_type' );
	if ( is_string( $requested ) && isset( $allowed[ $requested ] ) ) {
		$query->set( 'post_type', $requested );
	} else {
		$query->set( 'post_type', array_keys( $allowed ) );
	}
	$query->set( 'posts_per_page', 12 );
}
add_action( 'pre_get_posts', 'ktuehk_search_query' );

/**
 * Searchable content types with labels (only those that exist).
 *
 * @return array<string,string>
 */
function ktuehk_search_types() {
	$types = array(
		'post'                 => __( 'Yazılar', 'ktuehk' ),
		ktuehk_project_type()  => __( 'Projeler', 'ktuehk' ),
		ktuehk_event_type()    => __( 'Etkinlikler', 'ktuehk' ),
		'page'                 => __( 'Sayfalar', 'ktuehk' ),
	);
	return array_filter(
		$types,
		static function ( $type ) {
			return post_type_exists( $type );
		},
		ARRAY_FILTER_USE_KEY
	);
}

/**
 * Body classes.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function ktuehk_body_classes( $classes ) {
	$classes[] = 'header-' . ( 'light' === get_theme_mod( 'ktuehk_header_style', 'blue' ) ? 'light' : 'blue' );
	if ( is_singular() && has_post_thumbnail() ) {
		$classes[] = 'has-cover';
	}
	return $classes;
}
add_filter( 'body_class', 'ktuehk_body_classes' );

/**
 * Menu highlighting: mark the Projects / Events items on their single and
 * taxonomy views, and stop WordPress from highlighting the posts page on
 * non-post screens (a core back-compat quirk).
 *
 * @param string[] $classes Classes.
 * @param WP_Post  $item    Menu item.
 * @return string[]
 */
function ktuehk_menu_item_classes( $classes, $item ) {
	$url        = untrailingslashit( (string) $item->url );
	$is_section = static function ( $type ) use ( $url ) {
		$archive = get_post_type_archive_link( $type );
		return $archive && untrailingslashit( $archive ) === $url;
	};

	if ( ( is_singular( ktuehk_project_type() ) || is_tax( array( 'ehk_program', 'ehk_alan' ) ) ) && $is_section( ktuehk_project_type() ) ) {
		$classes[] = 'current-menu-ancestor';
	}
	if ( ( is_singular( ktuehk_event_type() ) || is_tax( 'ehk_etkinlik_turu' ) ) && $is_section( ktuehk_event_type() ) ) {
		$classes[] = 'current-menu-ancestor';
	}

	$is_post_context = is_singular( 'post' ) || is_home() || is_category() || is_tag() || is_author() || is_date();
	if ( ! $is_post_context && 'post_type' === $item->type && (int) $item->object_id === (int) get_option( 'page_for_posts' ) ) {
		$classes = array_diff( $classes, array( 'current_page_parent' ) );
	}
	return array_unique( $classes );
}
add_filter( 'nav_menu_css_class', 'ktuehk_menu_item_classes', 10, 2 );

/**
 * aria-current for highlighted menu items.
 *
 * @param array   $atts Link attributes.
 * @param WP_Post $item Menu item.
 * @return array
 */
function ktuehk_menu_link_atts( $atts, $item ) {
	$current = array_intersect( (array) $item->classes, array( 'current-menu-item' ) );
	if ( $current ) {
		$atts['aria-current'] = 'page';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'ktuehk_menu_link_atts', 10, 2 );

/**
 * Shorter, cleaner excerpts.
 *
 * @return int
 */
function ktuehk_excerpt_length() {
	return 28;
}
add_filter( 'excerpt_length', 'ktuehk_excerpt_length' );

/**
 * Excerpt suffix.
 *
 * @return string
 */
function ktuehk_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'ktuehk_excerpt_more' );

// Remove "Kategori:", "Arşivler:" prefixes; templates show the context separately.
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

/**
 * Drop the emoji detection script on the front end (saves a request and
 * ~20 KB of inline JS). Browsers render emoji natively.
 */
function ktuehk_disable_emoji() {
	if ( ! apply_filters( 'ktuehk_disable_emoji', true ) ) {
		return;
	}
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
}
add_action( 'init', 'ktuehk_disable_emoji' );

/**
 * Front page meta description: use the hero text (core plugin hook).
 *
 * @return string
 */
function ktuehk_home_description() {
	return ktuehk_mod( 'hero_text' );
}
add_filter( 'ktuehk_core_home_description', 'ktuehk_home_description' );

/**
 * Archive descriptions for SEO (core plugin hook).
 *
 * @param string $text   Description.
 * @param object $object Queried object.
 * @return string
 */
function ktuehk_archive_description( $text, $object ) {
	if ( $object instanceof WP_Post_Type ) {
		if ( ktuehk_project_type() === $object->name ) {
			return ktuehk_mod( 'intro_projects' );
		}
		if ( ktuehk_event_type() === $object->name ) {
			return ktuehk_mod( 'intro_events' );
		}
	}
	return $text;
}
add_filter( 'ktuehk_core_archive_description', 'ktuehk_archive_description', 10, 2 );
add_filter(
	'ktuehk_core_posts_description',
	static function () {
		return ktuehk_mod( 'intro_posts' );
	}
);

/**
 * Social profiles for Organization schema (core plugin hook).
 *
 * @return string[]
 */
function ktuehk_same_as() {
	return wp_list_pluck( ktuehk_social_links(), 'url' );
}
add_filter( 'ktuehk_core_same_as', 'ktuehk_same_as' );
