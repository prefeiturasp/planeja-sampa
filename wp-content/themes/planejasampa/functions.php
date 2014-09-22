<?php

  add_editor_style('style.css');

  /**
   * SETUP THEME IMAGES SIZES
   */
  if ( function_exists( 'add_theme_support' ) ) {
  add_theme_support( 'post-thumbnails' );
    add_image_size( '96xX', 96, 0 );
    add_image_size( '150xX', 150, 0 );
    add_image_size( '170xX', 170, 0 );
    add_image_size( '657xX', 657, 0 );
    add_image_size( '510xX', 510, 0 );
    add_image_size( '365x195', 664, 195, true);
  }

  /**
   * SETUP MENUS
   */
  function register_my_menus() {
    register_nav_menus(
      array(
        'header-menu' => __( 'Header Menu' ),
        'extra-menu' => __( 'Footer Menu' ),
        'revisao-menu' => __('Revisão dos instrumentos menu'),
        'arco-menu' => __('Arco do futuro menu'),
        'territorio-menu' => __('Território CEU menu'),
        'dialogo-menu' => __('Centro Diálogo Aberto')
      )
    );
  }
  add_action( 'init', 'register_my_menus' );

  /**
   * SETUP PAGE NAVIGATION
   */

  if ( ! function_exists( 'the_content_nav' ) ) :
  /**
   * Display navigation to next/previous pages when applicable
   */
  function the_content_nav() {
    global $wp_query;

    if ( $wp_query->max_num_pages > 1 ) :?>
      <div class="pages">
        <div class="prev"><?php echo previous_posts_link( 'Anterior' ); ?></div>
        <div class="next"><?php echo next_posts_link( 'Próxima' ); ?></div>
        <div class="clear"></div>
      </div>
    <?php endif;
  }
  endif; // twentyeleven_content_nav

  /********************************************************************************/

  /*******************************************************************************/

  /********************************************************************************/
  /**************** CUSTOM NOT�CIAS                 *******************************/
  /********************************************************************************/
  add_action('init', 'noticias_register');
  function noticias_register() {
    $labels = array(
      'name' => __('Notícias'),
      'singular_name' => __('Notícia'),
      'add_new' => __('Nova notícia'),
      'add_new_item' => __('Adicionar'),
      'edit_item' => __('Editar'),
      'new_item' => __('Novo'),
      'view_item' => __('Ver'),
      'search_items' => __('Procurar'),
      'not_found' =>  __('Nada encontrado'),
      'not_found_in_trash' => __('Nada encontrado na lixeira'),
      'parent_item_colon' => ''
    );
    $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'menu_icon' => get_stylesheet_directory_uri() . '/img/admin/icon_news.png',
      'rewrite' => array('slug' => 'noticia'),
      'capability_type' => 'post',
      'hierarchical' => false,
      'menu_position' => null,
      'supports' => array('title','editor','thumbnail','excerpt','comments'),
      'taxonomies' => array('newscategory', 'post_tag')
      );
    register_post_type( 'noticias' , $args );
    flush_rewrite_rules( );
  }
  
  /*******************************************************************************/
  /************************CUSTOM TAXONOMY PROJETOS***************/
  add_action('init', 'add_project_taxonomy', 0);
  function add_project_taxonomy() {
    $labels = array(
        'name' => _x( 'Projetos', 'taxonomy general name' ),
        'singular_name' => _x( 'Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Categories' ),
        'popular_items' => __( 'Popular Categories' ),
        'all_items' => __( 'Todos os projetos' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Category' ),
        'update_item' => __( 'Update Category' ),
        'add_new_item' => __( 'Add New Category' ),
        'new_item_name' => __( 'New Category Name' ),
        'separate_items_with_commas' => __( 'Separate categories with commas' ),
        'add_or_remove_items' => __( 'Add or remove categories' ),
        'choose_from_most_used' => __( 'Choose from the most used categories' ),
    );

    register_taxonomy('projetos', array('noticias', 'agenda'), array(
        'label' => __('Projetos'),
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'projetos' ),
        'show_in_nav_menus' => true,
    ));
  }
  
  function add_project_meta_data() {
    echo '<div class="form-field">';
      echo '<label for="term_meta[custom_term_meta]">Cor:</label>';
      echo '<input type="text" name="term_meta[custom_term_meta]" id="term_meta[custom_term_meta]">';
    echo '</div>';
    
    echo '<div class="form-field">';
      echo '<label for="term_meta[custom_term_meta]">Aparecer no topo:</label>';
      echo '<input type="text" name="term_meta[custom_term_meta_topo]" value="S">';
    echo '</div>';
  }
  add_action('projetos_add_form_fields', 'add_project_meta_data', 10, 2 );
  
  function edit_project_meta_data($term) {
    $termId = $term->term_id;
    $termMeta = get_option('taxonomy_'.$termId);
    
    if (!empty($termMeta['custom_term_meta'])) {
      echo '<tr class="form-field">';
        echo '<th scope="row" valign="top">';
          echo '<label for="term_meta[custom_term_meta]">Cor:</label>';
        echo '</th>';
        echo '<td>';
          echo '<input type="text" name="term_meta[custom_term_meta]" id="term_meta[custom_term_meta]" value="'.$termMeta['custom_term_meta'].'">';
        echo '</td>';
      echo '</tr>';
      
      echo '<tr class="form-field">';
        echo '<th scope="row" valign="top">';
          echo '<label for="term_meta[custom_term_meta]">Aparecer no topo:</label>';
        echo '</th>';
        echo '<td>';
          echo '<input type="text" name="term_meta[custom_term_meta_topo]" id="term_meta[custom_term_meta_topo]" value="'.$termMeta['custom_term_meta_topo'].'">';
        echo '</td>';
      echo '</tr>';
    }
  }
  add_action('projetos_edit_form_fields', 'edit_project_meta_data', 10, 2 );
  
  function save_projetos_custom_meta($term_id) {
    if ( isset( $_POST['term_meta'] ) ) {
      $t_id = $term_id;
      $term_meta = get_option( "taxonomy_$t_id" );
      $cat_keys = array_keys( $_POST['term_meta'] );
      foreach ( $cat_keys as $key ) {
        if ( isset ( $_POST['term_meta'][$key] ) ) {
          $term_meta[$key] = $_POST['term_meta'][$key];
        }
      }
      // Save the option array.
      update_option( "taxonomy_$t_id", $term_meta );
    }
  }
  
  add_action( 'edited_projetos', 'save_projetos_custom_meta', 10, 2 );  
  add_action( 'create_projetos', 'save_projetos_custom_meta', 10, 2 );

  add_filter("manage_edit-noticias_columns", "noticias_edit_columns");
  function noticias_edit_columns($columns){
    $columns = array(
      "cb" => "<input type=\"checkbox\" />",
      "title" => "Title",
      "author" => "Author",
    "date" => "Data",
    );
    return $columns;
  }

  function create_noticiascategory_taxonomy() {

    $labels = array(
        'name' => _x( 'Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Categories' ),
        'popular_items' => __( 'Popular Categories' ),
        'all_items' => __( 'All Categories' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Category' ),
        'update_item' => __( 'Update Category' ),
        'add_new_item' => __( 'Add New Category' ),
        'new_item_name' => __( 'New Category Name' ),
        'separate_items_with_commas' => __( 'Separate categories with commas' ),
        'add_or_remove_items' => __( 'Add or remove categories' ),
        'choose_from_most_used' => __( 'Choose from the most used categories' ),
    );

    register_taxonomy('newscategory','noticias', array(
        'label' => __('Category'),
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'noticias-category' ),
    ));
  }

