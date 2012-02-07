// @codekit-prepend js/underscore.min.js
// @codekit-prepend js/bootstrap-twipsy.js
// @codekit-prepend plugins/hover-intent.js
// @codekit-prepend plugins/jquery.history.adapter.min.js
// @codekit-prepend libs/history.min.js
// @codekit-prepend plugins/history.html4.min.js
// @codekit-prepend plugins/jquery.sap.min.js


Modernizr.addTest( 'cssmask', Modernizr.testAllProps('mask') );

$(document).ready(function(){


	if( !!window.prettyPrint )
		window.prettyPrint();

	// Social links get special settings
	$('#header .social a').twipsy({
		placement : 'below',
		offset : 5
	});

	$('[data-twipsy]').each(function(){
		$(this).twipsy({
			placement : $(this).data('tooltip-align') || 'above',
			offset: $(this).data('tooltip-offset') || 0
		});
	});

	// Widescreen only
	if( Modernizr.mq('screen and (min-width: 57em)') && !Modernizr.touch ){
		$('.project-nav-container').hoverIntent({
			sensitivity: 30,
			over : function(e){ $(this).stop().animate({height: $('.container', this).outerHeight() }, 'fast'); },
			out : function(e){ $(this).stop().animate({height: '2.5em' }, 'fast'); }
		}).sap({
			width:'100%',
			distanceFromTheTop: $('#header .bar').height()
		});
		
		$('#header .bar').sap({width:'100%'});
	}

	/** Single Project Pages **/
	$.Project = function(){

		var v = {
			title : '',
			ajaxurl : '/wp-admin/admin-ajax.php',
			loader : '.loader',
			isAccessible : true,
			accessibleAttr: 'left',
			nav : '#project-nav',
			navContainer : '.project-nav-container',
			container : '#playpen',
			display : '.display',
			details : '.details',
			desc : '.description',
			tmpl : _.template( $('#proj-tmpl').html() || '' ),
			speed : 250,
			current : 0,
			currentID : 0,
			total : 0,
			activeClass : 'active'
		}

		/** Initial Setup **/
		function setupImageDetails(){

			v.title = $('article header h1').first().text();
			v.contHeight = $(v.container).height();
			v.total = $('[data-action]', v.nav).length;
			v.current = $('li.active', v.nav).index();

			// Add the loader to the page
			v.$loader = $('<div class="'+v.loader.slice(1)+'" />').appendTo('article header').hide();

			// Set the height of the parent
			//$( window ).load(function(){
				//$(v.container).parent().height( v.contHeight );
			//});

			// Switch the navigation active class to the anchor element
			$('li.'+v.activeClass, v.nav)
				.removeClass(v.activeClass)
				.children('a')
				.addClass(v.activeClass);
		}

		function setupEvents(){
			// Nav thumbnail clicks
			$('body').delegate(v.nav+' a', 'click', function(e){
				getProjectItem(this);
				e.preventDefault();
			});

			// Arrow Keys
			$(window).keyup(function(e){
				switch( e.keyCode ){
					case 37 : previous(); break;
					case 39 : next(); break;
				}
			});
		}

		/** Initial history setup **/
		function setupHistoryEvents(){
			v.currentID = $('.display', v.container).data('id');

			// Replace the initial history state with the current, so we
			// can grab state data when the user goes back to the original
			$history.replace({
				id : v.currentID,
				title : $('.title', v.container).text(),
				desc : $('.description', v.container).html(),
				html : $('.display', v.container).html()
			}, window.title, window.location.href);

			$history.bind('statechange', function(){
				var state = $history.getState();
				if( !_.isEmpty(state.data) ) updatePage(state.data);
			});
		}

		/** Go to the Next Item **/
		function next(){
			var next = (v.current+1 < v.total) ? v.current+1 : 0;
			getProjectItem( $('li:eq('+next+')', v.nav).children('a').first() );
		}

		/** Go to the Previous Item **/
		function previous(){
			var prev = (v.current-1 >= 0) ? v.current-1 : v.total-1;
			getProjectItem( $('li:eq('+prev+')', v.nav).children('a').first() );
		}

		/** Go grab the requested item **/
		function getProjectItem( element ){
			if( typeof element !== 'object' )
				return false;

			if( $(element).data('id') == v.currentID )
				return false;

			$.ajax({
				data : {
					action: $(element).data('action'),
					id: $(element).data('id'),
					href: $(element).data('href') 
				},
				success : function(data){ success( data, element ) }
			});
		}

		/** If the request succeeded **/
		function success( data, element ){

			if( ! data.error && parseInt(data) != -1 ){
				$history.push( data, $history.formatTitle(data.title), $(element).prop('href') );
			}else{
				console.log( data );
			}
		}

		function updatePage( data ){
			updateDisplay(data);
			updateNavigation(data.id, v.currentID);
		}

		/** Perform the DOM transformations and animations **/
		function updateDisplay( data ){
			$(v.container)
				.parent().height(v.contHeight)
				.end().fadeOut( v.speed, function(){

					$(this).html( v.tmpl(data) );

					if( $('img', this).length && ! $('img', this).prop('complete') ){
						$('img', this).last().load(function(e){ showContent() });
					}else{
						showContent();
					}
				});
		}

		function showContent(){
			v.contHeight = $(v.container).css('visibility', 'hidden').show().height();
			v.$loader.fadeOut('fast');

			$(v.container)
				.hide().css('visibility', 'inherit')
				.parent().animate({height : v.contHeight}, v.speed )
				.end().fadeIn( Math.round( v.speed * 0.8, 0 ) );

			$(v.navContainer).trigger('mouseout');
		}

		function updateNavigation( newID, oldID ){
			findNavItem(oldID).removeClass(v.activeClass);
			findNavItem(newID).addClass(v.activeClass);
			v.current = findNavItem(newID).parent().index();
			v.currentID = newID;
		}

		function findNavItem( id ){ return $(v.nav).find('a[data-id="'+id+'"]') }

		function setupAjax(){
			$.ajaxSetup({
				url : v.ajaxurl,
				timeout : 4000,
				type : 'GET',
				dataType : 'json'
			});
			$(v.container)
				.ajaxError(ajaxError)
				.ajaxComplete(ajaxComplete)
				.ajaxSuccess(ajaxSuccess)
				.ajaxStart(ajaxStart)
				.ajaxStop(ajaxStop);
		}

		function ajaxError(event, xhr, settings, error){
			
		}

		function ajaxComplete(event, xmlhttp, settings){
			
		}

		function ajaxSuccess(event, xmlhttp, settings){
			
		}

		function ajaxStart(){
			v.$loader.fadeIn('fast');
		}

		function ajaxStop(){
			//v.$loader.fadeOut('fast');
		}

		return {
			init : function(obj){
				$.extend(v,obj);
				if( $(v.container).length ){
					setupImageDetails();
					setupAjax();
					setupEvents();
					setupHistoryEvents();
				}
			}
		}
	};


	// Object to track the visitors JS history
	$.History = function(){

		var v = {
			history : null,
			title : ''
		};

		function formatTitle( title ){

			return title + " | " + v.title;
		}

		return {
			bind : function(event, f){ return v.history.Adapter.bind(window, event, f) },
			getState : function(){ return v.history.getState() },
			push : function(data, title, url){
				v.history.pushState(data, title, url);
				if( !!window._gaq ) _.gaq.push(['_trackPageview', url]);
			},
			replace : function(data, title, url){ v.history.replaceState(data, title, url) },
			back : function(){ v.history.back() },
			go : function(state){ v.history.go(state) },
			get : function(item){
				return eval("v."+item);
			},
			formatTitle : function(title){ return formatTitle(title) },
			init : function(){
				v.history = window.History;
				v.title = document.title;
			}
		}
	};

	$history = $.History();
	$project = $.Project();
	
	$history.init();
	$project.init();
});



// Custom Capitalize function
String.prototype.capitalize = function() {
	return this.charAt(0).toUpperCase() + this.slice(1);
}