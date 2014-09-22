<?php
/*
Template Name: Noticias
*/
?>
<?php get_header(); ?>
<div class="wrap pagination">
    <div class="noticias">
      <h1>Not&iacute;cias</h1>
	  <?php 
	  	$paged = get_query_var('paged') ? get_query_var('paged') : 1;
	  	$wp_query = new WP_Query( array('post_type' => 'noticias', 'paged'=>$paged));
	  	$count = 1;
	  	while ( $wp_query->have_posts() ) : $wp_query->the_post();
	  ?>
			<div class="blocoNoticias">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<?php
							$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
							if($thumb[0]!=''){
								echo '<img src="'.$thumb[0].'" width="247" height="113" alt="" class="imgnoticias" />';
							} 
						?>
					</a>
	                <span class="data"><?php the_time('d/m/Y'); ?></span>
	                <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
	                <?php the_excerpt(); ?>
               
                <a href="<?php the_permalink(); ?>" title="Leia mais" class="leiamais">Leia mais</a>
                
                
           </div>
	  <?php 
	  	endwhile;
	  ?>
	  
	</div>
    <?php 
		if(function_exists('wp_paginate')) {
	    	wp_paginate();
		}
	?>
</div>
<?php get_footer(); ?>