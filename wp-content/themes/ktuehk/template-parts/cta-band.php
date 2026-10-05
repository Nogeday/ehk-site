<?php
/**
 * Call-to-action band (dark blue). Texts come from the Customizer.
 *
 * On the about page the button points to the projects instead of linking
 * to the page itself.
 *
 * Args: context (home|about).
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_context  = isset( $args['context'] ) ? $args['context'] : 'home';
$ktuehk_about    = ktuehk_about_page();
$ktuehk_projects = ktuehk_type_available( 'project' ) ? (string) get_post_type_archive_link( ktuehk_project_type() ) : '';

if ( 'about' !== $ktuehk_context && $ktuehk_about ) {
	$ktuehk_url   = (string) get_permalink( $ktuehk_about );
	$ktuehk_label = __( 'Hakkımızda', 'ktuehk' );
} elseif ( $ktuehk_projects ) {
	$ktuehk_url   = $ktuehk_projects;
	$ktuehk_label = __( 'Projeleri Keşfet', 'ktuehk' );
} else {
	$ktuehk_url   = ktuehk_posts_url();
	$ktuehk_label = __( 'Teknik Yazıları Oku', 'ktuehk' );
}
?>
<section class="cta-section" aria-labelledby="cta-title">
	<div class="container">
		<div class="cta-band">
			<?php get_template_part( 'template-parts/hero-visual', null, array( 'part' => 'cta' ) ); ?>
			<div class="cta-band__text-wrap">
				<p class="cta-band__eyebrow"><?php echo esc_html( ktuehk_mod( 'cta_eyebrow' ) ); ?></p>
				<h2 class="cta-band__title" id="cta-title"><?php echo esc_html( ktuehk_mod( 'cta_title' ) ); ?></h2>
				<p class="cta-band__text"><?php echo esc_html( ktuehk_mod( 'cta_text' ) ); ?></p>
			</div>
			<a class="btn btn--white" href="<?php echo esc_url( $ktuehk_url ); ?>">
				<?php echo esc_html( $ktuehk_label ); ?>
				<?php echo ktuehk_icon( 'arrow-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</a>
		</div>
	</div>
</section>
