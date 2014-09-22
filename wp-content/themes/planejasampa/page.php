
<?php get_header(); ?>
	<div class="wrap">
	    <div class="noticias">
	       <?php while ( have_posts() ) : the_post(); ?>
	       <h1><?php the_title(); ?></h1>
	       
           <div class="blocoNoticiasInterna semBorda">
				<?php
					$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
					if($thumb[0]!=''){
						echo '<img src="'.$thumb[0].'" width="748" height="217" alt="" class="imgnoticias" />';
					} 
				?>
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
           <?php endwhile; ?>
       </div>
     </div>
<?php get_footer(); ?>