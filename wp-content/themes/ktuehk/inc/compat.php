<?php
/**
 * Fallbacks for when the "KTÜ EHK Çekirdek" plugin is not active.
 *
 * Templates call the same helper names whether or not the plugin is active.
 * Each fallback delegates to the plugin's ktuehk_core_* implementation when
 * it exists (e.g. in the request that activates the plugin, where the theme
 * loads first), so the result never depends on load order. Without the
 * plugin, project/event sections simply stay hidden.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the core plugin is active.
 *
 * @return bool
 */
function ktuehk_has_core() {
	return function_exists( 'ktuehk_core_settings' );
}

if ( ! function_exists( 'ktuehk_project_type' ) ) {
	/**
	 * Project post type key.
	 *
	 * @return string
	 */
	function ktuehk_project_type() {
		return function_exists( 'ktuehk_core_project_type' ) ? ktuehk_core_project_type() : 'proje';
	}
}

if ( ! function_exists( 'ktuehk_event_type' ) ) {
	/**
	 * Event post type key.
	 *
	 * @return string
	 */
	function ktuehk_event_type() {
		return function_exists( 'ktuehk_core_event_type' ) ? ktuehk_core_event_type() : 'etkinlik';
	}
}

if ( ! function_exists( 'ktuehk_field' ) ) {
	/**
	 * Field reader.
	 *
	 * @param string           $name Field name.
	 * @param int|WP_Post|null $post Post.
	 * @return string
	 */
	function ktuehk_field( $name, $post = null ) {
		if ( function_exists( 'ktuehk_core_field' ) ) {
			return ktuehk_core_field( $name, $post );
		}
		$post  = get_post( $post );
		$value = $post ? get_post_meta( $post->ID, '_ehk_' . $name, true ) : '';
		return is_scalar( $value ) ? (string) $value : '';
	}
}

if ( ! function_exists( 'ktuehk_display_author' ) ) {
	/**
	 * Author data for a post.
	 *
	 * @param int|WP_Post|null $post Post.
	 * @return array
	 */
	function ktuehk_display_author( $post = null ) {
		if ( function_exists( 'ktuehk_core_display_author' ) ) {
			return ktuehk_core_display_author( $post );
		}
		$post    = get_post( $post );
		$user_id = $post ? (int) $post->post_author : 0;
		return array(
			'name'        => $user_id ? get_the_author_meta( 'display_name', $user_id ) : '',
			'description' => $user_id ? get_the_author_meta( 'description', $user_id ) : '',
			'url'         => $user_id ? get_author_posts_url( $user_id ) : '',
			'user_id'     => $user_id,
			'is_override' => false,
		);
	}
}

if ( ! function_exists( 'ktuehk_breadcrumb_items' ) ) {
	/**
	 * Breadcrumb trail.
	 *
	 * @return array
	 */
	function ktuehk_breadcrumb_items() {
		if ( function_exists( 'ktuehk_core_breadcrumb_items' ) ) {
			return ktuehk_core_breadcrumb_items();
		}
		if ( is_front_page() ) {
			return array();
		}
		$title = is_singular() ? single_post_title( '', false ) : wp_strip_all_tags( get_the_archive_title() );
		if ( is_search() ) {
			$title = __( 'Arama', 'ktuehk' );
		} elseif ( is_404() ) {
			$title = __( 'Sayfa bulunamadı', 'ktuehk' );
		} elseif ( is_home() ) {
			$title = __( 'Yazılar', 'ktuehk' );
		}
		return array(
			array(
				'name' => __( 'Ana Sayfa', 'ktuehk' ),
				'url'  => home_url( '/' ),
			),
			array(
				'name' => $title,
				'url'  => '',
			),
		);
	}
}

if ( ! function_exists( 'ktuehk_seo_plugin_active' ) ) {
	/**
	 * Whether a dedicated SEO plugin is active.
	 *
	 * @return bool
	 */
	function ktuehk_seo_plugin_active() {
		if ( function_exists( 'ktuehk_core_seo_plugin_active' ) ) {
			return ktuehk_core_seo_plugin_active();
		}
		return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
	}
}

/**
 * Whether project / event content types are available.
 *
 * @param string $kind project|event.
 * @return bool
 */
function ktuehk_type_available( $kind ) {
	return post_type_exists( 'project' === $kind ? ktuehk_project_type() : ktuehk_event_type() );
}
