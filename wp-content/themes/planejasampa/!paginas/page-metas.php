<?php
/*
Template Name:  METAS - Lista de metas
*/
?>
<?php get_header(); ?>
       
   <?php
	   	$postid = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'conheca-o-programa'");
	   	$programaMetas = get_post_meta($postid, "resumo", true);
	?>
       <h2 class="programa"><img src="<?php echo get_template_directory_uri(); ?>/metas/img/icoMetas.gif" width="78" height="26" alt="PROGRAMA DE METAS" /><br />PROGRAMA DE METAS</h2> 
        <div class="divIntro">
        <p class="intro"><?php echo $programaMetas;?><a href="<?php echo esc_url( home_url( '/index.php/programa-de-metas/metas/conheca-o-programa/' ) ); ?>" title="saiba mais">saiba mais &gt;&gt;</a></p>
		</div>
    <div id="lista-artigos">
    	<!--BUSCA-->
        <div id="divBusca">
			<span class="btnflutua"><a href="javascript:void(0);" title="Busca Rápida">Busca Rápida</a></span>        	
        	<div class="wrap">
                <h2 class="programa"><img src="<?php echo get_template_directory_uri(); ?>/metas/img/icoMetas.gif" width="78" height="26" alt="PROGRAMA DE METAS"><br>PROGRAMA DE METAS</h2>
                <form id="buscaObjetivos" class="busca">
                    <legend>Busca</legend>
                        <div class="divEixos">
                            <?
                                 for($e=1;$e<4;$e++){ 
				                                 
                                    $sql0001 = $wpdb->get_results("SELECT 
								t1.name,t1.slug,t2.description FROM wp_terms t1
                                        LEFT JOIN wp_term_taxonomy t2 ON t2.term_id = t1.term_id
                                            WHERE t1.slug = 'eixo".$e."' ");          
                                    foreach ($sql0001 as $sqlr0001){
                                        echo '
                                            <div class="filtro'.$sqlr0001->slug.' active">
                                                <strong>'.$sqlr0001->name.'</strong>
                                                <span><a title="'.$sqlr0001->description.'">'.$sqlr0001->description.'</a></span>
                                            </div>
                                            <input type="checkbox" value="'.$sqlr0001->slug.'" class="chk'.$sqlr0001->slug.'" name="eixo[]"  checked />';
                                    }
                                 }
                            ?>
                        </div>
                </form>   
                
                <form id="buscaCompleta" class="busca">
                    <div id="returnCmb">
                        <select class="slctArticulacao" name="articulacao">
                            <option value="">ARTICULAÇÃO</option>
                        </select>
                        <select class="slctObjetivo" name="objetivos">
                            <option value="">OBJETIVO</option>
                        </select>
                        <select class="slctSecretaria" name="secretaria">
                            <option value="">SECRETARIA</option>
                        </select>
                        
                        
                        <?
                            for($e=1;$e<4;$e++){ 
                                echo '<input type="hidden" name="eixo[]" value="eixo'.$e.'" />';
                            }
                            
                            $o1 = 0;
                            for($e=1;$e<4;$e++){ 
                                $sql002 = $wpdb->get_results("
                                    SELECT t1
                                        FROM wp_terms t1
                                            INNER JOIN wp_term_taxonomy t2 ON t2.parent = (SELECT t0.term_id 
                                                                                            FROM wp_terms t0 
                                                                                                WHERE t0.slug = 'eixo".$e."')
                                                WHERE t2.term_id = t1.term_id"); 
                                foreach ($sql002 as $sqlr002){
                                    $arrayObjetivos[$o1]['title'] = $sqlr002->name;
                                    $arrayObjetivos[$o1]['slug'] = $sqlr002->slug;
                                $o1++;}
                            }
                            
                            $met=0; 
                            $sql003 = $wpdb->get_results("
                                SELECT 
                                        p2.meta_key,
                                        p2.meta_value							
                                            FROM wp_posts p1
                                                LEFT JOIN wp_postmeta p2 ON p2.post_id = p1.ID
                                                    WHERE p1.post_type = 'metas' 
                                                        AND p1.post_status = 'publish'
                                                        AND p2.meta_value <> ''						
                                                        AND (p2.meta_key = 'articulacao' OR 
                                                             p2.meta_key='secretaria')
                                                                GROUP BY p2.meta_key,
                                                                         p2.meta_value");          
                            foreach ($sql003 as $sqlr003){
                                $arrayMeta[$sqlr003->meta_key][$met]['value'] = $sqlr003->meta_value;
                                $met++;
                            }
                        ?>
                        <div class="filtrosSelect">
                            <ul class="articulacao">
                                <li>ARTICULAÇÃO</li>
                                <ul>
                                    <?
                                        foreach($arrayMeta['articulacao'] as $art){
                                            echo '<li>'.$art[value].'</li>';
                                        }
                                    ?>
                                </ul>
                            </ul>
                            <ul class="objetivo">
                                <li>OBJETIVO</li>
                                <ul>
                                     <?
                                        for($obj=0;$obj<count($arrayObjetivos);$obj++){
                                            echo '<li data-val="'.$arrayObjetivos[$obj]['slug'].'">'.$arrayObjetivos[$obj]['title'].'</li>';
                                        }
                                    ?>
                                </ul>
                            </ul>
                            <ul class="secretaria">
                                <li>SECRETARIA</li>
                                <ul>
                                    <?
                                        foreach($arrayMeta['secretaria'] as $sec){
                                            echo '<li>'.$sec[value].'</li>';
                                        }
                                    ?>
                                </ul>
                            </ul>
                        </div>
                     </div>
                     <input type="submit" class="btbuscar" />
                </form>
                <!--BUSCA-->
              </div>
        </div>
        <!--FIM BUSCA-->
        
         <!--VISUALIZACAO-->
        <div class="visualizacao">
        	<div class="contentVis">
            	<span><strong>VISUALIZAÇÂO</strong> 
                	<a href="javascript:void(0);" title="Modo de visualização 1" class="modo1"><img src="<?php echo get_template_directory_uri(); ?>/metas/img/icoVis1.gif" width="25" height="23" alt="Visualização Tipo 1" /> </a>
                    <a href="javascript:void(0);" title="Modo de visualização 2" class="modo2"><img src="<?php echo get_template_directory_uri(); ?>/metas/img/icoVis2.gif" width="25" height="23" alt="Visualização tipo 2" /></a>
                    <!--<span class="desenv"><strong>DESENVOLVEDORES</strong> 
                    	<a href="#" title=""><img src="<?php echo get_template_directory_uri(); ?>/metas/img/icoDesenv.gif" width="25" height="23" alt="Desenvolvedores" /></a>-->
                    </span>
            	</span>
        	</div>
        </div>
        <!--FIM VISUALIZACAO-->
         
        <div class="list wrap" id="divObjetivos">
            <div id="list-metas"></div>
            <script>$('#list-metas').load('<?php echo esc_url(home_url('/index.php/programa-de-metas/metas/lista-de-metas/'));?>');</script>
        </div>
        
        <div class="footerRedes">
        	<a href="http://www.facebook.com/SEMPLASP" target="_blank" title="Facebook" class="redes"><img src="<?php echo get_template_directory_uri(); ?>/metas/img/icoFacebook.gif" width="29" height="29" /></a>
            <a href="https://twitter.com/SEMPLASP" target="_blank" title="Twitter" class="redes"><img src="<?php echo get_template_directory_uri(); ?>/metas/img/icoTwitter.gif" width="29" height="29" /></a>
             <a href="http://www.youtube.com/channel/UCWt0lT3VDLjqWDoCxckEh1A" target="_blank" title="Youtube" class="redes"><img src="<?php echo get_template_directory_uri(); ?>/metas/img/icoyoutube.gif" width="29" height="29" /></a>
             
             <a href="#divcontato" name="modal" title="Contato" class="contato"><img src="<?php echo get_template_directory_uri(); ?>/metas/img/icoMail.gif" width="29" height="29" /> <strong>contato</strong></a>
                <a href="#" title="Topo" class="topo"><img src="<?php echo get_template_directory_uri(); ?>/metas/img/icoTopo.gif" width="29" height="29" /> <strong>topo</strong></a>
        </div>
        <?php /*
        <script type="text/javascript" src="<?php echo bloginfo('template_url'); ?>/js/list-metas.min.js?<?php echo time(); ?>"></script>
		<script type="text/javascript">
            jQuery(document).ready(function () {

                var options = {
                    valueNames: [ 'name', 'type' ]
                };

                var hackerList = new List('lista-artigos', options);				

                jQuery('input[name="view-type"]').on('click', function () {
                    if (this.value === 'list') {
                        jQuery('#lista-artigos').addClass('modo-lista');
                    }else {
                        jQuery('#lista-artigos').removeClass('modo-lista');
                    }
                });
            });
        </script>
		*/ ?>
   </div>
<?php get_footer(); ?>