add_action( 'init', 'create_noticiascategory_taxonomy', 0 );

  /********************************************************************************/

  /********************************************************************************/
  /**************** CUSTOM BIBLIOTECA               *******************************/
  /********************************************************************************/
  add_action('init', 'biblioteca_register');
  function biblioteca_register() {
    $labels = array(
      'name' => __('Biblioteca'),
      'singular_name' => __('Biblioteca'),
      'add_new' => __('Novo post'),
      'add_new_item' => __('Adicionar'),
      'edit_item' => __('Editar'),
      'new_item' => __('Novo'),
      'view_item' => __('Ver'),
      'search_items' => __('Procurar'),
      'not_found' =>  __('Nada encontrado'),
      'not_found_in_trash' => __('Nada encontrado na lixeira'),
      'parent_item_colon' => ''
    );
    $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'menu_icon' => get_stylesheet_directory_uri() . '/img/admin/icon_library.png',
      'rewrite' => array('slug' => 'biblioteca-list'),
      'capability_type' => 'post',
      'hierarchical' => false,
      'menu_position' => null,
      'supports' => array('title',/*'editor','thumbnail','excerpt','comments'*/ 'page-attributes'),
      'taxonomies' => array('biblioteca-category')
      );
    register_post_type( 'biblioteca' , $args );
    flush_rewrite_rules( );
  }

  add_filter("manage_edit-biblioteca_columns", "biblioteca_edit_columns");
  function biblioteca_edit_columns($columns){
    $columns = array(
      "cb" => "<input type=\"checkbox\" />",
      "title" => "Title",
      "menu_order" => "Ordem",
      "author" => "Author",
    "date" => "Data",
    );
    return $columns;
  }

  function create_bibliotecacategory_taxonomy() {

    $labels = array(
        'name' => _x( 'Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Categories' ),
        'popular_items' => __( 'Popular Categories' ),
        'all_items' => __( 'All Categories' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Category' ),
        'update_item' => __( 'Update Category' ),
        'add_new_item' => __( 'Add New Category' ),
        'new_item_name' => __( 'New Category Name' ),
        'separate_items_with_commas' => __( 'Separate categories with commas' ),
        'add_or_remove_items' => __( 'Add or remove categories' ),
        'choose_from_most_used' => __( 'Choose from the most used categories' ),
    );

    register_taxonomy('librarycategory','biblioteca', array(
        'label' => __('Category'),
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'biblioteca' ),
        'show_in_nav_menus' => false,
    ));
  }

  add_action( 'init', 'create_bibliotecacategory_taxonomy', 0 );

  add_action( 'admin_init', 'biblioteca_create' );

  function biblioteca_create() {
      add_meta_box('biblioteca_meta_link', 'Link', 'biblioteca_meta_link', 'biblioteca');
  }

  function biblioteca_meta_link () {
    // - grab data -

    global $post;
    $custom = get_post_custom($post->ID);
    $meta_value = $custom["library_link"][0];

    // - output -

    ?>
    <div class="meta">
      <input type="hidden" name="library-nonce" value="<?php echo wp_create_nonce( 'library-nonce' ); ?>" />
      <input name="library_link" class="link" value="<?php echo $meta_value; ?>" style="width:90%" />
    </div>
    <?php
  }

  add_action ('save_post', 'save_biblioteca');

  function save_biblioteca(){

    global $post;

    // - still require nonce

    if ( !wp_verify_nonce( $_POST['library-nonce'], 'library-nonce' )) {
        return $post->ID;
    }

    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;


    update_post_meta($post->ID, "library_link", $_POST['library_link'] );

  }

  /********************************************************************************/

  /********************************************************************************/
  /**************** CUSTOM PERGUNTAS FREQUENTES     *******************************/
  /********************************************************************************/
  add_action('init', 'faq_register');
  function faq_register() {
    $labels = array(
      'name' => __('Perguntas frequentes'),
      'singular_name' => __('Pergunta'),
      'add_new' => __('Nova pergunta'),
      'add_new_item' => __('Adicionar'),
      'edit_item' => __('Editar'),
      'new_item' => __('Nova'),
      'view_item' => __('Ver'),
      'search_items' => __('Procurar'),
      'not_found' =>  __('Nada encontrado'),
      'not_found_in_trash' => __('Nada encontrado na lixeira'),
      'parent_item_colon' => ''
    );
    $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'menu_icon' => get_stylesheet_directory_uri() . '/img/admin/icon_faq.png',
      'rewrite' => array('slug' => 'perguntas-frequentes'),
      'capability_type' => 'post',
      'hierarchical' => false,
      'menu_position' => null,
      'supports' => array('title','editor'/*,'thumbnail','excerpt','comments', 'page-attributes'*/)
      );
    register_post_type( 'wp_super_faq' , $args );
    flush_rewrite_rules( );
  }

  add_filter("manage_edit-faq_columns", "faq_edit_columns");
  function faq_edit_columns($columns){
    $columns = array(
      "cb" => "<input type=\"checkbox\" />",
      "title" => "Title",
      "author" => "Author",
    "date" => "Data",
    );
    return $columns;
  }

  /********************************************************************************/


