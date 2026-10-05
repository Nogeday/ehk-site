<?php
/**
 * Not found.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

$ktuehk_links = array_filter(
	array(
		array(
			'url'   => ktuehk_posts_url(),
			'label' => __( 'Teknik yazılar', 'ktuehk' ),
			'icon'  => 'file-text',
		),
		array(
			'url'   => ktuehk_type_available( 'project' ) ? (string) get_post_type_archive_link( ktuehk_project_type() ) : '',
			'label' => __( 'Projeler', 'ktuehk' ),
			'icon'  => 'cpu',
		),
		array(
			'url'   => ktuehk_type_available( 'event' ) ? (string) get_post_type_archive_link( ktuehk_event_type() ) : '',
			'label' => __( 'Etkinlikler', 'ktuehk' ),
			'icon'  => 'calendar-days',
		),
		array(
			'url'   => home_url( '/' ),
			'label' => __( 'Ana sayfa', 'ktuehk' ),
			'icon'  => 'house',
		),
	),
	static function ( $link ) {
		return '' !== $link['url'];
	}
);
?>
<section class="page-hero page-hero--404">
	<div class="container page-hero__inner">
		<p class="eyebrow"><?php esc_html_e( 'Hata 404', 'ktuehk' ); ?></p>
		<h1 class="page-hero__title"><?php esc_html_e( 'Aradığınız sayfa bulunamadı', 'ktuehk' ); ?></h1>
		<p class="page-hero__desc"><?php esc_html_e( 'Bağlantı değişmiş veya sayfa kaldırılmış olabilir. Aramayı deneyin ya da aşağıdaki bölümlerden devam edin.', 'ktuehk' ); ?></p>
		<?php
		get_search_form(
			array(
				'size'       => 'large',
				'aria_label' => __( 'Aradığınızı bulun', 'ktuehk' ),
			)
		);
		?>
	</div>
</section>

<div class="section section--tight">
	<div class="container">
		<ul class="quick-links">
			<?php foreach ( $ktuehk_links as $ktuehk_link ) : ?>
				<li>
					<a class="quick-link" href="<?php echo esc_url( $ktuehk_link['url'] ); ?>">
						<span class="area__icon"><?php echo ktuehk_icon( $ktuehk_link['icon'], 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span><?php echo esc_html( $ktuehk_link['label'] ); ?></span>
						<?php echo ktuehk_icon( 'arrow-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php
get_footer();
