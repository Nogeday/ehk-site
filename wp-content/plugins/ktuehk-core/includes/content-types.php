<?php
/**
 * Content model: Projects, Events and their taxonomies.
 *
 * If another theme/plugin already registers the configured post type key,
 * we do not register it again; we only attach our taxonomies, supports and
 * fields to it. This keeps existing content, URLs and settings intact.
 *
 * @package KTUEHK_Core
 */

defined( 'ABSPATH' ) || exit;

const KTUEHK_TAX_PROGRAM = 'ehk_program';
const KTUEHK_TAX_AREA    = 'ehk_alan';
const KTUEHK_TAX_EVENT   = 'ehk_etkinlik_turu';

/**
 * Register post types and taxonomies.
 */
function ktuehk_core_register_content_types() {
	$project = ktuehk_project_type();
	$event   = ktuehk_event_type();

	if ( ! post_type_exists( $project ) ) {
		register_post_type(
			$project,
			array(
				'labels'          => array(
					'name'                  => __( 'Projeler', 'ktuehk-core' ),
					'singular_name'         => __( 'Proje', 'ktuehk-core' ),
					'menu_name'             => __( 'Projeler', 'ktuehk-core' ),
					'add_new'               => __( 'Yeni Ekle', 'ktuehk-core' ),
					'add_new_item'          => __( 'Yeni Proje Ekle', 'ktuehk-core' ),
					'edit_item'             => __( 'Projeyi Düzenle', 'ktuehk-core' ),
					'new_item'              => __( 'Yeni Proje', 'ktuehk-core' ),
					'view_item'             => __( 'Projeyi Görüntüle', 'ktuehk-core' ),
					'view_items'            => __( 'Projeleri Görüntüle', 'ktuehk-core' ),
					'search_items'          => __( 'Proje Ara', 'ktuehk-core' ),
					'not_found'             => __( 'Proje bulunamadı.', 'ktuehk-core' ),
					'not_found_in_trash'    => __( 'Çöp kutusunda proje yok.', 'ktuehk-core' ),
					'all_items'             => __( 'Tüm Projeler', 'ktuehk-core' ),
					'archives'              => __( 'Projeler', 'ktuehk-core' ),
					'attributes'            => __( 'Proje Özellikleri', 'ktuehk-core' ),
					'insert_into_item'      => __( 'Projeye ekle', 'ktuehk-core' ),
					'uploaded_to_this_item' => __( 'Bu projeye yüklenenler', 'ktuehk-core' ),
					'featured_image'        => __( 'Proje kapak görseli', 'ktuehk-core' ),
					'set_featured_image'    => __( 'Kapak görseli ayarla', 'ktuehk-core' ),
					'remove_featured_image' => __( 'Kapak görselini kaldır', 'ktuehk-core' ),
					'use_featured_image'    => __( 'Kapak görseli olarak kullan', 'ktuehk-core' ),
					'filter_items_list'     => __( 'Projeleri filtrele', 'ktuehk-core' ),
					'items_list'            => __( 'Proje listesi', 'ktuehk-core' ),
					'items_list_navigation' => __( 'Proje listesi gezinme', 'ktuehk-core' ),
					'item_published'        => __( 'Proje yayımlandı.', 'ktuehk-core' ),
					'item_updated'          => __( 'Proje güncellendi.', 'ktuehk-core' ),
					'item_scheduled'        => __( 'Proje zamanlandı.', 'ktuehk-core' ),
					'item_reverted_to_draft' => __( 'Proje taslağa döndürüldü.', 'ktuehk-core' ),
				),
				'description'     => __( 'KTÜ EHK öğrenci projeleri: TÜBİTAK, TEKNOFEST ve kulüp içi projeler.', 'ktuehk-core' ),
				'public'          => true,
				'show_in_rest'    => true,
				'menu_position'   => 6,
				'menu_icon'       => 'dashicons-lightbulb',
				'has_archive'     => ktuehk_core_settings( 'project_slug' ),
				'rewrite'         => array(
					'slug'       => ktuehk_core_settings( 'project_slug' ),
					'with_front' => false,
				),
				'supports'        => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions', 'custom-fields' ),
				'capability_type' => 'post',
				'map_meta_cap'    => true,
			)
		);
	} else {
		add_post_type_support( $project, array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ) );
	}

	if ( ! post_type_exists( $event ) ) {
		register_post_type(
			$event,
			array(
				'labels'          => array(
					'name'                  => __( 'Etkinlikler', 'ktuehk-core' ),
					'singular_name'         => __( 'Etkinlik', 'ktuehk-core' ),
					'menu_name'             => __( 'Etkinlikler', 'ktuehk-core' ),
					'add_new'               => __( 'Yeni Ekle', 'ktuehk-core' ),
					'add_new_item'          => __( 'Yeni Etkinlik Ekle', 'ktuehk-core' ),
					'edit_item'             => __( 'Etkinliği Düzenle', 'ktuehk-core' ),
					'new_item'              => __( 'Yeni Etkinlik', 'ktuehk-core' ),
					'view_item'             => __( 'Etkinliği Görüntüle', 'ktuehk-core' ),
					'view_items'            => __( 'Etkinlikleri Görüntüle', 'ktuehk-core' ),
					'search_items'          => __( 'Etkinlik Ara', 'ktuehk-core' ),
					'not_found'             => __( 'Etkinlik bulunamadı.', 'ktuehk-core' ),
					'not_found_in_trash'    => __( 'Çöp kutusunda etkinlik yok.', 'ktuehk-core' ),
					'all_items'             => __( 'Tüm Etkinlikler', 'ktuehk-core' ),
					'archives'              => __( 'Etkinlikler', 'ktuehk-core' ),
					'insert_into_item'      => __( 'Etkinliğe ekle', 'ktuehk-core' ),
					'uploaded_to_this_item' => __( 'Bu etkinliğe yüklenenler', 'ktuehk-core' ),
					'featured_image'        => __( 'Etkinlik görseli', 'ktuehk-core' ),
					'set_featured_image'    => __( 'Etkinlik görseli ayarla', 'ktuehk-core' ),
					'remove_featured_image' => __( 'Etkinlik görselini kaldır', 'ktuehk-core' ),
					'use_featured_image'    => __( 'Etkinlik görseli olarak kullan', 'ktuehk-core' ),
					'filter_items_list'     => __( 'Etkinlikleri filtrele', 'ktuehk-core' ),
					'items_list'            => __( 'Etkinlik listesi', 'ktuehk-core' ),
					'items_list_navigation' => __( 'Etkinlik listesi gezinme', 'ktuehk-core' ),
					'item_published'        => __( 'Etkinlik yayımlandı.', 'ktuehk-core' ),
					'item_updated'          => __( 'Etkinlik güncellendi.', 'ktuehk-core' ),
					'item_scheduled'        => __( 'Etkinlik zamanlandı.', 'ktuehk-core' ),
					'item_reverted_to_draft' => __( 'Etkinlik taslağa döndürüldü.', 'ktuehk-core' ),
				),
				'description'     => __( 'KTÜ EHK workshop, seminer, teknik eğitim ve diğer etkinlikler.', 'ktuehk-core' ),
				'public'          => true,
				'show_in_rest'    => true,
				'menu_position'   => 7,
				'menu_icon'       => 'dashicons-calendar-alt',
				'has_archive'     => ktuehk_core_settings( 'event_slug' ),
				'rewrite'         => array(
					'slug'       => ktuehk_core_settings( 'event_slug' ),
					'with_front' => false,
				),
				'supports'        => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions', 'custom-fields' ),
				'capability_type' => 'post',
				'map_meta_cap'    => true,
			)
		);
	} else {
		add_post_type_support( $event, array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ) );
	}

	register_taxonomy(
		KTUEHK_TAX_PROGRAM,
		array( $project ),
		array(
			'labels'            => array(
				'name'          => __( 'Yarışma / Program', 'ktuehk-core' ),
				'singular_name' => __( 'Yarışma / Program', 'ktuehk-core' ),
				'menu_name'     => __( 'Yarışma / Program', 'ktuehk-core' ),
				'all_items'     => __( 'Tüm programlar', 'ktuehk-core' ),
				'edit_item'     => __( 'Programı düzenle', 'ktuehk-core' ),
				'add_new_item'  => __( 'Yeni program ekle', 'ktuehk-core' ),
				'search_items'  => __( 'Program ara', 'ktuehk-core' ),
				'not_found'     => __( 'Program bulunamadı.', 'ktuehk-core' ),
				'back_to_items' => __( '← Programlara dön', 'ktuehk-core' ),
			),
			'description'       => __( 'TÜBİTAK 2209-A, TEKNOFEST gibi yarışma ve destek programları.', 'ktuehk-core' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'query_var'         => 'program',
			'rewrite'           => array(
				'slug'       => 'proje-programi',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		KTUEHK_TAX_AREA,
		array( $project ),
		array(
			'labels'            => array(
				'name'          => __( 'Proje Alanları', 'ktuehk-core' ),
				'singular_name' => __( 'Proje Alanı', 'ktuehk-core' ),
				'menu_name'     => __( 'Alanlar', 'ktuehk-core' ),
				'all_items'     => __( 'Tüm alanlar', 'ktuehk-core' ),
				'edit_item'     => __( 'Alanı düzenle', 'ktuehk-core' ),
				'add_new_item'  => __( 'Yeni alan ekle', 'ktuehk-core' ),
				'search_items'  => __( 'Alan ara', 'ktuehk-core' ),
				'not_found'     => __( 'Alan bulunamadı.', 'ktuehk-core' ),
				'back_to_items' => __( '← Alanlara dön', 'ktuehk-core' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'query_var'         => 'alan',
			'rewrite'           => array(
				'slug'       => 'proje-alani',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		KTUEHK_TAX_EVENT,
		array( $event ),
		array(
			'labels'            => array(
				'name'          => __( 'Etkinlik Türleri', 'ktuehk-core' ),
				'singular_name' => __( 'Etkinlik Türü', 'ktuehk-core' ),
				'menu_name'     => __( 'Etkinlik Türleri', 'ktuehk-core' ),
				'all_items'     => __( 'Tüm türler', 'ktuehk-core' ),
				'edit_item'     => __( 'Türü düzenle', 'ktuehk-core' ),
				'add_new_item'  => __( 'Yeni tür ekle', 'ktuehk-core' ),
				'search_items'  => __( 'Tür ara', 'ktuehk-core' ),
				'not_found'     => __( 'Tür bulunamadı.', 'ktuehk-core' ),
				'back_to_items' => __( '← Türlere dön', 'ktuehk-core' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'query_var'         => 'tur',
			'rewrite'           => array(
				'slug'       => 'etkinlik-turu',
				'with_front' => false,
			),
		)
	);
}
// Priority 20: lets an existing registration (default priority 10) win.
add_action( 'init', 'ktuehk_core_register_content_types', 20 );

/**
 * Default terms. Each term is created only once; if an admin deletes one
 * later it is not re-created.
 *
 * @return array<string,array<string,string>> taxonomy => slug => name
 */
function ktuehk_core_default_terms() {
	return array(
		KTUEHK_TAX_PROGRAM => array(
			'tubitak-2209-a' => 'TÜBİTAK 2209-A',
			'tubitak-2209-b' => 'TÜBİTAK 2209-B',
			'teknofest'      => 'TEKNOFEST',
			'kulup-ici'      => 'Kulüp İçi Proje',
		),
		KTUEHK_TAX_AREA    => array(
			'elektronik'       => 'Elektronik',
			'haberlesme'       => 'Haberleşme',
			'rf-ve-anten'      => 'RF ve Anten',
			'gomulu-sistemler' => 'Gömülü Sistemler',
			'pcb-tasarimi'     => 'PCB Tasarımı',
			'ag-teknolojileri' => 'Ağ Teknolojileri',
			'siber-guvenlik'   => 'Siber Güvenlik',
			'sinyal-isleme'    => 'Sinyal İşleme',
			'iot'              => 'IoT',
			'yapay-zeka'       => 'Yapay Zekâ',
			'yazilim'          => 'Yazılım',
		),
		KTUEHK_TAX_EVENT   => array(
			'workshop'             => 'Workshop',
			'seminer'              => 'Seminer',
			'teknik-egitim'        => 'Teknik Eğitim',
			'yarisma'              => 'Yarışma',
			'tanisma-toplantisi'   => 'Tanışma Toplantısı',
			'teknik-gezi'          => 'Teknik Gezi',
			'soylesi'              => 'Söyleşi',
			'lehimleme'            => 'Lehimleme Etkinliği',
			'pcb-egitimi'          => 'PCB Eğitimi',
			'ag-siber-guvenlik'    => 'Ağ / Siber Güvenlik Eğitimi',
		),
	);
}

/**
 * Create default terms that were never created before.
 */
function ktuehk_core_seed_terms() {
	$seeded = (array) get_option( 'ktuehk_core_seeded_terms', array() );
	foreach ( ktuehk_core_default_terms() as $taxonomy => $terms ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}
		foreach ( $terms as $slug => $name ) {
			$key = $taxonomy . ':' . $slug;
			if ( in_array( $key, $seeded, true ) ) {
				continue;
			}
			if ( ! term_exists( $slug, $taxonomy ) ) {
				wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
			}
			$seeded[] = $key;
		}
	}
	update_option( 'ktuehk_core_seeded_terms', $seeded, false );
}
