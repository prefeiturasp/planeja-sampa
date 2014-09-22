var lavaAnimations = true;
var codeBoxes = new Array();

jQuery(document).ready(function(){
    addResetSettings();
    bindSkin();

    jQuery('.js-only').removeClass('js-only');
	jQuery('.js-fallback').hide();
    jQuery('select').dropkick();
    dragAndDrop();

	bindSticky();
	bindButtons();
    bindImageUpload();
    bindImageChange();
    bindFocus();
    bindSettingToggle();
    bindAutoResize();
    bindDataSource();

    prettifyCheckboxes();
    prettifyPasswords();
    prettifyTexts();
    prettifyTimePeriods();
    prettifyColors();
    prettifyCode();


	jQuery( '.tiptip' ).tipTip({'delay':0});
    jQuery( '.tiptip-right' ).tipTip({'defaultPosition':'right','delay':0});
});

function dragAndDrop() {
    //works out whether there is drag and drop support
    jQuery('html').addClass("no-drag-drop");
    if( typeof(DataTransfer) != 'undefined' ) {//stupid opera
        if( typeof(DataTransfer) != undefined ) {
            if ("files" in DataTransfer.prototype) {
               jQuery('html').addClass("drag-drop").removeClass("no-drag-drop");
            }
        }
    } else {//WebKit
        if(RegExp(" AppleWebKit/").test(navigator.userAgent)) {
            jQuery('html').addClass("drag-drop").removeClass("no-drag-drop");
        }
    }
        
}


function prettifyCheckboxes()
{
    jQuery('.setting[data-type="checkbox"]').each(function(){
        var checked = jQuery(this).find('input[type="checkbox"]').addClass( "invisible" ).hasAttr( "checked" );
        jQuery(this).find('input[type="checkbox"]').change(function(){
            var checked = jQuery(this).hasAttr( "checked" );
            var checkboxUx = jQuery(this).parents( '.setting' ).find( '.checkbox-ux' );
            if( checked )
            {
                jQuery( checkboxUx ).removeClass( "unchecked" ).addClass("checked");
            }
            else
            {
                jQuery( checkboxUx ).removeClass( "checked" ).addClass("unchecked");
            }
        }).change();
        jQuery(this).find('.checkbox-ux' ).click(function(){
            if( jQuery(this).siblings('input[type="checkbox"]').hasAttr( "checked" ) )
            {
                jQuery(this).siblings('input[type="checkbox"]').removeAttr( "checked" ).change();
                jQuery(this).removeClass("checked").addClass("unchecked");
            }
            else
            {
                jQuery(this).siblings('input[type="checkbox"]').attr( "checked", "checked" ).change();
                jQuery(this).removeClass("unchecked").addClass("checked");
            }
        });
    });
}

function prettifyPasswords()
{
    jQuery('.setting[data-type="password"]').each(function(){
        jQuery(this).find( 'input[type="password"]' ).blur(function(){
            var password = jQuery(this).val();
            jQuery(this).siblings(".password-show").val(password);

        });
        jQuery(this).find( ".password-show" ).blur(function(){
            var password = jQuery(this).val();
            jQuery(this).siblings('input[type="password"]').val(password);
        });

        jQuery(this).find( ".show-password-handle" ).click(function(){
			var currentPassword = jQuery(this).parents('.setting').find('.input-cntr').attr("data-show", "text").find( '.password-show' ).val();//hack to prevent browser from selecting text in field
			jQuery(this).parents('.setting').find( '.password-show' ).change().focus().val( currentPassword );
            jQuery(this).siblings(".hide-password-handle").show();
            jQuery(this).hide();
        });

        jQuery(this).find( ".hide-password-handle" ).click(function(){
			var currentPassword = jQuery(this).parents('.setting').find('.input-cntr').attr("data-show", "password").find( 'input[type="password"]' ).val();
			jQuery(this).parents('.setting').find( 'input[type="password"]' ).change().focus().val( currentPassword );
            jQuery(this).siblings(".show-password-handle").show();
            jQuery(this).hide();
        });
    });
}

function prettifyTexts()
{
    jQuery('.setting[data-type="text"]').each(function(){
    });
}

