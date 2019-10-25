/**
 * File customizer-control.js.
 *
 * global: blank_customizer
 */

( function( document, blank, $, { customize } ) {
  customize.controlConstructor[ 'blank-basic' ] = customize.Control.extend( {} )

  customize.controlConstructor[ 'blank-dropdown' ] = customize.Control.extend( {} )

  customize.controlConstructor[ 'blank-typography' ] = customize.Control.extend( {
    ready() {
      const { container } = this

      const $family = container.find( '.family' )
      const $variant = container.find( '.variant' )
      const $sizeUnit = container.find( '.size-unit' )
      const $heightUnit = container.find( '.height-unit' )

      this.prepare( $family.data( 'value' ) )

      $family.find( `option[value="${ $family.data( 'value' ) }"]` ).attr( 'selected', true )
      $variant.find( `option[value="${ $variant.data( 'value' ) }"]` ).attr( 'selected', true )
      $sizeUnit.find( `option[value="${ $sizeUnit.data( 'value' ) }"]` ).attr( 'selected', true )
      $heightUnit.find( `option[value="${ $heightUnit.data( 'value' ) }"]` ).attr( 'selected', true )

      container.on( 'change', '.family', this.familyChanged.bind( this ) )
      container.on( 'change', '.variant', this.variantChanged.bind( this ) )
      container.on( 'change keyup paste', '.size-value', this.sizeValueChanged.bind( this ) )
      container.on( 'change', '.size-unit', this.sizeUnitChanged.bind( this ) )
      container.on( 'change keyup paste', '.height-value', this.heightValueChanged.bind( this ) )
      container.on( 'change', '.height-unit', this.heightUnitChanged.bind( this ) )
    },

    prepare( name ) {
      const family = blank.webfonts.find( ( font ) => font.family === name ) || {}

      this.container.find( '.variant option' ).each( ( i, option ) => {
        if ( family.variants && family.variants.includes( option.value ) ) {
          option.style = {}
        } else {
          option.style.display = 'none'
        }

        if ( this.params.default.variant === option.value ) {
          option.setAttribute( 'selected', true )
        }
      } )

      return family
    },

    familyChanged( e ) {
      const webfont = this.prepare( e.target.value )

      this.save( 'family', webfont.family )
    },

    variantChanged( e ) {
      this.save( 'variant', e.target.value )
    },

    sizeValueChanged( e ) {
      const value = []

      value.push( e.target.value, this.params.value.size[ 1 ] )

      this.save( 'size', value )
    },

    sizeUnitChanged( e ) {
      const value = []

      value.push( this.params.value.size[ 0 ], e.target.value )

      this.save( 'size', value )
    },

    heightValueChanged( e ) {
      const value = []

      value.push( e.target.value, this.params.value.height[ 1 ] )

      this.save( 'height', value )
    },

    heightUnitChanged( e ) {
      const value = []

      value.push( this.params.value.height[ 0 ], e.target.value )

      this.save( 'height', value )
    },

    save( prop, newValue ) {
      const { value } = Object.assign( {}, this.params )

      if ( ! value[ prop ] ) {
        throw new TypeError( `Undefined property ${ prop }` )
      }

      value[ prop ] = newValue

      this.setting.set( value )
    },
  } )
} )( document, window.blank_customizer, jQuery, wp )
