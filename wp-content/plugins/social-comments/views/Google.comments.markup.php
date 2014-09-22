<?php //?>
<!--Google Plus Comments By social Comments http://en.bainternet.info -->
<style type="text/css">#gplus_comments, #plusonecomments, #plusonecomments iframe {width: 98% !important;}</style>
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<div id="plusonecomments"></div>
<script type="text/javascript">
  window.setTimeout(function() {
    var id = 'plusonecomments';
    var divWidth = document.getElementById(id).offsetWidth;
    var width = !!divWidth ? Math.min(divWidth, 750) : 600;
    var url = "<?php the_permalink() ?>";
    var moderationUrl = "";
    var moderationMode = "FILTERED_POSTMOD";
    gapi.comments.render(id, {
        'href': url,
        'first_party_property': 'BLOGGER',
        'legacy_comment_moderation_url': moderationUrl,
        'view_type': moderationMode,
        'width': width,
        'height' : '200'
    });
  }, 10);
  //fix height and width
  jQuery(window).load(function() {
    jQuery("#plusonecomments").css({
        'height': '',
        'width' : '98%'
    });
    jQuery("#plusonecomments iframe").css({
        'height': '',
        'min-height': '300px',
        'width': '98%'
    });
    
  });
</script>