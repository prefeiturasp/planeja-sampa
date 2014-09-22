var passwordFieldAnimationSpeed = 0;
jQuery( document ).ready( function(){
    makeLabels();
    jQuery('#private_blog-settings-multiple_passwords').change( function(){
        if( jQuery(this).hasAttr( 'checked' ) )
        {
            enableMultiPass();
        }
        else
        {
            disableMultiPass();
        }
    }).change();
    passwordFieldAnimationSpeed = 100;
});

function enableMultiPass()
{
    if(passwordFieldAnimationSpeed == 0)
    {
        jQuery( '.setting.tag-multi-password' ).fadeIn(passwordFieldAnimationSpeed);
    }
    var theSetting = jQuery( '#setting-cntr_private_blog-settings-password1_value' );
    var pluralName = jQuery( theSetting ).attr( 'data-name-plural' );
    jQuery( theSetting ).find( ".setting-name" ).html( pluralName );
    
    slideInAndFadePassword( theSetting );
    
}

function disableMultiPass()
{
    if(passwordFieldAnimationSpeed == 0)
    {
        jQuery( '.setting.tag-multi-password' ).fadeOut(passwordFieldAnimationSpeed);
    }
    var theSetting = jQuery( '#setting-cntr_private_blog-settings-password1_value' );
    var singleName = jQuery( theSetting ).attr( 'data-name-singular' );
    jQuery( theSetting ).find( ".setting-name" ).html( singleName );

    theSetting = jQuery( '#setting-cntr_private_blog-settings-password10_value' );
    slideOutAndFadePassword( theSetting ); 
}

function slideOutAndFadePassword( selector )
{
    var shortName = jQuery(selector).attr( 'data-pass-short-name' );
    var labelWidth = jQuery(selector).find('.custom-password-label').width();
    var newOpacity = 0;
    var newOpacityRev = 1;
    if( shortName == "password1")
    {
        newOpacity = 1;
        newOpacityRev = 0;
    }
    jQuery(selector).find('.custom-password-label').animate({marginLeft: -(labelWidth + 10 ),opacity:newOpacityRev}, passwordFieldAnimationSpeed, function(){
        jQuery(this).css({'display':'none'});
        
    });
    jQuery(selector).animate({opacity: newOpacity}, passwordFieldAnimationSpeed, function(){
        var shortName = jQuery(this).attr( 'data-pass-short-name' );
        if( shortName == "password1")
        {
            var newName = shortName.substring( 8 );
            newName = parseInt(newName) - 1;
            var nextInput = jQuery('#setting-cntr_private_blog-settings-password' + newName + '_value');
            if( jQuery(nextInput).length > 0 )
            {
                slideOutAndFadePassword( jQuery(nextInput) );
            }
        }
        else
        {
            jQuery(this).slideUp( passwordFieldAnimationSpeed, function(){
                var shortName = jQuery(this).attr( 'data-pass-short-name' );
                var newName = shortName.substring( 8 );
                newName = parseInt(newName) - 1;
                var nextInput = jQuery('#setting-cntr_private_blog-settings-password' + newName + '_value');
                if( jQuery(nextInput).length > 0 )
                {
                    slideOutAndFadePassword( jQuery(nextInput) );
                }
            });
        }
    });
}

function slideInAndFadePassword( selector )
{
    var shortName = jQuery(selector).attr( 'data-pass-short-name' );
    jQuery(selector).slideDown( passwordFieldAnimationSpeed, function(){
        jQuery(this).find( '.custom-password-label' ).css({'display':'block'}).animate({marginLeft:0,opacity:1}, passwordFieldAnimationSpeed, function(){
            
        });
        jQuery(this).animate({opacity: 1}, passwordFieldAnimationSpeed, function(){
            var shortName = jQuery(this).attr( 'data-pass-short-name' );
            var newName = shortName.substring( 8 );
            newName = parseInt(newName) + 1;
            var nextInput = jQuery('#setting-cntr_private_blog-settings-password' + newName + '_value');
            if( jQuery(nextInput).length > 0 )
            {
                slideInAndFadePassword( jQuery(nextInput) );
            }
        });
    });
}

function makeLabels()
{
    jQuery( '.setting.tag-password-label').each(function(){
        var shortName = jQuery(this).attr( 'data-pass-short-name' );
        var labelColor = jQuery( '#private_blog-settings-' + shortName + '_colour').val();
        var labelName = jQuery( '#private_blog-settings-' + shortName + '_name').val();

        jQuery(this).find( '.custom-password-label span' ).html( labelName ).css({backgroundColor: labelColor});

        jQuery(this).find('.custom-password-label').click(function() {
            var current = jQuery( '#private_blog-settings-' + shortName + '_name').val();
            var newLabel = prompt( 'Enter password label', current );
            if(newLabel != null) {
                jQuery( '#private_blog-settings-' + shortName + '_name').val(newLabel);
                jQuery(this).find( 'span' ).html( newLabel )
            }
        });
    });
}
