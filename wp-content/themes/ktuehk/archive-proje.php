<?php
/**
 * Projects archive with filters (year, program, area, status).
 * Also used for program / area term archives.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $wp_query;
$ktuehk_type    = ktuehk_project_type();
$ktuehk_archive = (string) get_post_type_archive_link( $ktuehk_type );
$ktuehk_term    = is_tax( array( 'ehk_program', 'ehk_alan' ) ) ? get_queried_object() : null;

$ktuehk_current = array(
	'program' => (string) get_query_var( 'program' ),
	'alan'    => (string) get_query_var( 'alan' ),
	'yil'     => absint( get_query_var( 'yil' ) ),
	'durum'   => sanitize_key( (string) get_query_var( 'durum' ) ),
);
$ktuehk_filtered = $ktuehk_current['program'] || $ktuehk_current['alan'] || $ktuehk_current['yil'] || $ktuehk_current['durum'];

$ktuehk_title = $ktuehk_term ? $ktuehk_term->name : __( 'Projeler', 'ktuehk' );
$ktuehk_desc  = $ktuehk_term && $ktuehk_term->description ? wp_strip_all_tags( $ktuehk_term->description ) : ktuehk_mod( 'intro_projects' );

get_template_part(
	'template-parts/page-header',
	null,
	array(
		'eyebrow' => $ktuehk_term ? ( 'ehk_program' === $ktuehk_term->taxonomy ? __( 'Yarışma / Program', 'ktuehk' ) : __( 'Proje alanı', 'ktuehk' ) ) : __( 'Öğrenci projeleri', 'ktuehk' ),
		'title'   => $ktuehk_title,
		'desc'    => $ktuehk_desc,
	)
);

$ktuehk_select = static function ( $name, $label, $options, $current ) {
	$id = 'filter-' . $name;
	echo '<div class="filters__field">';
	echo '<label class="filters__label" for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
	echo '<span class="select"><select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '">';
	echo '<option value="">' . esc_html__( 'Tümü', 'ktuehk' ) . '</option>';
	foreach ( $options as $value => $text ) {
		printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $value ), selected( (string) $current, (string) $value, false ), esc_html( $text ) );
	}
	echo '</select>' . ktuehk_icon( 'chevron-down', 16 ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '</div>';
};

$ktuehk_term_options = static function ( $taxonomy ) {
	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
		)
	);
	return is_wp_error( $terms ) ? array() : wp_list_pluck( $terms, 'name', 'slug' );
};

$ktuehk_years    = function_exists( 'ktuehk_project_years' ) ? ktuehk_project_years() : array();
$ktuehk_statuses = function_exists( 'ktuehk_project_statuses' ) ? ktuehk_project_statuses() : array();
?>
<div class="section section--tight">
	<div class="container">
		<form class="filters" method="get" action="<?php echo esc_url( $ktuehk_archive ); ?>" data-clean-submit aria-label="<?php esc_attr_e( 'Projeleri filtrele', 'ktuehk' ); ?>">
			<span class="filters__title"><?php echo ktuehk_icon( 'sliders-horizontal', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Filtrele', 'ktuehk' ); ?></span>
			<?php
			$ktuehk_select( 'program', __( 'Yarışma / Program', 'ktuehk' ), $ktuehk_term_options( 'ehk_program' ), $ktuehk_current['program'] );
			$ktuehk_select( 'alan', __( 'Alan', 'ktuehk' ), $ktuehk_term_options( 'ehk_alan' ), $ktuehk_current['alan'] );
			$ktuehk_select( 'yil', __( 'Yıl', 'ktuehk' ), array_combine( $ktuehk_years, $ktuehk_years ) ?: array(), $ktuehk_current['yil'] ? $ktuehk_current['yil'] : '' );
			$ktuehk_select( 'durum', __( 'Durum', 'ktuehk' ), $ktuehk_statuses, $ktuehk_current['durum'] );
			?>
			<div class="filters__actions">
				<button type="submit" class="btn btn--primary btn--sm filters__submit"><?php esc_html_e( 'Uygula', 'ktuehk' ); ?></button>
				<?php if ( $ktuehk_filtered ) : ?>
					<a class="btn btn--ghost btn--sm" href="<?php echo esc_url( $ktuehk_archive ); ?>"><?php esc_html_e( 'Temizle', 'ktuehk' ); ?></a>
				<?php endif; ?>
			</div>
		</form>

		<p class="results-count" role="status">
			<?php
			/* translators: %d: number of projects */
			echo esc_html( sprintf( _n( '%d proje listeleniyor', '%d proje listeleniyor', (int) $wp_query->found_posts, 'ktuehk' ), (int) $wp_query->found_posts ) );
			?>
		</p>

		<?php if ( have_posts() ) : ?>
			<div class="grid grid--3">
				<?php
				$ktuehk_index = 0;
				while ( have_posts() ) :
					the_post();
					get_template_part(
						'template-parts/card',
						'project',
						array(
							'heading'  => 'h2',
							'priority' => 0 === $ktuehk_index++,
						)
					);
				endwhile;
				?>
			</div>
			<?php ktuehk_pagination(); ?>
		<?php else : ?>
			<?php
			get_template_part(
				'template-parts/empty-state',
				null,
				array(
					'icon'       => 'cpu',
					'title'      => $ktuehk_filtered ? __( 'Bu filtrelere uygun proje bulunamadı', 'ktuehk' ) : __( 'Henüz proje eklenmedi', 'ktuehk' ),
					'text'       => $ktuehk_filtered ? __( 'Farklı bir yıl, program veya alan seçmeyi deneyin.', 'ktuehk' ) : '',
					'link'       => $ktuehk_filtered ? $ktuehk_archive : '',
					'link_text'  => __( 'Filtreleri temizle', 'ktuehk' ),
					'admin_link' => admin_url( 'post-new.php?post_type=' . $ktuehk_type ),
					'admin_text' => __( 'Yeni proje ekle', 'ktuehk' ),
				)
			);
			?>
		<?php endif; ?>
	</div>
</div>
<?php
get_footer();
