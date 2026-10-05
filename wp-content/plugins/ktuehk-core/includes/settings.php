<?php
/**
 * Plugin settings: content type keys / URL slugs and a diagnostics screen
 * that lists every post type found in the database. This lets an admin map
 * an already existing project/event post type (created by an older theme or
 * plugin) instead of creating a second, empty one.
 *
 * @package KTUEHK_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default settings.
 *
 * @return array
 */
function ktuehk_core_default_settings() {
	return array(
		'project_type' => 'proje',
		'project_slug' => 'projeler',
		'event_type'   => 'etkinlik',
		'event_slug'   => 'etkinlikler',
	);
}

/**
 * Saved settings merged with defaults.
 *
 * @param string|null $key Optional single key.
 * @return mixed
 */
function ktuehk_core_settings( $key = null ) {
	$settings = wp_parse_args( (array) get_option( 'ktuehk_core_settings', array() ), ktuehk_core_default_settings() );
	if ( null === $key ) {
		return $settings;
	}
	return isset( $settings[ $key ] ) ? $settings[ $key ] : null;
}

/**
 * Sanitize settings on save.
 *
 * @param array $input Raw input.
 * @return array
 */
function ktuehk_core_sanitize_settings( $input ) {
	$defaults = ktuehk_core_default_settings();
	$old      = ktuehk_core_settings();
	$clean    = array();

	foreach ( array( 'project_type', 'event_type' ) as $key ) {
		$value = isset( $input[ $key ] ) ? sanitize_key( $input[ $key ] ) : '';
		// Post type keys are limited to 20 characters by WordPress.
		$clean[ $key ] = ( '' !== $value && strlen( $value ) <= 20 ) ? $value : $defaults[ $key ];
	}
	foreach ( array( 'project_slug', 'event_slug' ) as $key ) {
		$value         = isset( $input[ $key ] ) ? sanitize_title( $input[ $key ] ) : '';
		$clean[ $key ] = '' !== $value ? $value : $defaults[ $key ];
	}
	if ( $clean['project_type'] === $clean['event_type'] ) {
		$clean['event_type'] = $old['event_type'] !== $clean['project_type'] ? $old['event_type'] : $defaults['event_type'];
		add_settings_error( 'ktuehk_core_settings', 'same-type', __( 'Proje ve etkinlik için aynı içerik türü anahtarı kullanılamaz.', 'ktuehk-core' ) );
	}

	if ( $clean !== $old ) {
		update_option( 'ktuehk_core_flush_rewrite', 1 );
	}
	return $clean;
}

/**
 * Register setting + admin page.
 */
function ktuehk_core_register_settings() {
	register_setting(
		'ktuehk_core',
		'ktuehk_core_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'ktuehk_core_sanitize_settings',
			'default'           => ktuehk_core_default_settings(),
		)
	);
}
add_action( 'admin_init', 'ktuehk_core_register_settings' );

/**
 * Add "Ayarlar → KTÜ EHK" page.
 */
function ktuehk_core_add_settings_page() {
	add_options_page(
		__( 'KTÜ EHK İçerik Ayarları', 'ktuehk-core' ),
		__( 'KTÜ EHK', 'ktuehk-core' ),
		'manage_options',
		'ktuehk-core',
		'ktuehk_core_render_settings_page'
	);
}
add_action( 'admin_menu', 'ktuehk_core_add_settings_page' );

/**
 * Post types stored in the database with their counts (cached briefly).
 *
 * @return array<string,int>
 */