/********************************************************************************/
  /**************** CUSTOM HOME SLIDER            *******************************/
  /******************************************************************************/
  add_action('init', 'slider_register');
  function slider_register() {
    $labels = array(
      'name' => __('Slider'),
      'singular_name' => __('Slider'),
      'add_new' => __('Novo slider'),
      'add_new_item' => __('Adicionar'),
      'edit_item' => __('Editar'),
      'new_item' => __('Novo'),
      'view_item' => __('Ver'),
      'search_items' => __('Procurar'),
      'not_found' =>  __('Nada encontrado'),
      'not_found_in_trash' => __('Nada encontrado na lixeira'),
      'parent_item_colon' => ''
    );
    $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'menu_icon' => get_stylesheet_directory_uri() . '/img/admin/icon-calendar.png',
      'rewrite' => array('slug' => 'slider','with_front' => FALSE),
      'capability_type' => 'post',
      '_builtin' => false,
      'hierarchical' => false,
      'menu_position' => null,
      'supports' => array('title','editor', 'page-attributes'/*,'excerpt'*/, 'thumbnail','comments'),
      //'taxonomies' => array('category', 'post_tag')
      );
    register_post_type( 'slider' , $args );
    flush_rewrite_rules( );
  }

  // Show Columns

  //add_filter ("manage_edit-slider_columns", "slider_edit_columns");

  function slider_edit_columns($columns) {

  $columns = array(
      "cb" => "<input type=\"checkbox\" />",
      "title" => "Título",
      'menu_order' => "Ordem"
      );
  return $columns;
  }


  /********************************************************************************/
  /**************** CUSTOM METAS                 *******************************/
  /********************************************************************************/
  add_action('init', 'metas_register');
  function metas_register() {
    $labels = array(
      'name' => __('Metas'),
      'singular_name' => __('Metas'),
      'add_new' => __('Nova meta'),
      'add_new_item' => __('Adicionar'),
      'edit_item' => __('Editar'),
      'new_item' => __('Novo'),
      'view_item' => __('Ver'),
      'search_items' => __('Procurar'),
      'not_found' =>  __('Nada encontrado'),
      'not_found_in_trash' => __('Nada encontrado na lixeira'),
      'parent_item_colon' => ''
    );
    $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'menu_icon' => get_stylesheet_directory_uri() . '/img/admin/icon_news.png',
      'rewrite' => array('slug' => 'meta'),
      'capability_type' => 'post',
      'hierarchical' => false,
      'menu_position' => null,
      'supports' => array('title','thumbnail','comments','page-attributes'),
      'taxonomies' => array('newscategory')
      );
    register_post_type( 'metas' , $args );
    flush_rewrite_rules( );
  }
  

  add_filter("manage_edit-metas_columns", "metas_edit_columns");
  function metas_edit_columns($columns){
    $columns = array(
      "cb" => "<input type=\"checkbox\" />",
      "title" => "Title",
      "author" => "Author",
    "date" => "Data",
    );
    return $columns;
  }

  function create_metascategory_taxonomy() {

    $labels = array(
        'name' => _x( 'Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Categories' ),
        'popular_items' => __( 'Popular Categories' ),
        'all_items' => __( 'All Categories' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Category' ),
        'update_item' => __( 'Update Category' ),
        'add_new_item' => __( 'Add New Category' ),
        'new_item_name' => __( 'New Category Name' ),
        'separate_items_with_commas' => __( 'Separate categories with commas' ),
        'add_or_remove_items' => __( 'Add or remove categories' ),
        'choose_from_most_used' => __( 'Choose from the most used categories' ),
    );

    register_taxonomy('newscategory','metas', array(
        'label' => __('Category'),
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'metas-category' ),
    ));
  }

  	add_action( 'admin_init', 'metas_create' );

	function metas_create() {
		add_meta_box('meta_articulacao', 'Articula&ccedil;&atilde;o', 'meta_articulacao', 'metas');
		add_meta_box('meta_articulacaoText', 'Articula&ccedil;&atilde;o Resumo', 'meta_articulacaoText', 'metas');
		add_meta_box('meta_secretaria', 'Secretaria e Unidade respons&aacute;vel', 'meta_secretaria', 'metas');
		add_meta_box('meta_valor', 'Custo total da meta', 'meta_valor', 'metas');
		add_meta_box('meta_definicoes', 'Defini&ccedil;&otilde;es de termos t&eacute;cnicos', 'meta_definicoes', 'metas');
		add_meta_box('meta_oquevaiserentregue', 'O que vai ser entregue?', 'meta_oquevaiserentregue', 'metas');
		add_meta_box('meta_observacoes', 'Observa&ccedil;&otilde;es', 'meta_observacoes', 'metas');
		add_meta_box('meta_cronograma', 'Cronograma de entrega', 'meta_cronograma', 'metas');
		/*add_meta_box('meta_arquivodownload', 'Link do arquivo download', 'meta_arquivodownload', 'metas');*/
	}
	
	function meta_articulacao () {
		global $post;
		$custom = get_post_custom($post->ID);
		$meta_value = $custom["articulacao"][0];
		echo '<p>
				<input type="hidden" name="articulacao-nonce" value="'.wp_create_nonce( 'articulacao-nonce' ).'" />
				<input name="articulacao" type="text" value="'.$meta_value.'" style="width:90%" />
			  </p>';
	}
	add_action ('save_post', 'save_articulacao');
	function save_articulacao(){
		global $post;
		if ( !wp_verify_nonce( $_POST['articulacao-nonce'], 'articulacao-nonce' )) {return $post->ID;}
		if ( !current_user_can( 'edit_post', $post->ID ))return $post->ID;
		update_post_meta($post->ID, "articulacao", $_POST['articulacao'] );
	}

	function meta_articulacaoText () {
		global $post;
		$custom = get_post_custom($post->ID);
		$meta_value = $custom["articulacao-texto"][0];
		echo '<p>
				<input type="hidden" name="articulacao-texto-nonce" value="'.wp_create_nonce( 'articulacao-texto-nonce' ).'" />
				<textarea name="articulacao-texto" style="width:90%">'.$meta_value.'</textarea>
			  </p>';
	}
	add_action ('save_post', 'save_articulacaoText');
	function save_articulacaoText(){
		global $post;
		if ( !wp_verify_nonce( $_POST['articulacao-texto-nonce'], 'articulacao-texto-nonce' )) {return $post->ID;}
		if ( !current_user_can( 'edit_post', $post->ID ))return $post->ID;
		update_post_meta($post->ID, "articulacao-texto", $_POST['articulacao-texto'] );
	}

	function meta_secretaria () {
		global $post;
		$custom = get_post_custom($post->ID);
		$meta_value = $custom["secretaria"][0];
		echo '<p>
				  <input type="hidden" name="secretaria-nonce" value="'.wp_create_nonce( 'secretaria-nonce' ).'" />
				  <input name="secretaria" type="text" value="'.$meta_value.'" style="width:90%" />
				</p>';
	}
	add_action ('save_post', 'save_secretaria');
	function save_secretaria(){
		global $post;
		if ( !wp_verify_nonce( $_POST['secretaria-nonce'], 'secretaria-nonce' )) {return $post->ID;}
		if ( !current_user_can( 'edit_post', $post->ID ))return $post->ID;
		update_post_meta($post->ID, "secretaria", $_POST['secretaria'] );
	}
	
	function meta_valor () {
		global $post;
		$custom = get_post_custom($post->ID);
		$meta_value = $custom["valor"][0];
		echo '<p>
				  <input type="hidden" name="valor-nonce" value="'.wp_create_nonce( 'valor-nonce' ).'" />
				  <input name="valor" type="text" value="'.$meta_value.'" style="width:90%" />
				</p>';
	}
	add_action ('save_post', 'save_valor');
	function save_valor(){
		global $post;
		if ( !wp_verify_nonce( $_POST['valor-nonce'], 'valor-nonce' )) {return $post->ID;}
		if ( !current_user_can( 'edit_post', $post->ID ))return $post->ID;
		update_post_meta($post->ID, "valor", $_POST['valor'] );
	}
	
	function meta_definicoes () {
		global $post;
		$custom = get_post_custom($post->ID);
		$meta_value = $custom["definicoes"][0];
		echo '<p>
				  <input type="hidden" name="definicoes-nonce" value="'.wp_create_nonce( 'definicoes-nonce' ).'" />
				  <textarea name="definicoes" style="width:90%">'.$meta_value.'</textarea>
				</p>';
	}
	add_action ('save_post', 'save_definicoes');
	function save_definicoes(){
		global $post;
		if ( !wp_verify_nonce( $_POST['definicoes-nonce'], 'definicoes-nonce' )) {return $post->ID;}
		if ( !current_user_can( 'edit_post', $post->ID ))return $post->ID;
		update_post_meta($post->ID, "definicoes", $_POST['definicoes'] );
	}
	
	function meta_oquevaiserentregue () {
		global $post;
		$custom = get_post_custom($post->ID);
		$meta_value = $custom["oquevaiserentregue"][0];
		echo '<p>
				  <input type="hidden" name="oquevaiserentregue-nonce" value="'.wp_create_nonce( 'oquevaiserentregue-nonce' ).'" />
				  <textarea name="oquevaiserentregue" style="width:90%">'.$meta_value.'</textarea>
				</p>';
	}
	add_action ('save_post', 'save_oquevaiserentregue');
	function save_oquevaiserentregue(){
		global $post;
		if ( !wp_verify_nonce( $_POST['oquevaiserentregue-nonce'], 'oquevaiserentregue-nonce' )) {return $post->ID;}
		if ( !current_user_can( 'edit_post', $post->ID ))return $post->ID;
		update_post_meta($post->ID, "oquevaiserentregue", $_POST['oquevaiserentregue'] );
	}
	
	function meta_observacoes () {
		global $post;
		$custom = get_post_custom($post->ID);
		$meta_value = $custom["observacoes"][0];
		echo '<p>
				  <input type="hidden" name="observacoes-nonce" value="'.wp_create_nonce( 'observacoes-nonce' ).'" />
				  <textarea name="observacoes" style="width:90%">'.$meta_value.'</textarea>
				</p>';
	}
	add_action ('save_post', 'save_observacoes');
	function save_observacoes(){
		global $post;
		if ( !wp_verify_nonce( $_POST['observacoes-nonce'], 'observacoes-nonce' )) {return $post->ID;}
		if ( !current_user_can( 'edit_post', $post->ID ))return $post->ID;
		update_post_meta($post->ID, "observacoes", $_POST['observacoes'] );
	}
	
	function meta_cronograma () {
		global $post;
		$custom = get_post_custom($post->ID);
		$meta_value = $custom["cronograma"][0];
		echo '<p>
				  <input type="hidden" name="cronograma-nonce" value="'.wp_create_nonce( 'cronograma-nonce' ).'" />
				  <input name="cronograma" type="text" value="'.$meta_value.'" style="width:90%" />
				</p>';
	}
	add_action ('save_post', 'save_cronograma');
	function save_cronograma(){
		global $post;
		if ( !wp_verify_nonce( $_POST['cronograma-nonce'], 'cronograma-nonce' )) {return $post->ID;}
		if ( !current_user_can( 'edit_post', $post->ID ))return $post->ID;
		update_post_meta($post->ID, "cronograma", $_POST['cronograma'] );
	}

	
	/*
	function meta_arquivodownload () {
		global $post;
		$custom = get_post_custom($post->ID);
		$meta_value = $custom["arquivodownload"][0];
		echo '<p>
				  <input type="hidden" name="arquivodownload-nonce" value="'.wp_create_nonce( 'arquivodownload-nonce' ).'" />
				  <input name="arquivodownload" type="text" value="'.$meta_value.'" style="width:90%" />
				</p>';
	}
	add_action ('save_post', 'save_arquivodownload');
	function save_arquivodownload(){
		global $post;
		if ( !wp_verify_nonce( $_POST['arquivodownload-nonce'], 'arquivodownload-nonce' )) {return $post->ID;}
		if ( !current_user_can( 'edit_post', $post->ID ))return $post->ID;
		update_post_meta($post->ID, "arquivodownload", $_POST['arquivodownload'] );
	}*/
  

  /********************************************************************************/
  /**************** MINUTA PARTICIPATIVA            *******************************/
  /********************************************************************************/
	/*
  add_action('init', 'minuta_register');
  function minuta_register() {
    $labels = array(
      'name' => __('Minuta Participativa'),
      'singular_name' => __('Minuta Participativa'),
      'add_new' => __('Nova minuta'),
      'add_new_item' => __('Adicionar'),
      'edit_item' => __('Editar'),
      'new_item' => __('Novo'),
      'view_item' => __('Ver'),
      'search_items' => __('Procurar'),
      'not_found' =>  __('Nada encontrado'),
      'not_found_in_trash' => __('Nada encontrado na lixeira'),
      'parent_item_colon' => ''
    );
    $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'menu_icon' => get_stylesheet_directory_uri() . '/img/admin/icon_news.png',
      'rewrite' => array('slug' => 'minuta'),
      'capability_type' => 'post',
      'hierarchical' => false,
      'menu_position' => null,
      'supports' => array('title','excerpt','comments'),
      'taxonomies' => ''
      );
    register_post_type( 'minuta' , $args );
    flush_rewrite_rules( );
  }
  

  add_filter("manage_edit-minuta_columns", "minuta_edit_columns");
  function minuta_edit_columns($columns){
    $columns = array(
      "cb" => "<input type=\"checkbox\" />",
      "title" => "Title",
      "author" => "Author",
    "date" => "Data",
    );
    return $columns;
  }
 
  */
  /********************************************************************************/

  /**
   * CALL ON ACTIVATE/DEACTIVATE THEME
   */
  wp_register_theme_activation_hook('planejasampa', 'planejasampa_theme_activate');
  wp_register_theme_deactivation_hook('planejasampa', 'planejasampa_theme_deactivate');

  /**
   *
   * @desc registers a theme activation hook
   * @param string $code : Code of the theme. This can be the base folder of your theme. Eg if your theme is in folder 'mytheme' then code will be 'mytheme'
   * @param callback $function : Function to call when theme gets activated.
   */
  function wp_register_theme_activation_hook($code, $function) {
      $optionKey="theme_is_activated_" . $code;
      if(!get_option($optionKey)) {
          call_user_func($function);
          update_option($optionKey , 1);
      }
  }

  /**
   * @desc registers deactivation hook
   * @param string $code : Code of the theme. This must match the value you provided in wp_register_theme_activation_hook function as $code
   * @param callback $function : Function to call when theme gets deactivated.
   */
  function wp_register_theme_deactivation_hook($code, $function)
  {
      // store function in code specific global
      $GLOBALS["wp_register_theme_deactivation_hook_function" . $code]=$function;

      // create a runtime function which will delete the option set while activation of this theme and will call deactivation function provided in $function
      $fn=create_function('$theme', ' call_user_func($GLOBALS["wp_register_theme_deactivation_hook_function' . $code . '"]); delete_option("theme_is_activated_' . $code. '");');

      // add above created function to switch_theme action hook. This hook gets called when admin changes the theme.
      // Due to wordpress core implementation this hook can only be received by currently active theme (which is going to be deactivated as admin has chosen another one.
      // Your theme can perceive this hook as a deactivation hook.)
      add_action("switch_theme", $fn);
  }

  function planejasampa_theme_activate()
  {
      /*$default_pages = array(
          array(
              'title' => 'Metas',
              'content' => '',
              'template' => 'index.php'
              )
      );
      $existing_pages = get_pages();
      $existing_titles = array();

      foreach ($existing_pages as $page)
      {
          $existing_titles[] = $page->post_title;
      }

      foreach ($default_pages as $new_page)
      {
          if( !in_array( $new_page['title'], $existing_titles ) )
          {
              // create post object
              $add_default_pages = array(
                  'post_title' => $new_page['title'],
                  'post_content' => $new_page['content'],
                  'post_parent' => '27',
                  'post_parent' => 'publish',
                  'post_type' => 'page',
                  'page_template' => $new_page['template']
                );

              // insert the post into the database
              $result = wp_insert_post($add_default_pages);
          }
      }
*/
  }

  function planejasampa_theme_deactivate()
  {
     // code to execute on theme deactivation
  }

  /**
   * REMOVE OPTIONS FROM MENU
   */

   function remove_menus () {
    global $menu;
      //$restricted = array(__('Dashboard'), __('Posts'), __('Media'), __('Links'), __('Pages'), __('Appearance'), __('Tools'), __('Users'), __('Settings'), __('Comments'), __('Plugins'));
      $restricted = array(__('Posts'));
      //$restricted = array();
      end ($menu);
      while (prev($menu)){
        $value = explode(' ',$menu[key($menu)][0]);
        if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
      }
    }
    add_action('admin_menu', 'remove_menus');


  /**
   * IMPORTED FUNCTION
   */
  if ( ! function_exists( 'twentyeleven_comment' ) ) :
  /**
   * Template for comments and pingbacks.
   *
   * To override this walker in a child theme without modifying the comments template
   * simply create your own twentyeleven_comment(), and that function will be used instead.
   *
   * Used as a callback by wp_list_comments() for displaying the comments.
   *
   * @since Twenty Eleven 1.0
   */
  function twentyeleven_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
      case 'pingback' :
      case 'trackback' :
    ?>
    <li class="post pingback">
      <p><?php _e( 'Pingback:', 'twentyeleven' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?></p>
    <?php
        break;
      default :
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
      <article id="comment-<?php comment_ID(); ?>" class="comment">
        <div class="comment-meta">
          <div class="comment-author vcard">
            <?php
              $avatar_size = 68;
              if ( '0' != $comment->comment_parent )
                $avatar_size = 39;

              echo get_avatar( $comment, $avatar_size );

              /* translators: 1: comment author, 2: date and time */
              printf( __( '%1$s em %2$s <span class="says">disse:</span>', 'twentyeleven' ),
                sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
                sprintf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
                  esc_url( get_comment_link( $comment->comment_ID ) ),
                  get_comment_time( 'c' ),
                  /* translators: 1: date, 2: time */
                  sprintf( __( '%1$s as %2$s', 'twentyeleven' ), get_comment_date(), get_comment_time() )
                )
              );
            ?>

            <?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
          </div><!-- .comment-author .vcard -->

          <?php if ( $comment->comment_approved == '0' ) : ?>
            <em class="comment-awaiting-moderation"><?php _e( 'Seu comentário está aguardando moderação.', 'twentyeleven' ); ?></em>
            <br />
          <?php endif; ?>

        </div>

        <div class="comment-content"><?php comment_text(); ?></div>

        <div class="reply">
          <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Responder <span>&darr;</span>', 'twentyeleven' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
        </div><!-- .reply -->
      </article><!-- #comment-## -->

    <?php
        break;
    endswitch;
  }
  endif; // ends check for twentyeleven_comment()

