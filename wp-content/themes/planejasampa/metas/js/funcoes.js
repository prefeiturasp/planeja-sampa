
// JavaScript Document
$( document ).ready(function() {
	
	// Modo visualização
	$(".modo2").click(function(){
		$('#divObjetivos').addClass('modoVis2');
		$('#divObjetivos h5, #divObjetivos p,#metas #miolo .blocoObjetivos h4 img').hide()
		return false;
	});
	$(".modo1").click(function(){
		$('#divObjetivos').removeClass('modoVis2');
		$('#divObjetivos h5, #divObjetivos p,#metas #miolo .blocoObjetivos h4 img').show()
		return false;
	});
	
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
	
	//IR AO TOPO
	$('.footerRedes .topo').click(function(){
        $('html, body').animate({
            scrollTop:0
        }, 'slow');
    });
	
	$(window).scroll(function(){
		if ($(this).scrollTop() > 200) {
		   $('.footerRedes').slideDown();
		    $('#divBusca').addClass('flutua');
		   $('#btnflutua').show();	
		} else {
		   $('.footerRedes').slideUp();
		   $('#divBusca').removeClass('flutua');
		   $('#btnflutua').hide();
		}
		
	});	


// MODAL BOX

	//select all the a tag with name equal to modal
	$('a[name=modal]').click(function(e) {
		//Cancel the link behavior
		e.preventDefault();
		
		//Get the A tag
		var id = $(this).attr('href');
	
		//Get the screen height and width
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();
	
		//Set heigth and width to mask to fill up the whole screen
		$('#mask').css({'width':maskWidth,'height':maskHeight});
		
		//transition effect		
		$('#mask').fadeIn();	
		$('#mask').fadeTo();	
	
		//Get the window height and width
		var winH = $(window).height();
		var winW = $(window).width();
              
		//Set the popup window to center
		$(id).css('top',  winH/2-$(id).height()/2);
		$(id).css('left', winW/2-$(id).width()/2);
	
		//transition effect
		$(id).fadeIn(); 
	
	});
	
	//if close button is clicked
	$('#closeContact').click(function (e) {
		
		//Cancel the link behavior
		e.preventDefault();
		$('#mask').hide();
		$('.window').hide();
	});		
	
	//if mask is clicked
	$('#mask').click(function () {
		$(this).hide();
		$('.window').hide();
	});		
	
	//FILTROS BUSCA
	
	$('#metas #miolo #buscaObjetivos .filtroeixo1').click(function(){
		if($(this).is('.active')){
			$('#metas #miolo #buscaObjetivos .filtroeixo1').removeClass('active');
			$('.chkeixo1').val(' ');
		}else{
			$('#metas #miolo #buscaObjetivos .filtroeixo1').addClass('active');
			$('.chkeixo1').val('eixo1');
		}
        
		fLoadTargets();
		return false;
	});
	
	
	$('#metas #miolo #buscaObjetivos .filtroeixo2').click(function(){
		if($(this).is('.active')){
			$('#metas #miolo #buscaObjetivos .filtroeixo2').removeClass('active');
			$('.chkeixo2').val(' ');
		}else{
			$('#metas #miolo #buscaObjetivos .filtroeixo2').addClass('active');
			$('.chkeixo2').val('eixo2');;
		}
		fLoadTargets();
		return false;
	});
	
	$('#metas #miolo #buscaObjetivos .filtroeixo3').click(function(){
		if($(this).is('.active')){
			$('#metas #miolo #buscaObjetivos .filtroeixo3').removeClass('active');
			$('.chkeixo3').val(' ');
		}else{
			$('#metas #miolo #buscaObjetivos .filtroeixo3').addClass('active');
			$('.chkeixo3').val('eixo3');
		}
        fLoadTargets();
		return false;
	});
	
	
	$('#metas #miolo #buscaCompleta .articulacao').click(function(){
		$('#metas #miolo #buscaCompleta .articulacao ul').slideToggle();
		return false;
	});
	$('#metas #miolo #buscaCompleta .objetivo').click(function(){
		$('#metas #miolo #buscaCompleta .objetivo ul').slideToggle();
		return false;
	});
	$('#metas #miolo #buscaCompleta .secretaria').click(function(){
		$('#metas #miolo #buscaCompleta .secretaria ul').slideToggle();
		return false;
	});
	
	$('#metas #miolo #buscaCompleta .articulacao ul li').click(function(){
		var clickLista = '';
		clickLista = $(this).text();
		$('#metas #miolo #buscaCompleta .articulacao > li').text(clickLista);
		
		$('.slctArticulacao option').html(clickLista);
		$('.slctArticulacao option').val(clickLista);
		fLoadCmbs()
	});
	
	$('#metas #miolo #buscaCompleta .objetivo ul li').click(function(){
		var clickLista = '';
		clickLista = $(this).text();
		clickListaVal = $(this).attr("data-val");
		$('#metas #miolo #buscaCompleta .objetivo > li').text(clickLista);
		
		$('.slctObjetivo option').html(clickLista);
		$('.slctObjetivo option').val(clickListaVal);
		fLoadCmbs()
	});
	
	$('#metas #miolo #buscaCompleta .secretaria ul li').click(function(){
		var clickLista = '';
		clickLista = $(this).text();
		$('#metas #miolo #buscaCompleta .secretaria > li').text(clickLista);
		
		$('.slctSecretaria option').html(clickLista);
		$('.slctSecretaria option').val(clickLista);
		fLoadCmbs()
	});
	
	
	$('.btnflutua a').click(function(){
		$('#divBusca').toggleClass('active');
		return false;
	});
	
});


function fCloseWindow(){
	$('#mask').hide();
	$('.window').hide();	
}