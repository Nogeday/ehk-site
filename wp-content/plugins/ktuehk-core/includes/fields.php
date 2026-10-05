<?php
/**
 * Custom fields for projects, events and posts.
 *
 * Fields are stored as regular post meta (prefixed "_ehk_") and edited with
 * classic meta boxes, which work in both the block editor and the classic
 * editor. No third-party field plugin is required.
 *
 * @package KTUEHK_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field groups per content kind.
 *
 * Field types: text, textarea, lines, url, number, select, checkbox,
 * datetime, image, gallery.
 *
 * @return array
 */
function ktuehk_core_field_groups() {
	$groups = array(
		'project' => array(
			'ehk_project_info'    => array(
				'title'   => __( 'Proje Bilgileri', 'ktuehk-core' ),
				'context' => 'normal',
				'intro'   => __( 'Kısa özet için sağ paneldeki "Özet" alanını, kapak için "Proje kapak görseli"ni kullanın. Program ve alan seçimleri de sağ paneldedir.', 'ktuehk-core' ),
				'fields'  => array(
					'yil'       => array(
						'type'  => 'number',
						'label' => __( 'Yıl', 'ktuehk-core' ),
						'attrs' => array(
							'min'         => 1990,
							'max'         => 2100,
							'placeholder' => gmdate( 'Y' ),
						),
						'width' => 'third',
					),
					'durum'     => array(
						'type'    => 'select',
						'label'   => __( 'Durum', 'ktuehk-core' ),
						'options' => ktuehk_project_statuses(),
						'width'   => 'third',
					),
					'one_cikan' => array(
						'type'  => 'checkbox',
						'label' => __( 'Ana sayfada öne çıkar', 'ktuehk-core' ),
						'width' => 'third',
					),
					'basari'    => array(
						'type'        => 'text',
						'label'       => __( 'Başarı / Ödül', 'ktuehk-core' ),
						'placeholder' => __( 'Örn. TEKNOFEST 2025 Finalisti', 'ktuehk-core' ),
						'width'       => 'half',
					),
					'danisman'  => array(
						'type'        => 'text',
						'label'       => __( 'Danışman', 'ktuehk-core' ),
						'placeholder' => __( 'Örn. Dr. Öğr. Üyesi Ad Soyad', 'ktuehk-core' ),
						'width'       => 'half',
					),
					'ekip'      => array(
						'type'        => 'lines',
						'label'       => __( 'Ekip', 'ktuehk-core' ),
						'description' => __( 'Her satıra bir kişi. Rol eklemek için: Ad Soyad — Rol', 'ktuehk-core' ),
						'placeholder' => "Ayşe Yılmaz — Takım Lideri\nMehmet Demir — Donanım",
					),
					'github'    => array(
						'type'        => 'url',
						'label'       => __( 'GitHub bağlantısı', 'ktuehk-core' ),
						'placeholder' => 'https://github.com/...',
						'width'       => 'third',
					),
					'dokuman'   => array(
						'type'        => 'url',
						'label'       => __( 'Dokümantasyon / Rapor', 'ktuehk-core' ),
						'placeholder' => 'https://',
						'width'       => 'third',
					),
					'demo'      => array(
						'type'        => 'url',
						'label'       => __( 'Demo / Video', 'ktuehk-core' ),
						'placeholder' => 'https://',
						'width'       => 'third',
					),
				),
			),
			'ehk_project_details' => array(
				'title'   => __( 'Proje Detayları', 'ktuehk-core' ),
				'context' => 'normal',
				'intro'   => __( 'Boş bırakılan bölümler proje sayfasında gösterilmez. Ayrıntılı anlatım, görseller ve şemalar için ana içerik editörünü kullanabilirsiniz.', 'ktuehk-core' ),
				'fields'  => array(
					'problem'      => array(
						'type'  => 'textarea',
						'label' => __( 'Problem', 'ktuehk-core' ),
					),
					'amac'         => array(
						'type'  => 'textarea',
						'label' => __( 'Amaç', 'ktuehk-core' ),
					),
					'teknolojiler' => array(
						'type'        => 'text',
						'label'       => __( 'Kullanılan teknolojiler', 'ktuehk-core' ),
						'description' => __( 'Virgülle ayırın.', 'ktuehk-core' ),
						'placeholder' => 'STM32, LoRa, KiCad, Python',
					),
					'donanim'      => array(
						'type'        => 'lines',
						'label'       => __( 'Donanım', 'ktuehk-core' ),
						'description' => __( 'Her satıra bir bileşen.', 'ktuehk-core' ),
						'width'       => 'half',
					),
					'yazilim'      => array(
						'type'        => 'lines',
						'label'       => __( 'Yazılım', 'ktuehk-core' ),
						'description' => __( 'Her satıra bir araç / kütüphane.', 'ktuehk-core' ),
						'width'       => 'half',
					),
					'surec'        => array(
						'type'  => 'textarea',
						'label' => __( 'Süreç', 'ktuehk-core' ),
					),
					'sonuclar'     => array(
						'type'  => 'textarea',
						'label' => __( 'Sonuçlar', 'ktuehk-core' ),
					),
				),
			),
			'ehk_project_gallery' => array(
				'title'   => __( 'Proje Galerisi', 'ktuehk-core' ),
				'context' => 'normal',
				'fields'  => array(
					'galeri' => array(
						'type'  => 'gallery',
						'label' => __( 'Galeri görselleri', 'ktuehk-core' ),
					),
				),
			),
		),
		'event'   => array(
			'ehk_event_info'    => array(
				'title'   => __( 'Etkinlik Bilgileri', 'ktuehk-core' ),
				'context' => 'normal',
				'intro'   => __( 'Etkinlik türünü sağ paneldeki "Etkinlik Türleri" bölümünden seçin. Bitiş saati girilmezse etkinlik, başladığı günün sonuna kadar "yaklaşan" olarak listelenir.', 'ktuehk-core' ),
				'fields'  => array(
					'baslangic'    => array(
						'type'  => 'datetime',
						'label' => __( 'Başlangıç', 'ktuehk-core' ),
						'width' => 'half',
					),
					'bitis'        => array(
						'type'  => 'datetime',
						'label' => __( 'Bitiş (isteğe bağlı)', 'ktuehk-core' ),
						'width' => 'half',
					),
					'konum'        => array(
						'type'        => 'text',
						'label'       => __( 'Konum', 'ktuehk-core' ),
						'placeholder' => __( 'Örn. KTÜ Teknoloji Fakültesi, Konferans Salonu', 'ktuehk-core' ),
						'width'       => 'half',
					),
					'harita'       => array(
						'type'        => 'url',
						'label'       => __( 'Harita bağlantısı', 'ktuehk-core' ),
						'placeholder' => 'https://maps.google.com/...',
						'width'       => 'half',
					),
					'cevrimici'    => array(
						'type'  => 'checkbox',
						'label' => __( 'Çevrim içi etkinlik', 'ktuehk-core' ),
						'width' => 'half',
					),
					'kayit'        => array(
						'type'        => 'url',
						'label'       => __( 'Kayıt / başvuru bağlantısı', 'ktuehk-core' ),
						'placeholder' => 'https://forms.gle/...',
						'width'       => 'half',
					),
					'konusmacilar' => array(
						'type'        => 'lines',
						'label'       => __( 'Konuşmacılar / eğitmenler', 'ktuehk-core' ),
						'description' => __( 'Her satıra bir kişi. Ünvan eklemek için: Ad Soyad — Kurum/Ünvan', 'ktuehk-core' ),
					),
				),
			),
			'ehk_event_gallery' => array(
				'title'   => __( 'Afiş ve Fotoğraflar', 'ktuehk-core' ),
				'context' => 'normal',
				'fields'  => array(
					'afis'   => array(
						'type'        => 'image',
						'label'       => __( 'Etkinlik afişi', 'ktuehk-core' ),
						'description' => __( 'Afiş kırpılmadan gösterilir. Kart görseli olarak "Etkinlik görseli" kullanılır; o boşsa afiş kullanılır.', 'ktuehk-core' ),
					),
					'galeri' => array(
						'type'  => 'gallery',
						'label' => __( 'Fotoğraf galerisi', 'ktuehk-core' ),
					),
				),
			),
		),
		'post'    => array(
			'ehk_post_author' => array(
				'title'   => __( 'Yazar Bilgisi', 'ktuehk-core' ),
				'context' => 'side',
				'intro'   => __( 'Yazıyı bir öğrenci adına yayımlıyorsanız doldurun. Boş bırakılırsa WordPress kullanıcısı yazar olarak gösterilir.', 'ktuehk-core' ),
				'fields'  => array(
					'yazar_adi'   => array(
						'type'        => 'text',
						'label'       => __( 'Görünen yazar adı', 'ktuehk-core' ),
						'placeholder' => __( 'Ad Soyad', 'ktuehk-core' ),
					),
					'yazar_bilgi' => array(
						'type'        => 'text',
						'label'       => __( 'Kısa bilgi', 'ktuehk-core' ),
						'placeholder' => __( 'Örn. EHM 3. sınıf öğrencisi', 'ktuehk-core' ),
					),
				),
			),
		),
	);
	return (array) apply_filters( 'ktuehk_core_field_groups', $groups );
}

