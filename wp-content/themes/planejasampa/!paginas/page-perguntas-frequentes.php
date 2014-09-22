<?php
/*
Template Name: Entenda - Perguntas frequentes
*/
?>

<?php get_header(); ?>
<div class="wrap">
	<div class="perguntas">
		<h1>Perguntas frequentes</h1>
		<div class="blocoPerguntas">
			<?php 
				$wp_query = new WP_Query( array('post_type' => 'wp_super_faq'));
				while ( $wp_query->have_posts() ) : $wp_query->the_post();
			?>
                <h2><?php the_title(); ?></h2>
                <?php the_content(); ?>
	                
			<?php endwhile;?>
		</div>
	</div>
</div>

<?php get_footer(); ?>