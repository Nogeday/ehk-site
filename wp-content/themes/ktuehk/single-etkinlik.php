<?php
/**
 * Single event.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$ktuehk_date     = ktuehk_event_date();
	$ktuehk_when     = ktuehk_event_when();
	$ktuehk_upcoming = ktuehk_is_upcoming();
	$ktuehk_types    = get_the_terms( get_the_ID(), 'ehk_etkinlik_turu' );
	$ktuehk_place    = ktuehk_field( 'konum' );
	$ktuehk_map      = ktuehk_field( 'harita' );
	$ktuehk_online   = '1' === ktuehk_field( 'cevrimici' );
	$ktuehk_signup   = ktuehk_field( 'kayit' );
	$ktuehk_poster   = absint( ktuehk_field( 'afis' ) );
	$ktuehk_speakers = function_exists( 'ktuehk_field_lines' ) ? ktuehk_field_lines( 'konusmacilar' ) : array();
	$ktuehk_gallery  = function_exists( 'ktuehk_gallery_ids' ) ? ktuehk_gallery_ids() : array();
	$ktuehk_content  = ktuehk_prepare_content( false );
	?>
	<article <?php post_class( 'event ' . ( $ktuehk_upcoming ? 'is-upcoming' : 'is-past' ) ); ?>>
		<header class="page-hero page-hero--event">
			<div class="container page-hero__inner">
				<?php ktuehk_breadcrumbs(); ?>
				<div class="event-head">
					<?php if ( $ktuehk_date ) : ?>
						<time class="date-block" datetime="<?php echo esc_attr( $ktuehk_date['iso'] ); ?>">
							<span class="date-block__day"><?php echo esc_html( $ktuehk_date['day'] ); ?></span>
							<span class="date-block__month"><?php echo esc_html( $ktuehk_date['month'] ); ?></span>
							<span class="date-block__year"><?php echo esc_html( $ktuehk_date['year'] ); ?></span>
						</time>
					<?php endif; ?>
					<div class="event-head__text">
						<p class="card__badges">
							<?php if ( $ktuehk_upcoming ) : ?>
								<span class="badge badge--green"><?php esc_html_e( 'Yaklaşan etkinlik', 'ktuehk' ); ?></span>
							<?php elseif ( $ktuehk_date ) : ?>
								<span class="badge badge--gray"><?php esc_html_e( 'Gerçekleşti', 'ktuehk' ); ?></span>
							<?php endif; ?>
							<?php if ( $ktuehk_types && ! is_wp_error( $ktuehk_types ) ) : ?>
								<?php foreach ( $ktuehk_types as $ktuehk_type ) : ?>
									<a class="badge badge--blue" href="<?php echo esc_url( get_term_link( $ktuehk_type ) ); ?>"><?php echo esc_html( $ktuehk_type->name ); ?></a>
								<?php endforeach; ?>
							<?php endif; ?>
						</p>
						<h1 class="page-hero__title"><?php the_title(); ?></h1>
						<?php if ( has_excerpt() ) : ?>
							<p class="page-hero__desc"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
						<?php endif; ?>
						<ul class="meta meta--lg">
							<?php if ( $ktuehk_when ) : ?>
								<li class="meta__item"><?php echo ktuehk_icon( 'clock', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $ktuehk_when ); ?></li>
							<?php endif; ?>
							<?php if ( $ktuehk_place || $ktuehk_online ) : ?>
								<li class="meta__item"><?php echo ktuehk_icon( $ktuehk_online ? 'globe' : 'map-pin', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $ktuehk_place ? $ktuehk_place : __( 'Çevrim içi', 'ktuehk' ) ); ?></li>
							<?php endif; ?>
						</ul>
						<?php if ( $ktuehk_upcoming && ( $ktuehk_signup || $ktuehk_date ) ) : ?>
							<div class="page-hero__actions">
								<?php if ( $ktuehk_signup ) : ?>
									<a class="btn btn--primary" href="<?php echo esc_url( $ktuehk_signup ); ?>" target="_blank" rel="noopener">
										<?php echo ktuehk_icon( 'ticket', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
										<?php esc_html_e( 'Kayıt ol', 'ktuehk' ); ?>
									</a>
								<?php endif; ?>
								<?php if ( $ktuehk_date && function_exists( 'ktuehk_event_ics_url' ) ) : ?>
									<a class="btn btn--outline" href="<?php echo esc_url( ktuehk_event_ics_url() ); ?>" rel="nofollow">
										<?php echo ktuehk_icon( 'calendar-plus', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
										<?php esc_html_e( 'Takvime ekle', 'ktuehk' ); ?>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
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
			<aside class="doc-aside" aria-labelledby="facts-title">
				<div class="spec-card">
					<h2 class="spec-card__title" id="facts-title"><?php echo ktuehk_icon( 'calendar-days', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Etkinlik bilgileri', 'ktuehk' ); ?></h2>
					<dl class="spec">
						<div class="spec__row"><dt><?php esc_html_e( 'Tarih', 'ktuehk' ); ?></dt><dd><?php echo $ktuehk_when ? esc_html( $ktuehk_when ) : esc_html__( 'Duyurulacak', 'ktuehk' ); ?></dd></div>
						<?php if ( $ktuehk_place || $ktuehk_online ) : ?>
							<div class="spec__row">
								<dt><?php esc_html_e( 'Konum', 'ktuehk' ); ?></dt>
								<dd>
									<?php echo esc_html( $ktuehk_place ? $ktuehk_place : __( 'Çevrim içi', 'ktuehk' ) ); ?>
									<?php if ( $ktuehk_map ) : ?>
										<br><a class="link-arrow link-arrow--sm" href="<?php echo esc_url( $ktuehk_map ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Haritada aç', 'ktuehk' ); ?><?php echo ktuehk_icon( 'arrow-up-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
									<?php endif; ?>
								</dd>
							</div>
						<?php endif; ?>
						<?php if ( $ktuehk_types && ! is_wp_error( $ktuehk_types ) ) : ?>
							<div class="spec__row"><dt><?php esc_html_e( 'Tür', 'ktuehk' ); ?></dt><dd><?php echo esc_html( implode( ', ', wp_list_pluck( $ktuehk_types, 'name' ) ) ); ?></dd></div>
						<?php endif; ?>
						<?php if ( $ktuehk_signup && $ktuehk_upcoming ) : ?>
							<div class="spec__row"><dt><?php esc_html_e( 'Kayıt', 'ktuehk' ); ?></dt><dd><a href="<?php echo esc_url( $ktuehk_signup ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Kayıt formu', 'ktuehk' ); ?></a></dd></div>
						<?php endif; ?>
					</dl>

					<?php if ( $ktuehk_speakers ) : ?>
						<h3 class="spec-card__subtitle"><?php esc_html_e( 'Konuşmacılar', 'ktuehk' ); ?></h3>
						<ul class="team-list">
							<?php
							foreach ( $ktuehk_speakers as $ktuehk_line ) :
								$ktuehk_parts = preg_split( '/\s+[—–\-|]\s+/u', $ktuehk_line, 2 );
								?>
								<li class="team-list__item">
									<?php get_template_part( 'template-parts/author-avatar', null, array( 'author' => array( 'name' => $ktuehk_parts[0] ), 'size' => 32 ) ); ?>
									<span>
										<span class="team-list__name"><?php echo esc_html( $ktuehk_parts[0] ); ?></span>
										<?php if ( ! empty( $ktuehk_parts[1] ) ) : ?>
											<span class="team-list__role"><?php echo esc_html( $ktuehk_parts[1] ); ?></span>
										<?php endif; ?>
									</span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>

				<?php if ( $ktuehk_poster && wp_attachment_is_image( $ktuehk_poster ) ) : ?>
					<figure class="poster">
						<a href="<?php echo esc_url( wp_get_attachment_image_url( $ktuehk_poster, 'full' ) ); ?>" data-lightbox data-caption="<?php esc_attr_e( 'Etkinlik afişi', 'ktuehk' ); ?>">
							<?php
							echo wp_get_attachment_image(
								$ktuehk_poster,
								'medium_large',
								false,
								array(
									/* translators: %s: event title */
									'alt'      => sprintf( __( '%s etkinlik afişi', 'ktuehk' ), get_the_title() ),
									'loading'  => 'lazy',
									'decoding' => 'async',
									'sizes'    => '(min-width: 1024px) 340px, 100vw',
								)
							);
							?>
						</a>
						<figcaption><?php esc_html_e( 'Etkinlik afişi — büyütmek için tıklayın', 'ktuehk' ); ?></figcaption>
					</figure>
				<?php endif; ?>
			</aside>

			<div class="doc-main">
				<?php if ( ! $ktuehk_upcoming && $ktuehk_date ) : ?>
					<p class="notice-inline"><?php echo ktuehk_icon( 'calendar-check', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Bu etkinlik gerçekleşti. Etkinlikten notlar ve fotoğraflar aşağıda yer alıyor.', 'ktuehk' ); ?></p>
				<?php endif; ?>

				<?php if ( '' !== trim( $ktuehk_content['html'] ) ) : ?>
					<section class="doc-section" aria-labelledby="about-event">
						<h2 class="doc-section__title" id="about-event"><?php esc_html_e( 'Etkinlik hakkında', 'ktuehk' ); ?></h2>
						<div class="prose entry-content">
							<?php echo $ktuehk_content['html']; // phpcs:ignore WordPress.Security.EscapeOutput -- filtered post content. ?>
						</div>
					</section>
				<?php endif; ?>

				<?php if ( $ktuehk_gallery ) : ?>
					<section class="doc-section" aria-labelledby="event-gallery">
						<h2 class="doc-section__title" id="event-gallery"><?php esc_html_e( 'Fotoğraflar', 'ktuehk' ); ?></h2>
						<?php get_template_part( 'template-parts/gallery', null, array( 'ids' => $ktuehk_gallery ) ); ?>
					</section>
				<?php endif; ?>
			</div>
		</div>
	</article>

	<?php
	if ( function_exists( 'ktuehk_upcoming_events_args' ) ) :
		$ktuehk_more = get_posts(
			ktuehk_upcoming_events_args(
				array(
					'posts_per_page' => 3,
					'post__not_in'   => array( get_the_ID() ),
					'no_found_rows'  => true,
				)
			)
		);
		if ( count( $ktuehk_more ) < 3 ) {
			$ktuehk_more = array_merge(
				$ktuehk_more,
				get_posts(
					ktuehk_past_events_args(
						array(
							'posts_per_page' => 3 - count( $ktuehk_more ),
							'post__not_in'   => array_merge( array( get_the_ID() ), wp_list_pluck( $ktuehk_more, 'ID' ) ),
							'no_found_rows'  => true,
						)
					)
				)
			);
		}
		$ktuehk_current = $post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		if ( $ktuehk_more ) :
			?>
			<section class="section section--alt" aria-labelledby="more-events-title">
				<div class="container">
					<?php
					ktuehk_section_head(
						array(
							'eyebrow'   => __( 'ETKİNLİKLER', 'ktuehk' ),
							'title'     => __( 'Diğer Etkinlikler', 'ktuehk' ),
							'id'        => 'more-events-title',
							'link'      => get_post_type_archive_link( ktuehk_event_type() ),
							'link_text' => __( 'Tüm Etkinlikleri Gör', 'ktuehk' ),
						)
					);
					?>
					<div class="grid grid--3">
						<?php
						foreach ( $ktuehk_more as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride
							setup_postdata( $post );
							get_template_part( 'template-parts/card', 'event-compact' );
						endforeach;
						$post = $ktuehk_current; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
						setup_postdata( $post );
						?>
					</div>
				</div>
			</section>
			<?php
		endif;
	endif;
endwhile;

get_footer();