/**
 * Map content kind to post type key.
 *
 * @param string $kind project|event|post.
 * @return string
 */
function ktuehk_core_kind_post_type( $kind ) {
	if ( 'project' === $kind ) {
		return ktuehk_project_type();
	}
	if ( 'event' === $kind ) {
		return ktuehk_event_type();
	}
	return 'post';
}

/**
 * Flat list of fields per post type.
 *
 * @param string $post_type Post type.
 * @return array
 */
function ktuehk_core_fields_for( $post_type ) {
	$fields = array();
	foreach ( ktuehk_core_field_groups() as $kind => $groups ) {
		if ( ktuehk_core_kind_post_type( $kind ) !== $post_type ) {
			continue;
		}
		foreach ( $groups as $group ) {
			$fields += $group['fields'];
		}
	}
	return $fields;
}

/**
 * Register meta for REST / block editor access.
 */
function ktuehk_core_register_meta() {
	foreach ( array_keys( ktuehk_core_field_groups() ) as $kind ) {
		$post_type = ktuehk_core_kind_post_type( $kind );
		foreach ( ktuehk_core_fields_for( $post_type ) as $name => $field ) {
			$is_int = in_array( $field['type'], array( 'number', 'image' ), true );
			register_post_meta(
				$post_type,
				ktuehk_meta_key( $name ),
				array(
					'type'              => $is_int ? 'integer' : 'string',
					'single'            => true,
					'show_in_rest'      => true,
					'sanitize_callback' => static function ( $value ) use ( $field ) {
						return ktuehk_core_sanitize_field( $value, $field );
					},
					'auth_callback'     => static function ( $allowed, $meta_key, $post_id ) {
						return current_user_can( 'edit_post', $post_id );
					},
				)
			);
		}
	}
	// Computed: effective end of an event, used for upcoming/past queries.
	register_post_meta(
		ktuehk_event_type(),
		ktuehk_meta_key( 'son' ),
		array(
			'type'          => 'string',
			'single'        => true,
			'show_in_rest'  => false,
			'auth_callback' => '__return_false',
		)
	);
}
add_action( 'init', 'ktuehk_core_register_meta', 25 );

