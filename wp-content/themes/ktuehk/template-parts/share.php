<?php
/**
 * Share links (no third-party scripts).
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_url   = rawurlencode( get_permalink() );
$ktuehk_title = rawurlencode( html_entity_decode( get_the_title(), ENT_QUOTES, 'UTF-8' ) );
$ktuehk_links = array(
	array(
		'label' => 'LinkedIn',
		'icon'  => 'brand-linkedin',
		'url'   => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $ktuehk_url,
	),
	array(
		'label' => 'X',
		'icon'  => 'brand-x',
		'url'   => 'https://x.com/intent/post?url=' . $ktuehk_url . '&text=' . $ktuehk_title,
	),
	array(
		'label' => 'WhatsApp',
		'icon'  => 'brand-whatsapp',
		'url'   => 'https://wa.me/?text=' . $ktuehk_title . '%20' . $ktuehk_url,
	),
);
?>
<div class="share">
	<span class="share__label"><?php esc_html_e( 'Paylaş', 'ktuehk' ); ?></span>
	<ul class="share__list">
		<?php foreach ( $ktuehk_links as $ktuehk_link ) : ?>
			<li>
				<a class="icon-btn icon-btn--outline" href="<?php echo esc_url( $ktuehk_link['url'] ); ?>" target="_blank" rel="noopener">
					<?php echo ktuehk_icon( $ktuehk_link['icon'], 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span class="screen-reader-text">
						<?php
						/* translators: %s: network name */
						echo esc_html( sprintf( __( '%s ile paylaş', 'ktuehk' ), $ktuehk_link['label'] ) );
						?>
					</span>
				</a>
			</li>
		<?php endforeach; ?>
		<li>
			<button type="button" class="icon-btn icon-btn--outline" data-copy-link="<?php echo esc_url( get_permalink() ); ?>">
				<?php echo ktuehk_icon( 'link', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Bağlantıyı kopyala', 'ktuehk' ); ?></span>
			</button>
		</li>
	</ul>
	<span class="share__status" role="status" aria-live="polite" data-copy-status></span>
</div>
