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
			'text'  => __( 'Analog ve dijital devre tasarımı, ölçüm ve prototipleme.', 'ktuehk' ),
			'icon'  => 'zap',
			'slugs' => array( 'elektronik' ),
		),
		array(
			'title' => __( 'Haberleşme', 'ktuehk' ),
			'text'  => __( 'Modülasyon, kablosuz haberleşme ve haberleşme sistemleri.', 'ktuehk' ),
			'icon'  => 'radio-tower',
			'slugs' => array( 'haberlesme', 'haberlesme-sistemleri' ),
		),
		array(
			'title' => __( 'RF & Anten', 'ktuehk' ),
			'text'  => __( 'Anten tasarımı, RF devreleri ve elektromanyetik simülasyon.', 'ktuehk' ),
			'icon'  => 'antenna',
			'slugs' => array( 'rf-ve-anten', 'rf-anten', 'rf', 'anten' ),
		),
		array(
			'title' => __( 'Gömülü Sistemler', 'ktuehk' ),
			'text'  => __( 'Mikrodenetleyiciler, gerçek zamanlı yazılım ve sensör sistemleri.', 'ktuehk' ),
			'icon'  => 'cpu',
			'slugs' => array( 'gomulu-sistemler', 'mikrodenetleyiciler' ),
		),
		array(
			'title' => __( 'PCB Tasarımı', 'ktuehk' ),
			'text'  => __( 'Şematikten üretime baskı devre kartı tasarımı.', 'ktuehk' ),
			'icon'  => 'circuit-board',
			'slugs' => array( 'pcb-tasarimi', 'pcb' ),
		),
		array(
			'title' => __( 'Ağ & Siber Güvenlik', 'ktuehk' ),
			'text'  => __( 'Ağ altyapıları, Linux, protokoller ve güvenlik.', 'ktuehk' ),
			'icon'  => 'shield-check',
			'slugs' => array( 'ag-ve-siber-guvenlik', 'siber-guvenlik', 'ag-teknolojileri' ),
		),
		array(
			'title' => __( 'IoT', 'ktuehk' ),
			'text'  => __( 'Bağlı cihazlar, sensör ağları ve bulut entegrasyonu.', 'ktuehk' ),
			'icon'  => 'wifi',
			'slugs' => array( 'iot', 'nesnelerin-interneti' ),
		),
		array(
			'title' => __( 'Sinyal İşleme', 'ktuehk' ),
			'text'  => __( 'Sayısal sinyal işleme, filtre tasarımı ve analiz.', 'ktuehk' ),
			'icon'  => 'audio-waveform',
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

/**
 * Short pillars used in the home "Biz kimiz?" section.
 *
 * @return array
 */
function ktuehk_pillars() {
	return (array) apply_filters(
		'ktuehk_pillars',
		array(
			array(
				'title' => __( 'Üretiyoruz', 'ktuehk' ),
				'text'  => __( 'Devreler, prototipler ve yazılımlar geliştiriyor; fikirleri çalışan sistemlere dönüştürüyoruz.', 'ktuehk' ),
				'icon'  => 'circuit-board',
			),
			array(
				'title' => __( 'Araştırıyoruz', 'ktuehk' ),
				'text'  => __( 'Haberleşme, RF, gömülü sistemler ve ağ teknolojilerinde yeni yaklaşımları inceliyoruz.', 'ktuehk' ),
				'icon'  => 'flask-conical',
			),
			array(
				'title' => __( 'Paylaşıyoruz', 'ktuehk' ),
				'text'  => __( 'Öğrendiklerimizi teknik yazılar, eğitimler ve etkinliklerle topluluğa aktarıyoruz.', 'ktuehk' ),
				'icon'  => 'book-open',
			),
		)
	);
}
