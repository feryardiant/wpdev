/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( document ) {
  const $container = document.querySelector( '#wpbp-panel' )

  const activateTap = ( tab ) => {
    const $panel = $container.querySelector( '#panel-sections' )

    if ( ! tab ) {
      tab = '#' + $panel.querySelector( 'section' ).id
    }

    $container.querySelectorAll( '.active' ).forEach( ( $active ) => {
      $active.classList.remove( 'active' )
    } )

    $container.querySelector( `nav a[href="${ tab }"]` ).parentNode.classList.add( 'active' )
    $panel.querySelector( tab ).classList.add( 'active' )
  }

  activateTap( location.hash )

  $container.querySelectorAll( 'nav a' ).forEach( ( $tab ) => {
    $tab.addEventListener( 'click', ( e ) => {
      e.preventDefault()

      const $activated = $container.querySelector( 'nav li.active' )

      if ( $activated ) {
        $activated.classList.remove( 'active' )
      }

      e.target.parentNode.classList.toggle( 'active' )

      activateTap( e.target.hash )
      history.replaceState( null, null, e.target.hash )
    } )
  } )
} )( document )
