<?php $lnk = explode('/',$_SERVER[REQUEST_URI]);$n = ($lnk[1]=='SJ2231'?3:2);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <?php if (have_posts()):while(have_posts()):the_post(); endwhile; endif;?>
    <!-- if page is content page -->
    <?php if (is_single()) { ?>
    <meta property="og:type" content="blog" />
    <meta property="og:title" content="<?php single_post_title(''); ?>" />
    <meta property="og:description" content="<?php echo strip_tags(get_the_excerpt($post->ID)); ?>" />
    <meta property="og:site_name" content="Planeja Sampa" />
    <meta property="og:image" content="<?php if (function_exists('wp_get_attachment_thumb_url')) {echo wp_get_attachment_thumb_url(get_post_thumbnail_id($post->ID)); }?>" />
    <meta property="og:url" content="<?php the_permalink() ?>"/>
    
    <!-- if page is others -->
    <?php } else { ?>
    <meta property="og:type" content="blog" />
    <meta property="og:title" content="Planeja Sampa" />
    <meta property="og:description" content="O Planeja Sampa é a plataforma da Secretaria Municipal de Planejamento, Orçamento e Gestão – SEMPLA que estabelece como meta fazer de São Paulo uma cidade em que predomine a transparência e a participação social. " />
    <meta property="og:site_name" content="Planeja Sampa" />
    <meta property="og:image" content="http://planejasampa.prefeitura.sp.gov.br/wp-content/uploads/2013/10/logo-face.png" />
    <?php } ?>

    
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/favico.ico" type="image/ico" />
	
    <!-- JAVASCRIPT LIBS -->
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery-1.10.2.min.js"></script>
    
	<!-- CSS -->
    <?php if($lnk[$n]=='minuta-participativa'){ ?>
        <link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
        <link rel="Stylesheet" href="<?php echo get_template_directory_uri(); ?>/minuta/css/estilos.css" />
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/minuta/js/intro.js"></script>
	
    <?php }elseif($lnk[($n+1)]=='metas'){ ?>
        <link rel="Stylesheet" href="<?php echo get_template_directory_uri(); ?>/metas/css/metas.css" />
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/metas/js/funcoes.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Tinos:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Merriweather:400,700italic,300,300italic,400italic,700' rel='stylesheet' type='text/css'>
        
        <!-- FANCYBOX -->
        <link rel="Stylesheet" href="<?php echo get_template_directory_uri(); ?>/metas/css/jquery.fancybox.css" />
        
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/metas/js/jquery.fancybox.js"></script>
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/metas/js/jquery.fancybox.pack.js"></script>
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/metas/js/jquery.mousewheel-3.0.6.pack.js"></script>
        <script type="text/javascript">
        
                    $('.fancybox').fancybox();
        
                    $(".various").fancybox({
                        maxWidth	: 800,
                        maxHeight	: 600,
                        fitToView	: false,
                        width		: '70%',
                        height		: '70%',
                        autoSize	: false,
                        closeClick	: false,
                        openEffect	: 'none',
                        closeEffect	: 'none'
                    });
        
            </script>
            
	<?php }else{ ?>
        <link rel="Stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/estilos.css" />
        <link rel="Stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/responsive.css" />
        <link rel="Stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/wp-paginate.css" />
        <link rel="Stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/social_comments.css" />
        <link rel="Stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css" />
        <link href='http://fonts.googleapis.com/css?family=Tinos:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Quicksand:300,400,700' rel='stylesheet' type='text/css'>
    <?php } ?>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script src="https://apis.google.com/js/platform.js"></script>
	
	<!-- JAVASCRIPT -->	
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/funcoes.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/action.js"></script>

	<script type="text/javascript">
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    
      ga('create', 'UA-44883416-1', 'sp.gov.br');
      ga('send', 'pageview');
    </script>
    
    <!-- TV HOME -->
    <link rel="Stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/jquery.bxslider.css" />
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.bxslider.min.js"></script>  
    <!--<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.easing.1.3.js"></script> -->
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.fitvids.js"></script>  
    <noscript>Seu navegador nao suporta JavaScript!</noscript>
	<title>
		<?php bloginfo( 'name' )?>
		<?php wp_title( ' - ', true, 'left' ); ?>
	</title>
	<style>
		/**LOADING*/
		#loading{background:url("<?php echo get_template_directory_uri(); ?>/img/fancybox_overlay.png"); width: 100%; height: 100%; position: fixed; z-index: 8011;display:none; }
		#loading .text{position: absolute; top: 50%; left: 50%; z-index: 10001; padding: 10px; margin-top: -10px; margin-left: -50px; width:100px;}
		/**LOADING*/
	</style>
</head>

<body>
<div id="fb-root"></div>
<div id="loading"><div class="text"><img src="<?php echo get_template_directory_uri(); ?>/img/loader.gif" width="45" height="45" /></div></div>