function ktuehk_core_db_post_type_counts() {
	$counts = get_transient( 'ktuehk_core_db_types' );
	if ( false === $counts ) {
		global $wpdb;
		$rows   = $wpdb->get_results( "SELECT post_type, COUNT(*) AS total FROM {$wpdb->posts} WHERE post_status NOT IN ('auto-draft','trash','inherit') GROUP BY post_type ORDER BY total DESC" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$counts = array();
		foreach ( (array) $rows as $row ) {
			$counts[ $row->post_type ] = (int) $row->total;
		}
		set_transient( 'ktuehk_core_db_types', $counts, 5 * MINUTE_IN_SECONDS );
	}
	return $counts;
}

/**
 * Settings page markup.
 */
function ktuehk_core_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	delete_transient( 'ktuehk_core_db_types' );
	$settings = ktuehk_core_settings();
	$counts   = ktuehk_core_db_post_type_counts();
	$internal = array( 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'wp_block', 'wp_template', 'wp_template_part', 'wp_global_styles', 'wp_navigation', 'wp_font_family', 'wp_font_face', 'attachment' );
	?>
	<div class="wrap ktuehk-settings">
		<h1><?php esc_html_e( 'KTÜ EHK İçerik Ayarları', 'ktuehk-core' ); ?></h1>
		<p class="description" style="max-width:760px">
			<?php esc_html_e( 'Projeler ve Etkinlikler bu eklenti tarafından yönetilir. Sitenizde daha önce başka bir tema veya eklentiyle oluşturulmuş bir proje/etkinlik içerik türü varsa, aşağıdaki tanılama tablosunda görünür. Mevcut içeriği korumak için o türün anahtarını ve mevcut URL ön ekini buraya yazmanız yeterlidir; içerik taşınmaz veya silinmez.', 'ktuehk-core' ); ?>
		</p>

		<form method="post" action="options.php">
			<?php settings_fields( 'ktuehk_core' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="ktuehk-project-type"><?php esc_html_e( 'Proje içerik türü anahtarı', 'ktuehk-core' ); ?></label></th>
					<td>
						<input name="ktuehk_core_settings[project_type]" id="ktuehk-project-type" type="text" class="regular-text code" value="<?php echo esc_attr( $settings['project_type'] ); ?>" maxlength="20">
						<p class="description"><?php esc_html_e( 'Varsayılan: proje. Yalnızca mevcut bir içerik türünü kullanmak istiyorsanız değiştirin.', 'ktuehk-core' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="ktuehk-project-slug"><?php esc_html_e( 'Projeler URL ön eki', 'ktuehk-core' ); ?></label></th>
					<td>
						<code><?php echo esc_html( home_url( '/' ) ); ?></code><input name="ktuehk_core_settings[project_slug]" id="ktuehk-project-slug" type="text" class="regular-text code" value="<?php echo esc_attr( $settings['project_slug'] ); ?>"><code>/</code>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="ktuehk-event-type"><?php esc_html_e( 'Etkinlik içerik türü anahtarı', 'ktuehk-core' ); ?></label></th>
					<td>
						<input name="ktuehk_core_settings[event_type]" id="ktuehk-event-type" type="text" class="regular-text code" value="<?php echo esc_attr( $settings['event_type'] ); ?>" maxlength="20">
						<p class="description"><?php esc_html_e( 'Varsayılan: etkinlik.', 'ktuehk-core' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="ktuehk-event-slug"><?php esc_html_e( 'Etkinlikler URL ön eki', 'ktuehk-core' ); ?></label></th>
					<td>
						<code><?php echo esc_html( home_url( '/' ) ); ?></code><input name="ktuehk_core_settings[event_slug]" id="ktuehk-event-slug" type="text" class="regular-text code" value="<?php echo esc_attr( $settings['event_slug'] ); ?>"><code>/</code>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>

		<h2><?php esc_html_e( 'Tanılama: veritabanındaki içerik türleri', 'ktuehk-core' ); ?></h2>
		<p class="description"><?php esc_html_e( '"Kayıtlı değil" görünen bir tür, içeriği veritabanında duran ancak artık hiçbir tema/eklenti tarafından tanımlanmayan bir türdür (ör. eski temanın proje türü). Bu içerikler silinmemiştir.', 'ktuehk-core' ); ?></p>
		<table class="widefat striped" style="max-width:760px">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Anahtar', 'ktuehk-core' ); ?></th>
					<th><?php esc_html_e( 'Ad', 'ktuehk-core' ); ?></th>
					<th><?php esc_html_e( 'İçerik sayısı', 'ktuehk-core' ); ?></th>
					<th><?php esc_html_e( 'Durum', 'ktuehk-core' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $counts as $type => $total ) :
				if ( in_array( $type, $internal, true ) ) {
					continue;
				}
				$object = get_post_type_object( $type );
				$role   = '';
				if ( $type === $settings['project_type'] ) {
					$role = __( 'Projeler için kullanılıyor', 'ktuehk-core' );
				} elseif ( $type === $settings['event_type'] ) {
					$role = __( 'Etkinlikler için kullanılıyor', 'ktuehk-core' );
				}
				?>
				<tr>
					<td><code><?php echo esc_html( $type ); ?></code></td>
					<td><?php echo $object ? esc_html( $object->labels->name ) : '—'; ?></td>
					<td><?php echo esc_html( number_format_i18n( $total ) ); ?></td>
					<td>
						<?php
						if ( ! $object ) {
							echo '<strong style="color:#b32d2e">' . esc_html__( 'Kayıtlı değil', 'ktuehk-core' ) . '</strong>';
						} else {
							esc_html_e( 'Kayıtlı', 'ktuehk-core' );
						}
						if ( $role ) {
							echo ' · ' . esc_html( $role );
						}
						?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}
