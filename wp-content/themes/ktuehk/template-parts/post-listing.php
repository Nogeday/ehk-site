<?php
/**
 * Article grid for the main query (posts page, categories, tags, authors).
 *
 * Args: feature_first (bool) — show the newest article as a wide card on
 * the first page.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_feature = ! empty( $args['feature_first'] ) && ! is_paged();

if ( have_posts() ) :
	?>
	<div class="grid grid--3 post-grid">
		<?php
		$ktuehk_index = 0;
		while ( have_posts() ) :
			the_post();
			++$ktuehk_index;
			get_template_part(
				'template-parts/card',
				'post',
				array(
					'heading'  => 'h2',
					'featured' => $ktuehk_feature && 1 === $ktuehk_index && ktuehk_card_image()['id'],
					'priority' => 1 === $ktuehk_index,
				)
			);
		endwhile;
		?>
	</div>
	<?php
	ktuehk_pagination();
else :
	get_template_part(
		'template-parts/empty-state',
		null,
		array(
			'icon'       => 'file-text',
			'title'      => __( 'Bu bölümde henüz yazı yok', 'ktuehk' ),
			'text'       => __( 'Diğer kategorilere göz atabilir veya arama yapabilirsiniz.', 'ktuehk' ),
			'link'       => is_home() ? '' : ktuehk_posts_url(),
			'link_text'  => __( 'Tüm yazılar', 'ktuehk' ),
			'admin_link' => admin_url( 'post-new.php' ),
			'admin_text' => __( 'Yeni yazı ekle', 'ktuehk' ),
		)
	);
endif;
