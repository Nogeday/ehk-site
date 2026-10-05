<?php
/**
 * Posts page ("Yazılar").
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

$ktuehk_page_id = (int) get_option( 'page_for_posts' );
$ktuehk_title   = $ktuehk_page_id ? get_the_title( $ktuehk_page_id ) : __( 'Yazılar', 'ktuehk' );
$ktuehk_desc    = $ktuehk_page_id && has_excerpt( $ktuehk_page_id ) ? get_the_excerpt( $ktuehk_page_id ) : ktuehk_mod( 'intro_posts' );

get_template_part(
	'template-parts/page-header',
	null,
	array(
		'eyebrow' => __( 'Teknik içerik', 'ktuehk' ),
		'title'   => $ktuehk_title,
		'desc'    => $ktuehk_desc,
		'after'   => static function () {
			get_search_form(
				array(
					'post_type'   => 'post',
					'placeholder' => __( 'Yazılarda ara: anten, STM32, OFDM…', 'ktuehk' ),
					'size'        => 'large',
					'aria_label'  => __( 'Yazılarda ara', 'ktuehk' ),
				)
			);
		},
	)
);
?>
<div class="section section--tight">
	<div class="container">
		<?php get_template_part( 'template-parts/category-chips' ); ?>
		<?php get_template_part( 'template-parts/post-listing', null, array( 'feature_first' => true ) ); ?>
	</div>
</div>
<?php
get_footer();
