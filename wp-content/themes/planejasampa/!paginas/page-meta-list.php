<?php
/*
	* Template Name:  METAS - Lista de metas AJAX
	* Retorno de dados do Eixo 1                
*/

echo "&nbsp;"; 
//print_r($_POST['eixo']);
if($_POST['eixo'][0]==''||$_POST['eixo'][1]==''||$_POST['eixo'][2]==''){
	$_POST['eixo'] = array(
					'eixo1',
					'eixo2',
					'eixo3');
}

for($e=0;$e<count($_POST['eixo']);$e++){ 
		//echo $_POST['eixo'][$e];
		
	$query001 = "SELECT *
					FROM wp_terms t1
						INNER JOIN wp_term_taxonomy t2 ON t2.parent = (SELECT t0.term_id 
																				FROM wp_terms t0 
																					WHERE t0.slug = '".$_POST['eixo'][$e]."')
				 	LEFT JOIN wp_term_relationships r1 ON r1.term_taxonomy_id = t2.term_id
					LEFT JOIN wp_posts p1 ON p1.ID = r1.object_id
					LEFT JOIN wp_postmeta p2 ON p2.post_id = p1.ID
				 		WHERE t2.term_id = t1.term_id ";
						
						if($_POST['articulacao']!=''){
							$query001.= " AND p2.meta_value = '".$_POST['articulacao']."' ";
						}
						if($_POST['secretaria']!=''){
							$query001.= " AND p2.meta_value = '".$_POST['secretaria']."' ";
						}
											
						if($_POST['objetivos']!=''){
							$termid001 = $wpdb->get_var("SELECT 
														term_id 
															FROM $wpdb->terms 
																WHERE slug='".$_POST['objetivos']."'");
							$query001.= " AND t1.term_id = '".$termid001."' ";
						}
						
	$query001.= " GROUP BY t1.term_id ";										 
	$sql = $wpdb->get_results($query001);	
	$x = 1;               
	foreach ( $sql as $sqlr ){  

					  
		echo '<div class="'.$_POST['eixo'][$e].' objetivos" id="objetivo'.$e.'" style="display:block">
					<h3 class="type"><strong>'.$sqlr->name.'</strong><span>'.$sqlr->description.'</span></h3></div>';
			
			$query = "SELECT t1.term_taxonomy_id,
							t2.term_id,
							t3.term_id,
							p1.*
							FROM wp_posts p1
								LEFT JOIN wp_term_relationships t1 ON t1.object_id = p1.ID
								LEFT JOIN wp_term_taxonomy t2 ON t2.term_taxonomy_id = t1.term_taxonomy_id
								INNER JOIN wp_term_taxonomy t3 ON t3.parent = (SELECT t0.term_id 
																				FROM wp_terms t0 
																					WHERE t0.slug = '".$_POST['eixo'][$e]."')
								LEFT JOIN wp_postmeta p2 ON p2.post_id = p1.ID													
								WHERE t3.term_id = t2.term_id
											AND p1.post_type = 'metas' 
											AND p1.post_status = 'publish' ";
											
											if($_POST['objetivos']!=''){
												$termid = $wpdb->get_var("SELECT 
																			term_id 
																				FROM $wpdb->terms 
																					WHERE slug='".$_POST['objetivos']."'");
												$query.= " AND t2.term_id = '".$termid."' ";
											}else{
												$query.= " AND t2.term_id = '".$sqlr->term_id."' ";
											}
											
											
											
										
											if($_POST['articulacao']!=''){
												$query.= " AND p2.meta_value = '".$_POST['articulacao']."' ";
											}
											if($_POST['secretaria']!=''){
												$query.= " AND p2.meta_value = '".$_POST['secretaria']."' ";
											}
											$query.= " GROUP BY p1.ID
														ORDER BY p1.menu_order";
			
			//echo "<pre>".$query."</pre>";
											
			$sql1 = $wpdb->get_results($query);
			foreach ( $sql1 as $sqlr1 ){  
				$articulacao = get_post_meta($sqlr1->ID, 'articulacao', true);
				$articulacaoText = get_post_meta($sqlr1->ID, 'articulacao-texto', true);
				$secretaria = get_post_meta($sqlr1->ID, 'secretaria', true);
				$valor = get_post_meta($sqlr1->ID, 'valor', true);
				$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($sqlr1->ID),'full');
				$link = get_permalink($sqlr1->ID);
				
				echo '<div class="blocoObjetivos bloco'.ucfirst($_POST['eixo'][$e]).'">
						<span class="num">'.$sqlr1->menu_order.'</span>
						<h4 class="name">
							'.($thumb[0]!=''?'<img src="'.$thumb[0].'" width="221" height="139" alt="'.$sqlr1->post_title.'" />':'').'
							<span><a href="'.esc_url(home_url('/index.php/programa-de-metas/metas/visualizar-meta/?meta='.$sqlr1->ID)).'" title="leia mais" class="various fancybox.ajax leia" data-fancybox-type="ajax">'.$sqlr1->post_title.'</a></span>
						</h4>';
						if($articulacao!=''){
							echo '<h5>'.$articulacao.'</h5>
								  <p>'.$articulacaoText.'</p>';
						}
						if($secretaria!=''){
							echo '<h5>secretaria e unidade responsável</h5>
								  <p>'.$secretaria.'</p>';
						}
						if($valor!=''){
							echo '<p class="valores">R$ '.$valor.'</p>';
						}
					$comments = get_comments_number($sqlr1->ID);                                             
					echo '<p class="comentarios">';
						if($comments==0){
							echo '';
						}elseif($comments==1){
							echo '<strong>1</strong> <span>comentário</span>';
						}elseif($comments>1){
							echo '<strong>'.$comments.'</strong> <span>comentários</span>';
						}
					echo '</p>
						<a href="'.esc_url(home_url('/index.php/programa-de-metas/metas/visualizar-meta/?meta='.$sqlr1->ID)).'" title="leia mais" class="various fancybox.ajax leia" data-fancybox-type="ajax">leia mais</a>
					</div>';  
			}
														
	$x++;
	} 
}

?>