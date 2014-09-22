<?php get_header(); ?>

 <div class="wrap">
	<div class="agenda">
		<h1>Agenda</h1>
		<div class="divAgendaInterna">
		<?php 
			if ( have_posts() ) : 
				while ( have_posts() ) : the_post();
				$startDate = explode("-",$post->StartDate);
		?>
               <div class="blocoAgenda"> 
                    <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
                    <span class="data"><?php echo $startDate[2]."/".$startDate[1]."/".$startDate[0]; ?></span>
                    <h2><?php the_title(); ?></h2>
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
					</a>
                    <a href="<?php the_permalink() ?>" title="Saiba mais" class="saibamais">Saiba mais</a>
               </div>
	
    	<?php 
	    		endwhile;
	    	endif;
	    ?>
	  	
		</div>
		<?php if(function_exists('wp_paginate')) {
		    wp_paginate();
		} ?>
	</div>
</div>

<?php get_footer(); ?>
