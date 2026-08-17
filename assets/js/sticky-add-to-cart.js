/*global vonaco_sticky_add_to_cart_params */
( function() {
	"use strict";
	document.addEventListener( 'DOMContentLoaded', function() {
		var stickyAddToCart = document.getElementsByClassName( 'vonaco-sticky-add-to-cart' );

		if ( ! stickyAddToCart.length ) {
			return;
		}

		if ( typeof vonaco_sticky_add_to_cart_params === 'undefined' ) {
			return;
		}

		var trigger = document.getElementsByClassName( vonaco_sticky_add_to_cart_params.trigger_class );

		if ( trigger.length > 0 ) {
			var stickyAddToCartToggle = function() {

				if ( ( trigger[0].getBoundingClientRect().top + trigger[0].scrollHeight ) < 0 ) {
					stickyAddToCart[0].classList.add( 'vonaco-sticky-add-to-cart--slideOutUp' );
					stickyAddToCart[0].classList.remove( 'vonaco-sticky-add-to-cart--slideInDown' );
					if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
						stickyAddToCart[0].classList.add( 'vonaco-sticky-add-to-cart--slideInDown' );
						stickyAddToCart[0].classList.remove( 'vonaco-sticky-add-to-cart--slideOutUp' );
					}
				} else if ( stickyAddToCart[0].classList.contains( 'vonaco-sticky-add-to-cart--slideOutUp' ) ) {
					stickyAddToCart[0].classList.add( 'vonaco-sticky-add-to-cart--slideInDown' );
					stickyAddToCart[0].classList.remove( 'vonaco-sticky-add-to-cart--slideOutUp' );
				}
			};

			stickyAddToCartToggle();

			window.addEventListener( 'scroll', function() {
				stickyAddToCartToggle();
			} );

			// Get product id
			var product_id = null;

			document.body.classList.forEach( function( item ){
				if ( 'postid-' === item.substring( 0, 7 ) ) {
					product_id = item.replace( /[^0-9]/g, '' );
				}
			} );

			if ( product_id ) {
				var product = document.getElementById( 'product-' + product_id );

				if ( product ) {
					if ( ! product.classList.contains( 'product-type-simple' ) && ! product.classList.contains( 'product-type-external' ) ) {
						var selectOptions = document.getElementsByClassName( 'vonaco-sticky-add-to-cart__content-button' );

						selectOptions[0].addEventListener( 'click', function( event ) {
							event.preventDefault();
							document.getElementById( 'product-' + product_id ).scrollIntoView();
						} );
					}
				}
			}
		}
	} );
} )();