/**
 * Sanitize a field value by type.
 *
 * @param mixed $value Raw value.
 * @param array $field Field definition.
 * @return mixed
 */
function ktuehk_core_sanitize_field( $value, $field ) {
	if ( is_array( $value ) ) {
		$value = implode( ',', $value );
	}
	$value = is_scalar( $value ) ? (string) $value : '';

	switch ( $field['type'] ) {
		case 'textarea':
			return trim( wp_kses_post( $value ) );
		case 'lines':
			return trim( sanitize_textarea_field( $value ) );
		case 'url':
			return esc_url_raw( trim( $value ) );
		case 'number':
			$number = absint( $value );
			return ( $number >= 1990 && $number <= 2100 ) ? $number : 0;
		case 'image':
			return absint( $value );
		case 'select':
			return isset( $field['options'][ $value ] ) ? $value : '';
		case 'checkbox':
			return $value ? '1' : '';
		case 'datetime':
			return ktuehk_core_sanitize_datetime( $value );
		case 'gallery':
			return implode( ',', array_filter( array_map( 'absint', explode( ',', $value ) ) ) );
		default:
			return sanitize_text_field( $value );
	}
}

/**
 * Normalize a datetime-local value ("Y-m-d\TH:i") to "Y-m-d H:i:s".
 *
 * @param string $value Raw value.
 * @return string
 */
