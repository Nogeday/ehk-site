<?php
/**
 * Focus areas grid ("Çalışma alanlarımız").
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;
?>
<ul class="areas">
	<?php
	foreach ( ktuehk_focus_areas() as $ktuehk_area ) :
		$ktuehk_link = ktuehk_focus_area_link( $ktuehk_area );
		?>
		<li class="area<?php echo $ktuehk_link ? ' area--link' : ''; ?>">
			<span class="area__icon"><?php echo ktuehk_icon( $ktuehk_area['icon'], 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<h3 class="area__title">
				<?php if ( $ktuehk_link ) : ?>
					<a href="<?php echo esc_url( $ktuehk_link ); ?>"><?php echo esc_html( $ktuehk_area['title'] ); ?></a>
				<?php else : ?>
					<?php echo esc_html( $ktuehk_area['title'] ); ?>
				<?php endif; ?>
			</h3>
			<p class="area__text"><?php echo esc_html( $ktuehk_area['text'] ); ?></p>
		</li>
	<?php endforeach; ?>
</ul>
