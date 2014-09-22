<?php get_header(); ?>
	<?php if ( is_home() ):?>
         <div id="slider">
            <ul class="bxslider">
              	<!-- LOOP SLIDER HOME -->
		        <?php 
		        	$wp_query = new WP_Query( 
		        		array(
							'posts_per_page'=>3,
		        			'post_type' => 'slider', 
		        			'orderby'=>'menu_order',
		        			'order' => 'ASC'
		        			)
		        		);
		        	while ( $wp_query->have_posts()):$wp_query->the_post();
		        ?>
				        <li>
				        	<?php the_post_thumbnail(); ?>
				        	<div class="legenda">
				        		<?php the_title(); ?>
				        	</div>
				        	<a href="<?php the_permalink(); ?>" title="saiba mais" class="saiba">saiba mais</a></li>
		        <?php 
		        	endwhile;
		        ?>
		        <!-- LOOP SLIDER HOME -->
            </ul>
        </div>
    <?php endif; ?>  
    
    <!-- DESTAQUES PÁGINAS -->
    <div class="divDestaques">
    	<div class="wrap">
            <div class="boxDestaque">
            	<h1 class="titulo">Destaques</h1>
					<?php $programaMetas = get_post_meta("27", "resumo", true); ?>
                    <a href="<?php echo esc_url( home_url( '/index.php/programa-de-metas' ) ); ?>" class="programa">Programa de Metas</a>
                    <h2>Programa de Metas</h2>
                    <p><?php echo $programaMetas;?></p>
            </div>
            <div class="boxDestaque">
					<?php $planoPlurianual = get_post_meta("29", "resumo", true); ?>
                    <a href="<?php echo esc_url( home_url( '/index.php/plano-plurianual' ) ); ?>" class="plano">Plano plurianual</a>
                    <h2>planO Plurianual</h2>
                    <p><?php echo $planoPlurianual;?></p>
            </div>
            <div class="boxDestaque">
					<?php $leisDireOrc = get_post_meta("33", "resumo", true); ?>
                   <a href="<?php echo esc_url( home_url( '/index.php/leis-de-diretrizes-orcamentarias' ) ); ?>" class="diretrizes">Leis de Diretrizes Or&ccedil;ament&aacute;rias</a>
                    <h2>Leis de Diretrizes Or&ccedil;ament&aacute;rias</h2>
                    <p><?php echo $leisDireOrc;?></p>
            </div>
            <div class="boxDestaque terceiro">
					<?php $leisOrc = get_post_meta("31", "resumo", true); ?>
                    <a href="<?php echo esc_url( home_url( '/index.php/leis-orcamentarias-anuais' ) ); ?>" class="leis">Leis Or&ccedil;ament&aacute;rias Anuais</a>
                    <h2>Leis Or&ccedil;ament&aacute;rias Anuais</h2>
                    <p><?php echo $leisOrc;?></p>
            </div>
            <div class="boxDestaque">
					<?php $conselho = get_post_meta("35", "resumo", true); ?>
                    <a href="<?php echo esc_url( home_url( '/index.php/conselho-de-planejamento-e-orcamento-participativo' ) ); ?>" class="conselho">Conselho de Planejamento e Or&ccedil;amento Participativos</a>
                    <h2>Conselho de Planejamento e Or&ccedil;amento Participativos</h2>
                    <p><?php echo $conselho;?></p>
            </div>
         </div>
    </div>
    <!-- DESTAQUES PÁGINAS -->
    
    
    <div class="wrap">
    	<h3>Not&iacute;cias</h3>
        <a href="<?php echo esc_url( home_url( '/index.php/noticias' ) ); ?>" title="Veja todas as not&iacute;cias" class="veja">Veja todas as not&iacute;cias</a>
        
        <!-- LOOP NOTICIAS HOME -->
        <?php 
        	$wp_query = new WP_Query( array('post_type' => 'noticias', 'posts_per_page' => 3, 'order' => 'DESC'));
        	$count=0;
        	while ( $wp_query->have_posts() ) : $wp_query->the_post();
        ?>
		        <div class="boxNoticias <?php echo ($count==1?"centro":"")?>">
	        		
	        			<span class="data"><?php the_time('d/m/Y'); ?></span>
		            	<h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
		            	<?php the_excerpt(); ?>		            
		            <a href="<?php the_permalink(); ?>" title="Saiba mais" class="saibaNoticias">Saiba mais</a>
		        </div>
        <?php 
        	$count++;
        	endwhile;
        ?>
        <!-- LOOP NOTICIAS HOME -->

        <h3>Agenda</h3>
    </div>
    <div class="divAgenda">
    	<!-- LOOP AGENDA HOME -->
    	<?php 
    		$events = eo_get_events(array(
			     'get_posts'=>3,
			     'showpastevents'=>false,//Will be deprecated, but set it to true to play it safe.
				'order'=>'ASC'       
			));
			//echo "<pre>";
			//print_r($events);
			$count=0;
			foreach( $events as $post ){
				setup_postdata($post);
				$startDate = explode("-",$post->StartDate);
        ?>
        		<div class="boxAgenda <?php echo ($count==2?"last":"")?>">
			        	<span class="data"><?php echo $startDate[2]."/".$startDate[1]."/".$startDate[0]; ?></span>
			            <h4><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
			            
						<?php 
							$venue = eo_get_venue_name();
							if($venue!=''){
								$address = eo_get_venue_address($venue);
								echo '<p class="endereco">';
								echo $venue."<br/>(".$address['address']." - ".$address['city']."/".$address['state'].")";
								echo '</p>';	
							}
			
							if($post->StartTime!=''){
								$timeStart = explode(":",substr($post->StartTime,0,5));
								$timeFinish = explode(":",substr($post->FinishTime,0,5));
								echo '<p class="horario">'; 
									echo (substr($timeStart[0],0,1)=='0'?substr($timeStart[0],1,1):$timeStart[0]).($timeStart[1]!='00'?":".$timeStart[1]:'')."h".($post->FinishTime!=""?" &agrave;s ".(substr($timeFinish[0],0,1)=='0'?substr($timeFinish[0],1,1):$timeFinish[0]).($timeFinish[1]!='00'?":".$timeFinish[1]:'')."h":"");
								echo '</p>';
							}
						?>
		        </div>
        <?php 
        	$count++;
			}
        ?>
        <!-- LOOP AGENDA HOME -->
    	
    </div>
    <div class="wrap">
    	<h3>Redes Sociais</h3>
        <div class="divRedes">
            <h4>Facebook</h4>
            <div class="boxRedes">
				<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FSEMPLASP&amp;width=297&amp;height=403&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false&amp;appId=414769835295526" scrolling="no" frameborder="0" style="border:none; overflow:hidden;" title="Facebook">Desculpe, Seu browser não suporta frames.</iframe>
            </div>
        </div>
        
        <div class="divRedes">
            <h4>Twitter</h4>
            <div class="boxRedes" style="padding:0.688em 0.813em 0 1.125em;">
            	<div class="social-inner">
					<a class="twitter-timeline" href="http://twitter.com/SEMPLASP?widgetId=387943626036084737">Tweets by @SEMPLASP</a>
				</div>
            </div>
        </div>
        
        <div class="divRedes">
            <h4>Youtube</h4>
            <div class="boxRedes">
            	<?php echo do_shortcode('[youtubechannel channelname="UCWt0lT3VDLjqWDoCxckEh1A" numvideos="2" width="280" showtitle="No"]') ?>
				<a href="http://www.youtube.com/channel/UCWt0lT3VDLjqWDoCxckEh1A" title="Veja mais v&iacute;deos no canal da Sempla" target="_blank" class="btnVejaVideos">Veja mais v&iacute;deos no canal da Sempla</a>
            </div>
        </div>
    </div>

<?php get_footer(); ?>