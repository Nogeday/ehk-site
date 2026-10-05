<?php
/**
 * Lightweight SEO output: meta description, Open Graph / Twitter tags,
 * canonical for archives and JSON-LD schema (Organization, WebSite,
 * BreadcrumbList, Article, Event, CreativeWork for projects).
 *
 * When a dedicated SEO plugin (Yoast, Rank Math, AIOSEO, SEOPress, ...) is
 * active, it stays in charge: we only add Event schema, which those plugins
 * do not generate for custom event posts by default.
 *
 * @package KTUEHK_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Organization data used across schema output.
 *
 * @return array
 */
function ktuehk_core_organization() {
	$logo_id = (int) get_theme_mod( 'custom_logo' );
	$logo    = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : get_site_icon_url( 512 );
	$org     = array(
		'@type'              => 'Organization',
		'@id'                => home_url( '/#organization' ),
		'name'               => __( 'KTÜ Elektronik ve Haberleşme Kulübü', 'ktuehk-core' ),
		'alternateName'      => 'KTÜ EHK',
		'url'                => home_url( '/' ),
		'parentOrganization' => array(
			'@type' => 'CollegeOrUniversity',
			'name'  => 'Karadeniz Teknik Üniversitesi',
			'url'   => 'https://www.ktu.edu.tr/',
		),
	);
	if ( $logo ) {
		$org['logo'] = array(
			'@type' => 'ImageObject',
			'url'   => $logo,
		);
	}
	$same_as = array_values( array_filter( (array) apply_filters( 'ktuehk_core_same_as', array() ) ) );
	if ( $same_as ) {
		$org['sameAs'] = $same_as;
	}
	return (array) apply_filters( 'ktuehk_core_organization', $org );
}

/**
 * Plain-text description for the current request (max ~160 chars).
 *
 * @return string
 */
function ktuehk_core_meta_description() {
	$text = '';
	if ( is_front_page() ) {
		$text = (string) apply_filters( 'ktuehk_core_home_description', get_bloginfo( 'description' ) );
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		$text = has_excerpt( $post ) ? $post->post_excerpt : wp_strip_all_tags( strip_shortcodes( excerpt_remove_blocks( $post->post_content ) ) );
	} elseif ( is_home() ) {
		$page_id = (int) get_option( 'page_for_posts' );
		$text    = $page_id && has_excerpt( $page_id ) ? get_the_excerpt( $page_id ) : (string) apply_filters( 'ktuehk_core_posts_description', '' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$text = term_description();
	} elseif ( is_post_type_archive() ) {
		$object = get_queried_object();
		$text   = $object && ! empty( $object->description ) ? $object->description : '';
		$text   = (string) apply_filters( 'ktuehk_core_archive_description', $text, $object );
	} elseif ( is_author() ) {
		$text = get_the_author_meta( 'description', get_queried_object_id() );
	}
	$text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( (string) $text ) ) );
	if ( '' === $text ) {
		$text = get_bloginfo( 'description' );
	}
	return wp_html_excerpt( $text, 158, '…' );
}

/**
 * Canonical-ish URL of the current request (without filters / paging).
 *
 * @return string
 */
function ktuehk_core_current_url() {
	if ( is_singular() ) {
		return (string) get_permalink();
	}
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_home() ) {
		$page_id = (int) get_option( 'page_for_posts' );
		return $page_id ? (string) get_permalink( $page_id ) : home_url( '/' );
	}
	if ( is_category() || is_tag() || is_tax() ) {
		$link = get_term_link( get_queried_object() );
		return is_wp_error( $link ) ? home_url( '/' ) : $link;
	}
	if ( is_post_type_archive() ) {
		return (string) get_post_type_archive_link( get_query_var( 'post_type' ) );
	}
	if ( is_author() ) {
		return get_author_posts_url( get_queried_object_id() );
	}
	return home_url( '/' );
}

/**
 * Social share image for the current request.
 *
 * @return array{url:string,width:int,height:int,alt:string}|null
 */
