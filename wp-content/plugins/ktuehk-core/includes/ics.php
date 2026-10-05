<?php
/**
 * "Takvime ekle": serves a single event as an .ics file at
 * {event-url}?ics=1
 *
 * @package KTUEHK_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL of the calendar file for an event.
 *
 * @param int|WP_Post|null $post Post.
 * @return string
 */
function ktuehk_event_ics_url( $post = null ) {
	return add_query_arg( 'ics', '1', get_permalink( $post ) );
}

/**
 * Escape text for iCalendar.
 *
 * @param string $text Text.
 * @return string
 */
function ktuehk_core_ics_escape( $text ) {
	$text = wp_strip_all_tags( html_entity_decode( (string) $text, ENT_QUOTES, 'UTF-8' ) );
	return str_replace( array( '\\', ';', ',', "\r\n", "\n" ), array( '\\\\', '\;', '\,', '\n', '\n' ), $text );
}

/**
 * Output the .ics file.
 */
function ktuehk_core_serve_ics() {
	if ( ! isset( $_GET['ics'] ) || ! is_singular( ktuehk_event_type() ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}
	$post  = get_queried_object();
	$start = ktuehk_event_start( $post );
	if ( ! $start ) {
		return;
	}
	$end = ktuehk_event_end( $post );
	if ( ! $end ) {
		$end = $start->modify( '+2 hours' );
	}
	$utc      = new DateTimeZone( 'UTC' );
	$location = ktuehk_field( 'konum', $post );
	$summary  = has_excerpt( $post ) ? $post->post_excerpt : wp_trim_words( wp_strip_all_tags( $post->post_content ), 40 );

	$lines = array(
		'BEGIN:VCALENDAR',
		'VERSION:2.0',
		'PRODID:-//KTU EHK//Etkinlikler//TR',
		'CALSCALE:GREGORIAN',
		'METHOD:PUBLISH',
		'BEGIN:VEVENT',
		'UID:' . $post->ID . '@' . wp_parse_url( home_url(), PHP_URL_HOST ),
		'DTSTAMP:' . gmdate( 'Ymd\THis\Z' ),
		'DTSTART:' . $start->setTimezone( $utc )->format( 'Ymd\THis\Z' ),
		'DTEND:' . $end->setTimezone( $utc )->format( 'Ymd\THis\Z' ),
		'SUMMARY:' . ktuehk_core_ics_escape( get_the_title( $post ) ),
		'DESCRIPTION:' . ktuehk_core_ics_escape( $summary . "\n\n" . get_permalink( $post ) ),
		'URL:' . esc_url_raw( get_permalink( $post ) ),
	);
	if ( $location ) {
		$lines[] = 'LOCATION:' . ktuehk_core_ics_escape( $location );
	}
	$lines[] = 'END:VEVENT';
	$lines[] = 'END:VCALENDAR';

	nocache_headers();
	header( 'Content-Type: text/calendar; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="' . sanitize_file_name( $post->post_name ) . '.ics"' );
	header( 'X-Robots-Tag: noindex' );
	// Lines longer than 75 octets must be folded (RFC 5545 §3.1).
	foreach ( $lines as $line ) {
		$out = '';
		while ( strlen( $line ) > 74 ) {
			$cut  = 74;
			// Do not split a multi-byte UTF-8 character.
			while ( $cut > 0 && ( ord( $line[ $cut ] ) & 0xC0 ) === 0x80 ) {
				--$cut;
			}
			$out .= substr( $line, 0, $cut ) . "\r\n ";
			$line = substr( $line, $cut );
		}
		echo $out . $line . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- text/calendar output, escaped above.
	}
	exit;
}
add_action( 'template_redirect', 'ktuehk_core_serve_ics' );
