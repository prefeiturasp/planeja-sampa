// JavaScript Document
$( document ).ready(function() {
	//FUNCAO INPUT DEFAULT
	$(".defaultText").focus(function(srcc)
    {
		$(this).attr('value', '');
        if ($(this).val() == $(this)[0].title)
        {
            $(this).removeClass("defaultTextActive");
            $(this).val("");
        }
    });
    
    $(".defaultText").blur(function()
    {
        if ($(this).val() == "")
        {
            $(this).addClass("defaultTextActive");
            $(this).val($(this)[0].title);
        }
    });
    $(".defaultText").blur();        
	
	
	//SUBMENU ACESSIVEL
	function abreMenu(){
		$('#menu .nav li:first-child').find('ul').show();
		$( "#menu .nav li" ).first().addClass('active');
	}

	if($(window).width() > 767 ) {
		$('.submenu').hide();
		$("#menu .nav li:first-child").hover(function () {
			abreMenu();
		})
		$("#menu .nav li:first-child").on('keydown', function () {
			abreMenu();
		});
		$(".wrap").mouseleave(function () {
			$('#menu .nav ul').parent().find('ul.submenu').stop().slideUp();
			$('#menu .nav li a.apresentacao').removeClass('active');
			$( "#menu .nav li" ).first().removeClass('active');
		});
		$("body").click(function(){
			$('ul.submenu').hide();
		});
	}
	
	//SLIDER
	$('.bxslider').bxSlider({
	  mode: 'fade',
	  auto: true,
	  pause: 6000
	});
	
	//MENU PEQUENO
	$("#menu .menuPeq").click(function () {
		$('#menu .nav, #menu .menuDestaques').slideToggle();
		$('#menu').toggleClass('ativo');
		return false;
	});
	
	//FILTRO BIBLIOTECA
	$(".blocoBiblioteca .filtro a").hover(function () {
		$(this).toggleClass('active');
	});
	
	//CONNECT FACEBOOK
	(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=456398634424514";
			  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));


		//<![CDATA[
		if (typeof newsletter_check !== "function") {
			window.newsletter_check = function (f) {
			    var re = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-]{1,})+\.)+([a-zA-Z0-9]{2,})+$/;
			    if (!re.test(f.elements["ne"].value)) {
			        alert("The email is not correct");
			        return false;
			    }
			    if (f.elements["ny"] && !f.elements["ny"].checked) {
			        alert("You must accept the privacy statement");
			        return false;
			    }
			    return true;
			}
		}
		stLight.options({
            publisher:'f01f47c0-fb16-42f8-a3f9-d6e499803773',
            doNotHash: true, doNotCopy: true, hashAddressBar: false
	    });
	
	//CONNECT TWITTER
	!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
	
	$('#twitter-widget-0 .count-o').hide();
	
	//APLICA ALLOWTRANSPARENCY NOS IFRAMES
	$("iframe").attr('allowTransparency', 'true');
	
	//HOVER ICONES
	$('.boxDestaque').hover(function(){
		$(this).find('h2, p').toggleClass('active');
	});
});