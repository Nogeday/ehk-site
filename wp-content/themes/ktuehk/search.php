<?php
/**
 * Search results with content type tabs.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $wp_query;
$ktuehk_query     = get_search_query( false );
$ktuehk_types     = ktuehk_search_types();
$ktuehk_requested = get_query_var( 'post_type' );
$ktuehk_active    = is_string( $ktuehk_requested ) && isset( $ktuehk_types[ $ktuehk_requested ] ) ? $ktuehk_requested : '';

// Result counts per content type for the tabs.
$ktuehk_counts = array();
if ( '' !== $ktuehk_query ) {
	foreach ( array_keys( $ktuehk_types ) as $ktuehk_type ) {
		$ktuehk_count_query            = new WP_Query(
			array(
				's'                      => $ktuehk_query,
				'post_type'              => $ktuehk_type,
				'post_status'            => 'publish',
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);
		$ktuehk_counts[ $ktuehk_type ] = (int) $ktuehk_count_query->found_posts;
	}
}
$ktuehk_total = array_sum( $ktuehk_counts );

$ktuehk_icons = array(
	'post'                => 'file-text',
	ktuehk_project_type() => 'cpu',
	ktuehk_event_type()   => 'calendar-days',
	'page'                => 'book-open',
);

get_template_part(
	'template-parts/page-header',
	null,
	array(
		'eyebrow' => __( 'ARAMA', 'ktuehk' ),
		/* translators: %s: search query */
		'title'   => '' !== $ktuehk_query ? sprintf( __( '"%s" için sonuçlar', 'ktuehk' ), $ktuehk_query ) : __( 'Sitede ara', 'ktuehk' ),
		'class'   => 'page-hero--search',
		'after'   => static function () {
			get_search_form(
				array(
					'size'       => 'large',
					'aria_label' => __( 'Yeniden ara', 'ktuehk' ),
				)
			);
		},
	)
);
?>
<div class="section section--tight">
	<div class="container container--search">
		<?php if ( '' !== $ktuehk_query ) : ?>
			<nav class="tabs chips-wrap" aria-label="<?php esc_attr_e( 'Sonuç türleri', 'ktuehk' ); ?>">
				<ul class="chips">
					<li>
						<a class="chip<?php echo '' === $ktuehk_active ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 's', rawurlencode( $ktuehk_query ), home_url( '/' ) ) ); ?>"<?php echo '' === $ktuehk_active ? ' aria-current="page"' : ''; ?>>
							<?php esc_html_e( 'Tümü', 'ktuehk' ); ?> <span class="chip__count"><?php echo esc_html( number_format_i18n( $ktuehk_total ) ); ?></span>
						</a>
					</li>
					<?php foreach ( $ktuehk_types as $ktuehk_type => $ktuehk_label ) : ?>
						<?php
						if ( empty( $ktuehk_counts[ $ktuehk_type ] ) && $ktuehk_active !== $ktuehk_type ) {
							continue;
						}
						?>
						<li>
							<a class="chip<?php echo $ktuehk_active === $ktuehk_type ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( array( 's' => rawurlencode( $ktuehk_query ), 'post_type' => $ktuehk_type ), home_url( '/' ) ) ); ?>"<?php echo $ktuehk_active === $ktuehk_type ? ' aria-current="page"' : ''; ?>>
								<?php echo esc_html( $ktuehk_label ); ?> <span class="chip__count"><?php echo esc_html( number_format_i18n( $ktuehk_counts[ $ktuehk_type ] ) ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<p class="results-count" role="status">
				<?php
				/* translators: %d: number of results */
				echo esc_html( sprintf( _n( '%d sonuç bulundu', '%d sonuç bulundu', (int) $wp_query->found_posts, 'ktuehk' ), (int) $wp_query->found_posts ) );
				?>
			</p>
			<ol class="results">
				<?php
				while ( have_posts() ) :
					the_post();
					$ktuehk_type   = get_post_type();
					$ktuehk_label  = isset( $ktuehk_types[ $ktuehk_type ] ) ? $ktuehk_types[ $ktuehk_type ] : '';
					$ktuehk_detail = '';
					if ( 'post' === $ktuehk_type ) {
						$ktuehk_cat    = ktuehk_primary_term( 'category' );
						$ktuehk_detail = $ktuehk_cat ? $ktuehk_cat->name : '';
					} elseif ( ktuehk_project_type() === $ktuehk_type ) {
						$ktuehk_program = ktuehk_primary_term( 'ehk_program' );
						$ktuehk_detail  = trim( ( $ktuehk_program ? $ktuehk_program->name : '' ) . ' ' . ktuehk_field( 'yil' ) );
					} elseif ( ktuehk_event_type() === $ktuehk_type ) {
						$ktuehk_detail = ktuehk_event_when();
					}
					?>
					<li <?php post_class( 'result' ); ?>>
						<div class="result__body">
							<p class="result__type">
								<?php echo ktuehk_icon( isset( $ktuehk_icons[ $ktuehk_type ] ) ? $ktuehk_icons[ $ktuehk_type ] : 'file-text', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<span><?php echo esc_html( $ktuehk_label ); ?></span>
								<?php if ( $ktuehk_detail ) : ?>
									<span class="result__detail"><?php echo esc_html( $ktuehk_detail ); ?></span>
								<?php endif; ?>
							</p>
							<h2 class="result__title"><a href="<?php the_permalink(); ?>"><?php echo ktuehk_highlight( get_the_title(), $ktuehk_query ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper. ?></a></h2>
							<p class="result__excerpt"><?php echo ktuehk_highlight( ktuehk_search_snippet( get_post(), $ktuehk_query ), $ktuehk_query ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper. ?></p>
							<?php if ( 'post' === $ktuehk_type ) : ?>
								<?php ktuehk_post_meta( array( 'author', 'date', 'reading' ) ); ?>
							<?php endif; ?>
						</div>
						<?php if ( has_post_thumbnail() ) : ?>
							<?php
							echo ktuehk_media( // phpcs:ignore WordPress.Security.EscapeOutput
								array(
									'size'  => 'medium',
									'sizes' => '160px',
									'class' => 'result__thumb',
								)
							);
							?>
						<?php endif; ?>
					</li>
				<?php endwhile; ?>
			</ol>
			<?php ktuehk_pagination(); ?>
		<?php else : ?>
			<?php
			get_template_part(
				'template-parts/empty-state',
				null,
				array(
					'icon'      => 'search',
					'title'     => '' !== $ktuehk_query ? __( 'Sonuç bulunamadı', 'ktuehk' ) : __( 'Ne aramak istersiniz?', 'ktuehk' ),
					'text'      => '' !== $ktuehk_query ? __( 'Farklı veya daha genel kelimeler deneyin: örneğin "anten", "STM32", "PCB" ya da "ağ".', 'ktuehk' ) : __( 'Yazılar, projeler ve etkinlikler içinde arama yapabilirsiniz.', 'ktuehk' ),
					'link'      => ktuehk_posts_url(),
					'link_text' => __( 'Tüm yazılara göz at', 'ktuehk' ),
				)
			);
			?>
		<?php endif; ?>
	</div>
</div>
<?php
get_footer();
