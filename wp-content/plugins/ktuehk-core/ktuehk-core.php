<?php
/**
 * Plugin Name:       KTÜ EHK Çekirdek
 * Description:       KTÜ Elektronik ve Haberleşme Kulübü sitesi için içerik modeli: Projeler, Etkinlikler, proje/etkinlik alanları, yazar bilgisi ve temel SEO/schema çıktısı. İçerik temadan bağımsız saklanır; tema değişse bile kaybolmaz.
 * Version:           1.0.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            KTÜ EHK
 * License:           GPL-2.0-or-later
 * Text Domain:       ktuehk-core
 *
 * @package KTUEHK_Core
 */

defined( 'ABSPATH' ) || exit;

define( 'KTUEHK_CORE_VERSION', '1.0.0' );
define( 'KTUEHK_CORE_FILE', __FILE__ );
define( 'KTUEHK_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'KTUEHK_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once KTUEHK_CORE_DIR . 'includes/settings.php';
require_once KTUEHK_CORE_DIR . 'includes/helpers.php';
require_once KTUEHK_CORE_DIR . 'includes/content-types.php';
require_once KTUEHK_CORE_DIR . 'includes/fields.php';
require_once KTUEHK_CORE_DIR . 'includes/query.php';
require_once KTUEHK_CORE_DIR . 'includes/seo.php';
require_once KTUEHK_CORE_DIR . 'includes/ics.php';

if ( is_admin() ) {
	require_once KTUEHK_CORE_DIR . 'includes/admin.php';
}

/**
 * Activation: register types, seed default terms (only missing ones) and
 * flush rewrite rules so the new archive URLs work immediately.
 * Nothing existing is modified or deleted.
 */
function ktuehk_core_activate() {
	ktuehk_core_register_content_types();
	ktuehk_core_seed_terms();
	update_option( 'ktuehk_core_db_version', KTUEHK_CORE_VERSION );
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ktuehk_core_activate' );

/**
 * Deactivation only drops our rewrite rules. Content and settings stay in the
 * database, so re-activating restores everything as it was.
 */
function ktuehk_core_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ktuehk_core_deactivate' );

/**
 * Run lightweight upgrade routines when the plugin version changes
 * (e.g. after replacing the plugin files via FTP, where the activation hook
 * does not fire).
 */
function ktuehk_core_maybe_upgrade() {
	if ( get_option( 'ktuehk_core_db_version' ) === KTUEHK_CORE_VERSION ) {
		return;
	}
	ktuehk_core_seed_terms();
	update_option( 'ktuehk_core_db_version', KTUEHK_CORE_VERSION );
	update_option( 'ktuehk_core_flush_rewrite', 1 );
}
add_action( 'init', 'ktuehk_core_maybe_upgrade', 30 );

/**
 * Deferred rewrite flush (set when slugs change or after an upgrade).
 */
function ktuehk_core_maybe_flush_rewrite() {
	if ( get_option( 'ktuehk_core_flush_rewrite' ) ) {
		delete_option( 'ktuehk_core_flush_rewrite' );
		flush_rewrite_rules();
	}
}
add_action( 'init', 'ktuehk_core_maybe_flush_rewrite', 99 );
