<?php
/**
 * Customizer: texts, header style and social profiles.
 * Görünüm → Özelleştir → "KTÜ EHK Tema Ayarları".
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default values for theme options.
 *
 * @return array<string,string>
 */
function ktuehk_mod_defaults() {
	return array(
		'header_style'   => 'blue',
		'hero_eyebrow'   => __( 'Karadeniz Teknik Üniversitesi · Teknoloji Fakültesi', 'ktuehk' ),
		'hero_title'     => __( 'KTÜ Elektronik ve Haberleşme Kulübü', 'ktuehk' ),
		'hero_text'      => __( 'Elektronik, haberleşme ve teknoloji alanlarında üreten, araştıran ve geliştiren KTÜ öğrencilerinin topluluğu.', 'ktuehk' ),
		'about_title'    => __( 'Mühendisliği sınıfın dışına taşıyan bir topluluk', 'ktuehk' ),
		'about_text'     => __( 'KTÜ Elektronik ve Haberleşme Kulübü; elektronik ve haberleşme mühendisliği öğrencilerinin proje geliştirdiği, birlikte öğrendiği ve ürettiklerini paylaştığı bir öğrenci topluluğudur. Devre tasarımından RF sistemlerine, gömülü yazılımdan ağ teknolojilerine kadar geniş bir alanda uygulamalı çalışmalar yürütüyoruz.', 'ktuehk' ),
		'intro_posts'    => __( 'Kulüp üyelerinin elektronik, haberleşme, gömülü sistemler, ağ teknolojileri ve daha birçok alanda hazırladığı teknik ve bilimsel yazılar.', 'ktuehk' ),
		'intro_projects' => __( 'TÜBİTAK 2209, TEKNOFEST ve kulüp içi çalışmalarla geliştirilen öğrenci projeleri: problem, yaklaşım, ekip ve sonuçlarıyla.', 'ktuehk' ),
		'intro_events'   => __( 'Workshop, seminer, teknik eğitim ve gezilerimiz. Yaklaşan etkinliklere katılın, geçmiş etkinliklerden notlara ve fotoğraflara göz atın.', 'ktuehk' ),
	);
}

/**
 * Theme option with default.
 *
 * @param string $key Option key without prefix.
 * @return string
 */
function ktuehk_mod( $key ) {
	$defaults = ktuehk_mod_defaults();
	$value    = get_theme_mod( 'ktuehk_' . $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
	return is_string( $value ) && '' !== trim( $value ) ? $value : ( isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
}

/**
 * Supported social networks.
 *
 * @return array<string,string> key => label
 */
function ktuehk_social_networks() {
	return array(
		'instagram' => 'Instagram',
		'linkedin'  => 'LinkedIn',
		'github'    => 'GitHub',
		'youtube'   => 'YouTube',
		'x'         => 'X (Twitter)',
		'discord'   => 'Discord',
		'telegram'  => 'Telegram',
		'medium'    => 'Medium',
	);
}

/**
 * Configured social links.
 *
 * @return array<int,array{key:string,label:string,url:string}>
 */
function ktuehk_social_links() {
	$links = array();
	foreach ( ktuehk_social_networks() as $key => $label ) {
		$url = get_theme_mod( 'ktuehk_social_' . $key );
		if ( $url ) {
			$links[] = array(
				'key'   => $key,
				'label' => $label,
				'url'   => $url,
			);
		}
	}
	return $links;
}

/**
 * Register Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function ktuehk_customize_register( $wp_customize ) {
	$defaults = ktuehk_mod_defaults();

	$wp_customize->add_panel(
		'ktuehk',
		array(
			'title'    => __( 'KTÜ EHK Tema Ayarları', 'ktuehk' ),
			'priority' => 30,
		)
	);

	// Header.
	$wp_customize->add_section(
		'ktuehk_header',
		array(
			'title' => __( 'Üst menü', 'ktuehk' ),
			'panel' => 'ktuehk',
		)
	);
	$wp_customize->add_setting(
		'ktuehk_header_style',
		array(
			'default'           => 'blue',
			'sanitize_callback' => static function ( $value ) {
				return 'light' === $value ? 'light' : 'blue';
			},
		)
	);
	$wp_customize->add_control(
		'ktuehk_header_style',
		array(
			'label'       => __( 'Menü çubuğu rengi', 'ktuehk' ),
			'description' => __( 'Logonuz koyu zeminde iyi görünmüyorsa "Beyaz" seçin.', 'ktuehk' ),
			'section'     => 'ktuehk_header',
			'type'        => 'radio',
			'choices'     => array(
				'blue'  => __( 'KTÜ mavisi', 'ktuehk' ),
				'light' => __( 'Beyaz', 'ktuehk' ),
			),
		)
	);

	$wp_customize->add_setting(
		'ktuehk_logo_dark',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'ktuehk_logo_dark',
			array(
				'label'       => __( 'Koyu zemin logosu (isteğe bağlı)', 'ktuehk' ),
				'description' => __( 'Mavi menü çubuğunda ve alt bilgide kullanılacak beyaz/açık renkli logo. Yüklenmezse normal logo beyaz bir kutucuk içinde gösterilir.', 'ktuehk' ),
				'section'     => 'ktuehk_header',
				'mime_type'   => 'image',
			)
		)
	);

	// Texts.
	$wp_customize->add_section(
		'ktuehk_texts',
		array(
			'title'       => __( 'Ana sayfa ve bölüm metinleri', 'ktuehk' ),
			'panel'       => 'ktuehk',
			'description' => __( 'Boş bırakılan alanlarda varsayılan metin kullanılır.', 'ktuehk' ),
		)
	);
	$texts = array(
		'hero_eyebrow'   => array( __( 'Ana sayfa — üst etiket', 'ktuehk' ), 'text' ),
		'hero_title'     => array( __( 'Ana sayfa — başlık', 'ktuehk' ), 'text' ),
		'hero_text'      => array( __( 'Ana sayfa — açıklama (meta açıklama olarak da kullanılır)', 'ktuehk' ), 'textarea' ),
		'about_title'    => array( __( '"Biz kimiz?" başlığı', 'ktuehk' ), 'text' ),
		'about_text'     => array( __( '"Biz kimiz?" metni', 'ktuehk' ), 'textarea' ),
		'intro_posts'    => array( __( 'Yazılar sayfası açıklaması', 'ktuehk' ), 'textarea' ),
		'intro_projects' => array( __( 'Projeler sayfası açıklaması', 'ktuehk' ), 'textarea' ),
		'intro_events'   => array( __( 'Etkinlikler sayfası açıklaması', 'ktuehk' ), 'textarea' ),
	);
	foreach ( $texts as $key => $config ) {
		$wp_customize->add_setting(
			'ktuehk_' . $key,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => 'textarea' === $config[1] ? 'sanitize_textarea_field' : 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'ktuehk_' . $key,
			array(
				'label'   => $config[0],
				'section' => 'ktuehk_texts',
				'type'    => $config[1],
			)
		);
	}

	// Social.
	$wp_customize->add_section(
		'ktuehk_social',
		array(
			'title'       => __( 'Sosyal medya', 'ktuehk' ),
			'panel'       => 'ktuehk',
			'description' => __( 'Yalnızca doldurulan hesaplar alt bilgide gösterilir.', 'ktuehk' ),
		)
	);
	foreach ( ktuehk_social_networks() as $key => $label ) {
		$wp_customize->add_setting(
			'ktuehk_social_' . $key,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			'ktuehk_social_' . $key,
			array(
				'label'   => $label,
				'section' => 'ktuehk_social',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'ktuehk_customize_register' );
