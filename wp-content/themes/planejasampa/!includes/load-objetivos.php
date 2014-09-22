<?
/*
	Template Name:  METAS - Carregamento de COMBOS/AJAX (OBJETIVOS/ARTICULACAO/SECRETARIA)
*/
?>
<script type='text/javascript' src='<?php echo esc_url(home_url('')); ?>/wp-content/themes/planejasampa/metas/js/funcoes-cmb.js'></script>
<?
if(count($_POST['eixo'])<=0){
	$_POST['eixo'] = array(
					'eixo1',
					'eixo2',
					'eixo3');
}
		
	for($e=0;$e<count($_POST['eixo']);$e++){
		$opt.= '<input type="hidden" name="eixo[]" value="'.$_POST['eixo'][$e].'" />';
	}
	
	$opt.= '<select class="slctArticulacao" name="articulacao">
				<option value="">ARTICULAÇÃO</option>
			</select>
			<select class="slctObjetivo" name="objetivos">
				<option value="">OBJETIVO</option>
			</select>
			<select class="slctSecretaria" name="secretaria">
				<option value="">SECRETARIA</option>
			</select>';
	
	$opt.= '<div class="filtrosSelect">';
	
		/*
			* COMBO DE ARTICULAÇÕES RELACIONADO AOS EIXOS
		*/
		$opt.= '<ul class="articulacao">
					<li>ARTICULAÇÃO</li>
						<ul>';
			for($e=0;$e<count($_POST['eixo']);$e++){ 
				$sql001 = $wpdb->get_results("
					SELECT p2.*
						FROM wp_posts p1
							LEFT JOIN wp_term_relationships t1 ON t1.object_id = p1.ID
							LEFT JOIN wp_terms t2 ON t2.term_id = t1.term_taxonomy_id
							INNER JOIN wp_term_taxonomy t3 ON t3.parent = (SELECT t0.term_id 
																			FROM wp_terms t0 
																				WHERE t0.slug = '".$_POST['eixo'][$e]."')
							LEFT JOIN wp_postmeta p2 ON p2.post_id = p1.ID
								WHERE  t3.term_id = t2.term_id
										AND p1.post_type = 'metas' 
										AND p1.post_status = 'publish'
										AND p2.meta_value <> ''	
										AND p2.meta_key = 'articulacao'
											GROUP BY p2.meta_key,
													 p2.meta_value"); 
				foreach ($sql001 as $sqlr001){
					$opt.= '<li>'.$sqlr001->meta_value.'</li>';
				}
			}
		
				$opt.= '</ul>
					</ul>';
	

		/*
			* COMBO DE OBJETIVOS RELACIONADO AOS EIXOS
		*/
		$opt.= '<ul class="objetivo">
						<li>OBJETIVO</li>
							<ul>';
			for($e=0;$e<count($_POST['eixo']);$e++){ 
				$sql002 = $wpdb->get_results("
					SELECT *
						FROM wp_terms t1
							INNER JOIN wp_term_taxonomy t2 ON t2.parent = (SELECT t0.term_id 
																			FROM wp_terms t0 
																				WHERE t0.slug = '".$_POST['eixo'][$e]."')
								WHERE t2.term_id = t1.term_id"); 
				foreach ($sql002 as $sqlr002){
					$opt.= '<li data-val="'.$sqlr002->slug.'">'.$sqlr002->name.'</li>';
				}
			}
		
		$opt.= '</ul>
			</ul>';
	
		/*
			* COMBO DE SECRETARIAS RELACIONADO AOS EIXOS
		*/
		$opt.= '<ul class="secretaria">
					<li>SECRETARIA</li>
						<ul>';
			for($e=0;$e<count($_POST['eixo']);$e++){ 
				$sql003 = $wpdb->get_results("
					SELECT p2.*
						FROM wp_posts p1
							LEFT JOIN wp_term_relationships t1 ON t1.object_id = p1.ID
							LEFT JOIN wp_terms t2 ON t2.term_id = t1.term_taxonomy_id
							INNER JOIN wp_term_taxonomy t3 ON t3.parent = (SELECT t0.term_id 
																			FROM wp_terms t0 
																				WHERE t0.slug = '".$_POST['eixo'][$e]."')
							LEFT JOIN wp_postmeta p2 ON p2.post_id = p1.ID
								WHERE  t3.term_id = t2.term_id
										AND p1.post_type = 'metas' 
										AND p1.post_status = 'publish'
										AND p2.meta_value <> ''	
										AND p2.meta_key = 'secretaria'
											GROUP BY p2.meta_key,
													 p2.meta_value"
										); 
				foreach ($sql003 as $sqlr003){
					$opt.= '<li>'.$sqlr003->meta_value.'</li>';
				}
			}
		
		$opt.= '</ul>
			</ul>
	</div>';
	
	echo $opt;

?>