function ktuehk_core_sanitize_datetime( $value ) {
	$value = trim( str_replace( 'T', ' ', $value ) );
	foreach ( array( 'Y-m-d H:i:s', 'Y-m-d H:i', 'Y-m-d' ) as $format ) {
		$date = DateTimeImmutable::createFromFormat( '!' . $format, $value, wp_timezone() );
		if ( $date && $date->format( $format ) === $value ) {
			return $date->format( 'Y-m-d H:i:s' );
		}
	}
	return '';
}

/**
 * Add meta boxes.
 *
 * @param string $post_type Current post type.
 */
function ktuehk_core_add_meta_boxes( $post_type ) {
	foreach ( ktuehk_core_field_groups() as $kind => $groups ) {
		if ( ktuehk_core_kind_post_type( $kind ) !== $post_type ) {
			continue;
		}
		foreach ( $groups as $id => $group ) {
			add_meta_box(
				$id,
				$group['title'],
				'ktuehk_core_render_meta_box',
				$post_type,
				$group['context'],
				'side' === $group['context'] ? 'default' : 'high',
				array( 'group' => $group )
			);
		}
	}
}
add_action( 'add_meta_boxes', 'ktuehk_core_add_meta_boxes' );

/**
 * Render a meta box.
 *
 * @param WP_Post $post Post.
 * @param array   $box  Box args.
 */
function ktuehk_core_render_meta_box( $post, $box ) {
	$group = $box['args']['group'];
	wp_nonce_field( 'ktuehk_core_save_' . $post->ID, 'ktuehk_core_nonce' );

	echo '<div class="ehk-fields' . ( 'side' === $group['context'] ? ' ehk-fields--side' : '' ) . '">';
	if ( ! empty( $group['intro'] ) ) {
		echo '<p class="ehk-fields__intro">' . esc_html( $group['intro'] ) . '</p>';
	}
	foreach ( $group['fields'] as $name => $field ) {
		ktuehk_core_render_field( $post, $name, $field );
	}
	echo '</div>';
}

/**
 * Render a single field.
 *
 * @param WP_Post $post  Post.
 * @param string  $name  Field name.
 * @param array   $field Field definition.
 */
