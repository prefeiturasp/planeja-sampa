<?php
/*
Template Name: Minuta Participativa
*/
?>
<?php get_header(); ?>

<div id="conteudo">
	<?php
		/**
		* CONTA O NUMERO DE COMENTÁRIOS EM UM POST TYPE		
		*/
		
        $commcount = $wpdb->get_var("SELECT COUNT(*) 
										FROM $wpdb->comments 
											LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
												WHERE comment_approved = '1' AND comment_parent = '0'
													AND post_type='minuta' ");
        if (0 < $commcount) $commcount = number_format($commcount);
    ?>
	<div class="contEsq">
        <h3 data-step="1" data-intro="Bem-vindo a Minuta Participativa que cria o Conselho de Planejamento e Orçamento Participativos. Clique em 'Próximo' para entender como este site funciona ou 'Pular' para sair da ajuda inicial." class="">DECRETO Nº xxx, DE xx DE xxxxx DE 2013</h3>
        <span class="totalComents">Total de comentários (<?=$commcount;?>)</span>
        
        <div class="main-text">
        	<p>Cria o Conselho de Planejamento e Orçamento Participativos – CPOP, no âmbito da Secretaria Municipal de Planejamento, Orçamento e Gestão. FERNANDO HADDAD, Prefeito do Município de São Paulo, no uso das atribuições que lhe são conferidas por lei, D E C R E T A:</p>
            <?php 
				$wp_query = new WP_Query( array('post_type' => 'minuta', 'order' => 'ASC'));
				$count = 1;
				while ( $wp_query->have_posts() ) : $wp_query->the_post();
			  ?>
                    <div class="comment-pp">
                        <p class="comment" data-step="2" <?php if($count==1){?>data-intro="Estes são os trechos da Minuta de Anteprojeto de Lei. O texto foi desenvolvido pela Prefeitura de São Paulo considerando as propostas feitas pela população da cidade. Ao clicar sobre um trecho, você verá uma atualização do lado direito do site. Clique em próximo e entenda."<?php } ?>>
                            <?php the_title(); ?>
                        </p>
                        <a class="comment-button" onClick="fLoadComments('<?php the_ID(); ?>');">
                            <img src="<?php echo get_template_directory_uri(); ?>/minuta/img/icoMinuta.gif" width="20" height="19" alt="" />
                            <?php comments_number( '', '1', '%' ); ?>
                        </a>
                     </div>
                    <div class="boxLogin">
                    	<?
							if (is_user_logged_in() ) {
						?>
                            	<p class="nlogado"></p>
                                <div class="formComment">
                                    <form action="#" method="post" id="commentform-<?php the_ID(); ?>" onSubmit="return false">
                                        <h6><i class="icon-comment-bg"></i>comentar este trecho</h6>
                                        <ol>
                                          <li>
                                            <label for="opiniao"> Sua opinião sobre o dispositivo </label>
                                            <input type="hidden" name="opiniao" id="opiniao" value="" class="selectOpiniao">
                                            <div class="minict_wrapper bubble">
                                              <input type="text" value="" placeholder="Sua opinião" id="PlaceHolder">
                                              <ul>
                                                <li data-value="concordo" class="concordo">Concordo com o Dispositivo</li>
                                                <li data-value="concordo-com-ressalvas" class="concordo-com-ressalvas">Concordo com o Dispositivo com ressalvas</li>
                                                <li data-value="nao-concordo" class="nao-concordo">Discordo do Dispositivo</li>
                                                <li class="minict_empty">No results match your keyword.</li>
                                              </ul>
                                            </div>
                                          </li>
                                          <li class="agreed">
                                            <label id="labelContribuicao">Tipo de contribuição</label>
                                            <ol>
                                              <li class="concordo-com-ressalvas">
                                                <label>
                                                  <input type="radio" name="contribuicao" value="alteracao" class="altera" />
                                                  Alteração na redação </label>
                                              </li>
                                              <li class="concordo-com-ressalvas">
                                                <label>
                                                  <input type="radio" name="contribuicao" value="acrescimo" class="acrecimo" />
                                                  Acréscimo de novo dispositivo </label>
                                              </li>
                                              <li class="nao-concordo">
                                                <label>
                                                  <input type="radio" name="contribuicao" value="exclusao" class="exclusao" />
                                                  Exclusão </label>
                                              </li>
                                              <li class="nao-concordo">
                                                <label>
                                                  <input type="radio" name="contribuicao" value="redacao" class="proposta" />
                                                  Proposta de nova redação </label>
                                              </li>
                                            </ol>
                                          </li>
                                          <li class="contribContainer">
                                            <label>Contribuição</label>
                                            <textarea name="Textcontribuicao" id="text_Contribuicao" class="maxlength" maxlength="1000"></textarea>
                                            <p id="count_Contribuicao"><span class="label">Caracteres restantes: </span><span class="total" id="content-countdown" title="1000">1000</span></p>
                                          </li>
                                          <li>
                                            <label>Justificativa</label>
                                            <textarea name="Textjustificativa" id="text_Justificativa" class="maxlength" maxlength="1000"></textarea>
                                            <p id="count_Justificativa"><span class="label">Caracteres restantes: </span><span class="total" id="content-countdown" title="1000">1000</span></p>
                                          </li>
                                          <li class="last">
                                          	<a href="#" class="cancel-comment"><i class="icon-cancel"></i>cancel</a>
                                            <input type="submit" onclick="fInsertComments('<?php the_ID(); ?>');" class="send-comment" value="enviar comentário" />
                                          </li>
                                        </ol>
                                        <input type='hidden' name='comment_post_ID' value='<?php the_ID(); ?>' id='comment_post_ID' />
                                        <input type='hidden' name='comment_post_TYPE' id='comment_post_TYPE' value='json' />
                                    </form>
                                </div>
                        <?php }else{ ?>
                                <p class="nlogado">
                                    Para habilitar os comentários, por favor, faça seu 
                                    <a href="<?php echo esc_url(home_url("/wp-login.php?action=login&redirect_to=index.php/minuta-participativa/")); ?>" class="login-button">login</a>.
                                </p>
                                <div class="formComment"></div>
                        <?
							}
						?>
                    </div>
			  <?php 
				  $count++;
				endwhile;
			  ?>
        </div>
    </div>
    <div class="contDir">
    	<div id="comments" data-step="3" data-intro="Use este espaço para enviar o seu comentário." data-position="left">
        	<h5><strong>COMENTÁRIOS</strong></h5>
            <div class="returnComments">
                <img src="<?php echo get_template_directory_uri(); ?>/minuta/img/loader.gif" width="50" height="50" class="img-loader" />
            </div>
        </div>
    </div>
</div>

	  
<?php get_footer(); ?>