/**
 * File customizer-control.js.
 *
 * global: blank_customizer
 */

( function( document, blank, $, { customize } ) {
  customize.controlConstructor[ 'blank-typography' ] = customize.Control.extend( {
    ready() {
      const { container, setting, params } = this

      const prepare = ( name ) => {
        const family = blank.webfonts.find( ( font ) => font.family === name ) || {}

        container.find( '.variant option' ).each( ( i, option ) => {
          if ( family.variants && family.variants.includes( option.value ) ) {
            option.style = {}
          } else {
            option.style.display = 'none'
          }

          if ( params.default.variant === option.value ) {
            option.setAttribute( 'selected', true )
          }
        } )

        return family
      }

      const $family = container.find( '.family' )
      prepare( $family.data( 'value' ) )
      $family.find( `option[value="${ $family.data( 'value' ) }"]` ).attr( 'selected', true )

      const $variant = container.find( '.variant' )
      $variant.find( `option[value="${ $variant.data( 'value' ) }"]` ).attr( 'selected', true )

      const $sizeValue = container.find( '.size-value' )
      const $sizeUnit = container.find( '.size-unit' )
      $sizeUnit.find( `option[value="${ $sizeUnit.data( 'value' ) }"]` ).attr( 'selected', true )

      const $heightValue = container.find( '.height-value' )
      const $heightUnit = container.find( '.height-unit' )
      $heightUnit.find( `option[value="${ $heightUnit.data( 'value' ) }"]` ).attr( 'selected', true )

      container.on( 'change', '.family', ( e ) => {
        const webfont = prepare( e.target.value )

        setting.set( {
          family: webfont.family,
          variant: $variant.val(),
          size: [ $sizeValue.val(), $sizeUnit.val() ],
          height: [ $heightValue.val(), $heightUnit.val() ],
        } )
      } )

      container.on( 'change', '.variant', ( e ) => {
        setting.set( {
          family: $family.val(),
          variant: e.target.value,
          size: [ $sizeValue.val(), $sizeUnit.val() ],
          height: [ $heightValue.val(), $heightUnit.val() ],
        } )
      } )

      container.on( 'change keyup paste', '.size-value', ( e ) => {
        setting.set( {
          family: $family.val(),
          variant: $variant.val(),
          size: [ e.target.value, $sizeUnit.val() ],
          height: [ $heightValue.val(), $heightUnit.val() ],
        } )
      } )

      container.on( 'change', '.size-unit', ( e ) => {
        setting.set( {
          family: $family.val(),
          variant: $variant.val(),
          size: [ $sizeValue.val(), e.target.value ],
          height: [ $heightValue.val(), $heightUnit.val() ],
        } )
      } )

      container.on( 'change keyup paste', '.height-value', ( e ) => {
        setting.set( {
          family: $family.val(),
          variant: $variant.val(),
          size: [ $sizeValue.val(), $sizeUnit.val() ],
          height: [ e.target.value, $heightUnit.val() ],
        } )
      } )

      container.on( 'change', '.height-unit', ( e ) => {
        setting.set( {
          family: $family.val(),
          variant: $variant.val(),
          size: [ $sizeValue.val(), $sizeUnit.val() ],
          height: [ $heightValue.val(), e.target.value ],
        } )
      } )
    },
  } )
} )( document, window.blank_customizer, jQuery, wp )
