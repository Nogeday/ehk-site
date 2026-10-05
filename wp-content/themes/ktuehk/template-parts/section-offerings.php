<?php
/**
 * What the club offers students ("Öğrencilere sunduklarımız").
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;
?>
<ol class="offers">
	<?php foreach ( ktuehk_offerings() as $ktuehk_i => $ktuehk_offer ) : ?>
		<li class="offer">
			<span class="offer__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $ktuehk_i + 1 ) ); ?></span>
			<div class="offer__body">
				<h3 class="offer__title"><?php echo ktuehk_icon( $ktuehk_offer['icon'], 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $ktuehk_offer['title'] ); ?></h3>
				<p class="offer__text"><?php echo esc_html( $ktuehk_offer['text'] ); ?></p>
			</div>
		</li>
	<?php endforeach; ?>
</ol>
