<?php
/**
 * Default page template (also renders existing pages such as İletişim
 * with their current content unchanged).
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$ktuehk_content = ktuehk_prepare_content( false );
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
			<div class="prose entry-content page-content">
				<?php echo $ktuehk_content['html']; // phpcs:ignore WordPress.Security.EscapeOutput -- filtered post content. ?>
			</div>
			<?php
			wp_link_pages(
				array(
					'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Sayfalar', 'ktuehk' ) . '"><span>' . esc_html__( 'Sayfalar:', 'ktuehk' ) . '</span>',
					'after'  => '</nav>',
				)
			);
			?>
		</div>
	</div>
	<?php
	if ( comments_open() || get_comments_number() ) :
		?>
		<div class="section">
			<div class="container container--narrow">
				<?php comments_template(); ?>
			</div>
		</div>
		<?php
	endif;
endwhile;

get_footer();