function prettifyTimePeriods()
{
    jQuery('.setting[data-type="timeperiod"]').each(function(){
        jQuery(this).find('input[data-actual="true"]').addClass("invisible").change(function(){
            var newValue = jQuery( this ).val();
            newValue = Math.round( newValue / 60 ) * 60;
            jQuery( this ).val( newValue );//make sure it is a multiple of 60
            if( newValue % ( 60 * 60 * 24 * 7 ) == 0 )
            {
                jQuery( this ).parents( '.setting' ).find( '.time-period-ux' ).val( newValue / (60*60*24*7) );
                jQuery( this ).parents( '.setting' ).find( 'a[data-dk-dropdown-value="' + 60*60*24*7  + '"]' ).click();
            }
            else if( newValue % ( 60 * 60 * 24  ) == 0 )
            {
                jQuery( this ).parents( '.setting' ).find( '.time-period-ux' ).val( newValue / (60*60*24) );
                jQuery( this ).parents( '.setting' ).find( 'a[data-dk-dropdown-value="' + 60*60*24  + '"]' ).click();
            }
            else if( newValue % ( 60 * 60  ) == 0 )
            {
                jQuery( this ).parents( '.setting' ).find( '.time-period-ux' ).val( newValue / (60*60) );
                jQuery( this ).parents( '.setting' ).find( 'a[data-dk-dropdown-value="' + 60*60  + '"]' ).click();
            }
            else
            {
                jQuery( this ).parents( '.setting' ).find( '.time-period-ux' ).val( newValue / (60) );
                jQuery( this ).parents( '.setting' ).find( 'a[data-dk-dropdown-value="' + 60  + '"]' ).click();
            }
        });

        jQuery(this).find('select').change(function(){
            var quantity = jQuery(this).siblings('.input-cntr').find('.time-period-ux').val();
            var multiplier = jQuery(this).val();

            jQuery(this).siblings('input[data-actual="true"]').val( quantity * multiplier );
        });
        jQuery(this).find('.time-period-ux').change(function(){
            var quantity = jQuery(this).val();
            var multiplier = jQuery(this).parents('.setting-control').find('select').val();

            jQuery(this).parents('.setting-control').find('input[data-actual="true"]').val( quantity * multiplier );
            
        });
    });
}

function prettifyColors() {
    jQuery('.setting.type-color').each(function(){
        jQuery(this).find('input[data-actual="true"]').change(function(){
            var value = jQuery(this).val();
            jQuery(this).parents('.color-preview').css("backgroundColor", value ).find('.color-hex').html( value );
        }).change().ColorPicker({
    onShow: function (colpkr) {
        jQuery(colpkr).fadeIn(500);
        return false;
    },
    onHide: function (colpkr) {
        jQuery(colpkr).fadeOut(500);
        return false;
    },
    onChange: function (hsb, hex, rgb) {
    }
});;//load current colour
        jQuery(this).find('.lava-shadow-overlay').click(function(){
            jQuery(this).parents('.setting').find('input[data-actual="true"]').click();
        });
    });
}

function bindImageChange() {
    jQuery('.setting.type-image input[data-actual="true"]').change(function(){
        var url = jQuery(this).val();
        jQuery(this).parents('.image-thumb').find('img').attr('src', url);
    });
}

