/**
 * File customizer-control.js.
 *
 * global: blank_customizer
 */

( function( document, blank, $, { customize } ) {
  customize.controlConstructor[ 'blank-basic' ] = customize.Control.extend( {
    ready() {
      // console.log( 'basic control ready' )
    },
  } )

  customize.controlConstructor[ 'blank-dropdown' ] = customize.Control.extend( {
    ready() {
      // console.log( 'dropdown control ready' )
    },
  } )

  customize.controlConstructor[ 'blank-typography' ] = customize.Control.extend( {
    ready() {
      const $typo = document.querySelectorAll( '.blank-control-typography' )

      if ( $typo ) {
        $typo.forEach( () => {
          // const $family  = $typo.querySelector( '.blank-control-typography-family' )
          // const $size    = $typo.querySelector( '.blank-control-typography-size' )
          // const $variant = $typo.querySelector( '.blank-control-typography-variant' )
        } )
      }
    },
  } )
} )( document, window.blank_customizer, jQuery, wp )
