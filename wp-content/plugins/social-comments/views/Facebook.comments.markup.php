<?php //?>
<!--Facebook Comments By social Comments http://en.bainternet.info -->
<style type="text/css">.fb-comments, .fb-comments span, .fb-comments iframe {width: 100% !important;}</style>
<?php $this->modLink ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php $this->appID ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-comments" data-href="<?php the_permalink() ?>"; data-width="470" data-num-posts="10" data-colorscheme="<?php $this->Color_Scheme ?>"></div>