function addResetSettings()
{
    jQuery( '.setting' ).each(function(){
        jQuery(this).find( '.reset-setting' ).click(function(){
            var settingParent = jQuery(this).parents( ".setting" );
            var defaultValue = jQuery(settingParent).attr("data-default-value");
            var valueChanged = changeSettingValue(settingParent, defaultValue);
            if( valueChanged )
            {
                jQuery(this).siblings('.undo-reset-setting').show();
                jQuery(this).hide();
                jQuery(settingParent).find('.show-status').each(function(){
                    var originalColor = jQuery(this).css("backgroundColor");
                    var newColor = '#FDEEAB';
                    jQuery(this)
                        .css({'background-image': 'none'})
                        .animate({backgroundColor: newColor}, 100).animate({backgroundColor: originalColor }, 100)
                        .animate({backgroundColor: newColor}, 100).animate({backgroundColor: originalColor }, 100)
                        .animate({backgroundColor: newColor}, 100).animate({backgroundColor: originalColor }, 100)
                        .animate({backgroundColor: newColor}, 100).animate({backgroundColor: originalColor }, 100, function(){
                            jQuery(this).css({'background-image': ''});
                        });
                });
            }
        });
        jQuery(this).find( '.undo-reset-setting' ).click(function(){
            var settingParent = jQuery(this).parent().parent().parent();
            var newValue = jQuery(settingParent).attr("data-default-undo");
            var valueChanged = changeSettingValue(settingParent, newValue);
            jQuery(this).siblings('.reset-setting').show();
            jQuery(this).hide();
            jQuery(settingParent).find('.show-status').each(function(){
                var originalColor = jQuery(this).css("backgroundColor");
                var originalImage = jQuery(this).css("backgroundImage");
                var newColor = '#FDEEAB';
                jQuery(this)
                    .css({'background-image': 'none'})
                    .animate({backgroundColor: newColor}, 100).animate({backgroundColor: originalColor }, 100)
                    .animate({backgroundColor: newColor}, 100).animate({backgroundColor: originalColor }, 100)
                    .animate({backgroundColor: newColor}, 100).animate({backgroundColor: originalColor }, 100)
                    .animate({backgroundColor: newColor}, 100).animate({backgroundColor: originalColor }, 100, function(){
                        jQuery(this).css({'background-image': ''});
                    });
            });
            
        });
    });
}

function changeSettingValue(settingSelector, settingValue)
{
    
    var settingCurrent = jQuery(settingSelector).find('*[data-actual="true"]').val();
    var settingType = jQuery(settingSelector).attr("data-type");
    var doDefault = true;
    var isChanged = false;

    if(settingType == 'checkbox')
    {
        settingCurrent = "off";
        if(jQuery(settingSelector).find('.checkbox-ux').hasClass('checked'))
        {
            settingCurrent = "on";
        }
        if( settingValue == "on" )
        {
            jQuery(settingSelector).find('input[type="checkbox"]').attr("checked", "checked").change();
        }
        else
        {
            jQuery(settingSelector).find('input[type="checkbox"]').removeAttr("checked").change();
        }
    }
    jQuery(settingSelector).attr('data-default-undo', settingCurrent);

    if( settingCurrent != settingValue)
    {
        isChanged = true;
    }
    if( doDefault )
    {
        jQuery(settingSelector).find('*[data-actual="true"]').val( settingValue ).change().blur();
    }
    return isChanged;
}


function bindButtons() {
    //the save buttons
    jQuery(".lava-btn.lava-btn-form-submit").click(function(){
        if( jQuery(this).hasAttr('data-clicked-text') ) {
            var text = jQuery(this).attr('data-clicked-text');
            jQuery(this).html( text );
        }
        var formID = jQuery(this).attr( "data-form" );
		jQuery("#" + formID).submit();
    });
	//the underground buttons
	jQuery(".lava-btn.lava-btn-show-underground").click(function(){
        showUnderground();
    });
	jQuery(".lava-btn.lava-btn-hide-underground").click(function(){
        hideUnderground();
    });
	//not implemented buttons
	jQuery(".lava-btn.not-implemented").addClass("lava-btn-disabled").addClass("tiptip-right").attr("title", "This feature hasn't been implemented yet :(");
}

function bindSticky()
{
	jQuery('#wpbody').resize( function() {
		restartStickyBottom();
		restartStickyTop();
	});
	jQuery('#wpbody').resize();
	jQuery(window).scroll( function() {
		refreshStickyBottom();
		refreshStickyTop();
	});
	setTimeout( "restartStickyBottom()", 1000);
	setTimeout( "restartStickyTop()", 1000);
}

function bindSettingToggle() {
    jQuery( '.setting.tag-setting-toggle input[data-actual="true"]').change(function(){
        var setting_id = jQuery(this).parents('.setting').attr('data-setting-key');
        if( jQuery(this).hasAttr( "checked" ) ) {
            jQuery('.setting[data-setting-toggle="' + setting_id + '"]').removeClass( "lava-setting-toggle-hidden" );
        } else {
            jQuery('.setting[data-setting-toggle="' + setting_id + '"]').addClass( "lava-setting-toggle-hidden" );
        }
        codeRefresh();//hack to fix code box issues
    });
}

