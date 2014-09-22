
<?php get_header(); ?>
<div class="wrap">
	<?php while ( have_posts() ) : the_post(); ?>  
	       <?php 
	       	$page = explode('/',$_SERVER[REQUEST_URI]);
	       	$n = ($page[1]=='SJ2231'?3:2);
	       	if($page[$n]=='agenda'){
	       		$startDate = explode("-",$post->StartDate);
	       ?>
		       	<div class="agenda">
			       	<h1>Agenda</h1>
			       	<div class="divAgendaInterna">
						<div class="blocoAgenda"> 
							<span class="data"><?php echo $startDate[2]."/".$startDate[1]."/".$startDate[0]; ?></span>
					        <h2><?php the_title(); ?></h2>
					        <?php the_content(); ?>
							<p>
								<!-- Does the event have a venue? -->
								<?php if( eo_get_venue() ): ?>
									<!-- Display map -->
									<?php echo eo_get_venue_map(eo_get_venue(),array('width'=>'100%')); ?>
								<?php endif; ?>
							</p>
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
					</div>
		          	
		           <div class="divComentarios">
			           <h3>Coment&aacute;rios</h3>
			           <!--
							<p class="titulo">Paula em <strong>26/09/2013</strong> disse:</p>
			           		<p>aqui tem o plano diretor, e o plano regional? que faz parte quando ser&aacute; enviado para a c&acirc;mara?</p>
			           		<a href="#" title="Responder" class="responder">Responder</a>
			            -->
			
			           <?php comments_template(); ?>
		           </div>
	           </div>
	       <?php }elseif($page[$n]=='noticia'){?>		
		       <div class="noticias">
			        <h1>Not&iacute;cias</h1>
			        <div class="blocoNoticiasInterna">
						<?php
							/*$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
							if($thumb[0]!=''){
								echo '<img src="'.$thumb[0].'" width="748" height="217" alt="" class="imgnoticias" />';
							} */
						?>
		                <span class="data"><?php the_time('d/m/Y'); ?></span>
		                <h2><?php the_title(); ?></h2>
		                <?php the_content(); ?>
		                <br clear="all" />
		                <div class="social">
							<span class='st_facebook_hcount' displayText='Facebook'></span>
							<span class='st_fblike_hcount' displayText='Facebook Like'></span>
							<span class='st_twitter_hcount' displayText='Tweet'></span>
							<span class='st_googleplus_hcount' displayText='Google +'></span>
							<span class='st_email_hcount' displayText='Email'></span>
		                </div>
		           </div>
	          	
		           <div class="divComentarios">
			           <h3>Coment&aacute;rios</h3>
			           <!--
							<p class="titulo">Paula em <strong>26/09/2013</strong> disse:</p>
			           		<p>aqui tem o plano diretor, e o plano regional? que faz parte quando ser&aacute; enviado para a c&acirc;mara?</p>
			           		<a href="#" title="Responder" class="responder">Responder</a>
			            -->
			
			           <?php comments_template(); ?>
		           </div>
	           </div>
	       <?php }elseif($page[$n]=='slider'){?>		
		       <div class="noticias">
			        <h1><?php the_title(); ?></h1>
			        <div class="blocoNoticiasInterna">
						<?php
							/*$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
							if($thumb[0]!=''){
								echo '<img src="'.$thumb[0].'" width="748" height="217" alt="" class="imgnoticias" />';
							} */
						?>
		                <span class="data"><?php the_time('d/m/Y'); ?></span>
		                <br clear="all" />
		                <?php the_content(); ?>
		                <br clear="all" />
		                <div class="social">
							<span class='st_facebook_hcount' displayText='Facebook'></span>
							<span class='st_fblike_hcount' displayText='Facebook Like'></span>
							<span class='st_twitter_hcount' displayText='Tweet'></span>
							<span class='st_googleplus_hcount' displayText='Google +'></span>
							<span class='st_email_hcount' displayText='Email'></span>
		                </div>
		           </div>
	          	
		           <div class="divComentarios">
			           <h3>Coment&aacute;rios</h3>
			           <!--
							<p class="titulo">Paula em <strong>26/09/2013</strong> disse:</p>
			           		<p>aqui tem o plano diretor, e o plano regional? que faz parte quando ser&aacute; enviado para a c&acirc;mara?</p>
			           		<a href="#" title="Responder" class="responder">Responder</a>
			            -->
			
			           <?php comments_template(); ?>
		           </div>
	           </div>
	       <?php }elseif($page[$n]=='minuta'){?>		
		       <div class="noticias">
			        <h1><?php the_title(); ?></h1>
			        <div class="blocoNoticiasInterna">
						<?php
							/*$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
							if($thumb[0]!=''){
								echo '<img src="'.$thumb[0].'" width="748" height="217" alt="" class="imgnoticias" />';
							} */
						?>
		                <span class="data"><?php the_time('d/m/Y'); ?></span>
		                <br clear="all" />
		                <?php the_content(); ?>
		                <br clear="all" />
		                <div class="social">
							<span class='st_facebook_hcount' displayText='Facebook'></span>
							<span class='st_fblike_hcount' displayText='Facebook Like'></span>
							<span class='st_twitter_hcount' displayText='Tweet'></span>
							<span class='st_googleplus_hcount' displayText='Google +'></span>
							<span class='st_email_hcount' displayText='Email'></span>
		                </div>
		           </div>
	          	
		           <div class="divComentarios">
			           <h3>Coment&aacute;rios</h3>
			           <!--
							<p class="titulo">Paula em <strong>26/09/2013</strong> disse:</p>
			           		<p>aqui tem o plano diretor, e o plano regional? que faz parte quando ser&aacute; enviado para a c&acirc;mara?</p>
			           		<a href="#" title="Responder" class="responder">Responder</a>
			            -->
			
			           <?php comments_template(); ?>
		           </div>
	           </div>
           <?php }else{ ?>
           	<div class="noticias">
			        <h1>Not&iacute;cias</h1>
			        <div class="blocoNoticiasInterna">
		                <span class="data"><?php the_time('d/m/Y'); ?></span>
		                <h2><?php the_title(); ?></h2>
		                <?php the_content(); ?>
		                <br clear="all" />
		           </div>
	           </div>
	       <?php } ?>
     <?php endwhile; ?>
</div>
<?php get_footer(); ?>