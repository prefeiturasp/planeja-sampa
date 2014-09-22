<?php
/*
Template Name:  METAS - Interna de metas
*/
?>
<!-- JAVASCRIPT -->
<script>
	
	$( document ).ready(function() {
		var total = 0;
		if($('.boxMetas .divFicha .detalhamento .detEsq').size()){ total += 1;}
		if($('.boxMetas .divFicha .detalhamento .detDir').size()){ total += 1;}
		if(total==0){
			$('.detalhamento').hide();
		}else{
			$('.detalhamento').show();
		}
	});
	
</script>
<?
	$post = get_post($_GET['meta']); 
	$articulacao = get_post_meta($post->ID, 'articulacao', true);
	$articulacaoText = get_post_meta($post->ID, 'articulacao-texto', true);
	$secretaria = get_post_meta($post->ID, 'secretaria', true);
	$valor = get_post_meta($post->ID, 'valor', true);
	$definicoes = get_post_meta($post->ID, 'definicoes', true);
	$oquevaiserentregue = get_post_meta($post->ID, 'oquevaiserentregue', true);
	$observacoes = get_post_meta($post->ID, 'observacoes', true);
	$cronograma = get_post_meta($post->ID, 'cronograma', true);
	
	$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
	
	$sql = $wpdb->get_results("SELECT 
								t2.name AS objetivo,
								t2a.description AS objetivoDescription,
								t3.name AS eixo,
								t3.slug AS eixoSlug,
								t3a.description AS eixoDescription
								
							FROM wp_posts p1
										
											LEFT JOIN wp_term_relationships r1 ON r1.object_id = p1.ID
											LEFT JOIN wp_term_taxonomy t1 ON t1.term_taxonomy_id = r1.term_taxonomy_id
								  			LEFT JOIN wp_terms t2 ON t2.term_id = t1.term_id
								  			LEFT JOIN wp_term_taxonomy t2a ON t2a.term_id = t2.term_id
								  			LEFT JOIN wp_terms t3 ON t3.term_id = t2a.parent
								  			LEFT JOIN wp_term_taxonomy t3a ON t3a.term_id = t3.term_id
									WHERE p1.ID = '".$post->ID."'");
									
	$eixo = explode("eixo",$sql[0]->eixoSlug);
?>  
<div class="boxMetas eixo<?=$eixo[1];?>">
	<a href="javascript:$.fancybox.close();" class="fechar">fechar X</a>
	<div class="divFicha">
		<div class="titulo">
			<h2><strong>META <?php echo $post->menu_order;?></strong><br><span><?php echo $post->post_title;?></span></h2>
			<?php if($thumb[0]!=''){ ?>
            	<img src="<?php echo $thumb[0]; ?>" width="221" height="139" alt="<?php echo $post->post_title;?>" class="imgIco" />
            <?php } ?>
		</div>
		<h3>Eixo Temático <?=$eixo[1];?>. <?php echo $sql[0]->eixoDescription;?></h3>
		<h3>Objetivo temático associado</h3>
		<p><strong><?php echo $sql[0]->objetivo;?>.</strong> <?php echo $sql[0]->objetivoDescription;?></p>
		<?php if($secretaria!=''){ ?>
	        <h3>secretaria e unidade responsável</h3>
			<p><?=$secretaria;?></p>
        <?php } ?>
        <?php if($articulacao!=''){ ?>
	        <h3><?=$articulacao;?></h3>
			<p><?=$articulacaoText;?></p>
        <?php } ?>
		
		<div class="detalhamento">
			<h4>Detalhamento da Meta</h4>
            <?php if($definicoes!=''){ ?>
                <div class="detEsq">
                    <h5>Definições de termos técnicos</h5>
                    <p><?=$definicoes;?></p>
                </div>
            <?php } ?>
			<?php if($oquevaiserentregue!=''){ ?>
                <div class="detDir">
                    <h5>O que vai ser entregue?</h5>
                    <p><?=$oquevaiserentregue;?></p>
                </div>
            <?php } ?>
		</div>
        
        <?php if($observacoes!=''){ ?>
	        <h3>Observações</h3>
			<p><?=$observacoes;?></p>
        <?php } ?>
        <?php if($valor!=''){ ?>
	        <h3>custo total da meta</h3>
			<p><?=$valor;?></p>
        <?php } ?>
        <?php if($cronograma!=''){ ?>
	        <h3>cronograma de entrega</h3>
			<p><?=$cronograma;?></p>
        <?php } ?>
		
	</div>
    <?
		if (is_user_logged_in() ) {
	?>
	<form action="#" method="post" id="commentform" onSubmit="return false">
            <fieldset>
                <legend>Deixe seu comentário</legend>
            <fieldset>
            <div class="divForm">
                <textarea name="commentMeta" id="commentMeta" style="width:100%"></textarea>
                <?php echo comments_number( '<p></p>', '<p class="comentarios"><strong>1</strong> <span>comentário</span></p>', '<p class="comentarios"><strong>%</strong> <span>comentários</span></p>' ) ?>
                <input type="button" onClick="fMetaComments();" value="enviar" class="enviar" />
                <input type='hidden' name='comment_post_ID' value='<?php echo $post->ID; ?>' id='comment_post_ID' />
                <input type='hidden' name='comment_post_TYPE' id='comment_post_TYPE' value='json' />
       		</div>
	</form>
    <?php }else{ ?>
        <p class="nlogado">
            Para habilitar os comentários, por favor, faça seu 
            <a href="<?php echo esc_url(home_url("/wp-login.php?action=login&redirect_to=index.php/programa-de-metas/metas/")); ?>" class="login-button">login</a> ou faça um <a href="<?php echo esc_url(home_url("/wp-login.php?action=register&redirect_to=index.php/programa-de-metas/metas/")); ?>" class="login-button">cadastro</a>.
        </p>
    <?
        }
    ?>
    <!--ul class="recentes">
        <li><a href="#" title="Mais recentes">Mais recentes</a> </li>
        <li>| </li>
        <li><a href="#" title="Mais antigos">Mais antigos</a> </li>
        <li>| </li>
        <li><a href="#" title="Populares">Populares</a></li>
    </ul-->
        
    <div class="divComentarios">
       
       <?
		$comments = get_comments(array('post_id' => $post->ID));
		foreach($comments as $comment) :
			$comentario = explode("COMENTÁRIO META:",$comment->comment_content);
	   ?>
       	<!--Comentarios-->
        <div class="boxComentarios">
        	<!--img src="http://10.10.81.39:81/PlanejaSampa/wp-content/themes/planejasampa/metas/img/icoPerfil.jpg" width="44" height="45" alt="" class="imgPerfil" /-->
            <div class="blocoComentarios">
            	<span class="nome"><?php echo $comment->comment_author;?></span>
                <p><?php echo $comentario[1];?></p>
            </div>
        </div>
       <?php     
			endforeach;
	   ?> 
        
    </div>
</div>