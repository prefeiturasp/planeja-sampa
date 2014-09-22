// JavaScript Document
$( document ).ready(function() {
	//FUNCAO INPUT DEFAULT
	$(".defaultText").focus(function(srcc)
    {
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
	
	
	//TROCA DESTAQUES ANIMACAO
	 $('.boxDestaque .programa').hover(function () {
			$(this).stop().animate({background:'url(../_html/img/icoMetas-on.jpg)'},'slow');       
	});
	
	//SUBMENU
	$('.submenu').hide();
	$("#menu .nav li").hover(function () {
		$(this).find('ul').slideToggle();
		$(this).find('.apresentacao').toggleClass('active');
	});
	$(".wrap").mouseleave(function () {
		$('#menu .nav ul').parent().find('ul').slideUp();
		$('#menu .nav li a.apresentacao').removeClass('active');
	});
	
	$('.bxslider').bxSlider({
	  mode: 'fade'
	});
});