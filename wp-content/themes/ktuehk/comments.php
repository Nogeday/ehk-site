<?php
/**
 * Comments.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments" aria-labelledby="comments-title">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments__title" id="comments-title">
			<?php
			/* translators: %s: number of comments */
			echo esc_html( sprintf( _n( '%s yorum', '%s yorum', get_comments_number(), 'ktuehk' ), number_format_i18n( get_comments_number() ) ) );
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 40,
				)
			);
			?>
		</ol>
		<?php
		the_comments_navigation(
			array(
				'prev_text' => __( 'Önceki yorumlar', 'ktuehk' ),
				'next_text' => __( 'Sonraki yorumlar', 'ktuehk' ),
			)
		);
	else :
		?>
		<h2 class="comments__title" id="comments-title"><?php esc_html_e( 'Yorumlar', 'ktuehk' ); ?></h2>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="comments__closed"><?php esc_html_e( 'Bu yazı yorumlara kapatılmıştır.', 'ktuehk' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'        => __( 'Yorum yaz', 'ktuehk' ),
			'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h3>',
			'class_submit'       => 'btn btn--primary',
			'label_submit'       => __( 'Yorumu gönder', 'ktuehk' ),
		)
	);
	?>
</section>