function bindImageUpload() {
    jQuery('.setting.type-image .lava-file_upload-manual_select').bind('fileuploaddone', function (e, data) {
    });
}

function bindAutoResize() {
    jQuery('.lava-auto-resize').autoResize({'extraSpace': 5, 'animate': false}).removeClass('lava-auto-resize').addClass('lava-auto-resize-init');

    jQuery('.lava-auto-resize-init');
}

function bindFocus() {
    jQuery('input.lava-focus-inner').focus(function(){
        jQuery(this).parents('.lava-focus-outer').addClass( "focus" );
    }).blur(function(){
        jQuery(this).parents('.lava-focus-outer').removeClass( "focus" );
    });
}

function bindDataSource() {
    var offset = 0;
    jQuery('.lava-table-loader-refresh-button').click(function(){
        offset = 0;
        doDataSource(offset);
    })
    jQuery('.lava-table-loader-older-button').click(function(){
        offset = offset + 1;
        doDataSource(offset);
    })
    doDataSource(0);
}

function doDataSource(offset) {
    jQuery('.lava-full-page-loader').show();
    jQuery('.lava-table-viewer').each(function(){
        jQuery(this).find('table').html("<thead></thead><tbody></tbody>");
        var dataSource = jQuery(this).attr( "data-data-source" );
        var action = jQuery(this).attr( "data-ajax-action" );
        var nonce = jQuery(this).attr( "data-ajax-nonce" );
		jQuery.getJSON( ajaxurl + '?action=' + action + '&nonce=' + nonce + '&data-source=' + dataSource + '&offset=' + offset, function(data) {
			jQuery('.lava-full-page-loader').hide();
			parseTableData( dataSource, data["data"]["data"] );
        });
    });

    jQuery('.lava-table-update-trigger').change(function(){
    	jQuery(this).siblings('table').find('.impelements-timestamp').each(function(){
    		var timestamp = jQuery(this).html();
    	});
    });
}


function parseTableData( dataSource, data ) {

	var theTable = jQuery('.lava-table-viewer[data-data-source="' + dataSource + '"] table');
	var theTableBody = jQuery(theTable).find('tbody');

	for( row in data ) {
		var theRow = jQuery("<tr></tr>").appendTo(theTableBody);
		for( column in data[row] ) {
			var theCol = jQuery("<td></td>").appendTo( theRow );
			jQuery(theCol).attr('class', data[row][column]['classes']);
			jQuery(theCol).addClass( "cell-" + column );
			jQuery(theCol).attr( "data-value" + data[row][column] );
			jQuery(theCol).html( data[row][column]['data'] );
			jQuery(theCol).attr( "title", data[row][column]['title'] );
		}
	}

	jQuery('.lava-table-update-trigger').change();

}

function restartStickyTop()
{
	var leftPosition = jQuery('#adminmenuback').outerWidth();//work out how far from the left it should be when absolutely positioned;
	var topPosition = jQuery('#wpbody').offset();//work out how far from top it should be positioned so it doesn't cover the admin bar
	topPosition = topPosition.top;

	jQuery('html.cssanimations .lava-sticky-top').each(function(){
		var offset = jQuery(this).removeClass('sticky').offset();
		jQuery(this).attr( 'data-sticky-offset', offset.top - topPosition );
		jQuery(this).attr( 'data-sticky-leftposition', leftPosition );
		jQuery(this).attr( 'data-sticky-topposition', topPosition );
	});

	refreshStickyTop();
}

function refreshStickyTop()
{
	jQuery('html.cssanimations .lava-sticky-top').each(function(){
		var offset = jQuery(this).attr('data-sticky-offset');//distance between object and top of document
		var targetOffset = jQuery(document).scrollTop();
		var leftPosition = jQuery(this).attr('data-sticky-leftposition');
		var topPosition = jQuery(this).attr('data-sticky-topposition');
		offset = parseInt(offset);
		targetOffset = parseInt(targetOffset);

		if( offset < targetOffset ) {
			jQuery(this).addClass('sticky').css({'left':leftPosition + 'px', 'top':topPosition + 'px'});
		} else if( offset > targetOffset ) {
			jQuery(this).removeClass('sticky').css({'left':'0px','top':'0px'});
		}
	});
}

