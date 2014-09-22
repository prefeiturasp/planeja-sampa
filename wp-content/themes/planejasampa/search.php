<?php get_header(); ?>
<div class="wrap">
    <div class="noticias busca">
      <h1>Resultado de Busca</h1>
	  <?php if ( have_posts() ) : ?>
	  <?php while ( have_posts() ) : the_post(); ?>
			<div class="blocoNoticias">
				
					<?php
						$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
						if($thumb[0]!=''){
							echo '<img src="'.$thumb[0].'" width="247" height="113" alt="" class="imgnoticias" />';
						} 
					?>
	                <span class="data"><?php the_time('d/m/Y'); ?></span>
	                <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
	                <p><?php the_excerpt(); ?></p>
                <a href="<?php the_permalink(); ?>" title="Leia mais" class="leiamais">Leia mais</a>
                
           </div>
      <?php endwhile; ?>
	  <?php if(function_exists('wp_paginate')) {
		    wp_paginate();
		} ?>
	  <?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>