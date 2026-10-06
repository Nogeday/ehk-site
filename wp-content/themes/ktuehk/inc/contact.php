<?php
/**
 * Contact details: one central place for e-mail, social accounts and map.
 *
 * Values are edited in the admin under
 *   Görünüm › Özelleştir › KTÜ EHK Tema Ayarları › İletişim ve sosyal medya
 * and stored as theme mods, so they survive theme updates. The defaults
 * below are placeholders: as long as a value still looks like "[...]",
 * it is treated as "not set yet" (visitors see "Yakında eklenecek",
 * editors see which setting is missing). No real account data is invented.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

/**
 * Placeholder defaults (used until the Customizer fields are filled in).
 *
 * @return array<string,string>
 */
function ktuehk_contact_defaults() {
	return (array) apply_filters(
		'ktuehk_contact_defaults',
		array(
			'email'           => '[EMAIL]',
			'instagram'       => '[INSTAGRAM_URL]',
			'linkedin'        => '[LINKEDIN_URL]',
			'maps_embed'      => '[GOOGLE_MAPS_EMBED_URL]',
			'maps_directions' => '[GOOGLE_MAPS_DIRECTIONS_URL]',
		)
	);
}

/**
 * Theme mod that stores each contact value. Instagram and LinkedIn share
 * the existing social media settings (also used in the footer).
 *
 * @return array<string,string>
 */
function ktuehk_contact_settings() {
	return array(
		'email'           => 'ktuehk_contact_email',
		'instagram'       => 'ktuehk_social_instagram',
		'linkedin'        => 'ktuehk_social_linkedin',
		'maps_embed'      => 'ktuehk_maps_embed',
		'maps_directions' => 'ktuehk_maps_directions',
	);
}

/**
 * A contact value: the saved setting, otherwise the placeholder default.
 *
 * @param string $key email|instagram|linkedin|maps_embed|maps_directions.
 * @return string
 */
function ktuehk_contact( $key ) {
	$settings = ktuehk_contact_settings();
	$defaults = ktuehk_contact_defaults();
	$value    = isset( $settings[ $key ] ) ? trim( (string) get_theme_mod( $settings[ $key ], '' ) ) : '';
	return '' !== $value ? $value : ( isset( $defaults[ $key ] ) ? (string) $defaults[ $key ] : '' );
}

/**
 * Whether a contact value has been filled in (not empty, not "[...]").
 *
 * @param string $key Key.
 * @return bool
 */
function ktuehk_contact_ready( $key ) {
	$value = ktuehk_contact( $key );
	return '' !== $value && '[' !== $value[0];
}

/**
 * Sanitize the Google Maps embed setting. Accepts either the embed URL or
 * the full <iframe> code copied from Google Maps ("Paylaş › Harita yerleştir")
 * and keeps only an https Google Maps URL.
 *
 * @param string $value Raw input.
 * @return string
 */
function ktuehk_sanitize_maps_embed( $value ) {
	$value = trim( (string) $value );
	if ( preg_match( '/src=["\']([^"\']+)["\']/i', $value, $m ) ) {
		$value = html_entity_decode( $m[1], ENT_QUOTES );
	}
	$url  = esc_url_raw( $value, array( 'https' ) );
	$host = (string) wp_parse_url( $url, PHP_URL_HOST );
	$path = (string) wp_parse_url( $url, PHP_URL_PATH );
	if ( preg_match( '/(^|\.)google\.[a-z.]+$/i', $host ) && 0 === strpos( $path, '/maps' ) ) {
		return $url;
	}
	return '';
}

/**
 * University / department lines (footer and contact page).
 *
 * @return string[]
 */
function ktuehk_address_lines() {
	return (array) apply_filters(
		'ktuehk_address_lines',
		array(
			__( 'Karadeniz Teknik Üniversitesi', 'ktuehk' ),
			__( 'Teknoloji Fakültesi', 'ktuehk' ),
			__( 'Elektronik ve Haberleşme Mühendisliği', 'ktuehk' ),
		)
	);
}

/**
 * The contact page: slug "iletisim" or any page using the contact template.
 *
 * @param bool $refresh Ignore the per-request cache (after creating the page).
 * @return WP_Post|null
 */
function ktuehk_contact_page( $refresh = false ) {
	static $page = false;
	if ( false === $page || $refresh ) {
		$page = ktuehk_find_page( array( 'iletisim' ) );
		if ( ! $page ) {
			$found = get_posts(
				array(
					'post_type'      => 'page',
					'posts_per_page' => 1,
					'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
					'meta_value'     => 'page-templates/template-iletisim.php', // phpcs:ignore WordPress.DB.SlowDBQuery
					'no_found_rows'  => true,
				)
			);
			$page  = $found ? $found[0] : null;
		}
	}
	return $page;
}

/**
 * Instagram handle from the profile URL, e.g. "@ktuehk" (empty if unknown).
 *
 * @param string $url Profile URL.
 * @return string
 */
function ktuehk_instagram_handle( $url ) {
	$path  = trim( (string) wp_parse_url( $url, PHP_URL_PATH ), '/' );
	$first = $path ? strtok( $path, '/' ) : '';
	return $first && preg_match( '/^[A-Za-z0-9._]+$/', $first ) ? '@' . $first : '';
}

/**
 * Footer social links: configured social accounts plus the e-mail address.
 *
 * @return array<int,array{key:string,label:string,url:string,icon:string,external:bool}>
 */
function ktuehk_footer_social_links() {
	$links = array();
	foreach ( ktuehk_social_links() as $link ) {
		if ( ! ktuehk_contact_url_ok( $link['url'] ) ) {
			continue;
		}
		$links[] = array(
			'key'      => $link['key'],
			/* translators: %s: network name */
			'label'    => sprintf( __( '%s (yeni sekmede açılır)', 'ktuehk' ), $link['label'] ),
			'url'      => $link['url'],
			'icon'     => 'brand-' . $link['key'],
			'external' => true,
		);
	}
	if ( ktuehk_contact_ready( 'email' ) ) {
		$links[] = array(
			'key'      => 'email',
			'label'    => __( 'E-posta gönder', 'ktuehk' ),
			'url'      => 'mailto:' . ktuehk_contact( 'email' ),
			'icon'     => 'mail',
			'external' => false,
		);
	}
	return $links;
}

/**
 * Whether a stored URL is usable (not a "[...]" placeholder).
 *
 * @param string $url URL.
 * @return bool
 */
function ktuehk_contact_url_ok( $url ) {
	$url = trim( (string) $url );
	return '' !== $url && '[' !== $url[0] && false === strpos( $url, '%5B' );
}

/**
 * On the contact page, use the contact intro as excerpt when the page has
 * none, so meta descriptions never quote older contact texts from the page
 * body. In memory only; the page itself is not modified.
 */
function ktuehk_contact_page_excerpt() {
	$contact = ktuehk_contact_page();
	if ( ! $contact || ! is_page( $contact->ID ) ) {
		return;
	}
	$post = get_queried_object();
	if ( $post instanceof WP_Post && '' === trim( (string) $post->post_excerpt ) ) {
		$post->post_excerpt = ktuehk_mod( 'intro_contact' );
	}
}
add_action( 'wp', 'ktuehk_contact_page_excerpt' );
