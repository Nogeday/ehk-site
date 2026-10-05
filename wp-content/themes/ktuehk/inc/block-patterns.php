<?php
/**
 * Block styles and patterns that help editors write consistent technical
 * content (notes, warnings, spec tables).
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register block styles and patterns.
 */
function ktuehk_register_block_extras() {
	if ( function_exists( 'register_block_style' ) ) {
		register_block_style(
			'core/group',
			array(
				'name'  => 'ehk-note',
				'label' => __( 'Not kutusu', 'ktuehk' ),
			)
		);
		register_block_style(
			'core/group',
			array(
				'name'  => 'ehk-warning',
				'label' => __( 'Uyarı kutusu', 'ktuehk' ),
			)
		);
		register_block_style(
			'core/group',
			array(
				'name'  => 'ehk-card',
				'label' => __( 'Kart', 'ktuehk' ),
			)
		);
		register_block_style(
			'core/table',
			array(
				'name'  => 'ehk-spec',
				'label' => __( 'Teknik tablo', 'ktuehk' ),
			)
		);
	}

	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	register_block_pattern_category( 'ktuehk', array( 'label' => __( 'KTÜ EHK', 'ktuehk' ) ) );

	register_block_pattern(
		'ktuehk/note',
		array(
			'title'       => __( 'Not kutusu', 'ktuehk' ),
			'description' => __( 'Teknik yazılarda önemli bir bilgiyi vurgulamak için.', 'ktuehk' ),
			'categories'  => array( 'ktuehk' ),
			'content'     => '<!-- wp:group {"className":"is-style-ehk-note"} -->
<div class="wp-block-group is-style-ehk-note"><!-- wp:paragraph -->
<p><strong>Not:</strong> Buraya önemli bilgiyi yazın.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'ktuehk/warning',
		array(
			'title'       => __( 'Uyarı kutusu', 'ktuehk' ),
			'description' => __( 'Güvenlik veya dikkat edilmesi gereken noktalar için.', 'ktuehk' ),
			'categories'  => array( 'ktuehk' ),
			'content'     => '<!-- wp:group {"className":"is-style-ehk-warning"} -->
<div class="wp-block-group is-style-ehk-warning"><!-- wp:paragraph -->
<p><strong>Dikkat:</strong> Yüksek gerilimle çalışırken güç kaynağını kapatın.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'ktuehk/spec-table',
		array(
			'title'       => __( 'Teknik özellik tablosu', 'ktuehk' ),
			'description' => __( 'Bileşen veya sistem parametreleri için iki sütunlu tablo.', 'ktuehk' ),
			'categories'  => array( 'ktuehk' ),
			'content'     => '<!-- wp:table {"className":"is-style-ehk-spec"} -->
<figure class="wp-block-table is-style-ehk-spec"><table><thead><tr><th>Parametre</th><th>Değer</th></tr></thead><tbody><tr><td>Çalışma frekansı</td><td>2,4 GHz</td></tr><tr><td>Besleme gerilimi</td><td>3,3 V</td></tr><tr><td>Kazanç</td><td>5 dBi</td></tr></tbody></table></figure>
<!-- /wp:table -->',
		)
	);
}
add_action( 'init', 'ktuehk_register_block_extras' );
