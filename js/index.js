var Taberna = {};

Taberna.startLoading = function(){
	$("#loader").addClass("active");
};

Taberna.finishLoading = function(){
	$("#loader").removeClass("active");
};

Taberna.menuInitialize = function(){
	$("nav a").click(function(){
		$("nav a.selected").removeClass("selected");
		$(this).addClass("selected");

		$("body").click();
	});

	$("#menuVisibility").click(function(e){
		e.stopPropagation();
		$("nav ul").toggleClass("active");
	});

	$("body").click(function(){
		$("nav ul.active").removeClass("active");
	});

	$(".nano-content").scroll(function(){
		var topDistance = $("#menuAnchor").offset().top;
		if(topDistance < 20){
			$("nav").addClass("fixed");
			$("#side-menu").addClass("fixed");
			$("#menuAnchor").addClass("fixed");
		}else{
			$("nav").removeClass("fixed");
			$("#side-menu").removeClass("fixed");
			$("#menuAnchor").removeClass("fixed");
		}
	});
}

Taberna.initialize = function(scrollToMenu){
	$(".nano").nanoScroller();
	if(scrollToMenu) {
		if($("#menuAnchor").offset().top < 0)
			$(".nano").nanoScroller({scrollTo:$("#menuAnchor")});
	} else {
		$(".nano").nanoScroller({scroll:'top'});
	}
	
	Taberna.eventLinks();
};

Taberna.eventLinks = function(){
	$("a").each(function(){

		var target  = $(this).prop('target');
		var href    = $(this).prop('href');
		var managed = $(this).data('managed') === true;

		if(target.length > 1 || typeof window.history !== 'object' || managed)
			return;

		$(this).data('managed',true);

		$(this).click(function(e){
			
			e.stopPropagation();
			e.preventDefault();

			Taberna.loadContent(href, true);

			return false;
		});
	});

	$("#contact-send").click(function(){
		Taberna.sendMessage();
	});

	$('audio').mediaelementplayer();
};

Taberna.loadContent = function(href, regHistory){
	Taberna.startLoading();
	var request = $.get(href);
	
	request.done(function(data, status, xhr){
		var container = $("#wrapper-content");
		
		container.animate({
			'opacity' : 0
		},{
			'duration':200,
			'complete': function(){
				container.html(data);
				container.animate({
					'opacity' : 1
				},{
					'duration' : 200
				});

				FB.XFBML.parse();

				Taberna.initialize(true);
			}
		});

		document.title = xhr.getResponseHeader("X-TabernaHTMLTitle") || 'Taberna da Centopeia Perneta';

		if(typeof ga === 'function'){
			var urlSize = document.location.href.length - document.location.pathname.length;
			ga('send', 'pageview', href.substr(urlSize));
		}

		if(regHistory)
			window.history.pushState({'href':href},'Taberna da Centopeia Perneta', href);
	});

	request.fail(function(e){
		console.error("Error at request " + href + ": " + e);
	});

	request.always(function(){
		Taberna.finishLoading();
	});
}

Taberna.sendMessage = function(){
	var name = $("#data-name").val();
	var email = $("#data-email").val();
	var message = $("#data-message").val();

	var request = $.post($("#data-form").val(),{
		"data-name" : name,
		"data-email" : email,
		"data-message" : message
	});
		
	$("#contact-send").html("Obrigado!");
	setTimeout(function(){
		$("#menu li.middle a").click();
	},1000);

	return false;
}

$(document).ready(function(){
	$(".nano").nanoScroller();

	Taberna.initialize();
	Taberna.menuInitialize();
});


window.onpopstate = function(e){
	Taberna.loadContent("" + document.location, false);
	return false;
};