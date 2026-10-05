<?php
/**
 * Page heading area shared by archives and pages.
 *
 * Args: eyebrow, title, desc (plain text), after (callable printing extra
 * content such as a search form or filters), class.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_args = wp_parse_args(
	isset( $args ) ? $args : array(),
	array(
		'eyebrow' => '',
		'title'   => '',
		'desc'    => '',
		'after'   => null,
		'class'   => '',
	)
);
?>
<header class="page-hero <?php echo esc_attr( $ktuehk_args['class'] ); ?>">
	<div class="container page-hero__inner">
		<?php ktuehk_breadcrumbs(); ?>
		<?php if ( $ktuehk_args['eyebrow'] ) : ?>
			<p class="eyebrow"><?php echo esc_html( $ktuehk_args['eyebrow'] ); ?></p>
		<?php endif; ?>
		<h1 class="page-hero__title"><?php echo esc_html( $ktuehk_args['title'] ); ?></h1>
		<?php if ( $ktuehk_args['desc'] ) : ?>
			<p class="page-hero__desc"><?php echo esc_html( $ktuehk_args['desc'] ); ?></p>
		<?php endif; ?>
		<?php
		if ( is_callable( $ktuehk_args['after'] ) ) {
			call_user_func( $ktuehk_args['after'] );
		}
		?>
	</div>
</header>
