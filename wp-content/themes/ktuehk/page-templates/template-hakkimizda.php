<?php
/**
 * Template Name: Hakkımızda
 *
 * About page. The text is regular editable page content; dynamic sections
 * (focus areas, offerings, programs) are embedded with shortcodes:
 * [ehk_calisma_alanlari], [ehk_imkanlar], [ehk_programlar].
 * If the page is still empty, a default structure is shown.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$ktuehk_raw = trim( get_the_content() );
	if ( '' === $ktuehk_raw ) {
		$ktuehk_html = apply_filters( 'the_content', ktuehk_about_default_content() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- core hook.
	} else {
		$ktuehk_prepared = ktuehk_prepare_content( false );
		$ktuehk_html     = $ktuehk_prepared['html'];
	}

	get_template_part(
		'template-parts/page-header',
		null,
		array(
			'eyebrow' => __( 'HAKKIMIZDA', 'ktuehk' ),
			'title'   => get_the_title(),
			'desc'    => has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : ktuehk_mod( 'hero_text' ),
			'class'   => 'page-hero--about',
		)
	);
	?>
	<div class="section section--tight">
		<div class="container">
			<div class="prose entry-content about-content">
				<?php echo $ktuehk_html; // phpcs:ignore WordPress.Security.EscapeOutput -- filtered post content. ?>
			</div>
		</div>
	</div>

	<?php
	get_template_part( 'template-parts/cta-band', null, array( 'context' => 'about' ) );
endwhile;

get_footer();
