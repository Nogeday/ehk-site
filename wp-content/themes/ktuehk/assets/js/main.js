/**
 * KTÜ EHK theme — small progressive enhancements. Everything works without
 * JavaScript; this file only improves navigation and reading.
 */
( function () {
	'use strict';

	var l10n = window.ktuehkL10n || {};
	var doc = document;
	var header = doc.querySelector( '[data-site-header]' );

	/* ---------------------------------------------------------------
	 * Header: compact state after scrolling.
	 * ------------------------------------------------------------- */
	if ( header ) {
		var ticking = false;
		var onScroll = function () {
			if ( ticking ) {
				return;
			}
			ticking = true;
			window.requestAnimationFrame( function () {
				header.classList.toggle( 'is-scrolled', window.scrollY > 8 );
				ticking = false;
			} );
		};
		window.addEventListener( 'scroll', onScroll, { passive: true } );
		onScroll();
	}

	/* ---------------------------------------------------------------
	 * Mobile navigation.
	 * ------------------------------------------------------------- */
	var navToggle = doc.querySelector( '[data-nav-toggle]' );
	var nav = doc.querySelector( '[data-nav]' );
	var navLabel = doc.querySelector( '[data-nav-toggle-label]' );

	function setNav( open ) {
		if ( ! navToggle || ! nav ) {
			return;
		}
		navToggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		nav.classList.toggle( 'is-open', open );
		doc.body.classList.toggle( 'nav-open', open );
		if ( navLabel ) {
			navLabel.textContent = open ? l10n.closeMenu || 'Menüyü kapat' : l10n.openMenu || 'Menüyü aç';
		}
	}

	if ( navToggle && nav ) {
		navToggle.addEventListener( 'click', function () {
			var open = navToggle.getAttribute( 'aria-expanded' ) !== 'true';
			setNav( open );
			setSearch( false );
			if ( open ) {
				var first = nav.querySelector( 'a, input' );
				if ( first ) {
					first.focus();
				}
			}
		} );
		window.matchMedia( '(min-width: 1024px)' ).addEventListener( 'change', function ( e ) {
			if ( e.matches ) {
				setNav( false );
			}
		} );
	}

	/* ---------------------------------------------------------------
	 * Search panel.
	 * ------------------------------------------------------------- */
	var searchToggle = doc.querySelector( '[data-search-toggle]' );
	var searchPanel = doc.querySelector( '[data-search-panel]' );

	function setSearch( open ) {
		if ( ! searchToggle || ! searchPanel ) {
			return;
		}
		searchToggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		searchPanel.hidden = ! open;
		if ( open ) {
			var input = searchPanel.querySelector( 'input[type="search"]' );
			if ( input ) {
				input.focus();
			}
		}
	}

	if ( searchToggle && searchPanel ) {
		searchToggle.addEventListener( 'click', function () {
			setSearch( searchPanel.hidden );
			setNav( false );
		} );
	}

	doc.addEventListener( 'keydown', function ( e ) {
		if ( e.key !== 'Escape' ) {
			return;
		}
		if ( searchPanel && ! searchPanel.hidden ) {
			setSearch( false );
			searchToggle.focus();
		}
		if ( nav && nav.classList.contains( 'is-open' ) ) {
			setNav( false );
			navToggle.focus();
		}
	} );

	doc.addEventListener( 'click', function ( e ) {
		if ( header && ! header.contains( e.target ) ) {
			setSearch( false );
			setNav( false );
		}
	} );

	/* ---------------------------------------------------------------
	 * Filters: keep URLs clean (no empty parameters). Filters are applied
	 * with the button, not on every change, so keyboard users can browse
	 * the options without reloading the page.
	 * ------------------------------------------------------------- */
	doc.querySelectorAll( 'form[data-clean-submit]' ).forEach( function ( form ) {
		form.addEventListener( 'submit', function () {
			form.querySelectorAll( 'select, input' ).forEach( function ( field ) {
				if ( ! field.value ) {
					field.disabled = true;
				}
			} );
		} );
	} );

	/* ---------------------------------------------------------------
	 * Code blocks: language label + copy button.
	 * ------------------------------------------------------------- */
	doc.querySelectorAll( '.prose pre' ).forEach( function ( pre ) {
		if ( pre.closest( '.code-block' ) ) {
			return;
		}
		// Long lines scroll horizontally; keep the block reachable by keyboard.
		pre.setAttribute( 'tabindex', '0' );

		var wrap = doc.createElement( 'div' );
		wrap.className = 'code-block';
		pre.parentNode.insertBefore( wrap, pre );
		wrap.appendChild( pre );

		var code = pre.querySelector( 'code' ) || pre;
		var match = ( ( code.className || '' ) + ' ' + ( pre.className || '' ) ).match( /(?:lang|language)-([\w+#-]+)/ );
		if ( match ) {
			var label = doc.createElement( 'span' );
			label.className = 'code-block__lang';
			label.textContent = match[ 1 ];
			wrap.appendChild( label );
		}

		if ( ! navigator.clipboard ) {
			return;
		}
		var button = doc.createElement( 'button' );
		button.type = 'button';
		button.className = 'code-block__copy';
		button.textContent = l10n.copy || 'Kopyala';
		button.addEventListener( 'click', function () {
			navigator.clipboard.writeText( code.innerText ).then( function () {
				button.textContent = l10n.copied || 'Kopyalandı';
				window.setTimeout( function () {
					button.textContent = l10n.copy || 'Kopyala';
				}, 1800 );
			} );
		} );
		wrap.appendChild( button );
	} );

	/* ---------------------------------------------------------------
	 * Copy page link (share).
	 * ------------------------------------------------------------- */
	doc.querySelectorAll( '[data-copy-link]' ).forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			if ( ! navigator.clipboard ) {
				return;
			}
			var status = button.closest( '.share' );
			status = status ? status.querySelector( '[data-copy-status]' ) : null;
			navigator.clipboard.writeText( button.getAttribute( 'data-copy-link' ) ).then( function () {
				if ( status ) {
					status.textContent = l10n.linkCopied || 'Bağlantı kopyalandı';
					window.setTimeout( function () {
						status.textContent = '';
					}, 2500 );
				}
			} );
		} );
	} );

	/* ---------------------------------------------------------------
	 * Table of contents: highlight the section being read.
	 * ------------------------------------------------------------- */
	var tocLinks = doc.querySelectorAll( '.toc a[href^="#"]' );
	if ( tocLinks.length && 'IntersectionObserver' in window ) {
		var byId = {};
		tocLinks.forEach( function ( link ) {
			byId[ decodeURIComponent( link.getAttribute( 'href' ).slice( 1 ) ) ] = link;
		} );
		var active = null;
		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting && byId[ entry.target.id ] ) {
						if ( active ) {
							active.removeAttribute( 'aria-current' );
						}
						active = byId[ entry.target.id ];
						active.setAttribute( 'aria-current', 'true' );
					}
				} );
			},
			{ rootMargin: '-80px 0px -70% 0px' }
		);
		Object.keys( byId ).forEach( function ( id ) {
			var target = doc.getElementById( id );
			if ( target ) {
				observer.observe( target );
			}
		} );

		// On small screens the TOC starts collapsed.
		var details = doc.querySelector( '.toc__details' );
		if ( details && window.matchMedia( '(max-width: 1199px)' ).matches ) {
			details.open = false;
		}
	}

	/* ---------------------------------------------------------------
	 * Lightbox for galleries and posters (native <dialog>).
	 * ------------------------------------------------------------- */
	var triggers = doc.querySelectorAll( 'a[data-lightbox]' );
	if ( triggers.length && typeof HTMLDialogElement === 'function' ) {
		var dialog = doc.createElement( 'dialog' );
		dialog.className = 'lightbox';
		dialog.setAttribute( 'aria-label', l10n.viewer || 'Görsel görüntüleyici' );
		dialog.innerHTML =
			'<figure class="lightbox__figure"><img class="lightbox__img" alt=""><figcaption class="lightbox__caption"></figcaption></figure>' +
			'<button type="button" class="lightbox__btn lightbox__close">&times;</button>' +
			'<button type="button" class="lightbox__btn lightbox__prev">&#8249;</button>' +
			'<button type="button" class="lightbox__btn lightbox__next">&#8250;</button>';
		dialog.querySelector( '.lightbox__close' ).setAttribute( 'aria-label', l10n.close || 'Kapat' );
		dialog.querySelector( '.lightbox__prev' ).setAttribute( 'aria-label', l10n.previous || 'Önceki görsel' );
		dialog.querySelector( '.lightbox__next' ).setAttribute( 'aria-label', l10n.next || 'Sonraki görsel' );
		doc.body.appendChild( dialog );

		var img = dialog.querySelector( '.lightbox__img' );
		var caption = dialog.querySelector( '.lightbox__caption' );
		var prev = dialog.querySelector( '.lightbox__prev' );
		var next = dialog.querySelector( '.lightbox__next' );
		var group = [];
		var index = 0;

		var show = function ( i ) {
			index = ( i + group.length ) % group.length;
			var link = group[ index ];
			var thumb = link.querySelector( 'img' );
			img.src = link.href;
			img.alt = thumb ? thumb.alt : '';
			caption.textContent = link.getAttribute( 'data-caption' ) || '';
			caption.hidden = ! caption.textContent;
			prev.hidden = next.hidden = group.length < 2;
		};

		triggers.forEach( function ( link ) {
			link.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				var container = link.closest( '[data-lightbox-group]' );
				group = container ? Array.prototype.slice.call( container.querySelectorAll( 'a[data-lightbox]' ) ) : [ link ];
				show( group.indexOf( link ) );
				dialog.showModal();
			} );
		} );

		dialog.querySelector( '.lightbox__close' ).addEventListener( 'click', function () {
			dialog.close();
		} );
		prev.addEventListener( 'click', function () {
			show( index - 1 );
		} );
		next.addEventListener( 'click', function () {
			show( index + 1 );
		} );
		dialog.addEventListener( 'click', function ( e ) {
			if ( e.target === dialog ) {
				dialog.close();
			}
		} );
		dialog.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'ArrowLeft' && group.length > 1 ) {
				show( index - 1 );
			} else if ( e.key === 'ArrowRight' && group.length > 1 ) {
				show( index + 1 );
			}
		} );
		dialog.addEventListener( 'close', function () {
			img.removeAttribute( 'src' );
		} );
	}
}() );
