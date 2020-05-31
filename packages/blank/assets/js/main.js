/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( ( document ) => {
  const isIe = /(trident|msie)/i.test( navigator.userAgent )

  if ( isIe && document.getElementById && window.addEventListener ) {
    window.addEventListener( 'hashchange', function() {
      const id = location.hash.substring( 1 )

      if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
        return
      }

      const element = document.getElementById( id )

      if ( element ) {
        if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
          element.tabIndex = -1
        }

        element.focus()
      }
    }, false )
  }

  const $siteNav = document.querySelector( '.site-navigation' )
  const $navToggle = $siteNav.querySelector( '.menu-toggle' )
  const $navMenu = $siteNav.querySelector( '#' + $navToggle.getAttribute( 'aria-controls' ) )

  $navMenu.setAttribute( 'aria-expanded', 'false' )
  $navToggle.onclick = () => {
    const isExpanded = $siteNav.classList.contains( 'is-expanded' )
    $siteNav.classList.toggle( 'is-expanded', ! isExpanded )

    if ( ! isExpanded ) {
      $navMenu.setAttribute( 'aria-expanded', 'true' )
      $navToggle.setAttribute( 'aria-expanded', 'true' )
    } else {
      $navMenu.setAttribute( 'aria-expanded', 'false' )
      $navToggle.setAttribute( 'aria-expanded', 'false' )
    }
  }

  const $menuItems = $navMenu.querySelectorAll( '.menu-item.has-children' )

  const toggleFocus = ( e ) => {
    const $menuItem = e.target.parentNode
    const isFocused = $menuItem.classList.contains( 'is-focused' )

    if ( ! isFocused ) {
      e.preventDefault()
    }

    const $siblings = $menuItem.parentNode.children

    for ( let i = 0; i < $siblings.length; i++ ) {
      const $sibling = $siblings.item( i )
      if ( $menuItem === $sibling ) {
        continue
      }

      $sibling.classList.remove( 'is-focused' )
    }

    $menuItem.classList.toggle( 'is-focused' )
  }

  const menuToggle = ( $item ) => {
    const $link = $item.querySelector( '.menu-link' )

    $link.addEventListener( 'touchstart', toggleFocus, true )
  }

  $menuItems.forEach( menuToggle )
} )( document )
