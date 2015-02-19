/*!
 * @author: Pojo Team
 */
/* global jQuery */

( function( $ ) {
	'use strict';

	var Pojo_News_Ticker_App = {
		cache: {
			$document: $( document ),
			$window: $( window )
		},
		
		cacheElements: function() {},

		buildElements: function() {},

		bindEvents: function() {
			$( 'ul.pojo-news-ticker' ).each( function() {
				$( this ).pojoNewsTicker( $( this ).data( 'ticker_options' ) );
			} );
		},
		
		init: function() {
			this.cacheElements();
			this.buildElements();
			this.bindEvents();
		}
	};

	$( document ).ready( function( $ ) {
		Pojo_News_Ticker_App.init();
	} );

}( jQuery ) );