function ktuehk_core_share_image() {
	$image_id = 0;
	if ( is_singular() ) {
		$image_id = (int) get_post_thumbnail_id();
		if ( ! $image_id && get_post_type() === ktuehk_event_type() ) {
			$image_id = absint( ktuehk_field( 'afis' ) );
		}
	}
	if ( ! $image_id ) {
		$image_id = (int) apply_filters( 'ktuehk_core_default_share_image', 0 );
	}
	if ( $image_id ) {
		$src = wp_get_attachment_image_src( $image_id, 'large' );
		if ( $src ) {
			$alt = trim( (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true ) );
			return array(
				'url'    => $src[0],
				'width'  => (int) $src[1],
				'height' => (int) $src[2],
				'alt'    => $alt,
			);
		}
	}
	$icon = get_site_icon_url( 512 );
	return $icon ? array(
		'url'    => $icon,
		'width'  => 512,
		'height' => 512,
		'alt'    => '',
	) : null;
}

/**
 * Title used for social tags.
 *
 * @return string
 */
function ktuehk_core_social_title() {
	if ( is_front_page() ) {
		return get_bloginfo( 'name' );
	}
	if ( is_singular() ) {
		return single_post_title( '', false );
	}
	if ( is_home() ) {
		$listing = ktuehk_posts_listing();
		return $listing['name'];
	}
	if ( is_archive() ) {
		return wp_strip_all_tags( get_the_archive_title() );
	}
	return wp_get_document_title();
}

/**
 * Output meta description, canonical (archives) and social tags.
 */
function ktuehk_core_head_meta() {
	if ( ktuehk_seo_plugin_active() || is_404() || is_search() ) {
		return;
	}
	$description = ktuehk_core_meta_description();
	$url         = ktuehk_core_current_url();
	$image       = ktuehk_core_share_image();
	$is_article  = is_singular( array( 'post', ktuehk_project_type(), ktuehk_event_type() ) );
	$tags        = array();

	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}

	// WordPress prints rel=canonical for single views; add it for archives.
	if ( ! is_singular() && ! is_front_page() && ! is_paged() ) {
		$robots = ktuehk_core_robots( array() );
		if ( empty( $robots['noindex'] ) ) {
			echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
		}
	}

	$tags['og:locale']    = get_locale();
	$tags['og:type']      = $is_article ? 'article' : 'website';
	$tags['og:site_name'] = get_bloginfo( 'name' );
	$tags['og:title']     = ktuehk_core_social_title();
	$tags['og:url']       = $url;
	if ( $description ) {
		$tags['og:description'] = $description;
	}
	if ( $image ) {
		$tags['og:image']        = $image['url'];
		$tags['og:image:width']  = $image['width'];
		$tags['og:image:height'] = $image['height'];
		if ( $image['alt'] ) {
			$tags['og:image:alt'] = $image['alt'];
		}
	}
	if ( is_singular( 'post' ) ) {
		$tags['article:published_time'] = get_the_date( 'c' );
		$tags['article:modified_time']  = get_the_modified_date( 'c' );
		$cats                           = get_the_category();
		if ( $cats ) {
			$tags['article:section'] = $cats[0]->name;
		}
	}
	$tags['twitter:card'] = $image && $image['width'] >= 600 ? 'summary_large_image' : 'summary';

	foreach ( (array) apply_filters( 'ktuehk_core_social_tags', $tags ) as $property => $content ) {
		$attr = 0 === strpos( $property, 'twitter:' ) ? 'name' : 'property';
		printf( '<meta %1$s="%2$s" content="%3$s">' . "\n", esc_attr( $attr ), esc_attr( $property ), esc_attr( (string) $content ) );
	}
}
add_action( 'wp_head', 'ktuehk_core_head_meta', 2 );

/**
 * Build the JSON-LD graph for the current request.
 *
 * @return array
 */
