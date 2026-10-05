<?php
/**
 * Single project.
 *
 * Structured like a short technical report: summary and key facts first,
 * then problem → goal → details → process → results → gallery.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$ktuehk_programs = get_the_terms( get_the_ID(), 'ehk_program' );
	$ktuehk_areas    = get_the_terms( get_the_ID(), 'ehk_alan' );
	$ktuehk_status   = function_exists( 'ktuehk_project_status' ) ? ktuehk_project_status() : null;
	$ktuehk_year     = ktuehk_field( 'yil' );
	$ktuehk_award    = ktuehk_field( 'basari' );
	$ktuehk_advisor  = ktuehk_field( 'danisman' );
	$ktuehk_team     = function_exists( 'ktuehk_project_team' ) ? ktuehk_project_team() : array();
	$ktuehk_tech     = function_exists( 'ktuehk_field_list' ) ? ktuehk_field_list( 'teknolojiler' ) : array();
	$ktuehk_hardware = function_exists( 'ktuehk_field_lines' ) ? ktuehk_field_lines( 'donanim' ) : array();
	$ktuehk_software = function_exists( 'ktuehk_field_lines' ) ? ktuehk_field_lines( 'yazilim' ) : array();
	$ktuehk_gallery  = function_exists( 'ktuehk_gallery_ids' ) ? ktuehk_gallery_ids() : array();
	$ktuehk_content  = ktuehk_prepare_content( false );
	$ktuehk_links    = array_filter(
		array(
			array(
				'url'   => ktuehk_field( 'github' ),
				'label' => __( 'GitHub', 'ktuehk' ),
				'icon'  => 'brand-github',
			),
			array(
				'url'   => ktuehk_field( 'dokuman' ),
				'label' => __( 'Dokümantasyon', 'ktuehk' ),
				'icon'  => 'file-text',
			),
			array(
				'url'   => ktuehk_field( 'demo' ),
				'label' => __( 'Demo / Video', 'ktuehk' ),
				'icon'  => 'circle-play',
			),
		),
		static function ( $link ) {
			return '' !== $link['url'];
		}
	);

	$ktuehk_sections = array();
	foreach (
		array(
			'problem'  => __( 'Problem', 'ktuehk' ),
			'amac'     => __( 'Amaç', 'ktuehk' ),
			'content'  => __( 'Proje detayları', 'ktuehk' ),
			'surec'    => __( 'Süreç', 'ktuehk' ),
			'sonuclar' => __( 'Sonuçlar', 'ktuehk' ),
		) as $ktuehk_key => $ktuehk_label
	) {
		$ktuehk_html = 'content' === $ktuehk_key ? trim( $ktuehk_content['html'] ) : ( '' !== ktuehk_field( $ktuehk_key ) ? ktuehk_format_text( ktuehk_field( $ktuehk_key ) ) : '' );
		if ( '' !== $ktuehk_html ) {
			$ktuehk_sections[ $ktuehk_key ] = array(
				'title' => $ktuehk_label,
				'html'  => $ktuehk_html,
			);
		}
	}
	if ( $ktuehk_gallery ) {
		$ktuehk_sections['galeri'] = array(
			'title' => __( 'Proje galerisi', 'ktuehk' ),
			'html'  => '',
		);
	}
	?>
	<article <?php post_class( 'project' ); ?>>
		<header class="page-hero page-hero--project">
			<div class="container page-hero__inner">
				<?php ktuehk_breadcrumbs(); ?>
				<?php if ( ( $ktuehk_programs && ! is_wp_error( $ktuehk_programs ) ) || $ktuehk_status ) : ?>
					<p class="card__badges">
						<?php if ( $ktuehk_programs && ! is_wp_error( $ktuehk_programs ) ) : ?>
							<?php foreach ( $ktuehk_programs as $ktuehk_program ) : ?>
								<a class="badge badge--solid" href="<?php echo esc_url( get_term_link( $ktuehk_program ) ); ?>"><?php echo esc_html( $ktuehk_program->name ); ?></a>
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if ( $ktuehk_status ) : ?>
							<span class="pill pill--<?php echo esc_attr( $ktuehk_status['key'] ); ?>"><?php echo esc_html( $ktuehk_status['label'] ); ?></span>
						<?php endif; ?>
					</p>
				<?php endif; ?>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="page-hero__desc"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
				<?php endif; ?>
				<?php if ( $ktuehk_award ) : ?>
					<p class="award"><?php echo ktuehk_icon( 'trophy', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $ktuehk_award ); ?></p>
				<?php endif; ?>
				<?php if ( $ktuehk_links ) : ?>
					<div class="page-hero__actions">
						<?php foreach ( array_values( $ktuehk_links ) as $ktuehk_i => $ktuehk_link ) : ?>
							<a class="btn <?php echo 0 === $ktuehk_i ? 'btn--primary' : 'btn--outline'; ?>" href="<?php echo esc_url( $ktuehk_link['url'] ); ?>" target="_blank" rel="noopener">
								<?php echo ktuehk_icon( $ktuehk_link['icon'], 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php echo esc_html( $ktuehk_link['label'] ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="container cover">
				<?php
				echo ktuehk_media( // phpcs:ignore WordPress.Security.EscapeOutput
					array(
						'size'     => 'large',
						'sizes'    => '(min-width: 1240px) 1200px, 100vw',
						'class'    => 'cover__media',
						'priority' => true,
					)
				);
				?>
			</figure>
		<?php endif; ?>

		<div class="container doc-layout">
			<aside class="doc-aside" aria-labelledby="spec-title">
				<div class="spec-card">
					<h2 class="spec-card__title" id="spec-title"><?php echo ktuehk_icon( 'layers', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Proje künyesi', 'ktuehk' ); ?></h2>
					<dl class="spec">
						<?php if ( $ktuehk_programs && ! is_wp_error( $ktuehk_programs ) ) : ?>
							<div class="spec__row"><dt><?php esc_html_e( 'Program', 'ktuehk' ); ?></dt><dd><?php echo esc_html( implode( ', ', wp_list_pluck( $ktuehk_programs, 'name' ) ) ); ?></dd></div>
						<?php endif; ?>
						<?php if ( $ktuehk_year ) : ?>
							<div class="spec__row"><dt><?php esc_html_e( 'Yıl', 'ktuehk' ); ?></dt><dd><?php echo esc_html( $ktuehk_year ); ?></dd></div>
						<?php endif; ?>
						<?php if ( $ktuehk_status ) : ?>
							<div class="spec__row"><dt><?php esc_html_e( 'Durum', 'ktuehk' ); ?></dt><dd><span class="pill pill--<?php echo esc_attr( $ktuehk_status['key'] ); ?>"><?php echo esc_html( $ktuehk_status['label'] ); ?></span></dd></div>
						<?php endif; ?>
						<?php if ( $ktuehk_areas && ! is_wp_error( $ktuehk_areas ) ) : ?>
							<div class="spec__row">
								<dt><?php esc_html_e( 'Kategori', 'ktuehk' ); ?></dt>
								<dd>
									<?php
									$ktuehk_area_links = array();
									foreach ( $ktuehk_areas as $ktuehk_area ) {
										$ktuehk_area_links[] = '<a href="' . esc_url( get_term_link( $ktuehk_area ) ) . '">' . esc_html( $ktuehk_area->name ) . '</a>';
									}
									echo implode( ', ', $ktuehk_area_links ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
									?>
								</dd>
							</div>
						<?php endif; ?>
						<?php if ( $ktuehk_advisor ) : ?>
							<div class="spec__row"><dt><?php esc_html_e( 'Danışman', 'ktuehk' ); ?></dt><dd><?php echo esc_html( $ktuehk_advisor ); ?></dd></div>
						<?php endif; ?>
					</dl>

					<?php if ( $ktuehk_tech ) : ?>
						<h3 class="spec-card__subtitle"><?php esc_html_e( 'Kullanılan teknolojiler', 'ktuehk' ); ?></h3>
						<ul class="tag-list">
							<?php foreach ( $ktuehk_tech as $ktuehk_item ) : ?>
								<li class="tag tag--mono"><?php echo esc_html( $ktuehk_item ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php
					foreach (
						array(
							array( __( 'Donanım', 'ktuehk' ), $ktuehk_hardware, 'cpu' ),
							array( __( 'Yazılım', 'ktuehk' ), $ktuehk_software, 'code-xml' ),
						) as $ktuehk_list
					) :
						if ( ! $ktuehk_list[1] ) {
							continue;
						}
						?>
						<h3 class="spec-card__subtitle"><?php echo esc_html( $ktuehk_list[0] ); ?></h3>
						<ul class="spec-list">
							<?php foreach ( $ktuehk_list[1] as $ktuehk_item ) : ?>
								<li><?php echo ktuehk_icon( $ktuehk_list[2], 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $ktuehk_item ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endforeach; ?>

					<?php if ( $ktuehk_team ) : ?>
						<h3 class="spec-card__subtitle"><?php esc_html_e( 'Ekip', 'ktuehk' ); ?></h3>
						<ul class="team-list">
							<?php foreach ( $ktuehk_team as $ktuehk_member ) : ?>
								<li class="team-list__item">
									<?php get_template_part( 'template-parts/author-avatar', null, array( 'author' => array( 'name' => $ktuehk_member['name'] ), 'size' => 32 ) ); ?>
									<span>
										<span class="team-list__name"><?php echo esc_html( $ktuehk_member['name'] ); ?></span>
										<?php if ( $ktuehk_member['role'] ) : ?>
											<span class="team-list__role"><?php echo esc_html( $ktuehk_member['role'] ); ?></span>
										<?php endif; ?>
									</span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</aside>

			<div class="doc-main">
				<?php if ( $ktuehk_sections ) : ?>
					<?php
					$ktuehk_n = 0;
					foreach ( $ktuehk_sections as $ktuehk_key => $ktuehk_section ) :
						++$ktuehk_n;
						?>
						<section class="doc-section" aria-labelledby="section-<?php echo esc_attr( $ktuehk_key ); ?>">
							<h2 class="doc-section__title" id="section-<?php echo esc_attr( $ktuehk_key ); ?>">
								<span class="doc-section__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $ktuehk_n ) ); ?></span>
								<?php echo esc_html( $ktuehk_section['title'] ); ?>
							</h2>
							<?php if ( 'galeri' === $ktuehk_key ) : ?>
								<?php get_template_part( 'template-parts/gallery', null, array( 'ids' => $ktuehk_gallery ) ); ?>
							<?php else : ?>
								<div class="prose entry-content">
									<?php echo $ktuehk_section['html']; // phpcs:ignore WordPress.Security.EscapeOutput -- sanitized on save / filtered content. ?>
								</div>
							<?php endif; ?>
						</section>
					<?php endforeach; ?>
				<?php else : ?>
					<p class="muted"><?php esc_html_e( 'Proje ayrıntıları yakında eklenecek.', 'ktuehk' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</article>

	<?php
	$ktuehk_related_args = array(
		'post_type'      => ktuehk_project_type(),
		'posts_per_page' => 3,
		'post__not_in'   => array( get_the_ID() ),
		'no_found_rows'  => true,
	);
	if ( $ktuehk_areas && ! is_wp_error( $ktuehk_areas ) ) {
		$ktuehk_related_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
			array(
				'taxonomy' => 'ehk_alan',
				'terms'    => wp_list_pluck( $ktuehk_areas, 'term_id' ),
			),
		);
	}
	$ktuehk_related = get_posts( $ktuehk_related_args );
	if ( ! $ktuehk_related && isset( $ktuehk_related_args['tax_query'] ) ) {
		unset( $ktuehk_related_args['tax_query'] );
		$ktuehk_related = get_posts( $ktuehk_related_args );
	}
	$ktuehk_current = $post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
	if ( $ktuehk_related ) :
		?>
		<section class="section section--alt" aria-labelledby="related-title">
			<div class="container">
				<?php
				ktuehk_section_head(
					array(
						'eyebrow'   => __( 'PROJELER', 'ktuehk' ),
						'title'     => __( 'Benzer Projeler', 'ktuehk' ),
						'id'        => 'related-title',
						'link'      => get_post_type_archive_link( ktuehk_project_type() ),
						'link_text' => __( 'Tüm Projeleri Gör', 'ktuehk' ),
					)
				);
				?>
				<div class="grid grid--3">
					<?php
					foreach ( $ktuehk_related as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride
						setup_postdata( $post );
						get_template_part( 'template-parts/card', 'project' );
					endforeach;
					$post = $ktuehk_current; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
					setup_postdata( $post );
					?>
				</div>
			</div>
		</section>
		<?php
	endif;
endwhile;

get_footer();