function restartStickyBottom()
{
	var leftPosition = jQuery('#adminmenuback').outerWidth();
	jQuery('html.cssanimations .lava-sticky-bottom').each(function(){
		var offset = jQuery(this).removeClass('sticky').offset();
		var targetOffset = jQuery('body').height() - jQuery(this).outerHeight() + 5;

		jQuery(this).attr( 'data-sticky-offset', offset.top );
		jQuery(this).attr( 'data-sticky-target', targetOffset );
		jQuery(this).attr( 'data-sticky-leftposition', leftPosition );
	});

	refreshStickyBottom();
}

function refreshStickyBottom()
{
	jQuery('html.cssanimations .lava-sticky-bottom').each(function(){
		var offset = jQuery(this).attr('data-sticky-offset');
		var targetOffset = jQuery(document).scrollTop() + parseInt(jQuery(this).attr('data-sticky-target'));
		var leftMargin = jQuery(this).attr('data-sticky-leftposition');
		offset = parseInt(offset);
		targetOffset = parseInt(targetOffset);

		if( offset > targetOffset ) {
			jQuery(this).addClass('sticky').css({'left':leftMargin + 'px'});
		} else if( offset < targetOffset ) {
			jQuery(this).removeClass('sticky').css({'left':'0px'});
		}
	});
}


function showUnderground( context ) {
    if( typeof(context) == undefined ) {
        context = "page";
    }
	var animationDuration = 500;
    jQuery('.lava-underground').attr( 'data-underground-context', context );
	jQuery('.lava-underground').slideDown(animationDuration).removeClass('underground-hidden').addClass('underground-visible');
	jQuery('.lava-overground .underground-cancel-bar').slideDown().animate({'opacity':1},animationDuration, function(){
		jQuery('.lava-overground').addClass('lava-sticky-bottom');
		restartStickyBottom();
	});
	jQuery('.lava-overground .content').fadeOut(animationDuration);
    jQuery('.lava-content-cntr').addClass( "no-toolbar" );
}

function hideUnderground() {
	var animationDuration = 500;
	jQuery('.lava-overground').removeClass('lava-sticky-bottom').removeClass('sticky').css({'left':'0px'});
	jQuery('.lava-overground .content').fadeIn(animationDuration);
	jQuery('.lava-underground').slideUp(animationDuration).addClass('underground-hidden').removeClass('underground-visible');
	jQuery('.lava-overground .underground-cancel-bar').slideUp().animate({'opacity':0},animationDuration);
    jQuery('.lava-content-cntr').removeClass( "no-toolbar" );
}

function bindSkin() {
    jQuery( ".setting.type-skin input[data-actual='true']" ).change(function(){
        jQuery(this).parents('.setting-control').find('.skin').removeClass( "selected" );
        var currentTheme = jQuery(this).val();
        jQuery('.skin[data-slug="' + currentTheme + '"]').addClass('selected');

        //show skin options

        jQuery('.setting.tag-skin-setting').addClass( 'tag-setting-hidden' );
        jQuery('.setting[data-skin="' + currentTheme + '"]').removeClass( 'tag-setting-hidden' );
        codeRefresh();
        bindAutoResize();
    }).change();

    jQuery( '.skin-selector .skin .select-skin').click(function(){
        var new_skin = jQuery(this).parents('.skin').attr('data-slug');
        jQuery(this).parents('.setting-control').find('input[data-actual="true"]').val(new_skin).change();
    })
	
}

function prettifyCode() {
    jQuery('.lava-code-textarea').each(function(){
        var myCodeMirror = CodeMirror.fromTextArea(jQuery(this)[0], {
                lineWrapping: true,
                matchBrackets: true,
                indentUnit: 4,
                indentWithTabs: true,
                enterMode: "keep",
                tabMode: "shift",
                lineNumbers: true
        });
        codeBoxes.push(myCodeMirror);
    })
}

function codeRefresh() {
    for( i in codeBoxes ) {
        var current_value = codeBoxes[i].getValue();
        codeBoxes[i].setValue( "  " );
        codeBoxes[i].refresh();
        codeBoxes[i].setValue( current_value );
    }
}