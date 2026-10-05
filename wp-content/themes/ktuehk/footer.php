<?php
/**
 * Site footer: brand, university, quick links, social media.
 *
 * Contact details are intentionally not repeated here; the existing contact
 * page is only linked (through the menu) so it can be revised separately.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_social = ktuehk_social_links();
?>
</main>

<footer class="site-footer">
	<div class="container">
		<div class="site-footer__grid">
			<div class="footer-brand">
				<?php ktuehk_brand( 'footer' ); ?>
			</div>

			<address class="footer-uni">
				<?php esc_html_e( 'Karadeniz Teknik Üniversitesi', 'ktuehk' ); ?><br>
				<?php esc_html_e( 'Teknoloji Fakültesi', 'ktuehk' ); ?><br>
				<?php esc_html_e( 'Elektronik ve Haberleşme Mühendisliği', 'ktuehk' ); ?>
			</address>

			<nav class="footer-col" aria-labelledby="footer-links-title">
				<h2 class="footer-title" id="footer-links-title"><?php esc_html_e( 'Hızlı Bağlantılar', 'ktuehk' ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'footer-links',
						'depth'          => 1,
						'fallback_cb'    => 'ktuehk_footer_menu_fallback',
					)
				);
				?>
			</nav>

			<?php if ( $ktuehk_social ) : ?>
				<div class="footer-col">
					<h2 class="footer-title"><?php esc_html_e( 'Sosyal Medya', 'ktuehk' ); ?></h2>
					<ul class="social">
						<?php foreach ( $ktuehk_social as $ktuehk_link ) : ?>
							<li>
								<a class="social__link" href="<?php echo esc_url( $ktuehk_link['url'] ); ?>" rel="noopener me" target="_blank">
									<?php echo ktuehk_icon( 'brand-' . $ktuehk_link['key'], 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
									<span class="screen-reader-text"><?php echo esc_html( $ktuehk_link['label'] ); ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>

		<div class="site-footer__bottom">
			<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php esc_html_e( 'KTÜ Elektronik ve Haberleşme Kulübü. Tüm hakları saklıdır.', 'ktuehk' ); ?></p>
			<p class="site-footer__mark" aria-hidden="true"><span>KTÜ</span><span>EHK</span></p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