// Sidebars

  register_sidebar(array(
  'name' => __( 'Notícias - Barra lateral' ),
  'id' => 'noticias-sidebar',
  'description' => __( 'Widgets nesta área serão apresentados nas páginas da seção notícias.' ),
  'before_title' => '<h1 class="widget-title">',
  'after_title' => '</h1>',
  'before_widget' => '<div id="%1$s" class="widget %2$s" box>',
  'after_widget'  => '</div>'
));

  register_sidebar(array(
  'name' => __( 'Páginas - Barra lateral' ),
  'id' => 'paginas-sidebar',
  'description' => __( 'Widgets nesta área serão apresentados nas páginas simples' ),
  'before_title' => '<h1 class="widget-title">',
  'after_title' => '</h1>',
  'before_widget' => '<div id="%1$s" class="widget %2$s" box>',
  'after_widget'  => '</div>'
));


// Widgets

//load widget
//add_action( 'widgets_init', 'register_my_widget' );

//init widget
//function register_my_widget() {
//    register_widget( 'noticias_widget' );
//}

//enclose widget
//class noticias_widget extends WP_Widget {}

  //Adding the Open Graph in the Language Attributes
// function add_opengraph_doctype( $output ) {
//     return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
//   }
// add_filter('language_attributes', 'add_opengraph_doctype');

