<?php
/*
Template Name: Biblioteca
*/
?>
<?php get_header(); ?>
<div class="wrap pagination">
    <div class="biblioteca" id="lista-artigos">
		<h1>Biblioteca</h1>
		<div class="blocoBiblioteca">
        	<div class="input text">
            	<label for="search">Busca rápida: </label><input class="search defaultText" title="digite aqui" />
			</div>
            <div class="filtro">
            	<?php 
            		$lnk = explode('/',$_SERVER[REQUEST_URI]);
            		$n = ($lnk[1]=='SJ2231'?4:3);
            	?>
            	<label for="tipo">Tipos de informações: </label>
                <a href="<?php echo esc_url( home_url( '/index.php/biblioteca' ) ); ?>" class="btntodos <?php echo ($lnk[$n]==''?'active':'');?>" title="Todos">Todos</a>
                <?php
            		$terms=get_terms("librarycategory");
            		foreach ($terms as $term) :
            			echo '<a href="'.get_term_link($term->slug, 'librarycategory').'" class="btn'.$term->slug.' '.($lnk[$n]==$term->slug?'active':'').'" title="'.$term->name.'">'.$term->name.'</a>';
            		endforeach;
          		?>
			</div>
		</div>

		<ul class="list boxes">
	  		<?php
                $paged = get_query_var('paged') ? get_query_var('paged') : 1;
	  		  	$args = array('post_type' => 'biblioteca', 'paged'=>$paged);
	  		  	if (isset($global_term)) :
	    		    $args['tax_query'] = array(
	                                    array(
	                                      'taxonomy' => 'librarycategory',
	                                      'field' => 'slug',
	                                      'terms' => $global_term->slug
	                                    )
	                                  );
	  		  	endif;
	  		  	
	  		  	$wp_query = new WP_Query($args);
  		  		while ($wp_query->have_posts()) : $wp_query->the_post();
  		  	?>
	  			<li class="box">
	  				<a href="<?php echo get_post_meta( $post->ID, 'library_link', true ); ?>" target="_blank">
	  					<div class="inner-box">
	  					  	<div class="icons">
	  		  					<?php
		  					  		$terms = get_the_terms($post->ID, 'librarycategory');
		  					  		if($terms!=''){
		  					  		foreach ($terms as $term):
		  					  	?>
									<img src="<?php echo bloginfo('template_url'); ?>/img/ico<?php echo ucfirst($term->slug); ?>Grande.gif" />
		  						<?php endforeach;} ?>
	  						</div>
	  						<h5 class="name"><?php the_title(); ?></h5>
	  					</div>
	  				</a>
	  			</li>
	  		 <?php 
	  		 	endwhile;
	  		 ?>
		</ul>
		<script type="text/javascript" src="<?php echo bloginfo('template_url'); ?>/js/list.min.js?<?php echo time(); ?>"></script>
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
	</div>
	<?php 
		if(function_exists('wp_paginate')) {
	    	wp_paginate();
		}
	?>
</div>
<?php get_footer(); ?>
