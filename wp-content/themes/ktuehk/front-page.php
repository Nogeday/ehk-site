<?php
/**
 * Home page.
 *
 * Sections: hero, featured projects, latest technical articles, events,
 * focus areas and a call-to-action band. Every list is filled from
 * WordPress content and updates automatically. Sections without content
 * are hidden for visitors (editors see a hint with an "add" link instead).
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

$ktuehk_project_type = ktuehk_project_type();
$ktuehk_event_type   = ktuehk_event_type();
$ktuehk_has_projects = post_type_exists( $ktuehk_project_type );
$ktuehk_has_events   = post_type_exists( $ktuehk_event_type );
$ktuehk_projects_url = $ktuehk_has_projects ? (string) get_post_type_archive_link( $ktuehk_project_type ) : '';
$ktuehk_events_url   = $ktuehk_has_events ? (string) get_post_type_archive_link( $ktuehk_event_type ) : '';
$ktuehk_posts_url    = ktuehk_posts_url();
$ktuehk_about        = ktuehk_about_page();
$ktuehk_can_edit     = current_user_can( 'edit_posts' );
$ktuehk_title_lines  = preg_split( '/\r\n|\r|\n/', trim( ktuehk_mod( 'hero_title' ) ) );
?>

<section class="hero" aria-labelledby="hero-title">
	<div class="container hero__grid">
		<div class="hero__content">
			<p class="eyebrow"><?php echo esc_html( ktuehk_mod( 'hero_eyebrow' ) ); ?></p>
			<h1 class="hero__title" id="hero-title"><?php echo implode( '<br>', array_map( 'esc_html', $ktuehk_title_lines ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- each line escaped. ?></h1>
			<p class="hero__lead"><?php echo esc_html( ktuehk_mod( 'hero_text' ) ); ?></p>
			<div class="hero__actions">
				<?php if ( $ktuehk_projects_url ) : ?>
					<a class="btn btn--primary btn--lg" href="<?php echo esc_url( $ktuehk_projects_url ); ?>">
						<?php esc_html_e( 'Projeleri Keşfet', 'ktuehk' ); ?>
						<?php echo ktuehk_icon( 'arrow-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				<?php endif; ?>
				<a class="btn btn--outline btn--lg" href="<?php echo esc_url( $ktuehk_posts_url ); ?>">
					<?php esc_html_e( 'Teknik Yazıları Oku', 'ktuehk' ); ?>
					<?php echo ktuehk_icon( 'arrow-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
			</div>
		</div>
		<div class="hero__visual">
			<?php get_template_part( 'template-parts/hero-visual', null, array( 'part' => 'illustration' ) ); ?>
		</div>
	</div>
</section>

<?php
// Featured projects: those marked "öne çıkan" first, then the latest ones.
if ( $ktuehk_has_projects ) :
	$ktuehk_projects = get_posts(
		array(
			'post_type'      => $ktuehk_project_type,
			'posts_per_page' => 3,
			'meta_key'       => '_ehk_one_cikan', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
			'no_found_rows'  => true,
		)
	);
	if ( count( $ktuehk_projects ) < 3 ) {
		$ktuehk_projects = array_merge(
			$ktuehk_projects,
			get_posts(
				array(
					'post_type'      => $ktuehk_project_type,
					'posts_per_page' => 3 - count( $ktuehk_projects ),
					'post__not_in'   => wp_list_pluck( $ktuehk_projects, 'ID' ),
					'no_found_rows'  => true,
				)
			)
		);
	}
	if ( $ktuehk_projects || $ktuehk_can_edit ) :
		?>
		<section class="section section--alt" aria-labelledby="projects-title">
			<div class="container">
				<?php
				ktuehk_section_head(
					array(
						'eyebrow'   => __( 'PROJELER', 'ktuehk' ),
						'title'     => __( 'Öne Çıkan Projeler', 'ktuehk' ),
						'id'        => 'projects-title',
						'link'      => $ktuehk_projects ? $ktuehk_projects_url : '',
						'link_text' => __( 'Tüm Projeleri Gör', 'ktuehk' ),
					)
				);
				if ( $ktuehk_projects ) :
					?>
					<div class="grid grid--3">
						<?php
						global $post;
						foreach ( $ktuehk_projects as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride
							setup_postdata( $post );
							get_template_part( 'template-parts/card', 'project' );
						endforeach;
						wp_reset_postdata();
						?>
					</div>
				<?php else : ?>
					<?php
					get_template_part(
						'template-parts/empty-state',
						null,
						array(
							'icon'       => 'cpu',
							'title'      => __( 'Henüz proje eklenmedi', 'ktuehk' ),
							'text'       => __( 'Bu bölüm yalnızca yöneticilere görünüyor; proje eklendiğinde ziyaretçilere gösterilecek.', 'ktuehk' ),
							'admin_link' => admin_url( 'post-new.php?post_type=' . $ktuehk_project_type ),
							'admin_text' => __( 'Yeni proje ekle', 'ktuehk' ),
						)
					);
					?>
				<?php endif; ?>
			</div>
		</section>
		<?php
	endif;
endif;

$ktuehk_posts = get_posts(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 4,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
if ( $ktuehk_posts || $ktuehk_can_edit ) :
	?>
	<section class="section" aria-labelledby="posts-title">
		<div class="container">
			<?php
			ktuehk_section_head(
				array(
					'eyebrow'   => __( 'TEKNİK YAZILAR', 'ktuehk' ),
					'title'     => __( 'Son Teknik Yazılar', 'ktuehk' ),
					'id'        => 'posts-title',
					'link'      => $ktuehk_posts ? $ktuehk_posts_url : '',
					'link_text' => __( 'Tüm Yazıları Gör', 'ktuehk' ),
				)
			);
			if ( $ktuehk_posts ) :
				?>
				<div class="grid grid--4">
					<?php
					global $post;
					foreach ( $ktuehk_posts as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride
						setup_postdata( $post );
						get_template_part( 'template-parts/card', 'post', array( 'sizes' => '(min-width: 1200px) 290px, (min-width: 640px) 50vw, 100vw' ) );
					endforeach;
					wp_reset_postdata();
					?>
				</div>
			<?php else : ?>
				<?php
				get_template_part(
					'template-parts/empty-state',
					null,
					array(
						'icon'       => 'file-text',
						'title'      => __( 'Henüz yazı yayımlanmadı', 'ktuehk' ),
						'text'       => __( 'Bu bölüm yalnızca yöneticilere görünüyor.', 'ktuehk' ),
						'admin_link' => admin_url( 'post-new.php' ),
						'admin_text' => __( 'Yeni yazı ekle', 'ktuehk' ),
					)
				);
				?>
			<?php endif; ?>
		</div>
	</section>
	<?php
endif;

// Events: upcoming first (soonest first), filled up with the most recent past ones.
if ( $ktuehk_has_events && function_exists( 'ktuehk_upcoming_events_args' ) ) :
	$ktuehk_upcoming = get_posts( ktuehk_upcoming_events_args( array( 'posts_per_page' => 4, 'no_found_rows' => true ) ) );
	$ktuehk_events   = $ktuehk_upcoming;
	if ( count( $ktuehk_events ) < 4 ) {
		$ktuehk_events = array_merge(
			$ktuehk_events,
			get_posts(
				ktuehk_past_events_args(
					array(
						'posts_per_page' => 4 - count( $ktuehk_events ),
						'no_found_rows'  => true,
					)
				)
			)
		);
	}
	if ( count( $ktuehk_upcoming ) === count( $ktuehk_events ) ) {
		$ktuehk_events_title = __( 'Yaklaşan Etkinlikler', 'ktuehk' );
	} elseif ( $ktuehk_upcoming ) {
		$ktuehk_events_title = __( 'Yaklaşan ve Son Etkinlikler', 'ktuehk' );
	} else {
		$ktuehk_events_title = __( 'Son Etkinlikler', 'ktuehk' );
	}
	if ( $ktuehk_events || $ktuehk_can_edit ) :
		?>
		<section class="section section--alt" aria-labelledby="events-title">
			<div class="container">
				<?php
				ktuehk_section_head(
					array(
						'eyebrow'   => __( 'ETKİNLİKLER', 'ktuehk' ),
						'title'     => $ktuehk_events ? $ktuehk_events_title : __( 'Etkinlikler', 'ktuehk' ),
						'id'        => 'events-title',
						'link'      => $ktuehk_events ? $ktuehk_events_url : '',
						'link_text' => __( 'Tüm Etkinlikleri Gör', 'ktuehk' ),
					)
				);
				if ( $ktuehk_events ) :
					?>
					<div class="grid grid--4">
						<?php
						global $post;
						foreach ( $ktuehk_events as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride
							setup_postdata( $post );
							get_template_part( 'template-parts/card', 'event-compact' );
						endforeach;
						wp_reset_postdata();
						?>
					</div>
				<?php else : ?>
					<?php
					get_template_part(
						'template-parts/empty-state',
						null,
						array(
							'icon'       => 'calendar-days',
							'title'      => __( 'Henüz etkinlik eklenmedi', 'ktuehk' ),
							'text'       => __( 'Bu bölüm yalnızca yöneticilere görünüyor.', 'ktuehk' ),
							'admin_link' => admin_url( 'post-new.php?post_type=' . $ktuehk_event_type ),
							'admin_text' => __( 'Yeni etkinlik ekle', 'ktuehk' ),
						)
					);
					?>
				<?php endif; ?>
			</div>
		</section>
		<?php
	endif;
endif;
?>

<section class="section" aria-labelledby="areas-title">
	<div class="container">
		<?php
		ktuehk_section_head(
			array(
				'eyebrow' => __( 'ALANLARIMIZ', 'ktuehk' ),
				'title'   => __( 'Çalışma Alanlarımız', 'ktuehk' ),
				'id'      => 'areas-title',
			)
		);
		get_template_part( 'template-parts/section', 'areas' );
		?>
	</div>
</section>

<?php get_template_part( 'template-parts/cta-band' ); ?>

<?php
get_footer();
