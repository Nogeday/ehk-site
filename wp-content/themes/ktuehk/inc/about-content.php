<?php
/**
 * Default "Hakkımızda" content as editable blocks, plus the shortcodes that
 * embed dynamic sections (focus areas, offerings, programs) into any page.
 *
 * The same markup is used to pre-fill a new "Hakkımızda" page from the setup
 * assistant and as a fallback when the page has no content yet.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default about page content (block markup).
 *
 * @return string
 */
function ktuehk_about_default_content() {
	$card = static function ( $title, $text ) {
		return '<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"is-style-ehk-card"} -->
<div class="wp-block-group is-style-ehk-card"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . esc_html( $title ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html( $text ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->';
	};

	$h2 = static function ( $text ) {
		return '<!-- wp:heading -->
<h2 class="wp-block-heading">' . esc_html( $text ) . '</h2>
<!-- /wp:heading -->';
	};

	$p = static function ( $text ) {
		return '<!-- wp:paragraph -->
<p>' . esc_html( $text ) . '</p>
<!-- /wp:paragraph -->';
	};

	$parts = array(
		$h2( __( 'KTÜ Elektronik ve Haberleşme Kulübü nedir?', 'ktuehk' ) ),
		$p( __( 'KTÜ Elektronik ve Haberleşme Kulübü (KTÜ EHK), Karadeniz Teknik Üniversitesi Teknoloji Fakültesi Elektronik ve Haberleşme Mühendisliği öğrencilerinin proje geliştirdiği, birlikte öğrendiği ve ürettiklerini paylaştığı bir öğrenci topluluğudur.', 'ktuehk' ) ),
		$p( __( 'Derslerde öğrendiğimiz teoriyi devre kartlarına, antenlere, gömülü yazılımlara ve ağ altyapılarına dönüştürmeye çalışıyoruz. Farklı sınıflardan öğrencilerin deneyimlerini paylaştığı, sorularını rahatça sorabildiği ve birlikte üretebildiği bir ortam oluşturmayı önemsiyoruz.', 'ktuehk' ) ),
		'<!-- wp:columns {"className":"about-cards"} -->
<div class="wp-block-columns about-cards">' . $card( __( 'Amacımız', 'ktuehk' ), __( 'Öğrencilerin teorik bilgilerini uygulamalı projelerle pekiştirmesini, teknik yetkinliklerini geliştirmesini ve mühendislik kariyerlerine güçlü bir başlangıç yapmasını desteklemek.', 'ktuehk' ) )
			. "\n\n" . $card( __( 'Vizyonumuz', 'ktuehk' ), __( 'Elektronik ve haberleşme alanında ürettiği projeler, teknik içerikleri ve yetiştirdiği mühendislerle tanınan; üniversite içinde ve dışında örnek gösterilen bir öğrenci topluluğu olmak.', 'ktuehk' ) ) . '</div>
<!-- /wp:columns -->',
		$h2( __( 'Çalışma alanlarımız', 'ktuehk' ) ),
		$p( __( 'Çalışmalarımız elektronik ve haberleşme mühendisliğinin temel alanlarından güncel teknolojilere uzanıyor.', 'ktuehk' ) ),
		'<!-- wp:shortcode -->
[ehk_calisma_alanlari]
<!-- /wp:shortcode -->',
		$h2( __( 'Öğrencilere sunduğumuz imkânlar', 'ktuehk' ) ),
		'<!-- wp:shortcode -->
[ehk_imkanlar]
<!-- /wp:shortcode -->',
		$h2( __( 'Nasıl çalışıyoruz?', 'ktuehk' ) ),
		'<!-- wp:columns {"className":"about-cards"} -->
<div class="wp-block-columns about-cards">' . $card( __( 'Proje kültürü', 'ktuehk' ), __( 'Her çalışma bir problemle başlar. Küçük ekipler hâlinde fikirleri araştırıyor, prototip geliştiriyor, test ediyor ve sonuçları belgeliyoruz. Başarısız denemeler de öğrenme sürecinin bir parçası.', 'ktuehk' ) )
			. "\n\n" . $card( __( 'Teknik gelişim', 'ktuehk' ), __( 'Lehimlemeden PCB tasarımına, mikrodenetleyici programlamadan ağ yapılandırmasına kadar temel becerileri atölyelerde birlikte öğreniyor; deneyimli üyeler yeni katılanlara rehberlik ediyor.', 'ktuehk' ) )
			. "\n\n" . $card( __( 'Takım çalışması', 'ktuehk' ), __( 'Donanımın, yazılımın ve dokümantasyonun aynı masada buluştuğu projelerde iş bölümü yapmayı, birlikte karar almayı ve sorumluluk paylaşmayı deneyimliyoruz.', 'ktuehk' ) ) . '</div>
<!-- /wp:columns -->',
		$h2( __( 'Yarışmalar, TÜBİTAK ve TEKNOFEST', 'ktuehk' ) ),
		$p( __( 'Üyelerimizi TÜBİTAK 2209-A Üniversite Öğrencileri Araştırma Projeleri, TÜBİTAK 2209-B Sanayiye Yönelik Araştırma Projeleri ve TEKNOFEST teknoloji yarışmalarına katılmaya teşvik ediyoruz. Takım kurma, proje önerisi hazırlama ve rapor yazma süreçlerinde deneyimlerimizi paylaşıyoruz.', 'ktuehk' ) ),
		'<!-- wp:shortcode -->
[ehk_programlar]
<!-- /wp:shortcode -->',
	);

	return implode( "\n\n", $parts );
}

/**
 * [ehk_calisma_alanlari] — focus areas grid.
 *
 * @return string
 */
function ktuehk_shortcode_areas() {
	ob_start();
	get_template_part( 'template-parts/section', 'areas' );
	return '<div class="ehk-sc">' . ob_get_clean() . '</div>';
}
add_shortcode( 'ehk_calisma_alanlari', 'ktuehk_shortcode_areas' );

/**
 * [ehk_imkanlar] — what the club offers.
 *
 * @return string
 */
function ktuehk_shortcode_offerings() {
	ob_start();
	get_template_part( 'template-parts/section', 'offerings' );
	return '<div class="ehk-sc">' . ob_get_clean() . '</div>';
}
add_shortcode( 'ehk_imkanlar', 'ktuehk_shortcode_offerings' );

/**
 * [ehk_programlar] — competitions / programs with project counts.
 *
 * @return string
 */
function ktuehk_shortcode_programs() {
	ob_start();
	get_template_part( 'template-parts/section', 'programs' );
	return '<div class="ehk-sc">' . ob_get_clean() . '</div>';
}
add_shortcode( 'ehk_programlar', 'ktuehk_shortcode_programs' );