//Lets add Open Graph Meta Info

// function insert_fb_in_head() {
//   global $post;
//   if ( !is_singular()) //if it is not a post or a page
//     return;
//         echo '<meta property="fb:admins" content="161422927240513"/>';
//         echo '<meta property="og:title" content="' . get_the_title() . '"/>';
//         echo '<meta property="og:type" content="article"/>';
//         echo '<meta property="og:url" content="' . get_permalink() . '"/>';
//         echo '<meta property="og:site_name" content="Gestão Urbana SP"/>';
//   if(!has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
//     $default_image="http://example.com/image.jpg"; //replace this with a default image on your server or an image in your media library
//     echo '<meta property="og:image" content="' . $default_image . '"/>';
//   }
//   else{
//     $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
//     echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
//   }
//   echo "
// ";
// }
// add_action( 'wp_head', 'insert_fb_in_head', 5 );

//Create a Walker to change the way wp_list_categories displays the output
//Here I'm removing the links from the output

function get_breadcrumb_data($postId, $taxonomyName, $left = false) {
    $outPut = array();
    $postTerms = get_the_terms($postId, $taxonomyName);
    
    if (!empty($postTerms)) {
      $postTermsId = array();
      foreach ($postTerms as $term) {
        if (empty($term->parent)) {
          $customTermMeta = get_term_by('slug', $term->slug, $taxonomyName, ARRAY_A);
          if (!empty($customTermMeta)) {
            $customTermMetaData = get_option('taxonomy_'.$term->term_id);
            $postTermsId[]= array(
              'id' => $term->term_id,
              'name' => $term->name,
              'color' => $customTermMetaData['custom_term_meta']
            );
          }
        }
      }
      
      if (!empty($postTermsId)) {
        foreach ($postTermsId as $postTermId) {
          $childTerms = get_terms( $taxonomyName, array( 'child_of' => $postTermId['id']));
          $outPut['parent']['name'] = $postTermId['name'];
          $outPut['parent']['color'] = $postTermId['color'];
          $outPut['parent']['childs'] = $childTerms;
        }
      }
      
      
      if (!empty($outPut)) {
        ?>
        <div class="taxonomy-breadcrumb <?php echo($left) ? 'left' :'';?>">
            <div class="float">
              <div class="breadcrumb-border" style="border-right: 20px solid <?php echo $outPut['parent']['color'];?>"></div>
              <div class="breadcrumb-first breadcrumb-text" style="background: <?php echo $outPut['parent']['color'];?>">
                <a href="">&bull;<?php echo $outPut['parent']['name'];?></a>
              </div>
            </div>  
            <?php
              foreach($outPut['parent']['childs'] as $child):
                  ?>
                    <div class="float">
                      <div class="breadcrumb-border-next" style="border-right: 14px solid <?php echo $outPut['parent']['color'];?>"></div>
                      <div class="breadcrumb-next breadcrumb-text" style="background: <?php echo $outPut['parent']['color'];?>">
                          <a href="">&bull;<?php echo $child->name;?></a>
                      </div>
                    </div>  
                  <?php
              endforeach;
            ?>
        </div>    
      <?php
      }  
    }
}

function salvar_campo_comentario($comment_id){
    $opiniao = strip_tags(trim($_POST['opiniao']));
	if($opiniao!=''){
		add_comment_meta( $comment_id, 'opiniao', $opiniao);
	}
	
	$contribuicao = strip_tags(trim($_POST['contribuicao']));
	if($contribuicao!=''){
		add_comment_meta( $comment_id, 'contribuicao', $contribuicao);
	}
	$textContribuicao = strip_tags(trim($_POST['textContribuicao']));
	if($textContribuicao!=''){
		add_comment_meta( $comment_id, 'textContribuicao', $textContribuicao);
	}
	
	$textJustificativa = strip_tags(trim($_POST['textJustificativa']));
	if($textJustificativa!=''){
		add_comment_meta( $comment_id, 'textJustificativa', $textJustificativa);
	}
	
	$commentMeta = strip_tags(trim($_POST['commentMeta']));
	if($commentMeta!=''){
		add_comment_meta( $comment_id, 'commentMeta', $opiniao);
	}
	
	
}


 add_action( 'comment_post', 'salvar_campo_comentario' );
 
