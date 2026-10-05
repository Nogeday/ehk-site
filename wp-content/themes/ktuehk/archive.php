<?php
/**
 * Article archives: categories, tags, authors and dates.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

$ktuehk_eyebrow = __( 'Arşiv', 'ktuehk' );
$ktuehk_desc    = wp_strip_all_tags( (string) get_the_archive_description() );

if ( is_category() ) {
	$ktuehk_eyebrow = __( 'Kategori', 'ktuehk' );
} elseif ( is_tag() ) {
	$ktuehk_eyebrow = __( 'Etiket', 'ktuehk' );
} elseif ( is_author() ) {
	$ktuehk_eyebrow = __( 'Yazar', 'ktuehk' );
	$ktuehk_desc    = get_the_author_meta( 'description', get_queried_object_id() );
} elseif ( is_date() ) {
	$ktuehk_eyebrow = __( 'Tarih arşivi', 'ktuehk' );
}

if ( '' === trim( (string) $ktuehk_desc ) && ! is_author() ) {
	global $wp_query;
	/* translators: %d: number of articles */
	$ktuehk_desc = sprintf( _n( '%d yazı', '%d yazı', (int) $wp_query->found_posts, 'ktuehk' ), (int) $wp_query->found_posts );
}

get_template_part(
	'template-parts/page-header',
	null,
	array(
		'eyebrow' => $ktuehk_eyebrow,
		'title'   => wp_strip_all_tags( get_the_archive_title() ),
		'desc'    => $ktuehk_desc,
	)
);
?>
<div class="section section--tight">
	<div class="container">
		<?php
		if ( is_category() ) {
			get_template_part( 'template-parts/category-chips' );
		}
		get_template_part( 'template-parts/post-listing', null, array( 'feature_first' => false ) );
		?>
	</div>
</div>
<?php
get_footer();