function ktuehk_core_render_field( $post, $name, $field ) {
	$id    = 'ehk-' . $name;
	$input = 'ehk[' . $name . ']';
	$value = get_post_meta( $post->ID, ktuehk_meta_key( $name ), true );
	$width = isset( $field['width'] ) ? ' ehk-field--' . $field['width'] : '';
	$attrs = '';
	if ( ! empty( $field['placeholder'] ) ) {
		$attrs .= ' placeholder="' . esc_attr( $field['placeholder'] ) . '"';
	}
	if ( ! empty( $field['attrs'] ) ) {
		foreach ( $field['attrs'] as $attr => $attr_value ) {
			$attrs .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $attr_value ) . '"';
		}
	}

	echo '<div class="ehk-field ehk-field--' . esc_attr( $field['type'] ) . esc_attr( $width ) . '">';

	if ( 'checkbox' === $field['type'] ) {
		printf(
			'<label class="ehk-check"><input type="checkbox" id="%1$s" name="%2$s" value="1" %3$s> %4$s</label>',
			esc_attr( $id ),
			esc_attr( $input ),
			checked( '1', $value, false ),
			esc_html( $field['label'] )
		);
	} else {
		printf( '<label class="ehk-field__label" for="%1$s">%2$s</label>', esc_attr( $id ), esc_html( $field['label'] ) );

		switch ( $field['type'] ) {
			case 'textarea':
			case 'lines':
				printf(
					'<textarea id="%1$s" name="%2$s" rows="%3$d" class="widefat"%4$s>%5$s</textarea>',
					esc_attr( $id ),
					esc_attr( $input ),
					'lines' === $field['type'] ? 4 : 5,
					$attrs, // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
					esc_textarea( (string) $value )
				);
				break;
			case 'select':
				printf( '<select id="%1$s" name="%2$s" class="widefat"><option value="">%3$s</option>', esc_attr( $id ), esc_attr( $input ), esc_html__( '— Seçin —', 'ktuehk-core' ) );
				foreach ( $field['options'] as $option_value => $option_label ) {
					printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $option_value ), selected( $value, $option_value, false ), esc_html( $option_label ) );
				}
				echo '</select>';
				break;
			case 'datetime':
				$date = ktuehk_parse_datetime( (string) $value );
				printf(
					'<input type="datetime-local" id="%1$s" name="%2$s" value="%3$s" class="widefat">',
					esc_attr( $id ),
					esc_attr( $input ),
					esc_attr( $date ? $date->format( 'Y-m-d\TH:i' ) : '' )
				);
				break;
			case 'image':
				$image_id = absint( $value );
				echo '<div class="ehk-media" data-ehk-media="single">';
				printf( '<input type="hidden" id="%1$s" name="%2$s" value="%3$s" data-ehk-media-input>', esc_attr( $id ), esc_attr( $input ), esc_attr( $image_id ? $image_id : '' ) );
				echo '<div class="ehk-media__preview" data-ehk-media-preview>';
				if ( $image_id ) {
					echo wp_get_attachment_image( $image_id, 'thumbnail' );
				}
				echo '</div>';
				printf( '<button type="button" class="button" data-ehk-media-select>%s</button> ', esc_html__( 'Görsel seç', 'ktuehk-core' ) );
				printf( '<button type="button" class="button-link button-link-delete" data-ehk-media-clear%2$s>%1$s</button>', esc_html__( 'Kaldır', 'ktuehk-core' ), $image_id ? '' : ' hidden' );
				echo '</div>';
				break;
			case 'gallery':
				$ids = array_filter( array_map( 'absint', explode( ',', (string) $value ) ) );
				echo '<div class="ehk-media" data-ehk-media="gallery">';
				printf( '<input type="hidden" id="%1$s" name="%2$s" value="%3$s" data-ehk-media-input>', esc_attr( $id ), esc_attr( $input ), esc_attr( implode( ',', $ids ) ) );
				echo '<ul class="ehk-media__grid" data-ehk-media-preview>';
				foreach ( $ids as $image_id ) {
					echo '<li>' . wp_get_attachment_image( $image_id, 'thumbnail' ) . '</li>';
				}
				echo '</ul>';
				printf( '<button type="button" class="button" data-ehk-media-select>%s</button> ', esc_html__( 'Galeriyi düzenle', 'ktuehk-core' ) );
				printf( '<button type="button" class="button-link button-link-delete" data-ehk-media-clear%2$s>%1$s</button>', esc_html__( 'Tümünü kaldır', 'ktuehk-core' ), $ids ? '' : ' hidden' );
				echo '</div>';
				break;
			default:
				$type = in_array( $field['type'], array( 'url', 'number' ), true ) ? $field['type'] : 'text';
				printf(
					'<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="widefat"%5$s>',
					esc_attr( $type ),
					esc_attr( $id ),
					esc_attr( $input ),
					esc_attr( (string) ( $value ? $value : '' ) ),
					$attrs // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
				);
		}
	}

	if ( ! empty( $field['description'] ) ) {
		echo '<p class="description">' . esc_html( $field['description'] ) . '</p>';
	}
	echo '</div>';
}

