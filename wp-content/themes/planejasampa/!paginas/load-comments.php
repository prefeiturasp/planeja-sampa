<?php
/*
Template Name: Load Comentários
*/
	$arrOpiniao = array ("concordo" => "agree", 
						 "concordo-com-ressalvas" => "so-so", 
						 "nao-concordo" => "not-agree");
	$arrOpiniaoText = array ("concordo" => "Concordo com o Dispositivo", 
						 	 "concordo-com-ressalvas" => "Concordo com o Dispositivo com ressalvas", 
						 	 "nao-concordo" => "Discordo do Dispositivo");
							 
	$arrContribuicao = array ("redacao" => "return", 
						 	  "alteracao" => "change", 
						 	  "acrescimo" => "add", 
						 	  "exclusao" => "remove");
	$arrContribuicaoText = array ("redacao" => "Proposta de nova redação", 
						 	  	  "alteracao" => "Alteração no Texto", 
						 	  	  "acrescimo" => "Acréscimo de novo dispositivo", 
						 	  	  "exclusao" => "Exclusão");
						 
	$args = array('post_id' => $_POST[postId]);
	$comments = get_comments($args);
	foreach($comments as $comment) :
		$opiniao = get_comment_meta( $comment->comment_ID, 'opiniao', true );
		$contribuicao = get_comment_meta( $comment->comment_ID, 'contribuicao', true );
		$contribText = get_comment_meta( $comment->comment_ID, 'textContribuicao', true );
		$justificativa = get_comment_meta( $comment->comment_ID, 'textJustificativa', true );

?>
            <p class="datetime"><span class="date"><?php echo get_comment_date( 'j/m/Y', $comment->comment_ID );?></span> </p>
            <p class="author"><?php echo $comment->comment_author;?></p>
            <p class="infobar">
                <i class="icon-<?php echo $arrOpiniao[$opiniao]?>" title="<?php echo $arrOpiniaoText[$opiniao]?>"></i>
                <i class="icon-text-<?php echo $arrContribuicao[$contribuicao]?>" title="<?php echo $arrContribuicaoText[$contribuicao]?>"></i>
            </p>
            <div class="text-comment">
                <?php if($contribText!=''){?>
                	<strong>Contribuição</strong>: <?php echo $contribText;?><br>
                <?php } ?>
                <?php if($justificativa!=''){?>
                	<strong>Justificativa</strong>: <?php echo $justificativa;?><br><br><br>
                <?php } ?>
                
            </div>
<?php     
	endforeach;
?>