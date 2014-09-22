<?php 
	$lnk = explode('/',$_SERVER[REQUEST_URI]);
	$n = ($lnk[1]=='SJ2231'?3:2);

if($lnk[$n]=='minuta-participativa'){
    
}elseif($lnk[($n+1)]=='metas'){    
?>
        </div>
    </div>
    <!--MIOLO-->
    
</div>

<?php }else{ ?>
    	<div id="footer">
            <div class="wrap">
                <div class="logosFooter">
                    <a href="<?php echo esc_url( home_url( '/index.php/' ) ); ?>" title="Planeja Sampa"><img src="<?php echo get_template_directory_uri(); ?>/img/logop.jpg" alt="Planeja Sampa" width="140" height="80" /></a>
                    <a href="http://www.prefeitura.sp.gov.br/" target="_blank" title="Prefeitura de S&atilde;o Paulo"><img src="<?php echo get_template_directory_uri(); ?>/img/logoPrefeitura.jpg" alt="Prefeitura de São Paulo" width="156" height="71" class="logoPrefeitura"/></a>
                </div>
                
                <ul class="menuFooter">
                    <li><a href="<?php echo esc_url( home_url( '/index.php/regras' ) ); ?>" title="Regras">Regras</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/index.php/contato' ) ); ?>" title="Contato">Contato</a></li>
                </ul>
                <div class="redesSociais">
                    <span>Redes sociais: </span> 
                    <a href="http://www.facebook.com/SEMPLASP" title="Facebook" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/img/icoFacebook.jpg" width="40" height="45" alt="Facebook" /></a>
                    <a href="https://twitter.com/SEMPLASP" title="Twitter" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/img/icoTwitter.jpg" width="40" height="45" alt="Facebook" /></a>
                    <a href="http://www.youtube.com/channel/UCWt0lT3VDLjqWDoCxckEh1A" title="Youtube" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/img/icoYoutube.jpg" width="40" height="45" alt="Facebook" /></a>
                    
                </div>
                <p class="secretaria">Secretaria Municipal do Planejamento, Or&ccedil;amento e Gest&atilde;o (SEMPLA) - <br />
                    Prefeitura de S&atilde;o Paulo<br />
                    Viaduto do Ch&aacute;, 15 - 9&ordm; andar - Centro <br />
                    CEP 01002-020 - S&atilde;o Paulo - SP<br />
                    Telefone: 156 </p>
                  <p class="conteudo">Todo o conte&uacute;do do site est&aacute; dispon&iacute;vel sob licen&ccedil;a <a target="_blank" href="http://creativecommons.org/licenses/by-sa/3.0/deed.pt_BR">Creative Commons</a>. O c&oacute;digo deste site &eacute; livre, consulte nossa <a href="index.php/desenvolvimento/">p&aacute;gina sobre desenvolvimento</a>.</p>
    
            </div>
        </div>
    </div>
<?php } ?>
	

<?php wp_footer(); ?>
</body>
</html>