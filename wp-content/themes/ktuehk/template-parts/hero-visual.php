<?php
/**
 * Decorative illustrations (purely visual, hidden from assistive tech).
 *
 * - "illustration": isometric PCB with a chip, antenna tower, satellite dish
 *   and signal burst for the home hero. Generated with a small isometric
 *   projection script, so geometry stays consistent.
 * - "cta": line-art tower and faint PCB traces for the call-to-action band.
 *
 * Args: part (illustration|cta).
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_part = isset( $args['part'] ) ? $args['part'] : 'illustration';

if ( 'cta' === $ktuehk_part ) :
	?>
<div class="cta-band__art" aria-hidden="true"><svg class="cta-band__tower" viewBox="0 0 140 180" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M30 182 L62 56 M110 182 L78 56" stroke-width="2.2"/><path d="M30.0 182.0 L104.7 161.0 M110.0 182.0 L35.3 161.0 M35.3 161.0 L104.7 161.0" stroke-width="1.4"/><path d="M35.3 161.0 L99.3 140.0 M104.7 161.0 L40.7 140.0 M40.7 140.0 L99.3 140.0" stroke-width="1.4"/><path d="M40.7 140.0 L94.0 119.0 M99.3 140.0 L46.0 119.0 M46.0 119.0 L94.0 119.0" stroke-width="1.4"/><path d="M46.0 119.0 L88.7 98.0 M94.0 119.0 L51.3 98.0 M51.3 98.0 L88.7 98.0" stroke-width="1.4"/><path d="M51.3 98.0 L83.3 77.0 M88.7 98.0 L56.7 77.0 M56.7 77.0 L83.3 77.0" stroke-width="1.4"/><path d="M56.7 77.0 L78.0 56.0 M83.3 77.0 L62.0 56.0 M62.0 56.0 L78.0 56.0" stroke-width="1.4"/><path d="M70 56 V34" stroke-width="2.2"/><circle cx="70" cy="30" r="4.5" stroke-width="2"/><path d="M59.0 21.4 A14 14 0 0 0 59.0 38.6" stroke-width="2"/><path d="M81.0 21.4 A14 14 0 0 1 81.0 38.6" stroke-width="2"/><path d="M51.1 15.2 A24 24 0 0 0 51.1 44.8" stroke-width="2"/><path d="M88.9 15.2 A24 24 0 0 1 88.9 44.8" stroke-width="2"/><path d="M43.2 9.1 A34 34 0 0 0 43.2 50.9" stroke-width="2"/><path d="M96.8 9.1 A34 34 0 0 1 96.8 50.9" stroke-width="2"/></svg>
<svg class="cta-band__traces" viewBox="0 0 560 200" preserveAspectRatio="xMaxYMid slice" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M560 30H430l-24 24H330l-18 18"/><path d="M560 64H452l-20 20h-70"/><path d="M560 100H470l-22-22h-40l-16 16"/><path d="M560 136H440l-26 26H340"/><path d="M560 172H486l-18-18h-60"/><path d="M520 0v18l-16 16"/><path d="M600 200V180l-20-20"/><circle cx="312" cy="72" r="4"/><circle cx="362" cy="84" r="4"/><circle cx="392" cy="94" r="4"/><circle cx="340" cy="162" r="4"/><circle cx="408" cy="154" r="4"/><circle cx="504" cy="34" r="4"/></svg></div>
	<?php
	return;
endif;
?>
<svg class="hero-illus" viewBox="0 0 640 500" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
<defs><radialGradient id="hi-glow" cx="50%" cy="55%" r="50%"><stop offset="0" stop-color="#dbe7f8" stop-opacity=".95"/><stop offset="1" stop-color="#dbe7f8" stop-opacity="0"/></radialGradient></defs>
<ellipse cx="318" cy="310" rx="270" ry="170" fill="url(#hi-glow)"/>
<rect x="70" y="70" width="16" height="16" rx="2" fill="none" stroke="#8fb0e3" stroke-width="1.2" opacity=".55"/>
<rect x="520" y="60" width="14" height="14" rx="2" fill="none" stroke="#8fb0e3" stroke-width="1.2" opacity=".55"/>
<rect x="585" y="210" width="12" height="12" rx="2" fill="none" stroke="#8fb0e3" stroke-width="1.2" opacity=".55"/>
<rect x="42" y="330" width="12" height="12" rx="2" fill="none" stroke="#8fb0e3" stroke-width="1.2" opacity=".55"/>
<rect x="560" y="420" width="16" height="16" rx="2" fill="none" stroke="#8fb0e3" stroke-width="1.2" opacity=".55"/>
<rect x="120" y="455" width="10" height="10" rx="2" fill="none" stroke="#8fb0e3" stroke-width="1.2" opacity=".55"/>
<rect x="470" y="470" width="10" height="10" rx="2" fill="none" stroke="#8fb0e3" stroke-width="1.2" opacity=".55"/>
<rect x="210" y="40" width="10" height="10" rx="2" fill="none" stroke="#8fb0e3" stroke-width="1.2" opacity=".55"/>
<g stroke="#8fb0e3" stroke-width="1" opacity=".6" fill="none"><path d="M95 120 L150 92 L175 150 Z"/><path d="M505 120 L560 150 L548 92 Z"/></g>
<circle cx="95" cy="120" r="3.2" fill="#fff" stroke="#2a63c4" stroke-width="1.4" opacity=".75"/>
<circle cx="150" cy="92" r="3.2" fill="#fff" stroke="#2a63c4" stroke-width="1.4" opacity=".75"/>
<circle cx="175" cy="150" r="3.2" fill="#fff" stroke="#2a63c4" stroke-width="1.4" opacity=".75"/>
<circle cx="505" cy="120" r="3.2" fill="#fff" stroke="#2a63c4" stroke-width="1.4" opacity=".75"/>
<circle cx="560" cy="150" r="3.2" fill="#fff" stroke="#2a63c4" stroke-width="1.4" opacity=".75"/>
<circle cx="548" cy="92" r="3.2" fill="#fff" stroke="#2a63c4" stroke-width="1.4" opacity=".75"/>
<g fill="none" stroke="#bcd0ef" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
<path d="M463.0 309.3 L495.2 327.9 L495.2 346.5 L538.1 371.3"/>
<path d="M441.5 321.7 L500.6 355.8"/>
<path d="M420.0 334.1 L441.5 346.5 L479.1 346.5 L532.8 377.5"/>
<path d="M398.5 346.5 L441.5 371.3 L441.5 396.1 L473.7 414.7"/>
<path d="M377.1 358.9 L446.9 399.2"/>
<path d="M355.6 371.3 L382.4 386.8 L382.4 405.4 L420.0 427.1"/>
<path d="M334.1 383.7 L382.4 411.6"/>
<path d="M173.0 309.3 L130.1 334.1"/>
<path d="M194.5 321.7 L170.3 335.6 L132.8 335.6 L89.8 360.4"/>
<path d="M216.0 334.1 L151.5 371.3"/>
<path d="M237.5 346.5 L210.6 362.0 L210.6 383.7 L175.7 403.9"/>
<path d="M258.9 358.9 L207.9 388.4"/>
<path d="M280.4 371.3 L258.9 383.7 L258.9 408.5 L218.7 431.8"/>
<path d="M301.9 383.7 L264.3 405.4"/>
<path d="M291.2 222.5 L242.8 194.6"/>
<path d="M264.3 238.0 L194.5 197.7"/>
<path d="M237.5 253.5 L205.2 234.9"/>
<path d="M210.6 269.0 L151.5 234.9"/>
<path d="M183.8 284.5 L143.5 261.2"/>
<path d="M344.8 222.5 L379.7 202.4"/>
<path d="M371.7 238.0 L425.4 207.0"/>
<path d="M398.5 253.5 L425.4 238.0"/>
<path d="M425.4 269.0 L489.8 231.8"/>
<path d="M452.2 284.5 L495.2 259.7"/>
</g>
<ellipse cx="538.1" cy="371.3" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="500.6" cy="355.8" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="532.8" cy="377.5" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="473.7" cy="414.7" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="446.9" cy="399.2" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="420.0" cy="427.1" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="382.4" cy="411.6" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="130.1" cy="334.1" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="89.8" cy="360.4" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="151.5" cy="371.3" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="175.7" cy="403.9" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="207.9" cy="388.4" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="218.7" cy="431.8" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="264.3" cy="405.4" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="242.8" cy="194.6" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="194.5" cy="197.7" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="205.2" cy="234.9" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="151.5" cy="234.9" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="143.5" cy="261.2" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="379.7" cy="202.4" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="425.4" cy="207.0" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="425.4" cy="238.0" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="489.8" cy="231.8" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<ellipse cx="495.2" cy="259.7" rx="3.8" ry="2.2" fill="#fff" stroke="#8fb0e3" stroke-width="1.3"/>
<path d="M18.0 236.0 L18.9 235.8 L19.7 235.6 L20.6 235.4 L21.4 235.2 L22.2 235.1 L23.1 235.0 L23.9 235.0 L24.8 235.0 L25.6 235.1 L26.5 235.2 L27.4 235.3 L28.2 235.5 L29.0 235.6 L29.9 235.9 L30.8 236.1 L31.6 236.3 L32.5 236.5 L33.3 236.7 L34.1 236.8 L35.0 236.9 L35.9 237.0 L36.7 237.0 L37.5 237.0 L38.4 237.0 L39.2 236.9 L40.1 236.7 L41.0 236.5 L41.8 236.3 L42.6 236.1 L43.5 235.8 L44.3 235.6 L45.2 235.4 L46.0 235.2 L46.9 235.0 L47.8 234.9 L48.6 234.8 L49.5 234.8 L50.3 234.8 L51.1 234.9 L52.0 235.0 L52.9 235.2 L53.7 235.4 L54.6 235.7 L55.4 236.0 L56.2 236.3 L57.1 236.7 L57.9 237.0 L58.8 237.3 L59.6 237.5 L60.5 237.8 L61.4 237.9 L62.2 238.0 L63.0 238.0 L63.9 237.8 L64.8 237.6 L65.6 237.3 L66.5 236.9 L67.3 236.4 L68.2 235.8 L69.0 235.1 L69.8 234.5 L70.7 233.8 L71.6 233.1 L72.4 232.5 L73.2 232.0 L74.1 231.6 L75.0 231.4 L75.8 231.4 L76.7 231.7 L77.5 232.3 L78.3 233.1 L79.2 234.2 L80.1 235.5 L80.9 237.1 L81.8 238.8 L82.6 240.6 L83.5 242.5 L84.3 244.2 L85.1 245.8 L86.0 247.0 L86.8 247.8 L87.7 248.1 L88.5 247.8 L89.4 246.8 L90.2 245.1 L91.1 242.7 L92.0 239.6 L92.8 236.0 L93.7 231.9 L94.5 227.6 L95.3 223.1 L96.2 218.9 L97.0 215.1 L97.9 212.0 L98.8 209.8 L99.6 208.8 L100.5 209.0 L101.3 210.7 L102.2 213.8 L103.0 218.3 L103.9 224.1 L104.7 231.0 L105.5 238.6 L106.4 246.6 L107.2 254.7 L108.1 262.3 L109.0 269.1 L109.8 274.6 L110.7 278.4 L111.5 280.4 L112.3 280.3 L113.2 278.0 L114.1 273.6 L114.9 267.2 L115.8 259.1 L116.6 249.7 L117.5 239.5 L118.3 228.9 L119.1 218.6 L120.0 209.0 L120.9 200.6 L121.7 194.0 L122.5 189.3 L123.4 186.9 L124.3 186.9 L125.1 189.2 L126.0 193.6 L126.8 200.0 L127.6 207.9 L128.5 216.8 L129.3 226.4 L130.2 236.0 L131.1 245.2 L131.9 253.6 L132.8 260.7 L133.6 266.3 L134.4 270.2 L135.3 272.2 L136.2 272.4 L137.0 270.9 L137.8 267.8 L138.7 263.5 L139.6 258.2 L140.4 252.3 L141.2 246.1 L142.1 239.9 L142.9 234.1 L143.8 229.0 L144.6 224.6 L145.5 221.2 L146.3 218.8 L147.2 217.5 L148.0 217.2 L148.9 217.8 L149.8 219.3 L150.6 221.3 L151.4 223.8 L152.3 226.6 L153.2 229.6 L154.0 232.4 L154.8 235.2 L155.7 237.6 L156.6 239.6 L157.4 241.3 L158.2 242.5 L159.1 243.2 L159.9 243.5 L160.8 243.4 L161.7 243.0 L162.5 242.3 L163.3 241.4 L164.2 240.3 L165.1 239.2 L165.9 238.1 L166.8 237.0 L167.6 236.0 L168.5 235.1 L169.3 234.4 L170.2 233.8 L171.0 233.4 L171.8 233.2 L172.7 233.1 L173.5 233.1 L174.4 233.3 L175.2 233.6 L176.1 234.0 L176.9 234.4 L177.8 234.8 L178.7 235.3 L179.5 235.7 L180.3 236.1 L181.2 236.5 L182.1 236.8 L182.9 237.1 L183.8 237.3 L184.6 237.4 L185.5 237.5 L186.3 237.5 L187.2 237.4 L188.0 237.3 L188.9 237.1 L189.7 236.9 L190.6 236.6 L191.4 236.4 L192.2 236.1 L193.1 235.8 L193.9 235.6 L194.8 235.4 L195.7 235.2 L196.5 235.0 L197.3 234.9 L198.2 234.9 L199.0 234.9 L199.9 235.0 L200.8 235.1 L201.6 235.2 L202.4 235.4 L203.3 235.6 L204.2 235.8 L205.0 236.0" fill="none" stroke="#2a63c4" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>
<path d="M205 236 L205.2 234.9" stroke="#8fb0e3" stroke-width="1.6" fill="none" stroke-dasharray="3 4"/>
<g fill="none" stroke="#0e2f66" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
<path d="M384 200 L404 52 M436 200 L416 52"/>
<path d="M384.0 200.0 L432.7 175.3 M436.0 200.0 L387.3 175.3" stroke-width="1.5"/>
<path d="M387.3 175.3 L432.7 175.3" stroke-width="1.6"/>
<path d="M387.3 175.3 L429.3 150.7 M432.7 175.3 L390.7 150.7" stroke-width="1.5"/>
<path d="M390.7 150.7 L429.3 150.7" stroke-width="1.6"/>
<path d="M390.7 150.7 L426.0 126.0 M429.3 150.7 L394.0 126.0" stroke-width="1.5"/>
<path d="M394.0 126.0 L426.0 126.0" stroke-width="1.6"/>
<path d="M394.0 126.0 L422.7 101.3 M426.0 126.0 L397.3 101.3" stroke-width="1.5"/>
<path d="M397.3 101.3 L422.7 101.3" stroke-width="1.6"/>
<path d="M397.3 101.3 L419.3 76.7 M422.7 101.3 L400.7 76.7" stroke-width="1.5"/>
<path d="M400.7 76.7 L419.3 76.7" stroke-width="1.6"/>
<path d="M400.7 76.7 L416.0 52.0 M419.3 76.7 L404.0 52.0" stroke-width="1.5"/>
<path d="M404.0 52.0 L416.0 52.0" stroke-width="1.6"/>
<path d="M410 52 L410 36"/>
</g>
<circle cx="410" cy="33" r="4" fill="#0e2f66"/>
<g fill="none" stroke="#2a63c4" stroke-width="2" stroke-linecap="round">
<path d="M399.3 24.0 A14 14 0 0 0 399.3 42.0" opacity="1"/>
<path d="M420.7 24.0 A14 14 0 0 1 420.7 42.0" opacity="1"/>
<path d="M391.6 17.6 A24 24 0 0 0 391.6 48.4" opacity="0.75"/>
<path d="M428.4 17.6 A24 24 0 0 1 428.4 48.4" opacity="0.75"/>
<path d="M384.0 11.1 A34 34 0 0 0 384.0 54.9" opacity="0.5"/>
<path d="M436.0 11.1 A34 34 0 0 1 436.0 54.9" opacity="0.5"/>
</g>
<path d="M376 200 L410 190 L444 200 L410 210 Z" fill="#e3ecf9" stroke="#8fb0e3" stroke-width="1.2"/>
<path d="M262 158 L262 202" stroke="#8fb0e3" stroke-width="2"/>
<circle cx="262" cy="150" r="11" fill="#fff" stroke="#2a63c4" stroke-width="2"/>
<circle cx="262" cy="150" r="5" fill="none" stroke="#2a63c4" stroke-width="2"/>
<circle cx="262" cy="150" r="1.8" fill="#2a63c4"/>
<path d="M255 202 L262 199 L269 202 L262 205 Z" fill="#e3ecf9" stroke="#8fb0e3" stroke-width="1"/>
<path d="M318.0 247.9 L479.1 340.9 L318.0 433.9 L156.9 340.9 Z" fill="#0a2552" opacity=".07"/>
<path d="M318.0 232.4 L479.1 325.4 L318.0 418.4 L156.9 325.4 Z" fill="#0a2552" opacity=".07"/>
<path d="M156.9 300.0 L318.0 393.0 L318.0 402.9 L156.9 309.9 Z" fill="#0a2552"/>
<path d="M479.1 300.0 L318.0 393.0 L318.0 402.9 L479.1 309.9 Z" fill="#0d2f66"/>
<path d="M318.0 207.0 L479.1 300.0 L318.0 393.0 L156.9 300.0 Z" fill="#123b7c"/>
<path d="M318.0 213.8 L467.3 300.0 L318.0 386.2 L168.7 300.0 Z" fill="none" stroke="#2f63b3" stroke-width="1" opacity=".8"/>
<path d="M156.9 300.0 L318.0 207.0 L479.1 300.0" fill="none" stroke="#3f74c6" stroke-width="1.2" opacity=".7"/>
<path d="M318.0 214.1 L335.2 224.1 L318.0 234.0 L300.8 224.1 Z" fill="#c9d8f0"/>
<ellipse cx="318.0" cy="224.1" rx="4" ry="2.3" fill="#0a2552"/>
<path d="M449.5 290.1 L466.7 300.0 L449.5 309.9 L432.4 300.0 Z" fill="#c9d8f0"/>
<ellipse cx="449.5" cy="300.0" rx="4" ry="2.3" fill="#0a2552"/>
<path d="M318.0 366.0 L335.2 375.9 L318.0 385.9 L300.8 375.9 Z" fill="#c9d8f0"/>
<ellipse cx="318.0" cy="375.9" rx="4" ry="2.3" fill="#0a2552"/>
<path d="M186.5 290.1 L203.6 300.0 L186.5 309.9 L169.3 300.0 Z" fill="#c9d8f0"/>
<ellipse cx="186.5" cy="300.0" rx="4" ry="2.3" fill="#0a2552"/>
<g fill="none" stroke="#5f8fd8" stroke-width="1" stroke-linecap="round" opacity=".85">
<path d="M382.2 307.3 L404.2 320.0"/>
<path d="M253.8 307.3 L231.8 320.0"/>
<path d="M305.4 263.0 L287.4 252.6"/>
<path d="M330.6 263.0 L348.6 252.6"/>
<path d="M375.7 311.0 L389.7 319.1 L405.8 319.1 L401.8 316.7"/>
<path d="M260.3 311.0 L246.3 319.1 L230.2 319.1 L234.2 316.7"/>
<path d="M298.9 266.7 L281.0 256.3"/>
<path d="M337.1 266.7 L355.0 256.3"/>
<path d="M369.3 314.7 L391.3 327.4"/>
<path d="M266.7 314.7 L244.7 327.4"/>
<path d="M292.5 270.4 L274.5 260.0"/>
<path d="M343.5 270.4 L361.5 260.0"/>
<path d="M362.8 318.4 L376.8 326.5 L392.9 326.5 L388.9 324.2"/>
<path d="M273.2 318.4 L259.2 326.5 L243.1 326.5 L247.1 324.2"/>
<path d="M286.1 274.1 L268.1 263.7"/>
<path d="M349.9 274.1 L367.9 263.7"/>
<path d="M356.4 322.2 L378.4 334.9"/>
<path d="M279.6 322.2 L257.6 334.9"/>
<path d="M279.6 277.8 L261.6 267.4"/>
<path d="M356.4 277.8 L374.4 267.4"/>
<path d="M349.9 325.9 L363.9 333.9 L363.9 343.2 L359.9 340.9"/>
<path d="M286.1 325.9 L272.1 333.9 L272.1 343.2 L276.1 340.9"/>
<path d="M273.2 281.6 L255.2 271.2"/>
<path d="M362.8 281.6 L380.8 271.2"/>
<path d="M343.5 329.6 L365.5 342.3"/>
<path d="M292.5 329.6 L270.5 342.3"/>
<path d="M266.7 285.3 L248.7 274.9"/>
<path d="M369.3 285.3 L387.3 274.9"/>
<path d="M337.1 333.3 L351.0 341.4 L351.0 350.7 L347.0 348.4"/>
<path d="M298.9 333.3 L285.0 341.4 L285.0 350.7 L289.0 348.4"/>
<path d="M260.3 289.0 L242.3 278.6"/>
<path d="M375.7 289.0 L393.7 278.6"/>
<path d="M330.6 337.0 L352.6 349.8"/>
<path d="M305.4 337.0 L283.4 349.8"/>
<path d="M253.8 292.7 L235.8 282.3"/>
<path d="M382.2 292.7 L400.2 282.3"/>
</g>
<ellipse cx="404.2" cy="320.0" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="287.4" cy="252.6" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="401.8" cy="316.7" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="281.0" cy="256.3" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="391.3" cy="327.4" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="274.5" cy="260.0" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="388.9" cy="324.2" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="268.1" cy="263.7" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="378.4" cy="334.9" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="261.6" cy="267.4" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="359.9" cy="340.9" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="255.2" cy="271.2" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="365.5" cy="342.3" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="248.7" cy="274.9" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="347.0" cy="348.4" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="242.3" cy="278.6" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="352.6" cy="349.8" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<ellipse cx="235.8" cy="282.3" rx="1.9" ry="1.1" fill="#9fc0f0"/>
<g stroke="#a9c4ee" stroke-width="1.4" stroke-linecap="round">
<path d="M377.3 304.5 L382.2 307.3"/>
<path d="M258.7 304.5 L253.8 307.3"/>
<path d="M310.2 265.7 L305.4 263.0"/>
<path d="M325.8 265.7 L330.6 263.0"/>
<path d="M370.9 308.2 L375.7 311.0"/>
<path d="M265.1 308.2 L260.3 311.0"/>
<path d="M303.8 269.5 L298.9 266.7"/>
<path d="M332.2 269.5 L337.1 266.7"/>
<path d="M364.4 311.9 L369.3 314.7"/>
<path d="M271.6 311.9 L266.7 314.7"/>
<path d="M297.3 273.2 L292.5 270.4"/>
<path d="M338.7 273.2 L343.5 270.4"/>
<path d="M358.0 315.7 L362.8 318.4"/>
<path d="M278.0 315.7 L273.2 318.4"/>
<path d="M290.9 276.9 L286.1 274.1"/>
<path d="M345.1 276.9 L349.9 274.1"/>
<path d="M351.6 319.4 L356.4 322.2"/>
<path d="M284.4 319.4 L279.6 322.2"/>
<path d="M284.4 280.6 L279.6 277.8"/>
<path d="M351.6 280.6 L356.4 277.8"/>
<path d="M345.1 323.1 L349.9 325.9"/>
<path d="M290.9 323.1 L286.1 325.9"/>
<path d="M278.0 284.3 L273.2 281.6"/>
<path d="M358.0 284.3 L362.8 281.6"/>
<path d="M338.7 326.8 L343.5 329.6"/>
<path d="M297.3 326.8 L292.5 329.6"/>
<path d="M271.6 288.1 L266.7 285.3"/>
<path d="M364.4 288.1 L369.3 285.3"/>
<path d="M332.2 330.5 L337.1 333.3"/>
<path d="M303.8 330.5 L298.9 333.3"/>
<path d="M265.1 291.8 L260.3 289.0"/>
<path d="M370.9 291.8 L375.7 289.0"/>
<path d="M325.8 334.3 L330.6 337.0"/>
<path d="M310.2 334.3 L305.4 337.0"/>
<path d="M258.7 295.5 L253.8 292.7"/>
<path d="M377.3 295.5 L382.2 292.7"/>
</g>
<path d="M250.9 300.0 L318.0 338.8 L318.0 328.2 L250.9 289.5 Z" fill="#0f3270"/>
<path d="M385.1 300.0 L318.0 338.8 L318.0 328.2 L385.1 289.5 Z" fill="#163f86"/>
<path d="M318.0 250.7 L385.1 289.5 L318.0 328.2 L250.9 289.5 Z" fill="#1f56a8"/>
<path d="M318.0 270.2 L351.3 289.5 L318.0 308.7 L284.7 289.5 Z" fill="#3a74cf" stroke="#7fa8e6" stroke-width="1"/>
<path d="M250.9 289.5 L318.0 250.7 L385.1 289.5" fill="none" stroke="#5d8fdc" stroke-width="1.1"/>
<ellipse cx="318.0" cy="259.4" rx="2.6" ry="1.5" fill="#8db3ec"/>
<path d="M422.7 293.8 L437.5 302.3 L437.5 297.4 L422.7 288.8 Z" fill="#8fa9d6"/>
<path d="M445.5 297.7 L437.5 302.3 L437.5 297.4 L445.5 292.7 Z" fill="#a9c0e6"/>
<path d="M430.8 284.2 L445.5 292.7 L437.5 297.4 L422.7 288.8 Z" fill="#d7e3f6"/>
<path d="M189.1 298.4 L197.2 303.1 L197.2 298.1 L189.1 293.5 Z" fill="#8fa9d6"/>
<path d="M212.0 294.6 L197.2 303.1 L197.2 298.1 L212.0 289.6 Z" fill="#a9c0e6"/>
<path d="M203.9 285.0 L212.0 289.6 L197.2 298.1 L189.1 293.5 Z" fill="#d7e3f6"/>
<path d="M297.9 234.1 L316.7 245.0 L316.7 238.8 L297.9 227.9 Z" fill="#0f2f68"/>
<path d="M328.7 238.0 L316.7 245.0 L316.7 238.8 L328.7 231.8 Z" fill="#173f84"/>
<path d="M309.9 220.9 L328.7 231.8 L316.7 238.8 L297.9 227.9 Z" fill="#2a5fb5"/>
<path d="M309.9 363.6 L322.0 370.5 L322.0 363.7 L309.9 356.7 Z" fill="#0f2f68"/>
<path d="M334.1 363.6 L322.0 370.5 L322.0 363.7 L334.1 356.7 Z" fill="#173f84"/>
<path d="M322.0 349.8 L334.1 356.7 L322.0 363.7 L309.9 356.7 Z" fill="#2a5fb5"/>
<path d="M540 298 L566 285 L592 298 L566 311 Z" fill="#dfe9f8" stroke="#8fb0e3" stroke-width="1.2"/>
<path d="M556 296 L556 270 L576 270 L576 296 Z" fill="#0d2f66"/>
<path d="M556 296 L556 270 L566 270 L566 296 Z" fill="#0a2552"/>
<ellipse cx="566" cy="270" rx="10" ry="3.6" fill="#123b7c"/>
<path d="M566 256 L566 270" stroke="#0e2f66" stroke-width="3.2" stroke-linecap="round"/>
<g transform="rotate(-35 566 244)"><ellipse cx="566" cy="244" rx="36" ry="14" fill="#0d2f66"/><ellipse cx="566" cy="242" rx="32" ry="11" fill="#2c66c2"/><ellipse cx="566" cy="241" rx="20" ry="6.5" fill="#4a80d6" opacity=".7"/><path d="M566 242 L566 210" stroke="#0e2f66" stroke-width="2.2"/><circle cx="566" cy="208" r="4" fill="#0e2f66"/></g>
<g fill="none" stroke="#2a63c4" stroke-width="1.8" stroke-linecap="round" opacity=".6"><path d="M514 192 q -8 8 -2 18"/><path d="M504 184 q -12 13 -3 28"/></g>
<path d="M334.1 433.3 L352.9 444.1 L352.9 427.1 L334.1 416.2 Z" fill="#0f2f68"/>
<path d="M371.7 433.3 L352.9 444.1 L352.9 427.1 L371.7 416.2 Z" fill="#173f84"/>
<path d="M352.9 405.4 L371.7 416.2 L352.9 427.1 L334.1 416.2 Z" fill="#2a5fb5"/>
<ellipse cx="352.9" cy="416.2" rx="5" ry="2.8" fill="#8db3ec"/>
<path d="M343.5 420.1 L324.7 417.0" stroke="#0e2f66" stroke-width="2" stroke-linecap="round"/>
<circle cx="324.7" cy="417.0" r="3.2" fill="#fff" stroke="#0e2f66" stroke-width="1.6"/>
</svg>
