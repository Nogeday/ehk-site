<?php
/**
 * Template Name: Tam genişlik
 *
 * For pages built with wide blocks or a page builder: the content uses the
 * full container width instead of the reading width.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	get_template_part(
		'template-parts/page-header',
		null,
		array(
			'title' => get_the_title(),
			'desc'  => has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : '',
		)
	);
	?>
	<div class="section section--tight">
		<div class="container">
			<div class="entry-content page-content page-content--wide">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
	<?php
endwhile;

get_footer();
