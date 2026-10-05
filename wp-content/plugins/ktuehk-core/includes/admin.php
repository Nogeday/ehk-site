<?php
/**
 * Admin experience: list table columns, filters, assets, dashboard counts.
 *
 * @package KTUEHK_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue admin assets on our edit screens.
 *
 * @param string $hook Current admin page.
 */
function ktuehk_core_admin_assets( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen ) {
		return;
	}
	$types = array( ktuehk_project_type(), ktuehk_event_type(), 'post' );
	if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) && in_array( $screen->post_type, $types, true ) ) {
		wp_enqueue_media();
		wp_enqueue_style( 'ktuehk-core-admin', KTUEHK_CORE_URL . 'assets/admin.css', array(), KTUEHK_CORE_VERSION );
		wp_enqueue_script( 'ktuehk-core-admin', KTUEHK_CORE_URL . 'assets/admin.js', array( 'jquery' ), KTUEHK_CORE_VERSION, true );
		wp_localize_script(
			'ktuehk-core-admin',
			'ktuehkCoreAdmin',
			array(
				'selectImage'   => __( 'Görsel seç', 'ktuehk-core' ),
				'selectGallery' => __( 'Galeri görsellerini seç', 'ktuehk-core' ),
				'useImage'      => __( 'Bu görseli kullan', 'ktuehk-core' ),
				'useGallery'    => __( 'Galeriyi kaydet', 'ktuehk-core' ),
			)
		);
	}
	if ( 'edit.php' === $hook && in_array( $screen->post_type, $types, true ) ) {
		wp_enqueue_style( 'ktuehk-core-admin', KTUEHK_CORE_URL . 'assets/admin.css', array(), KTUEHK_CORE_VERSION );
	}
}
add_action( 'admin_enqueue_scripts', 'ktuehk_core_admin_assets' );

/**
 * Hook list table columns once post types are known.
 */
function ktuehk_core_admin_columns_init() {
	$project = ktuehk_project_type();
	$event   = ktuehk_event_type();

	add_filter( "manage_{$project}_posts_columns", 'ktuehk_core_project_columns' );
	add_action( "manage_{$project}_posts_custom_column", 'ktuehk_core_render_column', 10, 2 );
	add_filter( "manage_edit-{$project}_sortable_columns", 'ktuehk_core_project_sortable' );

	add_filter( "manage_{$event}_posts_columns", 'ktuehk_core_event_columns' );
	add_action( "manage_{$event}_posts_custom_column", 'ktuehk_core_render_column', 10, 2 );
	add_filter( "manage_edit-{$event}_sortable_columns", 'ktuehk_core_event_sortable' );
}
add_action( 'admin_init', 'ktuehk_core_admin_columns_init' );

/**
 * Insert columns after the title.
 *
 * @param array $columns Columns.
 * @param array $insert  New columns.
 * @return array
 */
function ktuehk_core_insert_columns( $columns, $insert ) {
	$out = array();
	foreach ( $columns as $key => $label ) {
		if ( 'title' === $key ) {
			$out['ehk_thumb'] = '<span class="screen-reader-text">' . esc_html__( 'Görsel', 'ktuehk-core' ) . '</span>';
		}
		$out[ $key ] = $label;
		if ( 'title' === $key ) {
			$out = array_merge( $out, $insert );
		}
	}
	return $out;
}

/**
 * Project columns.
 *
 * @param array $columns Columns.
 * @return array
 */
function ktuehk_core_project_columns( $columns ) {
	return ktuehk_core_insert_columns(
		$columns,
		array(
			'ehk_year'     => __( 'Yıl', 'ktuehk-core' ),
			'ehk_status'   => __( 'Durum', 'ktuehk-core' ),
			'ehk_featured' => __( 'Öne çıkan', 'ktuehk-core' ),
		)
	);
}

/**
 * Event columns.
 *
 * @param array $columns Columns.
 * @return array
 */
function ktuehk_core_event_columns( $columns ) {
	return ktuehk_core_insert_columns(
		$columns,
		array(
			'ehk_start'    => __( 'Etkinlik tarihi', 'ktuehk-core' ),
			'ehk_location' => __( 'Konum', 'ktuehk-core' ),
		)
	);
}

