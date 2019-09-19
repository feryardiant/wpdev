/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( document, $, customize ) {

	// Site title and description.
	customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

	// Update site background color...
	customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	customize( 'wpbp[wpbp_site_logo_display]', function( value ) {
		value.bind( function( to ) {
			$branding = document.querySelector( '.site-branding' );

			if ( 'none' === to ) {
				$branding.classList.add( 'hidden' )
			} else {
				$branding.classList.remove( 'hidden' )
			}
		} )
	} )

	// Update site background color...
	customize( 'background_color', function( value ) {
		value.bind(function (to) {
			document.documentElement.style.setProperty( '--background-color', to )
		} );
	} );

	[
		'link_color',
		'link_hover_color',
		'link_active_color',
		'text_color',
		'paragraph_color',
		'heading_color'
	].map( function ( color ) {
		// Update site link color in real time...
		customize( `wpbp[custom_${color}]`, function( value ) {
			value.bind( function( to ) {
				color = color.replace(/_/g, '-')
				document.documentElement.style.setProperty( `--${color}`, to )
			} );
		} );
	} )


	// Header text color.
	customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );
} )( document, jQuery, wp.customize );
