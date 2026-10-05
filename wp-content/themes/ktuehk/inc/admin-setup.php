<?php
/**
 * Admin notices and the one-click setup assistant.
 *
 * The assistant never overwrites existing content. It only:
 *  - assigns a posts page ("Yazılar") if none is set (re-using an existing
 *    page with the slug "yazilar" when present),
 *  - switches the front page to a static page only if it currently shows
 *    the latest posts (re-using "ana-sayfa" when present),
 *  - creates a "Hakkımızda" page only if no about page exists.
 * Every step is listed before the admin confirms.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

/**
 * Planned setup steps for the current site.
 *
 * @return array<string,string> step key => description
 */
function ktuehk_setup_steps() {
	$steps = array();
	if ( 'posts' === get_option( 'show_on_front' ) ) {
		$steps['front'] = __( 'Ana sayfa için statik bir "Ana Sayfa" sayfası atanacak (tasarım otomatik uygulanır, adres değişmez).', 'ktuehk' );
	}
	if ( ! (int) get_option( 'page_for_posts' ) || 'posts' === get_option( 'show_on_front' ) ) {
		$existing        = get_page_by_path( 'yazilar' );
		$steps['posts'] = $existing
			? __( 'Mevcut "yazilar" sayfası yazı listesi sayfası olarak atanacak.', 'ktuehk' )
			: __( '"Yazılar" sayfası oluşturulup yazı listesi sayfası olarak atanacak (/yazilar/).', 'ktuehk' );
	}
	if ( ! ktuehk_about_page() ) {
		$steps['about'] = __( '"Hakkımızda" sayfası, düzenlenebilir hazır içerikle taslak olarak oluşturulacak.', 'ktuehk' );
	}
	return $steps;
}

/**
 * Show admin notices.
 */
function ktuehk_admin_notices() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( ! ktuehk_has_core() ) {
		$plugin_file = 'ktuehk-core/ktuehk-core.php';
		$installed   = file_exists( WP_PLUGIN_DIR . '/' . $plugin_file );
		$url         = $installed
			? wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . rawurlencode( $plugin_file ) ), 'activate-plugin_' . $plugin_file )
			: admin_url( 'plugins.php' );
		printf(
			'<div class="notice notice-warning"><p><strong>%1$s</strong> %2$s <a href="%3$s">%4$s</a></p></div>',
			esc_html__( 'KTÜ EHK teması:', 'ktuehk' ),
			esc_html__( 'Projeler ve Etkinlikler bölümleri için "KTÜ EHK Çekirdek" eklentisi gereklidir.', 'ktuehk' ),
			esc_url( $url ),
			$installed ? esc_html__( 'Eklentiyi etkinleştir', 'ktuehk' ) : esc_html__( 'Eklentiler sayfasına git', 'ktuehk' )
		);
	}

	if ( isset( $_GET['ktuehk-setup'] ) && 'done' === $_GET['ktuehk-setup'] ) { // phpcs:ignore WordPress.Security.NonceVerification
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'KTÜ EHK kurulumu tamamlandı. Menüyü Görünüm → Menüler ekranından düzenleyebilirsiniz.', 'ktuehk' ) . '</p></div>';
		return;
	}

	if ( ! get_option( 'ktuehk_setup_notice' ) ) {
		return;
	}
	$steps = ktuehk_setup_steps();
	if ( ! $steps ) {
		delete_option( 'ktuehk_setup_notice' );
		return;
	}
	?>
	<div class="notice notice-info">
		<p><strong><?php esc_html_e( 'KTÜ EHK teması — önerilen kurulum', 'ktuehk' ); ?></strong></p>
		<p><?php esc_html_e( 'Aşağıdaki adımlar mevcut yazı, sayfa veya bağlantıları değiştirmez; yalnızca eksik olanları tamamlar:', 'ktuehk' ); ?></p>
		<ul style="list-style:disc;padding-left:1.5em">
			<?php foreach ( $steps as $step ) : ?>
				<li><?php echo esc_html( $step ); ?></li>
			<?php endforeach; ?>
		</ul>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:flex;gap:8px;margin:0 0 12px">
			<input type="hidden" name="action" value="ktuehk_setup">
			<?php wp_nonce_field( 'ktuehk_setup' ); ?>
			<button type="submit" name="do" value="run" class="button button-primary"><?php esc_html_e( 'Kurulumu tamamla', 'ktuehk' ); ?></button>
			<button type="submit" name="do" value="dismiss" class="button"><?php esc_html_e( 'Gizle', 'ktuehk' ); ?></button>
		</form>
	</div>
	<?php
}
add_action( 'admin_notices', 'ktuehk_admin_notices' );

/**
 * Handle the setup form.
 */
function ktuehk_handle_setup() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Bu işlem için yetkiniz yok.', 'ktuehk' ) );
	}
	check_admin_referer( 'ktuehk_setup' );

	$action = isset( $_POST['do'] ) ? sanitize_key( $_POST['do'] ) : '';
	delete_option( 'ktuehk_setup_notice' );

	if ( 'run' !== $action ) {
		wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url() );
		exit;
	}

	$steps = ktuehk_setup_steps();

	if ( isset( $steps['front'] ) ) {
		$front = get_page_by_path( 'ana-sayfa' );
		$front_id = $front ? $front->ID : wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => __( 'Ana Sayfa', 'ktuehk' ),
				'post_name'   => 'ana-sayfa',
			)
		);
		if ( $front_id && ! is_wp_error( $front_id ) ) {
			update_option( 'page_on_front', (int) $front_id );
			update_option( 'show_on_front', 'page' );
		}
	}

	if ( isset( $steps['posts'] ) ) {
		$posts_page = get_page_by_path( 'yazilar' );
		$posts_id   = $posts_page ? $posts_page->ID : wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => __( 'Yazılar', 'ktuehk' ),
				'post_name'   => 'yazilar',
			)
		);
		if ( $posts_id && ! is_wp_error( $posts_id ) && (int) $posts_id !== (int) get_option( 'page_on_front' ) ) {
			update_option( 'page_for_posts', (int) $posts_id );
		}
	}

	if ( isset( $steps['about'] ) ) {
		wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'draft',
				'post_title'   => __( 'Hakkımızda', 'ktuehk' ),
				'post_name'    => 'hakkimizda',
				'post_excerpt' => __( 'Elektronik ve haberleşme alanında üreten, araştıran ve öğrendiklerini paylaşan KTÜ öğrencilerinin topluluğu.', 'ktuehk' ),
				'post_content' => ktuehk_about_default_content(),
			)
		);
	}

	flush_rewrite_rules();
	wp_safe_redirect( add_query_arg( 'ktuehk-setup', 'done', admin_url( 'options-reading.php' ) ) );
	exit;
}
add_action( 'admin_post_ktuehk_setup', 'ktuehk_handle_setup' );
