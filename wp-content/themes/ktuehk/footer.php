<?php
/**
 * Site footer.
 *
 * Contact details are intentionally not repeated here; the existing contact
 * page is only linked so it can be revised separately.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_social = ktuehk_social_links();
$ktuehk_cats   = get_categories(
	array(
		'orderby'    => 'count',
		'order'      => 'DESC',
		'number'     => 6,
		'hide_empty' => true,
		'exclude'    => array( (int) get_option( 'default_category' ) ),
	)
);
?>
</main>

<footer class="site-footer">
	<div class="container">
		<div class="site-footer__grid">
			<div class="footer-brand">
				<?php ktuehk_brand( 'footer' ); ?>
				<p class="footer-brand__text"><?php echo esc_html( ktuehk_mod( 'hero_text' ) ); ?></p>
				<address class="footer-address">
					<?php echo ktuehk_icon( 'building-2', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span>
						<strong><?php esc_html_e( 'KTÜ Elektronik ve Haberleşme Kulübü', 'ktuehk' ); ?></strong>
						<?php esc_html_e( 'Karadeniz Teknik Üniversitesi', 'ktuehk' ); ?><br>
						<?php esc_html_e( 'Teknoloji Fakültesi', 'ktuehk' ); ?><br>
						<?php esc_html_e( 'Elektronik ve Haberleşme Mühendisliği', 'ktuehk' ); ?>
					</span>
				</address>
			</div>

			<nav class="footer-col" aria-labelledby="footer-links-title">
				<h2 class="footer-title" id="footer-links-title"><?php esc_html_e( 'Hızlı bağlantılar', 'ktuehk' ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'footer-links',
						'depth'          => 1,
						'fallback_cb'    => 'ktuehk_menu_fallback',
					)
				);
				?>
			</nav>

			<?php if ( $ktuehk_cats ) : ?>
				<nav class="footer-col" aria-labelledby="footer-topics-title">
					<h2 class="footer-title" id="footer-topics-title"><?php esc_html_e( 'Yazı konuları', 'ktuehk' ); ?></h2>
					<ul class="footer-links">
						<?php foreach ( $ktuehk_cats as $ktuehk_cat ) : ?>
							<li><a href="<?php echo esc_url( get_category_link( $ktuehk_cat ) ); ?>"><?php echo esc_html( $ktuehk_cat->name ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>

			<?php if ( $ktuehk_social ) : ?>
				<div class="footer-col">
					<h2 class="footer-title"><?php esc_html_e( 'Bizi takip edin', 'ktuehk' ); ?></h2>
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
			<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php esc_html_e( 'KTÜ Elektronik ve Haberleşme Kulübü', 'ktuehk' ); ?></p>
			<p class="site-footer__note"><?php esc_html_e( 'Karadeniz Teknik Üniversitesi öğrenci kulübü', 'ktuehk' ); ?></p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
