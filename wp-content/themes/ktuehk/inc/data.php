<?php
/**
 * Static content blocks used on the home and about pages. Each list is
 * filterable so it can be changed from a child theme or a small plugin
 * without editing templates.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

/**
 * Club focus areas ("Çalışma alanlarımız").
 *
 * "slugs" are matched against post categories first, then project areas,
 * to link each area to related content when it exists.
 *
 * @return array
 */
function ktuehk_focus_areas() {
	$areas = array(
		array(
			'title' => __( 'Elektronik', 'ktuehk' ),
			'text'  => __( 'Devre tasarımı, analog/dijital sistemler ve ölçüm teknikleri.', 'ktuehk' ),
			'icon'  => 'cpu',
			'slugs' => array( 'elektronik' ),
		),
		array(
			'title' => __( 'Haberleşme', 'ktuehk' ),
			'text'  => __( 'Kablosuz ve kablolu haberleşme sistemleri, modülasyon, kodlama.', 'ktuehk' ),
			'icon'  => 'radio-tower',
			'slugs' => array( 'haberlesme', 'haberlesme-sistemleri' ),
		),
		array(
			'title' => __( 'RF & Anten', 'ktuehk' ),
			'text'  => __( 'RF devreleri, anten tasarımı ve radyo frekans sistemleri.', 'ktuehk' ),
			'icon'  => 'radio',
			'slugs' => array( 'rf-ve-anten', 'rf-anten', 'rf', 'anten' ),
		),
		array(
			'title' => __( 'Gömülü Sistemler', 'ktuehk' ),
			'text'  => __( 'Mikrodenetleyiciler, RTOS, gömülü yazılım ve sensörler.', 'ktuehk' ),
			'icon'  => 'square-code',
			'slugs' => array( 'gomulu-sistemler', 'mikrodenetleyiciler' ),
		),
		array(
			'title' => __( 'PCB', 'ktuehk' ),
			'text'  => __( 'PCB tasarımı, üretim, montaj ve test süreçleri.', 'ktuehk' ),
			'icon'  => 'circuit-board',
			'slugs' => array( 'pcb-tasarimi', 'pcb' ),
		),
		array(
			'title' => __( 'Ağ & Siber Güvenlik', 'ktuehk' ),
			'text'  => __( 'Ağ teknolojileri, Linux altyapıları ve güvenlik.', 'ktuehk' ),
			'icon'  => 'shield',
			'slugs' => array( 'ag-ve-siber-guvenlik', 'siber-guvenlik', 'ag-teknolojileri' ),
		),
		array(
			'title' => __( 'IoT', 'ktuehk' ),
			'text'  => __( 'Nesnelerin interneti, sensör ağları ve bulut entegrasyonu.', 'ktuehk' ),
			'icon'  => 'cloud',
			'slugs' => array( 'iot', 'nesnelerin-interneti' ),
		),
		array(
			'title' => __( 'Sinyal İşleme', 'ktuehk' ),
			'text'  => __( 'Dijital sinyal işleme, filtreleme ve veri analizi.', 'ktuehk' ),
			'icon'  => 'activity',
			'slugs' => array( 'sinyal-isleme' ),
		),
	);
	return (array) apply_filters( 'ktuehk_focus_areas', $areas );
}

/**
 * Link for a focus area: a matching post category, else a matching project
 * area archive, else none.
 *
 * @param array $area Area definition.
 * @return string
 */
function ktuehk_focus_area_link( $area ) {
	foreach ( (array) $area['slugs'] as $slug ) {
		$term = get_term_by( 'slug', $slug, 'category' );
		if ( $term && $term->count > 0 ) {
			return (string) get_term_link( $term );
		}
	}
	if ( taxonomy_exists( 'ehk_alan' ) ) {
		foreach ( (array) $area['slugs'] as $slug ) {
			$term = get_term_by( 'slug', $slug, 'ehk_alan' );
			if ( $term && $term->count > 0 ) {
				$link = get_term_link( $term );
				return is_wp_error( $link ) ? '' : $link;
			}
		}
	}
	return '';
}

/**
 * What the club offers students.
 *
 * @return array
 */
function ktuehk_offerings() {
	return (array) apply_filters(
		'ktuehk_offerings',
		array(
			array(
				'title' => __( 'Gerçek proje deneyimi', 'ktuehk' ),
				'text'  => __( 'Fikirden prototipe uzanan mühendislik projelerinde ekip olarak çalışma fırsatı.', 'ktuehk' ),
				'icon'  => 'rocket',
			),
			array(
				'title' => __( 'Uygulamalı eğitimler', 'ktuehk' ),
				'text'  => __( 'Lehimleme, PCB tasarımı, gömülü programlama ve ağ teknolojileri atölyeleri.', 'ktuehk' ),
				'icon'  => 'wrench',
			),
			array(
				'title' => __( 'Yarışma ve program desteği', 'ktuehk' ),
				'text'  => __( 'TÜBİTAK 2209 ve TEKNOFEST süreçlerinde deneyim paylaşımı ve takım kurma.', 'ktuehk' ),
				'icon'  => 'trophy',
			),
			array(
				'title' => __( 'Teknik yazarlık', 'ktuehk' ),
				'text'  => __( 'Öğrendiklerini teknik yazılarla paylaşma ve kendi portfolyonu oluşturma.', 'ktuehk' ),
				'icon'  => 'pen-line',
			),
			array(
				'title' => __( 'Akran öğrenimi', 'ktuehk' ),
				'text'  => __( 'Farklı sınıflardan öğrencilerle bilgi, kaynak ve deneyim paylaşımı.', 'ktuehk' ),
				'icon'  => 'users',
			),
			array(
				'title' => __( 'Sektör ve akademiyle bağ', 'ktuehk' ),
				'text'  => __( 'Söyleşi, seminer ve teknik gezilerle mühendislik dünyasını yakından tanıma.', 'ktuehk' ),
				'icon'  => 'handshake',
			),
		)
	);
}