<?php if($lnk[$n]=='minuta-participativa'){ ?>
    <div id="divHeader">
        <div class="wrap">
            <h1><a href="<?php echo esc_url( home_url( '/index.php/' ) ); ?>" title="Planeja Sampa" class="logo">Planeja Sampa</a></h1>
            <a href="<?php echo esc_url( home_url( '/index.php/' ) ); ?>" class="setaVoltar"><img src="<?php echo get_template_directory_uri(); ?>/minuta/img/seta-voltar.png" width="20" height="16" alt="" /></a>
            <h2>Minuta Participativa do CPOP</h2>
            <div class="divRedes">
            	<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2FSEMPLASP&amp;width=103&amp;height=21&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false&amp;appId=414769835295526" scrolling="no" title="Redes Sociais" frameborder="0" style="border:none; overflow:hidden;" >Seu browser não suporta frames.</iframe>                
                <a class="twitter-follow-button" href="http://twitter.com/SEMPLASP?widgetId=387943626036084737" data-show-count="false" data-show-screen-name="false">Tweets by @SEMPLASP</a>
            </div>
        </div>
    </div>
    <div id="menu">
        <div class="wrap">
            <ul class="menutopo">
                <li><a class="help-button" href="#" onclick="javascript:introJs().start()">Ajuda</a></li>
                <?
                	if (!is_user_logged_in() ) {
						echo '<li><a class="register-button" href="'.esc_url(home_url("/wp-login.php?action=register&redirect_to=index.php/minuta-participativa/")).'">Cadastre-se</a></li>';
						echo '<li><a class="login-button" href="'.esc_url(home_url("/wp-login.php?action=login&redirect_to=index.php/minuta-participativa/")).'">Login</a></li>';
					}
				?>
            </ul>
        </div>
    </div>

<?php }elseif($lnk[($n+1)]=='metas'){ ?>
<div id="metas">
    <!--HEADER-->
	<div class="headerMetas">
    	<div class="wrap">
        	<h1><a href="<?php echo esc_url( home_url( '/index.php/' ) ); ?>" title="Planeja Sampa - fazendo juntos a cidade que a gente quer">Planeja Sampa</a></h1>
            <p class="programametas"><strong>&lt;&lt;</strong> <span>Programa de Metas</span></p>
        </div>
    </div>
    <!--FIM HEADER-->
    
    <!--MIOLO-->
    <div id="miolo">
		<div id="nav">
        	<div class="wrap">
        		<ul>
                	<li><a href="<?php echo esc_url( home_url( '/index.php/programa-de-metas/metas/conheca-o-programa/' ) ); ?>" title="conheça o programa">conheça o programa</a></li>
                    <li class="div">|</li>
                    <li><a href="<?php echo esc_url( home_url( '/index.php/programa-de-metas/metas/' ) ); ?>" title="objetivos e metas">objetivos e metas</a></li>
                    <li class="div">|</li>
                    <li><a href="<?php echo esc_url( home_url( '/index.php/programa-de-metas/metas/conceito-territorial/' ) ); ?>" title="conceito territorial">conceito territorial</a> </li>
                    <li class="div">|</li>
                    <li><a href="<?php echo esc_url( home_url( '/index.php/programa-de-metas/metas/contato/' ) ); ?>" title="contato">contato</a></li>
                </ul>
                <div class="redesSociais">
					<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2FSEMPLASP&amp;width=103&amp;height=21&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false&amp;appId=414769835295526" scrolling="no" title="Redes Sociais" frameborder="0" style="border:none; overflow:hidden;" >Seu browser não suporta frames.</iframe>                
                    <a class="twitter-follow-button" href="http://twitter.com/SEMPLASP?widgetId=387943626036084737" data-show-count="false" data-show-screen-name="false">Tweets by @SEMPLASP</a>
					<div class="g-ytsubscribe" data-channelid="UCWt0lT3VDLjqWDoCxckEh1A" data-layout="default" data-count="default"></div>
                </div>
        	</div>
        </div> 
          
        <!--LIGHTBOX-->
        <div id="boxes">
            <div id="divcontato" class="window">
                <div id="contatoContent"></div>
            </div>
        </div>
        <!-- Mask to cover the whole screen -->
		<div id="mask"></div>

            
<?php }else{ ?>
	<div id="page">
        <div id="header">
            <div class="wrap">
                <div class="logo"><a href="<?php echo esc_url( home_url( '/index.php/' ) ); ?>" title="<?php bloginfo( 'name' )?>"><?php bloginfo( 'name' )?></a></div>
                <div class="logoPrefeitura">
                    <a href="http://www.prefeitura.sp.gov.br/" target="_blank" >Prefeitura de S&#65533;o Paulo</a>
                </div>
                <div class="redesSociais">
                    <span class="siga">NOS SIGA</span>
                    <div class="btnFacebook"><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2FSEMPLASP&amp;width=103&amp;height=21&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false&amp;appId=414769835295526" scrolling="no" title="Redes Sociais" frameborder="0" style="border:none; overflow:hidden;" >Seu browser não suporta frames.</iframe></div>
                    <div class="btnTwitter"><a class="twitter-follow-button" href="http://twitter.com/SEMPLASP?widgetId=387943626036084737" data-show-count="false" data-show-screen-name="false">Tweets by @SEMPLASP</a></div>
					<div class="btnYoutube"><div class="g-ytsubscribe" data-channelid="UCWt0lT3VDLjqWDoCxckEh1A" data-layout="default" data-count="default"></div></div>
                </div>
                <div class="divBusca">
                    <form method="get" id="searchform" action="<?php echo esc_url( home_url( '/index.php/' ) ); ?>">
                    <fieldset>
                        <legend>Busca</legend>
                        <label for="s"><input class="busca defaultText" type="text" name="s" id="s"  title="buscar" value="busca" /></label>
                        <input type="submit" class="submit" name="submit" id="searchsubmit" value="Buscar" />
                        </fieldset>
                    </form>
                    
                </div>
                <div class="divNews">
                    <form method="post" action="<?php echo esc_url( home_url( '/wp-content/plugins/newsletter/do/subscribe.php' ) ); ?>" onsubmit="return newsletter_check(this)">
                        <fieldset>
                            <legend>Newsletter</legend>
                        <label for="newsletter">
                            <span>NEWSLETTER</span>
                            <input class="busca defaultText" type="text" name="ne" title="seu e-mail"  value="seu e-mail" id="newsletter"/>
                            <input class="newsletter-submit" type="submit" value="Subscribe"  id="Enviar" name="Enviar"/>
                        </label>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        
        
           
        <div class="wrap">
            <div id="menu">
                <a href="#" class="menuPeq">Menu</a>
                <ul class="nav">
                    <li tabindex="1">
                        <a title="Apresenta&ccedil;&atilde;o" style="cursor: pointer" <?php echo ($lnk[$n]=='apresentacao'?'class="ativo"':'');?>>Apresenta&ccedil;&atilde;o</a>
                        <ul class="submenu">
                            <li tabindex="2"><a href="<?php echo esc_url( home_url( '/index.php/apresentacao/o-que-e' ) ); ?>" title="O que &eacute;">O que &eacute;</a></li>
                            <li tabindex="3"><a href="<?php echo esc_url( home_url( '/index.php/apresentacao/fases' ) ); ?>" title="Fases">Fases</a></li>
                            <?php /*<li><a href="<?php echo esc_url( home_url( '/index.php/perguntas-frequentes' ) ); ?>" title="Perguntas frequentes">Perguntas frequentes</a></li>*/?>
                        </ul>
                    </li>
                    <li tabindex="4"><a href="<?php echo esc_url( home_url( '/index.php/noticias' ) ); ?>" title="Not&iacute;cias" <?php echo ($lnk[$n]=='noticias'?'class="ativo"':'');?> <?php echo ($lnk[$n]=='noticia'?'class="ativo"':'');?>>Not&iacute;cias</a></li>
                    <li tabindex="5"><a href="<?php echo esc_url( home_url( '/index.php/agenda' ) ); ?>" title="Agenda" <?php echo ($lnk[$n]=='agenda'?'class="ativo"':'');?>>Agenda</a></li>
                    <?php /*<li><a href="<?php echo esc_url( home_url( '/index.php/planejando-juntos' ) ); ?>" title="Planejando juntos" <?php echo ($lnk[3]=='planejando-juntos'?'class="ativo"':'');?>>Planejando juntos</a></li>*/?>
                    <li tabindex="6"><a href="<?php echo esc_url( home_url( '/index.php/biblioteca' ) ); ?>" title="Planejando juntos" <?php echo ($lnk[$n]=='biblioteca'?'class="ativo"':'');?>>Biblioteca</a></li>
                </ul>
                <ul class="menuDestaques">
                    <li><a href="<?php echo esc_url( home_url( '/index.php/programa-de-metas' ) ); ?>" title="Programa de Metas">Programa de Metas</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/index.php/plano-plurianual' ) ); ?>" title="Plano Plurianual">Plano Plurianual</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/index.php/leis-de-diretrizes-orcamentarias' ) ); ?>" title="Leis de Diretrizes Or&ccedil;ament&aacute;rias">Leis de Diretrizes<br />Or&ccedil;ament&aacute;rias</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/index.php/leis-orcamentarias-anuais' ) ); ?>" title="Leis Or&ccedil;ament&aacute;rias Anuais">Leis Or&ccedil;ament&aacute;rias Anuais</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/index.php/conselho-de-planejamento-e-orcamento-participativo' ) ); ?>" title="Conselho de Planejamento e Or&ccedil;amento Participativos">Conselho de Planejamento<br /> e Or&ccedil;amento Participativos</a></li>
                    
                </ul>
            </div>
        </div>
<?php } ?>