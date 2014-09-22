// JavaScript Document

function fLoadComments(id){
	$(".img-loader").show();
	$.post("load-comentarios",{postId:id},			
		function(data){
			$(".returnComments").html(data);
		}		
	)
	
}


function fInsertComments(id){
    $("#loading").show();
	var comment = "";
	var form = "#commentform-"+id
	
	if($(form).find("#text_Contribuicao").val()!=''){
		comment += "<strong>Contribuicao:</strong> " + $(form).find("#text_Contribuicao").val();
	}
	if($(form).find("#text_Justificativa").val()!=''){
		comment += " <br><br><strong>Justificativa:</strong> " + $(form).find("#text_Justificativa").val();
	}
	
	$.post("../../wp-comments-post.php",
		{
			comment : comment,
			textContribuicao : $(form).find("#text_Contribuicao").val(),
			textJustificativa : $(form).find("#text_Justificativa").val(),
			opiniao : $(form).find("#opiniao").val(),
			contribuicao : $(form).find("input[type=radio]:checked").val(),
			comment_post_ID : $(form).find("#comment_post_ID").val(),
			comment_post_TYPE : $(form).find("#comment_post_TYPE").val()
		},			
		function(data){
			
			if(data.ret==1){
		
				$(form)[0].reset();
				$(form).find("#opiniao").val("");
				$(form).find("#PlaceHolder").val("Sua opinião");
				$(form).parent().parent().slideUp();
				alert("Sua contribuição foi enviada para aprovação!");
				$("#loading").hide();
				fLoadComments(id);
                
			}else{
				alert("Impossível enviar comentário!");
			}
			
		}, "json"	
	)
	
}

function fMetaComments(){
	$("#loading").show();
	var comment = "";
	if($("#commentform").find("#commentMeta").val()!=''){
		comment += "<strong>COMENTÁRIO META:</strong> " + $("#commentform").find("#commentMeta").val();
	}
	
	
	$.post("../../../wp-comments-post.php",
		{
			comment : comment,
			commentMeta : $("#commentform").find("#commentMeta").val(),
			comment_post_ID : $("#commentform").find("#comment_post_ID").val(),
			comment_post_TYPE : $("#commentform").find("#comment_post_TYPE").val()
		},			
		function(data){
			
			if(data.ret==1){
		
				$("#commentform")[0].reset();
				$("#loading").hide();
				alert("Seu comentário foi enviado para aprovação!");
				
			}else{
				alert("Impossível enviar comentário!");
			}
			
		}, "json"	
	)
	
}

/**
* CARREGAR VALORES DOS COMBOS NA BUSCA DE METAS
*/	
function fLoadTargets(){
	$.post("lista-de-metas/load-cmb/",$("#buscaObjetivos").serialize(),
	function(data){
		if(data!=''){
			$("#returnCmb").html(data);
		}
	})
    
	$("#loading").show();
	$.post("lista-de-metas",$("#buscaObjetivos").serialize(),	
	function(data){
		if(data!=''){
			$("#list-metas").html(data);
			$("#loading").hide();
			
		}
	})
}

function fLoadCmbs(){
	
	$("#loading").show();
	$.post("lista-de-metas",$("#buscaCompleta").serialize(),	
	function(data){
		if(data!=''){
			$("#list-metas").html(data);
			$("#loading").hide();
			
		}
	})
}


$( document ).ready(function() {
	/*BOX LOGIN*/
	$("#conteudo .contEsq .main-text .comment").click(function () {
		$('.boxLogin').slideUp();
		$(this).parent().next().slideToggle();
	});
	$('.cancel-comment').click(function(){
		$('.boxLogin').slideUp();
	});
	$('.minict_wrapper').click(function () {
		$(this).parent().find('ul').first().slideToggle();
	});
	/*SELECT FORM*/
	$('.minict_wrapper ul li').click(function(){
		valSelect = '';
		valSelect = $(this).html();
		$('.minict_wrapper input').val(valSelect);
	});
	$('.minict_wrapper ul li.concordo').click(function(){
		$('.selectOpiniao').val("concordo");
		$('.agreed').hide();
		$('.contribContainer').hide();
	});
	$('.minict_wrapper ul li.concordo-com-ressalvas').click(function(){
		$('.selectOpiniao').val("concordo-com-ressalvas");
		$('.agreed').show();
		$('.contribContainer').hide();
		$('.agreed .concordo-com-ressalvas').show();
		$('.agreed .nao-concordo').hide();
	});
	$('.minict_wrapper ul li.nao-concordo').click(function(){
		$('.selectOpiniao').val("nao-concordo");
		$('.agreed').show();
		$('.agreed .nao-concordo').show();
		$('.agreed .concordo-com-ressalvas').hide();
		$('.contribContainer').hide();
	});
	$('.altera').click(function(){
		$('.contribContainer label').html('Proposta de alteração');
		$('.contribContainer').children("#text_Contribuicao").attr("name","alteracao");
		$('.contribContainer').show();
	});
	$('.acrecimo').click(function(){
		$('.contribContainer label').html('Dispositivo a ser acrescentado');
		$('.contribContainer').children("#text_Contribuicao").attr("name","acrescimo");
		$('.contribContainer').show();
	});
	$('.exclusao').click(function(){
		$('.contribContainer').hide();
	});
	$('.proposta').click(function(){
		$('.contribContainer label').html('Nova redação');
		$('.contribContainer').children("#text_Contribuicao").attr("name","redacao");
		$('.contribContainer').show();
	});
	
	/*LEIA MAIS*/
	$('.read-more a').click(function(){
		$('.details').show();
		$(this).hide();
		$('.pontos').hide();
	});
	$('.read-less a').click(function(){
		$('.text-comment .details').hide();
		$('.read-more a').show();
		$('.pontos').show();
	});
	
	
	
	// CARACTERES INPUT
	$(function(){
	 
		/*
			Keyup é um evento que é disparado sempre que o usuário tirou o dedo da tecla.
			Ou seja, não queremos fazer nada quando o usuário clica, somente quando ele solta
			a tecla.
		*/
		$(".maxlength").keyup(function(event){
	 
			// abaixo algumas variáveis que iremos utilizar.
	 
			// pega a span onde esta a quantidade máxima de caracteres.
			var target    = $(this).parent().find('p').find('span#content-countdown');
	 
			// pego pelo atributo title a quantidade maxima permitida.
			var max        = target.attr('title');
	 
			// tamanho da string dentro da textarea.
			var len     = $(this).val().length;
	 
			// quantidade de caracteres restantes dentro da textarea.
			var remain    = max - len;
	 
			// caso a quantidade dentro da textarea seja maior que
			// a quantidade maxima.
			if(len > max)
			{
				// abaixo vamos pegar tudo que tiver na string e limitar
				// a quantidade de caracteres para o máximo setado.
				// isso significa que qualquer coisa que seja maior que
				// o máximo será cortado.
				var val = $(this).val();
				$(this).val(val.substr(0, max));
	 
				// setamos o restante para 0.
				remain = 0;
			}
	 
			// atualizamos a quantidade de caracteres restantes.
			target.html(remain);
	 
		});
	 
	});
});