/**
 * TrackTel - tracking events on telephone link taps
 * v1.2
 * Released under the MIT license
 * Last modified at 2016-10-26T11:23+09:00(JST)
 */
window.isMobile = navigator.userAgent.match(/(?:iphone|ipod|android.*mobile)/i);
window.gaEventTrackLimit = 2000;
document.addEventListener('click', function(e){
	if( window.isMobile && window.ga && e.target ) {
		var elem = e.target;
		while(elem) {
			if( elem.tagName.toLowerCase() == 'a' ) {
				var href = elem.getAttribute('href') || '';
				if( href.match(/^tel\:(.*)$/) ) {
					e.preventDefault();
					
					var tel = RegExp.$1,
						called = false,
						trackCallback = function() {
							if( called ) return false;
							called = true;
							location.href = href;
						};
					
					var id = elem.getAttribute('id'), selector;
					if( id ) {
						selector = '#'+id;
					} else {
						var j = 4, parent = elem.parentElement;
						selector = 'a';
						while( j-- ) {
							if( !parent )
								break;
							
							if( parent.getAttribute('id') ) {
								selector = '#'+parent.getAttribute('id')+ ' ' + selector;
								break;
							}
							
							selector = parent.tagName.toLowerCase() + ' ' + selector;
							parent = parent.parentElement;
						}
					}
					
					var action = 'telto-' + tel.replace(/\-/g, '');
					ga('send', 'event', 'Tel', action, selector, {
						hitCallback  : trackCallback,
						nonInteraction: true
					});
					setTimeout(trackCallback, window.gaEventTrackLimit);
				}
				break;
			}
			elem = elem.parentElement;
		}
	}
});