function ktuehk_core_schema_graph() {
	$graph   = array();
	$seo     = ktuehk_seo_plugin_active();
	$project = ktuehk_project_type();
	$event   = ktuehk_event_type();

	if ( ! $seo ) {
		$graph[] = ktuehk_core_organization();
		if ( is_front_page() ) {
			$graph[] = array(
				'@type'           => 'WebSite',
				'@id'             => home_url( '/#website' ),
				'url'             => home_url( '/' ),
				'name'            => get_bloginfo( 'name' ),
				'inLanguage'      => get_bloginfo( 'language' ),
				'publisher'       => array( '@id' => home_url( '/#organization' ) ),
				'potentialAction' => array(
					'@type'       => 'SearchAction',
					'target'      => array(
						'@type'       => 'EntryPoint',
						'urlTemplate' => home_url( '/?s={search_term_string}' ),
					),
					'query-input' => 'required name=search_term_string',
				),
			);
		}

		$crumbs = ktuehk_breadcrumb_items();
		if ( count( $crumbs ) > 1 ) {
			$list = array();
			foreach ( $crumbs as $i => $crumb ) {
				$item = array(
					'@type'    => 'ListItem',
					'position' => $i + 1,
					'name'     => $crumb['name'],
				);
				$item_url = $crumb['url'] ? $crumb['url'] : ( $i === count( $crumbs ) - 1 ? ktuehk_core_current_url() : '' );
				if ( $item_url ) {
					$item['item'] = $item_url;
				}
				$list[] = $item;
			}
			$graph[] = array(
				'@type'           => 'BreadcrumbList',
				'itemListElement' => $list,
			);
		}
	}

	if ( is_singular() ) {
		$post  = get_queried_object();
		$image = has_post_thumbnail( $post ) ? wp_get_attachment_image_url( get_post_thumbnail_id( $post ), 'large' ) : '';
		$desc  = ktuehk_core_meta_description();

		if ( ! $seo && is_singular( 'post' ) ) {
			$author  = ktuehk_display_author( $post );
			$text    = wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
			$words   = count( preg_split( '/\s+/u', trim( $text ), -1, PREG_SPLIT_NO_EMPTY ) );
			$article = array(
				'@type'            => (string) apply_filters( 'ktuehk_core_article_type', 'Article' ),
				'headline'         => wp_html_excerpt( get_the_title( $post ), 110 ),
				'description'      => $desc,
				'datePublished'    => get_the_date( 'c', $post ),
				'dateModified'     => get_the_modified_date( 'c', $post ),
				'mainEntityOfPage' => get_permalink( $post ),
				'author'           => array_filter(
					array(
						'@type' => 'Person',
						'name'  => $author['name'],
						'url'   => $author['url'],
					)
				),
				'publisher'        => array( '@id' => home_url( '/#organization' ) ),
				'inLanguage'       => get_bloginfo( 'language' ),
				'wordCount'        => $words,
			);
			if ( $image ) {
				$article['image'] = array( $image );
			}
			$cats = get_the_category( $post->ID );
			if ( $cats ) {
				$article['articleSection'] = wp_list_pluck( $cats, 'name' );
			}
			$tags = get_the_tags( $post->ID );
			if ( $tags && ! is_wp_error( $tags ) ) {
				$article['keywords'] = implode( ', ', wp_list_pluck( $tags, 'name' ) );
			}
			$graph[] = $article;
		}

		if ( ! $seo && is_singular( $project ) ) {
			$work = array(
				'@type'              => 'CreativeWork',
				'name'               => get_the_title( $post ),
				'abstract'           => $desc,
				'url'                => get_permalink( $post ),
				'inLanguage'         => get_bloginfo( 'language' ),
				'sourceOrganization' => array( '@id' => home_url( '/#organization' ) ),
			);
			if ( $image ) {
				$work['image'] = $image;
			}
			$year = absint( ktuehk_field( 'yil', $post ) );
			if ( $year ) {
				$work['dateCreated'] = (string) $year;
			}
			$tech = ktuehk_field_list( 'teknolojiler', $post );
			if ( $tech ) {
				$work['keywords'] = implode( ', ', $tech );
			}
			$team = ktuehk_project_team( $post );
			if ( $team ) {
				$work['creator'] = array_map(
					static function ( $member ) {
						return array(
							'@type' => 'Person',
							'name'  => $member['name'],
						);
					},
					$team
				);
			}
			$areas = get_the_terms( $post, KTUEHK_TAX_AREA );
			if ( $areas && ! is_wp_error( $areas ) ) {
				$work['about'] = wp_list_pluck( $areas, 'name' );
			}
			$award = ktuehk_field( 'basari', $post );
			if ( $award ) {
				$work['award'] = $award;
			}
			$graph[] = $work;
		}

		if ( is_singular( $event ) && (bool) apply_filters( 'ktuehk_core_event_schema', true ) ) {
			$start = ktuehk_event_start( $post );
			if ( $start ) {
				$end    = ktuehk_event_end( $post );
				$online = '1' === ktuehk_field( 'cevrimici', $post );
				$place  = ktuehk_field( 'konum', $post );
				$signup = ktuehk_field( 'kayit', $post );
				$poster = absint( ktuehk_field( 'afis', $post ) );
				$schema = array(
					'@type'               => 'Event',
					'name'                => get_the_title( $post ),
					'description'         => $desc,
					'startDate'           => $start->format( 'c' ),
					'eventStatus'         => 'https://schema.org/EventScheduled',
					'eventAttendanceMode' => $online ? 'https://schema.org/OnlineEventAttendanceMode' : 'https://schema.org/OfflineEventAttendanceMode',
					'url'                 => get_permalink( $post ),
					'organizer'           => array(
						'@type' => 'Organization',
						'name'  => __( 'KTÜ Elektronik ve Haberleşme Kulübü', 'ktuehk-core' ),
						'url'   => home_url( '/' ),
					),
				);
				if ( $end ) {
					$schema['endDate'] = $end->format( 'c' );
				}
				if ( $online ) {
					$schema['location'] = array(
						'@type' => 'VirtualLocation',
						'url'   => $signup ? $signup : get_permalink( $post ),
					);
				} else {
					$schema['location'] = array(
						'@type'   => 'Place',
						'name'    => $place ? $place : 'Karadeniz Teknik Üniversitesi',
						'address' => array(
							'@type'           => 'PostalAddress',
							'addressLocality' => 'Trabzon',
							'addressCountry'  => 'TR',
						),
					);
				}
				$images = array_filter( array( $image, $poster ? wp_get_attachment_image_url( $poster, 'large' ) : '' ) );
				if ( $images ) {
					$schema['image'] = array_values( $images );
				}
				if ( $signup ) {
					$schema['offers'] = array(
						'@type'         => 'Offer',
						'url'           => $signup,
						'price'         => 0,
						'priceCurrency' => 'TRY',
						'availability'  => 'https://schema.org/InStock',
						'validFrom'     => get_the_date( 'c', $post ),
					);
				}
				$speakers = array();
				foreach ( ktuehk_field_lines( 'konusmacilar', $post ) as $line ) {
					$parts      = preg_split( '/\s+[—–\-|]\s+/u', $line, 2 );
					$speakers[] = array(
						'@type' => 'Person',
						'name'  => trim( $parts[0] ),
					);
				}
				if ( $speakers ) {
					$schema['performer'] = $speakers;
				}
				$graph[] = $schema;
			}
		}
	}

	return (array) apply_filters( 'ktuehk_core_schema_graph', $graph );
}

/**
 * Print JSON-LD.
 */
function ktuehk_core_print_schema() {
	if ( is_404() || is_search() ) {
		return;
	}
	$graph = ktuehk_core_schema_graph();
	if ( ! $graph ) {
		return;
	}
	$data = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ) . '</script>' . "\n";
}
add_action( 'wp_head', 'ktuehk_core_print_schema', 20 );
