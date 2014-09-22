<?php
/*
Template Name: METAS - Contato aplicação metas
*/
?>

	<?php while ( have_posts() ) : the_post(); ?>
    <div class="pageContato">
    	<a href="#" onClick="fCloseWindow();" class="fechar close">fechar X</a>
        <div class="internaTexto">
            <?php the_content(); ?>
        </div>	
    </div>
	<?php endwhile; ?>

<script type='text/javascript' src='<?php echo esc_url(home_url('')); ?>/wp-includes/js/jquery/jquery-migrate.min.js?ver=1.2.1'></script>
<script type='text/javascript' src='<?php echo esc_url(home_url('')); ?>/wp-content/plugins/wp-super-faq/wp_super_faq.js?ver=3.6.1'></script>
<script type='text/javascript' src='<?php echo esc_url(home_url('')); ?>/wp-content/plugins/easy-contact-forms/easy-contact-forms-forms.1.4.7.js?ver=3.6.1'></script>