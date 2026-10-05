/* KTÜ EHK Çekirdek — media pickers for image / gallery fields. */
( function ( $ ) {
	'use strict';

	var l10n = window.ktuehkCoreAdmin || {};

	$( document ).on( 'click', '[data-ehk-media-select]', function ( event ) {
		event.preventDefault();

		var $box = $( this ).closest( '[data-ehk-media]' );
		var isGallery = $box.data( 'ehk-media' ) === 'gallery';
		var $input = $box.find( '[data-ehk-media-input]' );
		var $preview = $box.find( '[data-ehk-media-preview]' );
		var $clear = $box.find( '[data-ehk-media-clear]' );
		var current = String( $input.val() || '' ).split( ',' ).filter( Boolean );

		var frame = wp.media( {
			title: isGallery ? l10n.selectGallery : l10n.selectImage,
			button: { text: isGallery ? l10n.useGallery : l10n.useImage },
			library: { type: 'image' },
			multiple: isGallery ? 'add' : false
		} );

		frame.on( 'open', function () {
			var selection = frame.state().get( 'selection' );
			current.forEach( function ( id ) {
				var attachment = wp.media.attachment( id );
				attachment.fetch();
				selection.add( attachment );
			} );
		} );

		frame.on( 'select', function () {
			var items = frame.state().get( 'selection' ).toJSON();
			var ids = items.map( function ( item ) {
				return item.id;
			} );

			$input.val( ids.join( ',' ) ).trigger( 'change' );
			$preview.empty();

			items.forEach( function ( item ) {
				var size = item.sizes && item.sizes.thumbnail ? item.sizes.thumbnail : item;
				var $img = $( '<img>', { src: size.url, alt: '' } );
				$preview.append( isGallery ? $( '<li>' ).append( $img ) : $img );
			} );
			$clear.prop( 'hidden', ids.length === 0 );
		} );

		frame.open();
	} );

	$( document ).on( 'click', '[data-ehk-media-clear]', function ( event ) {
		event.preventDefault();
		var $box = $( this ).closest( '[data-ehk-media]' );
		$box.find( '[data-ehk-media-input]' ).val( '' ).trigger( 'change' );
		$box.find( '[data-ehk-media-preview]' ).empty();
		$( this ).prop( 'hidden', true );
	} );
}( jQuery ) );
