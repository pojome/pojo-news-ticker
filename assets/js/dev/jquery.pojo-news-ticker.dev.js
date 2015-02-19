/*!
* @author: Pojo Team
 */

;(function ( $, window, document, undefined ) {
	var pluginName = "pojoNewsTicker",
		defaults = {
			effect: 'fade',
			delay: 2000,
			pauseHover: true
		};

	function Plugin( element, options ) {
		this.$element = $( element );
		this.settings = $.extend( {}, defaults, options );
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
	}
	
	Plugin.prototype = {
		init: function () {
			var self = this,
				$elem = self.$element,
				$children = $elem.children();
			
			self.running = true;
			
			$children.not( ':first' ).hide();

			if ( self.settings.pauseHover ) {
				$elem.on( 'mouseenter', function() {
					self.running = false;
				} ).on( 'mouseleave', function() {
						self.running = true;
					} );
			}
			
			setInterval( function() {
				if ( ! self.running ) {
					return;
				}
				var $children = $elem.children();
				$children.not( ":first" ).hide();
				var $currentItem = $children.eq( 0 );
				var $nextItem = $children.eq( 1 );

				if ( 'fade' === self.settings.effect ) {
					$currentItem.fadeOut( function() {
						$nextItem.fadeIn();
						$currentItem
							.remove()
							.appendTo( $elem );
					} );
				}
				else if ( 'slide' === self.settings.effect ) {
					$currentItem.slideUp();
					$nextItem.slideDown( function() {
						$currentItem
							.remove()
							.appendTo( $elem );
					} );
				}
			}, self.settings.delay );
		}
	};

	$.fn[ pluginName ] = function ( options ) {
		this.each( function() {
			if ( ! $.data( this, "plugin_" + pluginName ) ) {
				$.data( this, "plugin_" + pluginName, new Plugin( this, options ) );
			}
		} );

		return this;
	};
} ) ( jQuery, window, document );