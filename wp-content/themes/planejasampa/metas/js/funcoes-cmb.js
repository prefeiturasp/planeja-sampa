
// JavaScript Document
$( document ).ready(function() {
	
	//FILTROS BUSCA
	
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
	
	
});

