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
	$ktuehk_projects = ktuehk_type_available( 'project' ) ? get_post_type_archive_link( ktuehk_project_type() ) : '';

	get_template_part(
		'template-parts/page-header',
		null,
		array(
			'eyebrow' => __( 'Hakkımızda', 'ktuehk' ),
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

	<section class="section section--alt cta-band" aria-labelledby="about-cta">
		<div class="container cta-band__inner">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'Keşfetmeye başlayın', 'ktuehk' ); ?></p>
				<h2 class="section-title" id="about-cta"><?php esc_html_e( 'Ürettiklerimize yakından bakın', 'ktuehk' ); ?></h2>
			</div>
			<div class="cta-band__actions">
				<?php if ( $ktuehk_projects ) : ?>
					<a class="btn btn--primary" href="<?php echo esc_url( $ktuehk_projects ); ?>"><?php esc_html_e( 'Projelerimizi Keşfet', 'ktuehk' ); ?><?php echo ktuehk_icon( 'arrow-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
				<?php endif; ?>
				<a class="btn btn--outline" href="<?php echo esc_url( ktuehk_posts_url() ); ?>"><?php esc_html_e( 'Teknik Yazıları Oku', 'ktuehk' ); ?></a>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
