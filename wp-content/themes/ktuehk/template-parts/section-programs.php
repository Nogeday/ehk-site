<?php
/**
 * Competitions / programs with their project counts and links.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

if ( ! taxonomy_exists( 'ehk_program' ) ) {
	return;
}
$ktuehk_programs = get_terms(
	array(
		'taxonomy'   => 'ehk_program',
		'hide_empty' => false,
		'orderby'    => 'count',
		'order'      => 'DESC',
		'number'     => 8,
	)
);
if ( ! $ktuehk_programs || is_wp_error( $ktuehk_programs ) ) {
	return;
}
?>
<ul class="programs">
	<?php foreach ( $ktuehk_programs as $ktuehk_program ) : ?>
		<li class="program">
			<span class="program__icon"><?php echo ktuehk_icon( false !== stripos( $ktuehk_program->slug, 'teknofest' ) ? 'rocket' : ( false !== stripos( $ktuehk_program->slug, 'tubitak' ) ? 'flask-conical' : 'boxes' ), 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<span class="program__name">
				<?php if ( $ktuehk_program->count ) : ?>
					<a href="<?php echo esc_url( get_term_link( $ktuehk_program ) ); ?>"><?php echo esc_html( $ktuehk_program->name ); ?></a>
				<?php else : ?>
					<?php echo esc_html( $ktuehk_program->name ); ?>
				<?php endif; ?>
			</span>
			<span class="program__count">
				<?php
				echo esc_html(
					$ktuehk_program->count
						/* translators: %d: number of projects */
						? sprintf( _n( '%d proje', '%d proje', $ktuehk_program->count, 'ktuehk' ), $ktuehk_program->count )
						: __( 'Yakında', 'ktuehk' )
				);
				?>
			</span>
		</li>
	<?php endforeach; ?>
</ul>
