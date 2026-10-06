<?php
/**
 * KTÜ EHK theme bootstrap.
 *
 * Content types (Projeler, Etkinlikler) live in the "KTÜ EHK Çekirdek"
 * plugin so content survives a theme change. This theme only handles
 * presentation and degrades gracefully when the plugin is inactive.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

define( 'KTUEHK_VERSION', '1.2.0' );
define( 'KTUEHK_DIR', get_template_directory() );
define( 'KTUEHK_URI', get_template_directory_uri() );

require KTUEHK_DIR . '/inc/compat.php';
require KTUEHK_DIR . '/inc/icon-set.php';
require KTUEHK_DIR . '/inc/data.php';
require KTUEHK_DIR . '/inc/setup.php';
require KTUEHK_DIR . '/inc/assets.php';
require KTUEHK_DIR . '/inc/template-tags.php';
require KTUEHK_DIR . '/inc/contact.php';
require KTUEHK_DIR . '/inc/content.php';
require KTUEHK_DIR . '/inc/customizer.php';
require KTUEHK_DIR . '/inc/block-patterns.php';
require KTUEHK_DIR . '/inc/about-content.php';

if ( is_admin() ) {
	require KTUEHK_DIR . '/inc/admin-setup.php';
}
