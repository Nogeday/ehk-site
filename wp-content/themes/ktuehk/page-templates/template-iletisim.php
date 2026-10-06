<?php
/**
 * Template Name: İletişim
 *
 * Contact page: e-mail / Instagram / LinkedIn cards and a map. All values
 * come from the central contact settings (inc/contact.php, editable under
 * Görünüm › Özelleştir › KTÜ EHK Tema Ayarları › İletişim ve sosyal medya).
 * The page's own editor content is not shown here, so older contact texts
 * stay in the database untouched but are not published twice.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

$ktuehk_editor   = current_user_can( 'edit_theme_options' );
$ktuehk_settings = admin_url( 'customize.php?autofocus[section]=ktuehk_social' );

/**
 * Small "not set yet" hint, only for people who can fix it.
 *
 * @param string $placeholder Placeholder name, e.g. [EMAIL].
 */
$ktuehk_hint = static function ( $placeholder ) use ( $ktuehk_editor, $ktuehk_settings ) {
	if ( ! $ktuehk_editor ) {
		return;
	}
	printf(
		'<span class="contact-card__admin">%1$s <a href="%2$s">%3$s</a></span>',
		/* translators: %s: placeholder name */
		esc_html( sprintf( __( '%s ayarlanmadı.', 'ktuehk' ), $placeholder ) ),
		esc_url( $ktuehk_settings ),
		esc_html__( 'Özelleştir’den ekleyin', 'ktuehk' )
	);
};

