<?php
/**
 * Fallback template.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part(
	'template-parts/page-header',
	null,
	array(
		'title' => is_archive() ? wp_strip_all_tags( get_the_archive_title() ) : get_bloginfo( 'name' ),
		'desc'  => is_archive() ? wp_strip_all_tags( (string) get_the_archive_description() ) : get_bloginfo( 'description' ),
	)
);
?>
<div class="section section--tight">
	<div class="container">
		<?php get_template_part( 'template-parts/post-listing' ); ?>
	</div>
</div>
<?php
get_footer();
