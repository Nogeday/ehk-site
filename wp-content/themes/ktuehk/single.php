<?php
/**
 * Single article.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$ktuehk_content  = ktuehk_prepare_content();
	$ktuehk_category = ktuehk_primary_term( 'category' );
	$ktuehk_author   = ktuehk_display_author();
	$ktuehk_has_toc  = ! empty( $ktuehk_content['toc'] );
	$ktuehk_updated  = get_the_modified_date( 'U' ) - get_the_date( 'U' ) > DAY_IN_SECONDS;
	?>
	<article <?php post_class( 'article' ); ?>>
		<header class="article__header">
			<div class="container article__header-inner">
				<?php ktuehk_breadcrumbs(); ?>
				<?php if ( $ktuehk_category ) : ?>
					<p class="article__eyebrow"><a class="badge badge--blue" href="<?php echo esc_url( get_category_link( $ktuehk_category ) ); ?>"><?php echo esc_html( $ktuehk_category->name ); ?></a></p>
				<?php endif; ?>
				<h1 class="article__title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="article__lead"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
				<?php endif; ?>
				<div class="byline">
					<?php get_template_part( 'template-parts/author-avatar', null, array( 'author' => $ktuehk_author ) ); ?>
					<div class="byline__text">
						<span class="byline__name">
							<?php if ( $ktuehk_author['url'] ) : ?>
								<a href="<?php echo esc_url( $ktuehk_author['url'] ); ?>" rel="author"><?php echo esc_html( $ktuehk_author['name'] ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $ktuehk_author['name'] ); ?>
							<?php endif; ?>
						</span>
						<ul class="meta">
							<li class="meta__item"><?php echo ktuehk_icon( 'calendar', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></li>
							<li class="meta__item">
								<?php
								echo ktuehk_icon( 'clock', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput
								/* translators: %d: minutes */
								echo esc_html( sprintf( __( '%d dk okuma', 'ktuehk' ), ktuehk_reading_time() ) );
								?>
							</li>
							<?php if ( $ktuehk_updated ) : ?>
								<li class="meta__item">
									<?php echo ktuehk_icon( 'history', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
									<?php esc_html_e( 'Güncellendi:', 'ktuehk' ); ?>
									<time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php echo esc_html( get_the_modified_date() ); ?></time>
								</li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="container article__cover">
				<?php
				$ktuehk_thumb_id  = get_post_thumbnail_id();
				$ktuehk_thumb_alt = trim( (string) get_post_meta( $ktuehk_thumb_id, '_wp_attachment_image_alt', true ) );
				echo wp_get_attachment_image(
					$ktuehk_thumb_id,
					'large',
					false,
					array(
						'alt'           => '' !== $ktuehk_thumb_alt ? $ktuehk_thumb_alt : get_the_title(),
						'sizes'         => '(min-width: 1100px) 1040px, 100vw',
						'loading'       => false,
						'fetchpriority' => 'high',
						'decoding'      => 'async',
					)
				);
				$ktuehk_caption = wp_get_attachment_caption( $ktuehk_thumb_id );
				if ( $ktuehk_caption ) :
					?>
					<figcaption><?php echo esc_html( $ktuehk_caption ); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endif; ?>

		<div class="container article__layout<?php echo $ktuehk_has_toc ? ' has-toc' : ''; ?>">
			<?php if ( $ktuehk_has_toc ) : ?>
				<aside class="article__aside">
					<?php ktuehk_toc( $ktuehk_content['toc'] ); ?>
				</aside>
			<?php endif; ?>

			<div class="article__main">
				<div class="prose entry-content">
					<?php echo $ktuehk_content['html']; // phpcs:ignore WordPress.Security.EscapeOutput -- filtered post content. ?>
				</div>

				<?php
				wp_link_pages(
					array(
						'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Yazı sayfaları', 'ktuehk' ) . '"><span>' . esc_html__( 'Sayfalar:', 'ktuehk' ) . '</span>',
						'after'  => '</nav>',
					)
				);
				?>

				<footer class="article__footer">
					<?php
					$ktuehk_tags = get_the_tags();
					if ( $ktuehk_tags && ! is_wp_error( $ktuehk_tags ) ) :
						?>
						<ul class="tag-list tag-list--links" aria-label="<?php esc_attr_e( 'Etiketler', 'ktuehk' ); ?>">
							<?php foreach ( $ktuehk_tags as $ktuehk_tag ) : ?>
								<li><a class="tag" href="<?php echo esc_url( get_tag_link( $ktuehk_tag ) ); ?>">#<?php echo esc_html( $ktuehk_tag->name ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php get_template_part( 'template-parts/share' ); ?>

					<?php if ( $ktuehk_author['name'] && ( $ktuehk_author['description'] || $ktuehk_author['is_override'] ) ) : ?>
						<section class="author-box" aria-label="<?php esc_attr_e( 'Yazar hakkında', 'ktuehk' ); ?>">
							<?php get_template_part( 'template-parts/author-avatar', null, array( 'author' => $ktuehk_author, 'size' => 56 ) ); ?>
							<div>
								<p class="author-box__label"><?php esc_html_e( 'Yazar', 'ktuehk' ); ?></p>
								<p class="author-box__name"><?php echo esc_html( $ktuehk_author['name'] ); ?></p>
								<?php if ( $ktuehk_author['description'] ) : ?>
									<p class="author-box__bio"><?php echo esc_html( $ktuehk_author['description'] ); ?></p>
								<?php endif; ?>
							</div>
						</section>
					<?php endif; ?>
				</footer>
			</div>
		</div>
	</article>

	<?php
	$ktuehk_related_args = array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post__not_in'        => array( get_the_ID() ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);
	if ( $ktuehk_category ) {
		$ktuehk_related_args['cat'] = $ktuehk_category->term_id;
	}
	$ktuehk_related = get_posts( $ktuehk_related_args );
	if ( count( $ktuehk_related ) < 3 && $ktuehk_category ) {
		unset( $ktuehk_related_args['cat'] );
		$ktuehk_related_args['posts_per_page'] = 3 - count( $ktuehk_related );
		$ktuehk_related_args['post__not_in']   = array_merge( array( get_the_ID() ), wp_list_pluck( $ktuehk_related, 'ID' ) );
		$ktuehk_related                        = array_merge( $ktuehk_related, get_posts( $ktuehk_related_args ) );
	}
	$ktuehk_current = $post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
	if ( $ktuehk_related ) :
		?>
		<section class="section section--alt related" aria-labelledby="related-title">
			<div class="container">
				<?php
				ktuehk_section_head(
					array(
						'eyebrow'   => __( 'OKUMAYA DEVAM ET', 'ktuehk' ),
						'title'     => __( 'İlgili Yazılar', 'ktuehk' ),
						'id'        => 'related-title',
						'link'      => ktuehk_posts_url(),
						'link_text' => __( 'Tüm Yazıları Gör', 'ktuehk' ),
					)
				);
				?>
				<div class="grid grid--3">
					<?php
					foreach ( $ktuehk_related as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride
						setup_postdata( $post );
						get_template_part( 'template-parts/card', 'post' );
					endforeach;
					$post = $ktuehk_current; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
					setup_postdata( $post );
					?>
				</div>
			</div>
		</section>
		<?php
	endif;

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