while ( have_posts() ) :
	the_post();

	get_template_part(
		'template-parts/page-header',
		null,
		array(
			'eyebrow' => __( 'İLETİŞİM', 'ktuehk' ),
			'title'   => get_the_title(),
			'desc'    => has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : ktuehk_mod( 'intro_contact' ),
			'class'   => 'page-hero--contact',
		)
	);

	$ktuehk_email     = ktuehk_contact( 'email' );
	$ktuehk_instagram = ktuehk_contact( 'instagram' );
	$ktuehk_linkedin  = ktuehk_contact( 'linkedin' );
	$ktuehk_handle    = ktuehk_contact_ready( 'instagram' ) ? ktuehk_instagram_handle( $ktuehk_instagram ) : '';
	?>
	<div class="section section--tight">
		<div class="container contact-layout">
			<section class="contact-col" aria-labelledby="contact-options-title">
				<h2 class="contact-col__title" id="contact-options-title"><?php esc_html_e( 'Bizimle İletişime Geçin', 'ktuehk' ); ?></h2>
				<ul class="contact-cards">
					<li>
						<?php if ( ktuehk_contact_ready( 'email' ) && is_email( $ktuehk_email ) ) : ?>
							<a class="contact-card" href="mailto:<?php echo antispambot( $ktuehk_email, 1 ); // phpcs:ignore WordPress.Security.EscapeOutput -- sanitized e-mail, entity-encoded. ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: e-mail address */ __( 'E-posta gönder: %s', 'ktuehk' ), $ktuehk_email ) ); ?>">
								<span class="contact-card__icon"><?php echo ktuehk_icon( 'mail', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span class="contact-card__body">
									<span class="contact-card__label"><?php esc_html_e( 'E-POSTA', 'ktuehk' ); ?></span>
									<span class="contact-card__value"><?php echo antispambot( $ktuehk_email ); // phpcs:ignore WordPress.Security.EscapeOutput -- sanitized e-mail, entity-encoded. ?></span>
									<span class="contact-card__text"><?php esc_html_e( 'Sorularınız ve iş birlikleri için yazın.', 'ktuehk' ); ?></span>
								</span>
								<span class="contact-card__arrow" aria-hidden="true"><?php echo ktuehk_icon( 'arrow-right', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							</a>
						<?php else : ?>
							<div class="contact-card is-pending">
								<span class="contact-card__icon"><?php echo ktuehk_icon( 'mail', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span class="contact-card__body">
									<span class="contact-card__label"><?php esc_html_e( 'E-POSTA', 'ktuehk' ); ?></span>
									<span class="contact-card__value"><?php esc_html_e( 'Yakında eklenecek', 'ktuehk' ); ?></span>
									<?php $ktuehk_hint( '[EMAIL]' ); ?>
								</span>
							</div>
						<?php endif; ?>
					</li>
					<?php
					foreach (
						array(
							array(
								'key'   => 'instagram',
								'label' => __( 'INSTAGRAM', 'ktuehk' ),
								'icon'  => 'brand-instagram',
								'value' => __( 'Instagram’da bizi takip edin', 'ktuehk' ),
								'text'  => $ktuehk_handle,
								'aria'  => __( 'Instagram’da KTÜ EHK’yı takip edin (yeni sekmede açılır)', 'ktuehk' ),
								'url'   => $ktuehk_instagram,
								'ph'    => '[INSTAGRAM_URL]',
							),
							array(
								'key'   => 'linkedin',
								'label' => __( 'LINKEDIN', 'ktuehk' ),
								'icon'  => 'brand-linkedin',
								'value' => __( 'LinkedIn’de bizi takip edin', 'ktuehk' ),
								'text'  => __( 'Kulübümüzün LinkedIn sayfası', 'ktuehk' ),
								'aria'  => __( 'LinkedIn’de KTÜ EHK’yı takip edin (yeni sekmede açılır)', 'ktuehk' ),
								'url'   => $ktuehk_linkedin,
								'ph'    => '[LINKEDIN_URL]',
							),
						) as $ktuehk_item
					) :
						$ktuehk_ready = ktuehk_contact_ready( $ktuehk_item['key'] ) && ktuehk_contact_url_ok( $ktuehk_item['url'] );
						?>
						<li>
							<?php if ( $ktuehk_ready ) : ?>
								<a class="contact-card" href="<?php echo esc_url( $ktuehk_item['url'] ); ?>" target="_blank" rel="noopener me" aria-label="<?php echo esc_attr( $ktuehk_item['aria'] ); ?>">
							<?php else : ?>
								<div class="contact-card is-pending">
							<?php endif; ?>
								<span class="contact-card__icon"><?php echo ktuehk_icon( $ktuehk_item['icon'], 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span class="contact-card__body">
									<span class="contact-card__label"><?php echo esc_html( $ktuehk_item['label'] ); ?></span>
									<span class="contact-card__value"><?php echo $ktuehk_ready ? esc_html( $ktuehk_item['value'] ) : esc_html__( 'Yakında eklenecek', 'ktuehk' ); ?></span>
									<?php if ( $ktuehk_ready && $ktuehk_item['text'] ) : ?>
										<span class="contact-card__text"><?php echo esc_html( $ktuehk_item['text'] ); ?></span>
									<?php elseif ( ! $ktuehk_ready ) : ?>
										<?php $ktuehk_hint( $ktuehk_item['ph'] ); ?>
									<?php endif; ?>
								</span>
							<?php if ( $ktuehk_ready ) : ?>
									<span class="contact-card__arrow" aria-hidden="true"><?php echo ktuehk_icon( 'arrow-up-right', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								</a>
							<?php else : ?>
								</div>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>

			<section class="contact-col" aria-labelledby="contact-map-title">
				<h2 class="contact-col__title" id="contact-map-title"><?php esc_html_e( 'Konumumuz', 'ktuehk' ); ?></h2>
				<?php if ( ktuehk_contact_ready( 'maps_embed' ) && ktuehk_sanitize_maps_embed( ktuehk_contact( 'maps_embed' ) ) ) : ?>
					<div class="map-frame">
						<iframe src="<?php echo esc_url( ktuehk_contact( 'maps_embed' ) ); ?>" title="<?php esc_attr_e( 'KTÜ Teknoloji Fakültesi konumu — Google Haritalar', 'ktuehk' ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
					</div>
				<?php else : ?>
					<div class="map-frame map-frame--empty">
						<span class="map-frame__icon"><?php echo ktuehk_icon( 'map-pin', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<p class="map-frame__text"><?php esc_html_e( 'Harita yakında eklenecek.', 'ktuehk' ); ?></p>
						<?php $ktuehk_hint( '[GOOGLE_MAPS_EMBED_URL]' ); ?>
					</div>
				<?php endif; ?>
				<div class="contact-place">
					<address class="contact-place__address">
						<?php echo ktuehk_icon( 'building-2', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php echo implode( '<br>', array_map( 'esc_html', ktuehk_address_lines() ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped per line. ?></span>
					</address>
					<?php if ( ktuehk_contact_ready( 'maps_directions' ) && ktuehk_contact_url_ok( ktuehk_contact( 'maps_directions' ) ) ) : ?>
						<a class="btn btn--outline contact-place__btn" href="<?php echo esc_url( ktuehk_contact( 'maps_directions' ) ); ?>" target="_blank" rel="noopener">
							<?php esc_html_e( 'Google Maps’te Yol Tarifi Al', 'ktuehk' ); ?>
							<?php echo ktuehk_icon( 'arrow-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span class="screen-reader-text"><?php esc_html_e( '(yeni sekmede açılır)', 'ktuehk' ); ?></span>
						</a>
					<?php elseif ( $ktuehk_editor ) : ?>
						<?php $ktuehk_hint( '[GOOGLE_MAPS_DIRECTIONS_URL]' ); ?>
					<?php endif; ?>
				</div>
			</section>
		</div>
	</div>
	<?php
endwhile;

get_footer();
