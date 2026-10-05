<?php
/**
 * Category filter chips for article listings.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_categories = get_categories(
	array(
		'orderby'    => 'count',
		'order'      => 'DESC',
		'hide_empty' => true,
		'number'     => (int) apply_filters( 'ktuehk_category_chip_limit', 14 ),
	)
);
if ( count( $ktuehk_categories ) < 2 ) {
	return;
}
$ktuehk_current = is_category() ? get_queried_object_id() : 0;
$ktuehk_total   = (int) wp_count_posts( 'post' )->publish;
?>
<nav class="chips-wrap" aria-label="<?php esc_attr_e( 'Kategoriler', 'ktuehk' ); ?>">
	<ul class="chips">
		<li>
			<a class="chip<?php echo $ktuehk_current ? '' : ' is-active'; ?>" href="<?php echo esc_url( ktuehk_posts_url() ); ?>"<?php echo $ktuehk_current ? '' : ' aria-current="page"'; ?>>
				<?php esc_html_e( 'Tümü', 'ktuehk' ); ?> <span class="chip__count"><?php echo esc_html( number_format_i18n( $ktuehk_total ) ); ?></span>
			</a>
		</li>
		<?php foreach ( $ktuehk_categories as $ktuehk_category ) : ?>
			<?php $ktuehk_active = $ktuehk_current === $ktuehk_category->term_id; ?>
			<li>
				<a class="chip<?php echo $ktuehk_active ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_category_link( $ktuehk_category ) ); ?>"<?php echo $ktuehk_active ? ' aria-current="page"' : ''; ?>>
					<?php echo esc_html( $ktuehk_category->name ); ?> <span class="chip__count"><?php echo esc_html( number_format_i18n( $ktuehk_category->count ) ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
