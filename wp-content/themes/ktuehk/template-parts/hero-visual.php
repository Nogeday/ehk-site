<?php
/**
 * Decorative hero graphics: faint PCB traces (background) and an
 * AM-modulated signal on an oscilloscope-like grid. Purely decorative.
 *
 * Args: part (traces|scope).
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_part = isset( $args['part'] ) ? $args['part'] : 'traces';

if ( 'traces' === $ktuehk_part ) :
	?>
<svg class="hero__traces" viewBox="0 0 640 420" preserveAspectRatio="xMaxYMid slice" aria-hidden="true" focusable="false">
	<g fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
		<path d="M640 52H508l-28 28H392"/>
		<path d="M640 112H530l-30 30h-58l-22 22v56"/>
		<path d="M640 168h-74l-24 24v74l26 26h72"/>
		<path d="M640 344H520l-30-30h-62"/>
		<path d="M640 392H452l-26-26h-70"/>
		<path d="M612 0v28l-20 20"/>
	</g>
	<g fill="var(--hero-bg, #fff)" stroke="currentColor" stroke-width="1.5">
		<circle cx="392" cy="80" r="4.5"/>
		<circle cx="420" cy="220" r="4.5"/>
		<circle cx="640" cy="292" r="4.5"/>
		<circle cx="428" cy="314" r="4.5"/>
		<circle cx="356" cy="366" r="4.5"/>
		<circle cx="592" cy="48" r="4.5"/>
	</g>
</svg>
	<?php
else :
	?>
<svg class="scope" viewBox="0 0 360 140" preserveAspectRatio="none" aria-hidden="true" focusable="false">
	<g class="scope__grid" stroke="currentColor" stroke-width="1">
		<path d="M0 35H360M0 70H360M0 105H360M45 0V140M90 0V140M135 0V140M180 0V140M225 0V140M270 0V140M315 0V140"/>
	</g>
	<path class="scope__env" d="M0 68.0 L10 67.7 L20 66.8 L30 65.3 L40 63.3 L50 60.9 L60 58.0 L70 54.8 L80 51.5 L90 48.0 L100 44.5 L110 41.2 L120 38.0 L130 35.1 L140 32.7 L150 30.7 L160 29.2 L170 28.3 L180 28.0 L190 28.3 L200 29.2 L210 30.7 L220 32.7 L230 35.1 L240 38.0 L250 41.2 L260 44.5 L270 48.0 L280 51.5 L290 54.8 L300 58.0 L310 60.9 L320 63.3 L330 65.3 L340 66.8 L350 67.7 L360 68.0" fill="none" stroke-width="1.25" stroke-dasharray="3 4"/>
	<path class="scope__env" d="M0 72.0 L10 72.3 L20 73.2 L30 74.7 L40 76.7 L50 79.1 L60 82.0 L70 85.2 L80 88.5 L90 92.0 L100 95.5 L110 98.8 L120 102.0 L130 104.9 L140 107.3 L150 109.3 L160 110.8 L170 111.7 L180 112.0 L190 111.7 L200 110.8 L210 109.3 L220 107.3 L230 104.9 L240 102.0 L250 98.8 L260 95.5 L270 92.0 L280 88.5 L290 85.2 L300 82.0 L310 79.1 L320 76.7 L330 74.7 L340 73.2 L350 72.3 L360 72.0" fill="none" stroke-width="1.25" stroke-dasharray="3 4"/>
	<path class="scope__signal" d="M0 70 Q4.5 65.9 9 70 Q13.5 75.1 18 70 Q22.5 63.0 27 70 Q31.5 79.9 36 70 Q40.5 56.4 45 70 Q49.5 88.0 54 70 Q58.5 46.9 63 70 Q67.5 98.7 72 70 Q76.5 35.3 81 70 Q85.5 110.9 90 70 Q94.5 22.9 99 70 Q103.5 123.3 108 70 Q112.5 10.7 117 70 Q121.5 134.9 126 70 Q130.5 0.0 135 70 Q139.5 144.4 144 70 Q148.5 -8.1 153 70 Q157.5 151.0 162 70 Q166.5 -12.9 171 70 Q175.5 153.9 180 70 Q184.5 -13.9 189 70 Q193.5 152.9 198 70 Q202.5 -11.0 207 70 Q211.5 148.1 216 70 Q220.5 -4.4 225 70 Q229.5 140.0 234 70 Q238.5 5.1 243 70 Q247.5 129.3 252 70 Q256.5 16.7 261 70 Q265.5 117.1 270 70 Q274.5 29.1 279 70 Q283.5 104.7 288 70 Q292.5 41.3 297 70 Q301.5 93.1 306 70 Q310.5 52.0 315 70 Q319.5 83.6 324 70 Q328.5 60.1 333 70 Q337.5 77.0 342 70 Q346.5 64.9 351 70 Q355.5 74.1 360 70" fill="none" stroke-width="1.75" stroke-linejoin="round"/>
</svg>
	<?php
endif;