/**
 * Save meta box values.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post.
 */
function ktuehk_core_save_fields( $post_id, $post ) {
	if ( ! isset( $_POST['ktuehk_core_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['ktuehk_core_nonce'] ), 'ktuehk_core_save_' . $post_id ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = ktuehk_core_fields_for( $post->post_type );
	$input  = isset( $_POST['ehk'] ) && is_array( $_POST['ehk'] ) ? $_POST['ehk'] : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitized per field below.

	foreach ( $fields as $name => $field ) {
		$raw   = isset( $input[ $name ] ) ? wp_unslash( $input[ $name ] ) : '';
		$value = ktuehk_core_sanitize_field( $raw, $field );
		$key   = ktuehk_meta_key( $name );
		if ( '' === $value || 0 === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			// update_post_meta() expects slashed data (keeps backslashes in code / LaTeX).
			update_post_meta( $post_id, $key, wp_slash( $value ) );
		}
	}
}
add_action( 'save_post', 'ktuehk_core_save_fields', 10, 2 );

/**
 * Keep the computed "effective end" of events in sync, whatever saved the
 * dates (meta box, REST API, import, WP-CLI).
 *
 * @param int $post_id Post ID.
 */
function ktuehk_core_sync_event_end( $post_id ) {
	if ( get_post_type( $post_id ) !== ktuehk_event_type() || wp_is_post_revision( $post_id ) ) {
		return;
	}
	$until = ktuehk_event_effective_end( ktuehk_field( 'baslangic', $post_id ), ktuehk_field( 'bitis', $post_id ) );
	if ( '' === $until ) {
		delete_post_meta( $post_id, ktuehk_meta_key( 'son' ) );
	} else {
		update_post_meta( $post_id, ktuehk_meta_key( 'son' ), $until );
	}
}
add_action( 'save_post', 'ktuehk_core_sync_event_end', 20 );

/**
 * Also sync when event dates are written directly as meta (REST / import).
 *
 * @param int    $meta_id  Meta ID.
 * @param int    $post_id  Post ID.
 * @param string $meta_key Meta key.
 */
function ktuehk_core_sync_event_end_on_meta( $meta_id, $post_id, $meta_key ) {
	if ( in_array( $meta_key, array( ktuehk_meta_key( 'baslangic' ), ktuehk_meta_key( 'bitis' ) ), true ) ) {
		ktuehk_core_sync_event_end( $post_id );
	}
}
add_action( 'added_post_meta', 'ktuehk_core_sync_event_end_on_meta', 10, 3 );
add_action( 'updated_post_meta', 'ktuehk_core_sync_event_end_on_meta', 10, 3 );
add_action( 'deleted_post_meta', 'ktuehk_core_sync_event_end_on_meta', 10, 3 );

/**
 * Invalidate cached project years when projects change.
 *
 * @param int $post_id Post ID.
 */
function ktuehk_core_flush_project_cache( $post_id ) {
	if ( get_post_type( $post_id ) === ktuehk_project_type() ) {
		delete_transient( 'ktuehk_project_years' );
	}
}
add_action( 'save_post', 'ktuehk_core_flush_project_cache', 30 );
add_action( 'deleted_post', 'ktuehk_core_flush_project_cache' );
add_action( 'trashed_post', 'ktuehk_core_flush_project_cache' );
