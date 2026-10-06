<?php
/**
 * Site footer: brand, university, quick links, social media.
 *
 * Social accounts and e-mail come from the central contact settings
 * (inc/contact.php); placeholders that were not filled in are not shown.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_social = ktuehk_footer_social_links();
?>
</main>

<footer class="site-footer">
	<div class="container">
		<div class="site-footer__grid">
			<div class="footer-brand">
				<?php ktuehk_brand( 'footer' ); ?>
			</div>

			<address class="footer-uni">
				<?php echo implode( '<br>', array_map( 'esc_html', ktuehk_address_lines() ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped per line. ?>
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
								<a class="social__link" href="<?php echo esc_url( $ktuehk_link['url'], array( 'http', 'https', 'mailto' ) ); ?>" aria-label="<?php echo esc_attr( $ktuehk_link['label'] ); ?>"<?php echo $ktuehk_link['external'] ? ' rel="noopener me" target="_blank"' : ''; ?>>
									<?php echo ktuehk_icon( $ktuehk_link['icon'], 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
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
