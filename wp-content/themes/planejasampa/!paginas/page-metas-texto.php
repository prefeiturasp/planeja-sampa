<?php
/*
Template Name: METAS - Texto aplicação metas
*/
?>
<?php get_header(); ?>
	<?php while ( have_posts() ) : the_post(); ?>
    <h2 class="programa"><img src="<?php echo get_template_directory_uri(); ?>/metas/img/icoMetas.gif" width="78" height="26" alt="PROGRAMA DE METAS" /><br /><?php the_title(); ?></h2> 
	<div class="internaTexto">
		<?php the_content(); ?>
    </div>
	<?php endwhile; ?>
<?php get_footer(); ?>