/**
 * Render custom column cells.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function ktuehk_core_render_column( $column, $post_id ) {
	switch ( $column ) {
		case 'ehk_thumb':
			$thumb = get_post_thumbnail_id( $post_id );
			if ( ! $thumb && get_post_type( $post_id ) === ktuehk_event_type() ) {
				$thumb = absint( ktuehk_field( 'afis', $post_id ) );
			}
			echo $thumb ? wp_get_attachment_image( $thumb, array( 48, 48 ), false, array( 'class' => 'ehk-col-thumb' ) ) : '<span class="ehk-col-thumb ehk-col-thumb--empty" aria-hidden="true"></span>';
			break;
		case 'ehk_year':
			$year = ktuehk_field( 'yil', $post_id );
			echo $year ? esc_html( $year ) : '—';
			break;
		case 'ehk_status':
			$status = ktuehk_project_status( $post_id );
			echo $status ? '<span class="ehk-pill ehk-pill--' . esc_attr( $status['key'] ) . '">' . esc_html( $status['label'] ) . '</span>' : '—';
			break;
		case 'ehk_featured':
			echo '1' === ktuehk_field( 'one_cikan', $post_id ) ? '<span class="dashicons dashicons-star-filled" aria-hidden="true"></span><span class="screen-reader-text">' . esc_html__( 'Evet', 'ktuehk-core' ) . '</span>' : '';
			break;
		case 'ehk_start':
			$start = ktuehk_event_start( $post_id );
			if ( $start ) {
				echo esc_html( wp_date( get_option( 'date_format' ) . ' H:i', $start->getTimestamp() ) );
				echo ktuehk_event_is_upcoming( $post_id )
					? ' <span class="ehk-pill ehk-pill--upcoming">' . esc_html__( 'Yaklaşan', 'ktuehk-core' ) . '</span>'
					: ' <span class="ehk-pill">' . esc_html__( 'Geçmiş', 'ktuehk-core' ) . '</span>';
			} else {
				echo '<span class="ehk-muted">' . esc_html__( 'Tarih girilmedi', 'ktuehk-core' ) . '</span>';
			}
			break;
		case 'ehk_location':
			$place = ktuehk_field( 'konum', $post_id );
			echo $place ? esc_html( $place ) : ( '1' === ktuehk_field( 'cevrimici', $post_id ) ? esc_html__( 'Çevrim içi', 'ktuehk-core' ) : '—' );
			break;
	}
}

/**
 * Sortable project columns.
 *
 * @param array $columns Columns.
 * @return array
 */
function ktuehk_core_project_sortable( $columns ) {
	$columns['ehk_year'] = 'ehk_year';
	return $columns;
}

/**
 * Sortable event columns.
 *
 * @param array $columns Columns.
 * @return array
 */
function ktuehk_core_event_sortable( $columns ) {
	$columns['ehk_start'] = 'ehk_start';
	return $columns;
}

/**
 * Apply admin sorting by meta.
 *
 * @param WP_Query $query Query.
 */
function ktuehk_core_admin_sorting( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	$orderby = $query->get( 'orderby' );
	if ( 'ehk_year' === $orderby ) {
		$query->set( 'meta_key', ktuehk_meta_key( 'yil' ) );
		$query->set( 'orderby', 'meta_value_num' );
	} elseif ( 'ehk_start' === $orderby ) {
		$query->set( 'meta_key', ktuehk_meta_key( 'baslangic' ) );
		$query->set( 'orderby', 'meta_value' );
	}
}
add_action( 'pre_get_posts', 'ktuehk_core_admin_sorting' );

/**
 * Taxonomy dropdown filters above list tables.
 *
 * @param string $post_type Post type.
 */
function ktuehk_core_admin_filters( $post_type ) {
	$map = array(
		ktuehk_project_type() => array( KTUEHK_TAX_PROGRAM, KTUEHK_TAX_AREA ),
		ktuehk_event_type()   => array( KTUEHK_TAX_EVENT ),
	);
	if ( empty( $map[ $post_type ] ) ) {
		return;
	}
	foreach ( $map[ $post_type ] as $taxonomy ) {
		$tax = get_taxonomy( $taxonomy );
		if ( ! $tax ) {
			continue;
		}
		wp_dropdown_categories(
			array(
				'taxonomy'        => $taxonomy,
				'name'            => $tax->query_var,
				'value_field'     => 'slug',
				'show_option_all' => $tax->labels->all_items,
				'selected'        => isset( $_GET[ $tax->query_var ] ) ? sanitize_title( wp_unslash( $_GET[ $tax->query_var ] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification
				'hierarchical'    => true,
				'hide_empty'      => false,
				'hide_if_empty'   => true,
			)
		);
	}
}
add_action( 'restrict_manage_posts', 'ktuehk_core_admin_filters' );

/**
 * Dashboard "At a glance" counts.
 *
 * @param string[] $items Items.
 * @return string[]
 */
function ktuehk_core_glance_items( $items ) {
	foreach ( array( ktuehk_project_type(), ktuehk_event_type() ) as $type ) {
		$object = get_post_type_object( $type );
		if ( ! $object || ! current_user_can( $object->cap->edit_posts ) ) {
			continue;
		}
		$count   = (int) wp_count_posts( $type )->publish;
		$items[] = sprintf(
			'<a class="ehk-glance ehk-glance--%1$s" href="%2$s">%3$s %4$s</a>',
			esc_attr( $type ),
			esc_url( admin_url( 'edit.php?post_type=' . $type ) ),
			esc_html( number_format_i18n( $count ) ),
			esc_html( $object->labels->name )
		);
	}
	return $items;
}
add_filter( 'dashboard_glance_items', 'ktuehk_core_glance_items' );

/**
 * Settings link on the plugins screen.
 *
 * @param string[] $links Links.
 * @return string[]
 */
function ktuehk_core_action_links( $links ) {
	array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=ktuehk-core' ) ) . '">' . esc_html__( 'Ayarlar', 'ktuehk-core' ) . '</a>' );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( KTUEHK_CORE_FILE ), 'ktuehk_core_action_links' );

/**
 * Helpful placeholder for the title field.
 *
 * @param string  $text Placeholder.
 * @param WP_Post $post Post.
 * @return string
 */
function ktuehk_core_title_placeholder( $text, $post ) {
	if ( $post->post_type === ktuehk_project_type() ) {
		return __( 'Proje adı', 'ktuehk-core' );
	}
	if ( $post->post_type === ktuehk_event_type() ) {
		return __( 'Etkinlik adı', 'ktuehk-core' );
	}
	return $text;
}
add_filter( 'enter_title_here', 'ktuehk_core_title_placeholder', 10, 2 );
