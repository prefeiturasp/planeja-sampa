
/*
	Copyright (C) 2008 - 2013 Georgiy Vasylyev 
*/

iAjax = new function(){

	this.dParams = {};
	this.method = 'POST';

	this.request = function(requesto){
		var url = requesto.url || this.url;
		var ajx = {};      
		ajx.type = requesto.method || this.method;
		var params = AppMan.Utils.apply({}, this.dParams);
		params = AppMan.Utils.apply(params, requesto.params);

		ajx.data = params;
		ajx.data.action = 'easy-contact-forms-submit';
		if (requesto.dataType) ajx.dataType = requesto.dataType;
		if (requesto.success) ajx.success = requesto.success;
		ajx.error = (requesto.error) ? requesto.error : AppMan.handleError;

		var jaxhr = jQuery.ajax(url, ajx);
		if (requesto.object) jaxhr.robj = requesto.object;

	};

};

CalendarFactory = function(){

	this.instances = [];
	this.create = function(elId, config){
		elId = AppMan.Utils.idJoin(AppMan.factoryflag, elId);
		config.inputField=elId; 	
		config.button=AppMan.Utils.idJoin(elId, 'Trigger'); 	
		var calendar = Calendar.setup(config);
		this.instances.push(calendar);	
	};

	this.clear=function(){
		this.instances.length=0;
	};

};

TMCEFactory = function(){

	this.instances = {};
	this.create = function(elId, params){
		elId = AppMan.Utils.idJoin(AppMan.factoryflag, elId);
		params.mode='exact';
		params.elements=elId;
		var tmce = tinyMCE.init(params);
		this.instances[elId]=tmce;	
		jQuery('#'+elId).addClass('ufo-tinymce');
	};

	this.removeTinyMCEs=function(){
		for (var prop in this.instances){	
			var e=tinyMCE.get(prop);
			if (e!=null)
				tinyMCE.remove(e);
			delete this.instances[prop];				
		}
	};

	this.getTMCEContent=function(id, header, result){
		var e=tinyMCE.get(id);
		if (e!=null){
			var content = e.getContent();
			return content;
		}
		return false;
	};

};

AutoSuggestFactory = function(){

	this.instances = [];
	this.infos = [];

	this.create = function(elId, uparams, config){
		elId = AppMan.Utils.idJoin(AppMan.factoryflag, elId);
		params = {};
		params.minchars = 1;
		params.varname = 'query';
		params.className = 'autosuggest';
		params.timeout = 4500;
		params.delay = 500;
		params.offsety = -5;
		params.shownoresults = true;
		params.noresults = AppMan.resources.NoResults;
		params.maxheight = 250;
		params.cache = true;
		asFact = this;
		params.callback = function(data){asFact.setValues(data, elId);};
		params = AppMan.Utils.apply(params, uparams);
		if (!config.m)config.m='ajaxsuggest'; 
 
		var as = new AutoSuggest(elId, params, config);
		this.instances.push(as);	
		this.init(elId);
	};

	this.clear = function(){
		this.instances.length=0;	
		this.clearInfo();
	};

	this.clearInfo = function(){
		for (var i = 0; i < this.infos.length; i++){
			var info = this.infos[i];
			info.empty();
			info.remove();
		}
		this.infos.length=0;
	};

	this.getItem = function(elId){
		for(var i = 0; i< this.instances.length; i++){
			var as = this.instances[i];
			if (as.valueId == elId) return as;
		}
		return null;
	};

	this.getConfig = function(elId){
		var as = this.getItem(elId);
		if (as) return as.config;
		return null;
	};

	this.redirect = function(el, elId, objtype){
		elId = AppMan.Utils.siblingId(el, elId);
		objid = parseInt(jQuery('#'+elId).val());
		if (objid > 0){
			var request = {};
			request.t = objtype;
			request.m = 'show';
			request.oid = objid;
			ufo.redirect(request);
		}
	};

	this.init = function(elId){
		var value = jQuery('#'+elId).val();
		if (value && value != 0 && value != ''){
			var config = this.getConfig(elId);
			var ajx = {};
			ajx.params = AppMan.Utils.apply({}, config);
			ajx.params.oid = value;
			ajx.object = {};
			ajx.object.id = elId;
			ajx.object.asf = this;
			ajx.success = this.setAjaxValues;
			AppMan.Ajax.request(ajx); 			
		}
	};

	this.getFirstRecord = function(data){
		var response = AppMan.JSON.decode(data);
		if (response.results.length == 0) return false;
		return response.results[0];
	};	
	
	this.setAjaxValues = function(data, status, jqxhr){
		var rec = AppMan.AutoSuggest.getFirstRecord(data);
		AppMan.AutoSuggest.setValues(rec, jqxhr.robj.id);
	};	
	
	this.setValues = function(data, id){
		jQuery('#'+id).val(data.id);
		var $input = jQuery('#'+id+'input');
		$input.val(data.value);
		this.setTriggerHover(data, id);
	};		

	this.showInfo = function(config, el){
		var elid = el.id;
		if (jQuery('#'+elid+'info').length > 0) return;
		var ajx = {};
		ajx.params = AppMan.Utils.apply({}, config);
		ajx.success = function(data){
			var rec = AppMan.AutoSuggest.getFirstRecord(data);
			AppMan.AutoSuggest.setTriggerHover(rec, elid);
		};
		AppMan.Ajax.request(ajx); 			
	};

	this.setTriggerHover = function(data, id){

		var triggerid='#'+id+'-Trigger';
		$trigger = jQuery(triggerid);
		if ($trigger.length == 0)$trigger = jQuery('#'+id);
			
		var infoid=id+'info';
		var $info=jQuery('#'+infoid);
		
		if (data.info != undefined && data.info != ''){
			var width= $trigger.width(), height= $trigger.height(), infohtml;

			if ($info.length == 0){
				$info=jQuery('<div class="ufo-as-info"></div>');
				$info.attr('id', infoid);
				jQuery('body').append($info);
				this.infos.push($info);
			}

			infohtml = '<div class="ufo-as-info-inner">';
			infohtml+=data.info;
			infohtml+='</div>';

			$info.html(infohtml);
			$trigger.unbind('mouseenter mouseleave mouseover');

			$trigger.hover(
				function(){
					var pos =  jQuery(this).offset();
		 			var vpos =  pos.top + $info.height()- jQuery(window).scrollTop() < jQuery(window).height() ?  pos.top - 5: pos.top + 5 - $info.height();
		 			var hpos =  pos.left + width + $info.width() + 20 < jQuery(window).width() ? pos.left + width + 2 : pos.left - $info.width() - 22;
					$info.css({left:hpos+'px', top:vpos+'px'}).fadeIn(300);
				},
				function(){
					$info.stop(true,true);
					$info.fadeOut(300);
				}
			);	
		} else {
			$info.empty();
			$trigger.unbind('mouseenter mouseleave');
		}
	};		

	this.blur = function(el, elId, md){
		var id = AppMan.Utils.siblingId(el, elId);
		var as = this.getItem(id);
		if (as) as.clearSuggestions();
		jQuery('#'+id+'info').stop(true, true);
		jQuery('#'+id+'info').animate({opacity:"hide", left:"80"},"fast");
		if (!jQuery('#'+id+'input').val()){
			var data = {id:'', value:'', info:''};
			this.setValues(data, id);
		}
		if (md != undefined){ 
			var valueEl = document.getElementById(id);
			AppMan.Filter.getListsOptions(valueEl, md);
		}		
	};		

};

AjaxUploadFactory = function(){

	this.instances = [];

	this.create = function(elId, request, oncomplete){
		elId = AppMan.Utils.idJoin(AppMan.factoryflag, elId);
		params = {};
		params.action = AppMan.Ajax.url;
		params.data = AppMan.Utils.apply({}, AppMan.Ajax.dParams);
		params.data.m = 'upload';
		params.data.action = 'easy-contact-forms-submit';
		params.data = AppMan.Utils.apply(params.data, request);
		params.name = request.t+'_'+request.fld+'_'+request.oid;
		params.onSubmit = this.onSubmit;
		params.onComplete = this.onComplete;
		var object = new AjaxUpload('#'+elId, params);
		object.oncomplete = oncomplete;
		this.instances.push(object);	
	};

	this.clear = function(){
		function removeNode(el){
			el.parentNode.removeChild(el);
		}
		for (var i = 0; i < this.instances.length; i++){	
			var au = this.instances[i]; 	
			if ( ! au._input ) continue;	
			removeNode(au._input.parentNode);			
		}
		this.instances.length=0;	
	};

	this.onSubmit = function(){
		jQuery('#'+this._button.id+' span a').text(AppMan.resources.Uploading);	
	};

	this.onComplete = function(){
		this.oncomplete.call();	
	};

	this.deleteFile = function(id, request, el, callback){
		request.m='delete';			
		ajx = {};			
		ajx.params = request;			
		ajx.success = this.deleteSuccess;			
		ajx.object = {};			
		ajx.object.id = id;			
		ajx.object.el = el;			
		ajx.object.callback = callback;			
		AppMan.Ajax.request(ajx);			
	};

	this.deleteSuccess = function(data, status, jqxhr){
		jQuery('#'+jqxhr.robj.id+' span button').text(AppMan.resources.Upload);	
		jQuery(jqxhr.robj.el).remove();	
		jqxhr.robj.callback.call(this);	
	};

};

var History = new function(){
	this.Actions={};
	this.Actions.clear=0;
	this.Actions.next=1;
	this.Actions.apply=2;
	this.Actions.applygui=3;
	this.Actions.refresh=5;
	this.Actions.back=6;
	this.Actions.reload=7;
	this.Actions.doNothing=8;
	this.data=[];
	this.selectors = {tab:'.ufo-tabs', tabmenu:'.ufo-tab-header li a', tableheader:'.ufo-tableheader'};
	this.tableheaders = ['.thacs', '.thdesc'];
	this.lastStep = null;
	this.getViewData=function(){
		var result = {};
		for (var prop in this.selectors){
			result[prop]={};
			jQuery(this.selectors[prop]).each(function(){
				var isActive = jQuery(this).hasClass('ufo-active') ? 'ufo-active' : 'none'; 
				result[prop][jQuery(this).attr('id')]=isActive;
			});			
		}	
		var sortarray=[];	
		for (var i = 0; i<this.tableheaders.length; i++){
			var headerclass=this.tableheaders[i];
			 
			jQuery(headerclass).each(function(){
				var th = {id: jQuery(this).attr('id'), className: headerclass.slice(1)};
				sortarray.push(th);
			});			
		}
		if (sortarray.length > 0) result.tableheaders = sortarray;		
		var activatedviews=[];	
		jQuery('.ufo-view-activated').each(function(){
			activatedviews.push(jQuery(this).attr('id')); 
		});
		if (activatedviews.length > 0) result.activatedviews=activatedviews;
		return result;
	};
	this.applyViewData=function(){
		if (!this.lastStep) return;
		var history = this.lastStep.viewdata;
		for (var prop in this.selectors){
			for (var id in history[prop]){
				jQuery('#'+id).removeClass('ufo-active');
				if (history[prop][id]=='ufo-active')
					jQuery('#'+id).addClass('ufo-active');
			}
		}
		var i;
		if (history.tableheaders){
			for (i = 0; i < history.tableheaders.length; i++){
				var th = history.tableheaders[i]; 
				jQuery('#'+th.id).addClass(th.className);
				jQuery('#'+th.id).parent().addClass('ufo-active');
			}
		}
		if (history.activatedviews){
			for (i = 0; i < history.activatedviews.length; i++){
				jQuery('#'+history.activatedviews[i]).addClass('ufo-view-activated'); 
			}
		}
	};
	this.doAction=function(action, request){
		switch(action){
			case this.Actions.next:

					this.next(request);	
					break;	
			case this.Actions.apply: 

					this.apply(request);	
					break;	
			case this.Actions.applygui: 

					this.applygui(request);	
					break;	
			case this.Actions.back: 

					this.back();	
					break;	
			case this.Actions.refresh: 

					this.refresh();	
					break;	
			case this.Actions.reload: 

					this.reload();	
					break;	
			case this.Actions.doNothing: 

			return;
			default: this.clear(request);
			break;	

		}
	};
	this.clear=function(request){
		this.data.length=0;
		this.lastStep.request=request;
	};
	this.next = function(request){
		if (this.lastStep) {
			this.lastStep.currentScrollPosition = this.wndScroll;
			delete this.wndScroll;
			this.wndScroll = undefined;
			this.data.push(this.lastStep);
		}
			
		this.lastStep = {};
		this.lastStep.request=request;
		this.doAction(this.Actions.refresh,null);
	};
	this.back = function(){
		if (this.lastStep) delete this.lastStep;
		this.lastStep=null;
		if (this.data.length==0) return;
		this.lastStep=this.data.pop();
		
		if (typeof(this.lastStep.currentScrollPosition) != 'undefined') {
			this.tgWndScroll = this.lastStep.currentScrollPosition;
		}
		
		AppMan.request(this.Actions.apply,this.lastStep.request);
	};
	this.refresh = function(){
		this.lastStep.filterdata = AppMan.Filter.getFilterData();
		this.lastStep.viewdata = this.getViewData();
	};
	this.apply = function(request){
		this.doAction(this.Actions.applygui,null);
		if (request.viewTarget == AppMan.bodyid)
			this.lastStep.request=request;
		AppMan.Filter.refreshDetailedViews(this.lastStep.filterdata);
	};
	this.applygui = function(){
		this.applyViewData();
		AppMan.Filter.applyViewData(this.lastStep.filterdata);
	};
	this.reload = function(){
		this.doAction(this.Actions.refresh,null);
		if (this.lastStep.request.m=='new'){
			this.lastStep.request.m='show';
			var elId = AppMan.Utils.idJoin(this.lastStep.request.hash,'oid'); 
			this.lastStep.request.oid=jQuery('#'+elId).val();			
		}
		AppMan.request(this.Actions.apply,this.lastStep.request);
	};
};

function Utils(){
	this.idDelimeter='-';
			
	this.apply=function(target, source){
		if (!target) target = {};
		if (!source) return target;
		for(var prop in source)
			target[prop]=source[prop];
		return target;
	};
			
	this.applyIf=function(target, source){
		if (!target) target = {};
		if (!source) return target;
		for(var prop in source)
			if (!target[prop])
				target[prop]=source[prop];
		return target;
	};
			
	this.splitUrlString=function(url){
		var params = {};
		var pairs = url.split('&');
		for(var i = 0; i < pairs.length; i++){
			var pair = pairs[i].split('=');
			params[pair[0]]=pair[1];
		}
		return params;
	};
			
	this.getUrlString=function(params){
		var str = [];
		for(var prop in params){
			str.push(prop +'='+params[prop]);
		}
		return str.join('&');
	};
			
	this.format=function(str, args){
		if (!str) return '';
		if (!args) return str;			
		var formatted = str;
		for (var i = 0; i < args.length; i++) {
			var regexp = new RegExp('\\{'+i+'\\}', 'gi');
			formatted = formatted.replace(regexp, args[i]);
		}
		return formatted;
	};
			
	this.getRoot =function (object, type, leaf){
		if (!object [type]) 
			object [type]={};
		if (!leaf)
			return object[type];
		if (!object [type][leaf] )
			object [type][leaf]=[];
		return object [type][leaf];
	};
			
	this.getConfig=function(config){
		var defaultConfig = {t:undefined, m:'view', viewTarget:AppMan.bodyid};
		if (typeof(config) == 'string'){ 
			defaultConfig.t=config;
			config=defaultConfig;
		} else {
			config = this.applyIf(config,defaultConfig);
		}
		if (config.viewTarget==AppMan.bodyid){
			config.hash = AppMan.hash;        	
		} else {
			config.hash = AppMan.Utils.idSplit(config.viewTarget)[0];				
		}
			
		return config;
	};
			
	this.getValue=function(el){
		var value, dbValue;
		if (el.hasClass('ufo-tinymce'))
			value = AppMan.TMCEFactory.getTMCEContent(el.attr('id'));
		else
			value = el.val();			
		dbValue = el.data('dbValue');
		if (dbValue == value) return undefined;
		return value;
	};
			
	this.getViewData=function(header, type, forms){
		var result = {}, id, names, root, form, value, ref = this;
		jQuery('.ufo-formvalue[id^="'+header+'"]').each(function(){
			value = ref.getValue(jQuery(this));
			if (value == undefined) return;
			id = jQuery(this).attr('id');
			names = AppMan.Utils.idSplit(id);
			root = AppMan.Utils.getRoot(result, names[1]);
			root[names[2]] = value;
		});
		if (!forms) forms = [];
		for (var prop in result){
			form = {};
			form.t = type;
			form.oid = prop;
			form.a = result[prop];
			forms.push(form);
		}
		return forms;
	};
			
	this.getFormData=function(header, type){
			
		var result = {}, id, value, ref = this;
			
		jQuery('.ufo-formvalue[id^="'+header+'"]').each(function(){
			value = ref.getValue(jQuery(this));
			if (value == undefined) return;
			id = jQuery(this).attr('id');
			id = id.slice(header.length);
			result[id]=value;
		});
			
		var form = {};
		form.t = type;
		form.oid = jQuery('#'+header+'oid').val();
		form.a = result;
		return form;
			
	};
			
	this.idSplit=function(id){
		return id.split(this.idDelimeter);
	};
			
	this.idJoin=function(){
		var result = '';
		for (var i=0; i < arguments.length; i++){
			var delim = i > 0 ? this.idDelimeter : '';
			result += delim + arguments[i];			
		}
		return result;
	};
			
	this.siblingId=function(el, dbid){
		var hash = this.idSplit(el.id)[0];
		return this.idJoin(hash, dbid);
	};
			
	this.showHideSibling=function(el, dbid, condition){
		var sid = this.siblingId(el, dbid);
		var $s = jQuery('#'+sid);
		if (condition) {
			$s.show();									
		}			
		else {
			$s.hide();									
		}			
	};
			
	this.getHash=function(str){
		var hash=5381, ch;
		for (i = 0; i < str.length; i++) {
			ch = str.charCodeAt(i);
			hash = ((hash<<5)+hash)+ch;
		}
		hash = Math.abs(hash);
		return hash.toString(32);
	};
			
	this.getRequestHashAttributes = function(request){
		var attribs = ['t', 'oid', 'a', 'specialfilter'];
		var result = {};
		for (var i = 0; i < attribs.length; i++){
			var attrib = attribs[i]; 
			if (request.hasOwnProperty(attrib)){
				result[attrib]=request[attrib];	
			}
		}
		return result;
	};
			
	this.getRequestHash = function(request){
		var attribs=this.getRequestHashAttributes(request);
		var str=AppMan.JSON.encode(attribs);
		return this.getHash(str);
	};
			
	this.getViewSibling = function(itemid, target){
		var view = jQuery('#'+itemid).parents('.ufo-view')[0];
		var targets = jQuery(view).data('targets');
		for (i=0; i< targets.length; i++){
			var names = AppMan.Utils.idSplit(targets[i].attr('id'));
			var exit = target ? names[1]==target : targets[i].attr('id') != itemid; 
			if (exit) return {id:targets[i].attr('id'), hash:names[0]}; 
		}
		return undefined; 
	};
			
	this.prepareHints = function(){
		jQuery('.ufo-label-hint').each(function(){
 			var elid = jQuery(this).attr('id');
 			elid = elid+'t';
 			var str = jQuery('#'+elid).val();
			var infoid = elid+'info';
			var $info = jQuery('#'+infoid);
		
			if ($info.length == 0){
				$info=jQuery('<div class="ufo-as-hint">' + str + '</div>');
				$info.attr('id', infoid);
				jQuery('body').append($info);
			}

			jQuery(this).unbind('mouseenter mouseleave mouseover');

			jQuery(this).hover(
				function(){
					var pos =  jQuery(this).offset();
	 				var vpos =  pos.top - $info.height()- 10 ;
	 				var hpos =  pos.left;
	 				if ($info.width() > 400) {
						$info.css({width:'400px'});
					}
					$info.css({left:hpos+'px', top:vpos+'px'}).fadeIn(300);
				},
				function(){
					$info.stop(true,true);
					$info.fadeOut(300);
				}
			);	
		});
	};
			
	this.prepareElement = function(el, dbid, hash){
		var newId = AppMan.Utils.idJoin(hash, dbid);
		el.attr('id', newId);
		el.data('id', dbid);
		return el;
	};
			
	this.changeIds = function(viewTarget, hash){

		var selector = '#'+viewTarget+' ';     	

		var childSelectors = [                
					
			'#oid',		
			'.ufostddata[id="t"]',		
			'.ufo-filtervalue',		
			'.ufo-filtersign',		
			'.ufo-filter',		     
			'.ufo-viewscrollervalues',		
			'.ufo-tableheader',		
			'.ufo-tab-header li a',		
			'.ufo-tabs',		
			'.ufo-asinput',		
			'.ufo-as-info',		
			'.ufo-upload',		
			'.ufo-triggerbutton',		
			'.ufo-id-link',		
			'.ufo-deletecb'		
		];
                           
		var ref = this, $el, id;

		for	(var i = 0; i < childSelectors.length; i++){
			var childSelector = childSelectors[i]; 
			jQuery(selector+childSelector).each(function(){
				id = jQuery(this).attr('id');
				ref.prepareElement(jQuery(this), id, hash);
			});
		}
		jQuery(selector+'label').each(function(){
			id = jQuery(this).attr('for');
			id = ref.idJoin(hash, id);
			jQuery(this).attr('for',id);
		});
		jQuery(selector+'.ufo-formvalue').each(function(){
			id = jQuery(this).attr('id');
			$el=ref.prepareElement(jQuery(this), id, hash);
			$el.data('dbValue', $el.val());
		});
	};    
}

function DataFilter(){
	this.getFilterValues=function(result, filteredHash){
		var hash, id, fvalue, sid, svalue, fitem, header, selector;
		header = AppMan.Utils.idJoin(filteredHash, '');
		selector = filteredHash ? '[id^="'+header+'"]' : '';
		jQuery('.ufo-filtervalue'+selector).each(function(){
			fvalue = jQuery(this).val(); 
			if (!fvalue) return; 
			id = jQuery(this).attr('id');
			var names = AppMan.Utils.idSplit(id);
			hash = names[0];
			froot = AppMan.Utils.getRoot(result, hash, 'filter');
			fitem = {};
			fitem.property = names[1];
			fitem.value = {};
			fitem.value.values=[fvalue];
			sid = AppMan.Utils.idJoin(hash, 'sgn', fitem.property);
			svalue = jQuery('#'+sid).val(); 
			if ( svalue )
				fitem.value.sign=svalue;
			froot.push(fitem);
		});			
	};
	this.getSortValues=function(result, filteredHash){
		var names, hash, id, field, sitem, sroot, header, selector;
		header = AppMan.Utils.idJoin(filteredHash, '');
		selector = filteredHash ? '[id^="'+header+'"]' : '';
		jQuery('.ufo-tableheader.ufo-active'+selector).each(function(){
			id = jQuery(this).attr('id');
			names = AppMan.Utils.idSplit(id);
			hash = names[0];
			sroot = AppMan.Utils.getRoot(result, hash, 'sort');
			sitem = {};
			sitem.property = names[2];
			sitem.direction = jQuery(this).val();
			sroot.push(sitem);
		});			
	};
	this.getPagingValues=function(result, filteredHash){
		var names, hash, id, field, proot, header, selector;
		header = AppMan.Utils.idJoin(filteredHash, '');
		selector = filteredHash ? '[id^="'+header+'"]' : '';
		jQuery('.ufo-viewscrollervalues'+selector).each(function(){
			id = jQuery(this).attr('id');
			names = AppMan.Utils.idSplit(id);
			hash = names[0];
			proot = AppMan.Utils.getRoot(result, hash);
			proot[names[1]]=jQuery(this).val();
		});			
	};
	this.getFilterData=function(filteredHash){
		var result = {};
		this.getFilterValues(result,filteredHash); 
		this.getSortValues(result,filteredHash); 
		this.getPagingValues(result,filteredHash); 
		return result;
	};
	this.applyFilterViewData=function(filters, hash){
		var hashfilter = filters[hash]['filter'];
		if (!hashfilter) return;
		var field, fieldname, el, elid; 
		for (var i = 0; i < hashfilter.length; i++){
			field = hashfilter[i];
			fieldname = field.property;
			if (field.value.sign)           	
				jQuery('#'+AppMan.Utils.idJoin(hash, 'sgn', fieldname)).val(field.value.sign);
			elid = AppMan.Utils.idJoin(hash, fieldname);
			el = jQuery('#'+elid);
			el.val(field.value.values[0]);
			if (el.hasClass('ufo-as')){
				AppMan.AutoSuggest.init(elid);
			}
			if (el.hasClass('ufo-cb')){
				el.attr('checked', el.val() == 'on');
			}
		}
	};
	this.applySortViewData=function(filters, hash){
		var hashsort = filters[hash]['sort']; 			
		if (!hashsort) return;
		var field, fieldname; 
		for (var i = 0; i < hashsort.length; i++){
			field = hashsort[i];
			jQuery('#'+AppMan.Utils.idJoin(hash, 'srt', field.property)).val(field.direction);
		}
	};
	this.applyPadingViewData=function(filters, hash){
		var hashscroll = filters[hash]; 			
		var startid='#'+AppMan.Utils.idJoin(hash, 'start');
		var limitid='#'+AppMan.Utils.idJoin(hash, 'limit');
		jQuery(startid).val(hashscroll.start);
		jQuery(limitid).val(hashscroll.limit);
	};
	this.applyViewData=function(filters){
		for (var hash  in filters){
			this.applyFilterViewData(filters, hash); 			
			this.applySortViewData(filters, hash); 			
			this.applyPadingViewData(filters, hash);
		}
	};
	this.refreshDetailedViews=function(filters){
		var targets, target, filter, request; 
		jQuery('.ufo-view-activated').each(function(){
			targets = jQuery(this).data('targets');			
			for (var i = 0; i < targets.length; i++){
				target = targets[i]; 
				request = target.data('request'); 
				filter = filters[request.hash];
				if (filter) request=AppMan.Utils.apply(request, filter);
				if (request.filter) 
					request.filter = AppMan.JSON.encode(request.filter);			
				if (request.sort) 
					request.sort = AppMan.JSON.encode(request.sort);			
				AppMan.request(AppMan.History.Actions.applygui, request); 
			}
		});
	};
	this.filter=function(request){
		var hash = request.hash; 
		var filterData = this.getFilterData(hash); 
		filterData = filterData[hash]; 
		if (filterData){ 
			filterData.filter = AppMan.JSON.encode(filterData.filter); 
			filterData.sort = AppMan.JSON.encode(filterData.sort); 
		} 
		else { 
			filterData = {};
		} 
		var specialfilter = jQuery('#'+AppMan.Utils.idJoin(hash, 'specialfilter')).val(); 
		if (specialfilter) 
			filterData.specialfilter=specialfilter; 
		filterData=AppMan.Utils.apply(filterData, request); 
		AppMan.History.doAction(AppMan.History.Actions.refresh,null);
		if (request.viewTarget == AppMan.bodyid)
			AppMan.History.lastStep.request=filterData;
		AppMan.request(AppMan.History.Actions.applygui, filterData); 
	};
	this.scroll=function(config,direction){
		var hash=config.hash, start, limit, rowcount, startid, limitid, rowcountid;
		startid='#'+AppMan.Utils.idJoin(hash, 'start');
		limitid='#'+AppMan.Utils.idJoin(hash, 'limit');
		rowcountid='#'+AppMan.Utils.idJoin(hash, 'rowcount');
		start = parseInt(jQuery(startid).val());
		limit = parseInt(jQuery(limitid).val());
		rowcount = parseInt(jQuery(rowcountid).val());
		start += direction*limit;
		if (direction == -2) start = 0;                            
		if (direction == 2) start = rowcount-limit;
		start = Math.min(start,rowcount-limit);
		start = Math.max(start,0);
		jQuery(startid).val(start);
		jQuery(limitid).val(limit);
		this.filter(config);
	};
	this.sort=function(config, field){
		var hash = config.hash;
		var id = '#'+AppMan.Utils.idJoin(hash, 'srt',field);
		var selector = AppMan.Utils.idJoin(hash, 'srt','');
		var direction = jQuery(id).val();
		jQuery('.ufo-tableheader[id^="'+selector+'"]').each(function(){
			jQuery(this).val('');
			jQuery(this).removeClass('thacs');
			jQuery(this).removeClass('thdesc');
			jQuery(this).removeClass('ufo-active');
			jQuery(this).parent('th').removeClass('ufo-active');
		});
		direction = (direction=='ASC')?'DESC':'ASC';
		var className = (direction=='ASC')?'thacs':'thdesc';
		jQuery(id).val(direction);
		jQuery(id).addClass(className);
		jQuery(id).addClass('ufo-active');
		jQuery(id).parent('th').addClass('ufo-active');
		this.filter(config);
	};
	this.mdelete=function(config, mdconfig){
		var hash = config.hash, request = [], id, selector = AppMan.Utils.idJoin(hash, 'cb', '');
		jQuery('.ufo-deletecb[id^="'+selector+'"]').each(function(){
			if (jQuery(this).val() != 'on') return;
			id = jQuery(this).attr('id');
			id = id.slice(selector.length);
			request.push(id);
		});
		if (request.length==0){
			alert(AppMan.resources.NoRecordsSelected);		
			return;		
		}		
		var message = mdconfig && mdconfig.messageText ? mdconfig.messageText : AppMan.resources.ItwillDeleteRecordsAreYouSure;
		if (!confirm(message)) return;
		config.a={};
		config.a.a=request;
		config.a.m='mdelete';
		config.a = AppMan.JSON.encode(config.a);
		AppMan.Filter.filter(config);
	};
	this.moveRow = function (config, movedirection, id){
		var hash = config.hash;
		config.a={};
		config.a.srt=jQuery('#'+AppMan.Utils.idJoin(hash, 'srt', 'ListPosition')).val();
		config.a.lpd=movedirection;
		config.a.lpi=id;
		config.a.m='moveRow';
		config.a=AppMan.JSON.encode(config.a);
		AppMan.Filter.filter(config);
	};
	this.newObject = function (config){
		config.m='new';
		config.viewTarget=AppMan.bodyid;
		AppMan.request(AppMan.History.Actions.next, config);
	};
	this.saveObjects = function (config){
		var header = AppMan.Utils.idJoin(config.hash, ''), 
			type= config.t, result =[];
		result = AppMan.Utils.getViewData(header, type);
		config.a={};
		config.a.m='save';
		config.a.a=AppMan.JSON.encode(result);
		AppMan.Filter.filter(config);
	};
	this.saveObject = function (config, historyAction){
		var header = AppMan.Utils.idJoin(config.hash, ''), 
			type = config.t, result =[], targets, request;
		var formdata = AppMan.Utils.getFormData(header, type); 	
		result.push(formdata);
		jQuery('.ufo-view[id^="'+header+'"]').each(function(){
			targets = jQuery(this).data('targets');
			for (var i = 0; i < targets.length; i++){
				request = targets[i].data('request');
				header = AppMan.Utils.idJoin(request.hash, '');
				AppMan.Utils.getViewData( header, request.t, result );
			}
		});
		config.a=AppMan.JSON.encode(result);
		AppMan.request(historyAction, config);
	};
	this.plainsave = function (config){
		if (AppMan.History.lastStep == null) AppMan.History.lastStep=config;
		config.m='apply';
		this.saveObject(config, AppMan.History.Actions.reload);
	};
	this.save = function (config){
		config.m='save';
		this.saveObject(config, AppMan.History.Actions.back);
	};
	this.apply = function (config){
		var hash = config.hash;
		config.m='apply';
		config.oid=jQuery('#'+AppMan.Utils.idJoin(hash,'oid')).val();
		this.saveObject(config, AppMan.History.Actions.reload);
	};
	this.copy = function (config){
		var hash = config.hash;
		config.m='copy';
		config.oid=jQuery('#'+AppMan.Utils.idJoin(hash,'oid')).val();
		AppMan.request(AppMan.History.Actions.back, config);
	};
	this.saveMainView = function (config){
		var header = AppMan.Utils.idJoin(config.hash, ''), 
			type = config.t, result =[];
		AppMan.Utils.getViewData( header, type, result );
		config.a={};
		config.a.a=result;
		config.a.m='save';
		config.a = AppMan.JSON.encode(config.a);
		AppMan.Filter.filter(config);
	};
	this.addRow = function (config){
		config.m2='addRow';
		AppMan.Filter.filter(config);
	};
	this.link = function(addconfig, config){
		var names = AppMan.Utils.idSplit(config.viewTarget);
		config.hash = names[0];
		addconfig.m2='addRow';
		addconfig.callbackfunc = function(){
			AppMan.Filter.filter(config);
		};
		var sibling = 
			AppMan.Utils.getViewSibling(config.viewTarget, addconfig.viewTarget);
		addconfig.viewTarget = sibling.id;
		addconfig.hash = sibling.hash;
		AppMan.Filter.filter(addconfig);
	};
	this.mtmdelete = function(config){
		var sibling = 
			AppMan.Utils.getViewSibling(config.viewTarget);
		if (sibling){
			refreshconfig = jQuery('#'+sibling.id).data('request');
			config.callbackfunc = function(){
				AppMan.Filter.filter(refreshconfig);
			};
		}
		this.mdelete(config);
	};
	this.getListsOptions = function(el, configs){
		for (var i = 0; i < configs.length; i++){  
			this.getListOptions(el, configs[i]);		
		}
	};   
	this.getListOptions = function(el, config){
		var dId = AppMan.Utils.siblingId(el, config.dbId); 
		var masterValue = jQuery(el).val(); 
		config.oid=masterValue;
		config.m = config.m ? config.m : 'list';
		ajx = {};
		ajx.params  = config;
		ajx.success = function(data){
			var loptions = AppMan.JSON.decode(data),
			$dList = jQuery('#'+dId), $option, option;
			$dList.empty();
			if (! config.noemtpy){
				$option = jQuery('<option/>');
				$option.val('');
				$option.text('...');
				$dList.append($option);
			}
			for (var i = 0; i< loptions.length; i++){
				option = loptions[i];
				$option = jQuery('<option/>');
				$option.val(option.id);
				$option.text(option.Description);
				$dList.append($option);
			}
			$dList.trigger('onchange');
		};
		AppMan.Ajax.request(ajx);
	};   
}

if (typeof(ufoFormsConfig) == 'undefined') {
	var ufoFormsConfig = {};
	ufoFormsConfig.submits = [];
	ufoFormsConfig.resets = [];
	ufoFormsConfig.validations = [];
}

ufoForms = new function(){

	this.regex={};
	this.regex.numeric = /^[0-9]+$/;
	this.regex.integer = /^\-?[0-9]+$/;
	this.regex.decimal = /^\-?[0-9]*\.?[0-9]+$/;
	this.regex.email = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,6})+$/;
	this.regex.natural = /^[0-9]+$/i;
	this.regex.currency = /^([0-9]*|\d*\.\d{1}?\d*)$/;
	this.regex.phonenumber = /^(\+{0,1}\d{1,2})*\s*(\(?\d{3}\)?\s*)*\d{3}(-{0,1}|\s{0,1})\d{2}(-{0,1}|\s{0,1})\d{2}$/;
   
	this.forms={};
	this.submits={};
	this.els = {};

	this.initValidation = function (){
		if (typeof(ufoFormsConfig.phonenumberre) != 'undefined') {
			ufoForms.regex.phonenumber = ufoFormsConfig.phonenumberre;
		}
		for (var i = 0; i < ufoFormsConfig.validations.length; i++) {
			ufoForms.addValidation(ufoFormsConfig.validations[i]);
		} 
		for (i = 0; i < ufoFormsConfig.submits.length; i++) {
			ufoForms.addSubmit(ufoFormsConfig.submits[i]);
		} 
		for (i = 0; i < ufoFormsConfig.resets.length; i++) {
			ufoForms.addReset(ufoFormsConfig.resets[i]);
		} 
		if (typeof(ufoFormsConfig.onloads) != 'undefined') {
			for (i = 0; i < ufoFormsConfig.onloads.length; i++) {
				ufoFormsConfig.onloads[i];
			} 
		} 
	};

	this.docReady = function(func){
		var _timer = false;
		function init() {
			if (arguments.callee.done) return;
			arguments.callee.done = true;
			if (_timer) {
				clearInterval(_timer);
				_timer = null;
			}
			func();
		}
		if (document.addEventListener) {
			document.addEventListener('DOMContentLoaded', init, false);
		}
		/*@cc_on @*/
		/*@if (@_win32)
		var empty = (location.protocol == "https:") ? "//:" : "javascript:void(0)";
		document.write("<script id=__ie_onload defer src='" + empty + "'><\/script>");
		var script = document.getElementById("__ie_onload");
		script.onreadystatechange = function() {
			if (this.readyState == "complete") {
				init(); 
			}
		};
		/*@end @*/
		if (/WebKit/i.test(navigator.userAgent)) { 
			_timer = setInterval(function() {
				if (/loaded|complete/.test(document.readyState)) {
					init(); 
				}
			}, 10);
		}
		window.onload = init;
	};

	this.enableSubmits = function (formid, enable){
		var submits = this.submits[formid];
		if (!submits) {
			return;
		}
		for (var i = 0; i < submits.length; i++) {
			var config = submits[i];
			var submit = config.domEl;
			submit.disabled = !enable;			
		}
	};

	this.validateForm = function (formid, enforce){
		var submits = this.submits[formid];
		if (!submits) {
			return true;
		}
		var fields = this.forms[formid];
		if (!fields) {
			return true;
		}
		var isValid = true;
		for (var i = 0; i < fields.length; i++) {
			var config = fields[i];
			if (enforce){
				config.isvalid = ufoForms.validateField(config, 'blur');				
			}
			if (!config.isvalid) {
				isValid = false;
				break;
			}
		}
		this.enableSubmits(formid, isValid);
		return isValid;
	};

	this.addSubmit = function (config){
		if (!this.submits[config.form]) {
			this.submits[config.form]=[];			
		}
		for (var i = 0; i < this.submits[config.form].length; i++) {
			var cfg = this.submits[config.form][i];
			if (config.id == cfg.id) {
				return;
			}
		}
		this.submits[config.form].push(config);

		var submit = document.createElement('button');
		try{
			submit.type = 'button';
		} catch (e) {
			submit.setAttribute('type', 'button');
		}
		var container = this.get(config.id+'-span');
		if (ufoFormsConfig.w2c === true) {
			var input = ufoForms.get(config.id);
			container.replaceChild(submit, input);
			var fid = config.form.split('-');
			fid = fid[fid.length - 1];
			input = ufoForms.get('cf-no-script-' + fid);
			input.value = '';
		}
		submit.className = config.CSSClass || '';
		submit.style.cssText = config.CSSStyle || '';
		var parent = submit;
		if (config.LabelCSSClass || config.LabelCSSStyle) {
			var span = document.createElement('span'); 
			parent.appendChild(span);
			parent = span;
			span.className = config.LabelCSSClass || '';
			span.style.cssText = config.LabelCSSStyle || '';
		}
		parent.innerHTML = config.Label || '';
		if (ufoFormsConfig.w2c !== true) {
			container.appendChild(submit);
		}
		config.domEl = submit;
		var formid = config.form;
		var wndscroll = config.WindowScroll;

		this.addEvent(submit, 'click', function(){
			if (!ufoForms.validateForm(formid, true)) {
				return;
			}
			ufoForms.disableInput(formid);
			ufoForms.enableSubmits(formid, false);

			var els = [];
			var frm = ufoForms.get(formid);

			var collections = [];
			collections.push(frm.getElementsByTagName('input'));
			collections.push(frm.getElementsByTagName('select'));
			collections.push(frm.getElementsByTagName('textarea'));
			for (i = 0; i < collections.length; i++) {
				var collection = collections[i];
				for (var j = 0; j < collection.length; j++) {
					els.push(collection[j]);
				}
			}
			var result = [];
			for (i = 0; i < els.length; i++){
				var el = els[i];
				config = ufoForms.els[el.id];
				var empty = config ? ufoForms.isEmpty(config) : el.value == '' || el.value == 'off';
				if (!empty) {
					var id = el.id.split('-');
					id = id[id.length - 2] + '-' + id[id.length - 1];
					value = el.value;
					value = value.replace(/&/g,'%26');
					value = value.replace(/=/g,'%3D');
					result.push(id + '=' + value);
				}                                                                            
			}
			result = result.join('&');
			if (ufoFormsConfig && ufoFormsConfig.uploads && ufoFormsConfig.uploads[formid]) {
				var fileuploads = ufoFormsConfig.uploads[formid];
				for (var prop in fileuploads) {
					var fileupload = fileuploads[prop];
					fileupload.submit();
				} 
				var interval;  
				var oncomplete = function() {
					for (var prop in fileuploads) {
						var fileupload = fileuploads[prop];
						if (fileupload.fileattached && !fileupload.fileuploadcomplete) {
							return;
						}
					} 
					clearInterval(interval);
					ufoForms.request(result, ufoForms.callback);	
					for (prop in fileuploads) {
						fileupload = fileuploads[prop];
						fileupload.enable();
						fileupload.fileuploadcomplete = false;
						fileupload.fileattached = false;
					} 
				}; 
				interval = setInterval(oncomplete, 100); 
			} 
			else {
				ufoForms.request(result, ufoForms.callback);	
			} 
			if (wndscroll && window.scrollTo) {
				ufoForms.scroll(wndscroll);
			}
		});
	};
	
	this.disableInput = function(formid){
		var form = ufoForms.get(formid);
		form.style.position = 'relative';
		var disable = document.createElement('div');
		disable.className = 'ufo-customforms-disable';
		disable.id = formid + '-disable';
		form.appendChild(disable);
	};
	
	this.enableInput = function(formid){
		var disable = document.getElementById(formid + '-disable');
		if (!disable) return;
		var form = ufoForms.get(formid);
		form.removeChild(disable);
	};

	this.showMessage = function(resp){
		var formid = resp.formid; 
		var fid = formid.split('-');
		fid = fid[fid.length - 1];
		ufoForms.enableSubmits(fid, true);

		var fadeDelay = 1000; 
		var messageDelay = 3000; 
		if (typeof(ecfconfig) != 'undefined' && typeof(ecfconfig[fid]) != 'undefined'){
			var fconfig = ecfconfig[fid];
			fadeDelay = fconfig.fadeDelay ? fconfig.fadeDelay : fadeDelay; 
			messageDelay = fconfig.messageDelay ? fconfig.messageDelay : messageDelay; 
		} 
		if (fadeDelay < 100)  fadeDelay = 100; 
		if (resp.status == 1) {
			function redirect(){
				if (resp.url) {
					var t = setTimeout('document.location.href = "' + resp.url + '"',messageDelay);
				}
			}
			function success() {
				if (form.offsetHeight > 0){
					form.style.height = form.offsetHeight+'px';
				}
				while (form.hasChildNodes()){
	  				form.removeChild(form.firstChild);
				}			
				ufoForms.enableInput(formid);
				if (resp.text) {
					var div = document.createElement('div');
					div.className = resp.className;
					div.innerHTML = resp.text;
					form.appendChild(div);
					ufoForms.doFade(form, 0, 1, 1000, redirect);
				}
				else {
					redirect();
				}
			}
			var form = this.get(formid);
			form.disabled = true;
			this.doFade(form, 1, 0, fadeDelay, success);	
		}
		else if (resp.status == 2) {
			var message = this.get(formid+'-message');
			message.innerHTML = resp.text;
			this.addClass(message, resp.className);
			ufoForms.enableInput(formid);
		}
		else if (resp.status == 0) {
			ufoForms.enableInput(formid);
		}
	};
       
	this.hadleError = function(uhxr) {
		switch(uhxr.status){
    		case 12029:
			case 12030:
    		case 12031:
    		case 12152:
    		case 12159:
				uhxr.cObject.request(uhxr.cValues, uhxr.cFunction);
				break;
			default:				
				alert('Error. Status='+uhxr.status);
				break;
		}
	};
       
	this.callback = function(){
		if (uhxr.readyState == 4) {
			if (uhxr.status == 200) {
				if (('' + uhxr.responseText) == '') return;				
				try {
					var resp = eval('(' + uhxr.responseText + ')');								
					ufoForms.showMessage(resp);
				} catch (e) {
				}
			}
			else {
				ufoForms.hadleError(uhxr);
			}
		}
	};

	this.request = function(values, callbackfunction, m, asynch){
		m = m || 'add';
		asynch = asynch == undefined ? true : asynch;
		values = values.replace(/\+/gi,'%2B');
		values += '&t=CustomForms';
		values += '&ac=1';
		values += '&m='+m;
		values += '&action=easy-contact-forms-submit'; 
		uhxr = this.getXHR();
		if (!uhxr) return false;
		uhxr.cValues = values;
		uhxr.cObject = this;
		uhxr.cFunction = callbackfunction;
		uhxr.onreadystatechange = callbackfunction;
		uhxr.open('POST', ufobaseurl, asynch);
		uhxr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		uhxr.send(values);
		return true;
  	};

	this.getXHR = function(){
		if (window.XMLHttpRequest) { 
			uhxr = new XMLHttpRequest();
			if (uhxr.overrideMimeType) {
				uhxr.overrideMimeType('text/html');
			}
		} else if (window.ActiveXObject) { 
			try {
				uhxr = new ActiveXObject('Microsoft.XMLHTTP');
			} catch (e) {}
		}
		return uhxr;
  	};

	this.get = function(id){
		return document.getElementById(id);
	};

	this.isEmpty = function(config){
		var val = config.domEl.value;
		if (config.IsBlankValue && config.DefaultValue == val) {
			return true;
		}
		if (this.hasClass(config.domEl, 'ufo-cb')){
			return config.domEl.value != 'on';
		}
		return config.domEl.value == '';
	};

	this.addReset = function (config) {
		var reset = this.get(config.id);
		config.domEl = reset;

		var formid = config.form;
		this.addEvent(reset, 'click', function(){
			var fields = ufoForms.forms[formid];
			if (!fields) {
				return;
			}
			for (var i = 0; i < fields.length; i++) {
				var config = fields[i];
				ufoForms.fieldReset(config);
			}
			var frm = ufoForms.get(ufoForms.frmIdPx+formid);
			frm.reset();
			ufoForms.validateForm(config.form);
		});
	};
	
	this.getMessageDiv = function(id, config, absolute) {
		var mdiv = this.get(id);
		if (mdiv) {
			if (absolute) {
				mdiv.style.position = 'absolute';
				var parent = mdiv.parentNode;
				parent.removeChild(mdiv);
				if (this.hasClass(parent, 'ufo-cell-center') && !parent.hasChildNodes()) {
					var sparent = parent.parentNode;
					sparent.removeChild(parent);
					sparent.parentNode.removeChild(sparent);
				}
			}
		}
		else {
			mdiv = document.createElement('div');
			mdiv.style.position = 'absolute';
			mdiv.id = id;
		}
		if (mdiv.style.position == 'absolute') {
			config.domEl.parentNode.appendChild(mdiv);
		}
		return mdiv;
	};                                
                                                   
	this.addMessages = function(config) {
		var mdiv, className;
		if (config.Required || config.Validate) {
			mdiv = this.getMessageDiv(config.id+'-invalid', config, config.AbsolutePosition);
			mdiv.innerHTML = config.RequiredMessage || '';
			className = config.RequiredMessageCSSClass || 'ufo-customfields-invalid';
			this.addClass(mdiv, className);
			className = config.RequiredMessagePosition ? 'ufo-hint-position-'+config.RequiredMessagePosition : 'ufo-hint-position-right';
			this.addClass(mdiv, className);				
			if (!config.InvalidCSSClass) {
				config.InvalidCSSClass = 'ufo-customfields-invalidvalue';            	
			}
			if (config.RequiredMessageCSSStyle) {
				try {
					mdiv.style.cssText = config.RequiredMessageCSSStyle;		
				} catch (e) {}
			}
			if (config.AbsolutePosition) {
				mdiv.style.position = 'absolute';
			}
			mdiv.style.display = 'none';
		}
		if (config.showValid) {
			mdiv = this.getMessageDiv(config.id+'-valid', config, config.ValidMessageAbsolutePosition);
			mdiv.innerHTML = config.ValidMessage || '';
			className = config.ValidCSSClass || 'ufo-customfields-valid';
			this.addClass(mdiv, className);
			className = config.ValidMessagePosition ? 'ufo-hint-position-'+config.ValidMessagePosition : 'ufo-hint-position-right';
			this.addClass(mdiv, className);				
			if (config.ValidCSSStyle) {
				try {
					mdiv.style.cssText = config.ValidCSSStyle;		
				} catch (e) {}
			}
			if (config.ValidMessageAbsolutePosition) {
				mdiv.style.position = 'absolute';
			}
			mdiv.style.display = 'none';
		}
	};

	this.addValidation = function(config){

		if (typeof(this.els[config.id]) != 'undefined') {
			return;
		}

		config.isvalid = true;

		this.els[config.id] = config;
		if (!this.forms[config.form]) {
			this.forms[config.form]=[];			
		}
		this.forms[config.form].push(config);			
		var el = this.get(config.id);
		config.domEl = el;

		var changeset = false;
		for (var evt in config.events) {
			if (evt == 'keypress') {
				changeset = true;
			}
			this.addEvent(el, evt, (function(evt, config){
				return function(event){
					if ( event.preventDefault ) {
						event.preventDefault();
					} else {
						event.returnValue = false;
					}
					config.isvalid = ufoForms.validateField(config, evt);
					ufoForms.validateForm(config.form);
				};
			})(evt, config));
		}
		if (!changeset) {
			this.addEvent(el, 'keypress', (function(config){
				return function(event){
					ufoForms.enableSubmits(config.form, true);
				};
			})(config));
		}
		this.addMessages(config);
	};

	this.validateField = function(config, event){
		var result = undefined, types = config.events[event];

		for (i = 0; i < types.length; i++) {
			if (typeof(ufoValidators) == 'undefined') {
				ufoValidators = {};				
			}
			var type = types[i];
			var vresult = ufoForms['validate'+type] ? 
				ufoForms['validate'+type](config, event) : ufoValidators[type] ? 
				ufoValidators[type](config, event) : ufoForms.validateRe(type, config, event);  				
			if (result == false) {
				continue;	
			}		
			result = vresult == undefined ? result : vresult;
		}
		if (typeof(result) != 'undefined') {
			this.changeView(result, config);
		}
		else {
			result = true;
		}
		return result;
	};

	this.validateRe = function(type, config, event){
		if (!config.required && this.isEmpty(config)) {
			this.fieldReset(config);
		 	return undefined;
		} 
		var result = true;
		if (config.required && this.isEmpty(config)) {
		 	result = false;
		} 
		else if (this.regex[type]) {
			result = this.regex[type].test(config.domEl.value);
		}
		return result;
	};
	
	this.validaterequired = function(config, event){
		return !this.isEmpty(config);
	};

	this.validatedefault = function(config, event){
		if (event == 'blur') {
			if (config.domEl.value == '') {
				config.domEl.value = config.DefaultValue;				
			}
			this.switchClass(config.domEl, config.DefaultValueCSSClass, config.domEl.value == config.DefaultValue);
		}
		if (event == 'focus') {
			this.removeClass(config.domEl, config.DefaultValueCSSClass);
			if (config.domEl.value == config.DefaultValue) {
				config.domEl.value = '';				
			}
		}
		return undefined;
	};

	this.validateminmax = function(config, event){
		if (!config.required && this.isEmpty(config)) {
			this.fieldReset(config);
		 	return undefined;
		} 
		var value = config.domEl.value ? config.domEl.value : '';
		if (config.max && value.length > config.max) {
			return false;	
		}
		if (config.min && value.length < config.min) {
			return false;	
		}
		return true;
	};

	this.validateminmaxnumeric = function(config, event){
		if (!config.required && this.isEmpty(config)) {
			this.fieldReset(config);
		 	return undefined;
		} 
		var value = config.domEl.value ? config.domEl.value : '0';
		if (value > config.max) {
			return false;	
		}
		if (value < config.min) {             
			return false;	
		}
		return true;
	};

	this.changeView = function(result, config){
		if (result) {
			this.fieldValid(config);
		}
		else {
			this.fieldInvalid(config);
		}
	}; 
	this.doFade = function(el, from, to, duration, callback){
		if (duration == undefined) {
			duration = 400;
		}
		var fade = new _bsn.Fader(el,from, to, duration, callback);
	};

	this.fadeOut = function(elid, duration, callback){
		var el = this.get(elid);
		if (!el) {
	   		return;
		}
		if (el.style.display == 'none') {
			if (callback) {
				callback(el);
			}
	   		return;
		}
		if (!callback) {
			callback = function(){
				el.style.display='none';
			};		
		}
		this.doFade(el, 1, 0, duration, callback);	
	};

	this.fadeIn = function(elid, duration, callback){
		var el = this.get(elid);
		if (!el) {
			return;
		}
		_bsn.Fader._setOpacity(el, 0);
		el.style.display = 'block';
		if (el.style.position == 'absolute') {
			this.alignOffset(el);
		}
		else {
			this.alignWidth(el);
		}
		this.doFade(el, 0, 1, duration, callback);	
	};

	this.alignWidth = function(el) {
	};

	this.alignOffset = function(el) {
		var pid = el.id.split('-');
		pid.pop();
		var parent = this.get(pid.join('-'));
		if (this.hasClass(parent, 'ufo-hidden')) {
			parent = parent.parentNode;
		}		
		parent.parentNode.style.position = 'relative';		
		var pright = this.hasClass(el, 'ufo-hint-position-right');
		var pbottom = this.hasClass(el, 'ufo-hint-position-bottom');
		var delta = 5;
		var xOffset = pright ? parent.offsetWidth + delta : 0;
		var yOffset = pbottom ? parent.offsetTop + parent.offsetHeight + delta : parent.offsetTop - el.offsetHeight - delta;
		yOffset = pright ? - Math.max(0, (el.offsetHeight - parent.offsetHeight) / 2 ) : yOffset;
		var width = el.offsetWidth;
		if (pright && !el.style.width) {
			el.style.width = width+'px';
		}		
		el.style.top = yOffset+'px';
		el.style.left = xOffset+'px';
		parent.parentNode.appendChild(el);		
	};

	this.fieldValidInvalid = function (config, valid){
		var el =  config.domEl, callback;
		if (config.InvalidCSSClass && valid) {
			this.removeClass(el, config.InvalidCSSClass);
		}
		if (config.InvalidCSSClass && !valid) {
			this.addClass(el, config.InvalidCSSClass);
		}
		if (valid) {
			if (config.showValid){
				callback = function() {
					var inval = ufoForms.get(el.id+'-invalid');
					inval.style.display = 'none';
					ufoForms.fadeIn(el.id+'-valid');
				};
			}
			this.fadeOut(el.id+'-invalid', 200, callback);
		}
		else {
			if (config.showValid){
				callback = function() {
					var val = ufoForms.get(el.id+'-valid');
					val.style.display = 'none';
					ufoForms.fadeIn(el.id+'-invalid');
					ufoForms.scrollToInvalid(el);
				};
				this.fadeOut(el.id+'-valid', 200, callback);
			}
			else {
				this.fadeIn(el.id+'-invalid');
				ufoForms.scrollToInvalid(el);
			}
		}
	};

	this.showHide = function(id, show){
		var display = show ? 'block' : 'none';
		var el = this.get(id);
		if (el) {
			el.style.display = display;
		}
	}; 

	this.fieldReset = function (config){
		config.isvalid = true;
		if (config.InvalidCSSClass) {
			this.removeClass(config.domEl, config.InvalidCSSClass);
		}
		this.showHide(config.id+'-invalid', false);
		if (config.showValid){
			this.showHide(config.id+'-valid', false);
		}
	};

	this.fieldValid = function (config, valid){
		this.fieldValidInvalid(config, true);
	};

	this.fieldInvalid = function (config, valid){
		this.fieldValidInvalid(config, false);
	};

	this.findPosY = function(obj) {
    	var curtop = 0;
    	if (obj.offsetParent) {
        	while (1) {
            	curtop+=obj.offsetTop;
            	if (!obj.offsetParent) {
                	break;
            	}
            	obj=obj.offsetParent;
        	}
    	} else if (obj.y) {
        	curtop+=obj.y;
    	}
    	return curtop;
	};

	this.scrollToInvalid = function(el) {
		var curr = ufoForms.currentYPosition();
		var ely = ufoForms.findPosY(el);
		if (ely < curr){
			ufoForms.scroll(ely - 25);
		}
	};

	this.currentYPosition = function(){
		if (self.pageYOffset) return self.pageYOffset;
		if (document.documentElement && document.documentElement.scrollTop)
			return document.documentElement.scrollTop;
		if (document.body.scrollTop) return document.body.scrollTop;
		return 0;
	};
	
	this.scroll = function(to){
		var curr = this.currentYPosition();
		var distance = curr - to;
		if (distance < 100) {
			scrollTo(0, to); return;
		}
		var speed = Math.round(distance/25);
		if (speed >= 20) speed = 20;
		var step = Math.round(distance/25);
		var leap = curr - step;
		var timer = 0;
		for ( var i=curr; i>to; i-=step ) {
			setTimeout('window.scrollTo(0, '+leap+')', timer * speed);
			leap -= step; if (leap < to) leap = to; timer++;
		}
	};
	
	this.addEvent = function(elem, evType, fn) {
		if (elem.addEventListener) {
			elem.addEventListener(evType, fn, false);
		}
		else if (elem.attachEvent) {
			elem.attachEvent('on' + evType, fn);
		}
		else {
			elem['on' + evType] = fn;
		}
	};

	this.hasClass = function(el, className){
		var re = new RegExp("(^|\\s)" + className + "(\\s|$)", "g");
		return re.test(el.className);
	};

	this.switchClass = function(el, className, on){
		if (on) {
			this.addClass(el, className);
		}
		else {
			this.removeClass(el, className);
		}
	};
	  
	this.addClass = function(el, className){
		var re = new RegExp("(^|\\s)" + className + "(\\s|$)", "g");
		if (re.test(el.className)) return;
		el.className = (el.className + " " + className).replace(/\s+/g, " ").replace(/(^ | $)/g, "");
	};
	  
	this.removeClass = function(el, className){
		var re = new RegExp("(^|\\s)" + className + "(\\s|$)", "g");
		el.className = el.className.replace(re, "$1").replace(/\s+/g, " ").replace(/(^ | $)/g, "");
	};
};

AppMan = new function(){

	this.ufoForms = ufoForms; 
	this.AutoSuggest = new AutoSuggestFactory(); 
	this.AjaxUpload = new AjaxUploadFactory(); 
	this.Calendar = new CalendarFactory(); 
	this.TMCEFactory = new TMCEFactory();
	this.Filter = new DataFilter(); 
	this.Utils = new Utils(); 
	this.History = History; 
	this.JSON = JSON; 
	this.Ajax = iAjax;


	this.init=function(config){
		this.resources=config.resources;
		this.bodyid=config.bodyid;
		this.Ajax.dParams={};
		this.Ajax.dParams.ac=1;
		this.Ajax.url=config.url;
		this.hash = this.Utils.getRequestHash(config.initial);
		this.preparePage(this.hash, this.bodyid);
		config.initial.hash = this.hash;
		this.History.doAction(this.History.Actions.next, config.initial);
		if (typeof(config.phonenumberre) != 'undefined') {
			this.ufoForms.regex.phonenumber = config.phonenumberre;
		}
		jQuery('#'+AppMan.bodyid).ajaxStop(function(){
			jQuery('#disabler').remove();
		});
	};

	this.request=function(action, request){
		if (typeof(request) == 'string') 
			request = AppMan.Utils.splitUrlString(request);

		var ro = {};	

		if (request.hash) 
			delete request.hash;

		ro.params = request;
		ro.success=this.updateview;			
		ro.error=this.handleError;			

		callback = {};
		callback.currentRequest=request;			
		callback.action=action;			
		callback.appman=this;			

		if (request.callbackfunc){
			callback.callbackfunc=request.callbackfunc;			
			delete request.callbackfunc;		
		}		

		ro.object=callback;

		this.Ajax.request(ro);			

		if (action == AppMan.History.Actions.next) {
			this.History.wndScroll = ufoForms.currentYPosition();	
		}

		this.disableInput();

	};

	this.handleError=function(jqxhr, status, error){
		alert('Ajax error. Status =' + status + '\n' + error);
		AppMan.enableInput();
	};


	this.updateview=function(data, status, jqxhr){
		var hash, callback = jqxhr.robj;
		var appman = callback.appman;
		var currentRequest = callback.currentRequest;
		var viewTarget = currentRequest.viewTarget || appman.bodyid;

		appman.AutoSuggest.clearInfo();			
		if (viewTarget == appman.bodyid){
			appman.AutoSuggest.clear();			
			appman.AjaxUpload.clear();			
			appman.Calendar.clear();			
			appman.TMCEFactory.removeTinyMCEs();
			appman.clearValidation();
			hash = appman.Utils.getRequestHash(callback.currentRequest);
			appman.hash=hash;
		} else {
			var names = appman.Utils.idSplit(currentRequest.viewTarget);
			hash = names[0];
		}
		callback.currentRequest.hash=hash;

		appman.clearIntevals(viewTarget);

		if (callback.action != appman.History.Actions.back &&
				callback.action != appman.History.Actions.reload){
			jQuery('#'+viewTarget).html(data);
			appman.preparePage(hash, viewTarget);
		}

		appman.History.doAction(callback.action, callback.currentRequest);
		
		if (appman.History.tgWndScroll) {
			ufoForms.scroll(appman.History.tgWndScroll);
			appman.History.tgWndScroll = false;
		}
		
		if (viewTarget != appman.bodyid) {
			appman.enableInput();
		}

		if (callback.callbackfunc)
			callback.callbackfunc.call(jqxhr);			
	};

	this.preparePage=function(hash, viewTarget){
		this.Utils.changeIds(viewTarget, hash);
		this.factoryflag=hash;
		jQuery('.ufo-eval').each(function(){
			eval(jQuery(this).val());
			jQuery(this).remove();
		});
		this.Utils.prepareHints();
		delete this.factoryflag;
	};

	this.disableInput=function(){

		var $disabler = jQuery('#disabler');		
		if ( $disabler.length > 0 ) return; 		
		var div = jQuery("<div id='disabler' style='display:none' class='disableall'></div>");		
		var body = jQuery('#'+this.bodyid);		
		var pos =  body.position();
		var width = body.width();
		var height = body.height();
		div.css({width:width+'px', height:height+'px', top:pos.top+'px', left:pos.left+'px'});
		body.append(div);
		div.show();

	};

	this.enableInput=function(){
		jQuery('#disabler').remove();		
	};

	this.addInterval=function(viewTarget, intId){
 		if (!this.intervals) {
			this.intervals = [];        	
		}
		this.intervals.push(intId);        	
	};

	this.clearIntevals=function(viewTarget){
 		if (!this.intervals) {
			return;        	
		}
 		for (var i = 0; i < this.intervals.length; i++) {
			var intId = this.intervals[i];        	
			clearInterval( intId );
		}
		this.intervals.length = 0;
	};

	this.addTabSwitchHandler = function(func, tabs){
		var switchhandlers;			
		for (var i = 0; i < tabs.length; i++){
			var tabid = AppMan.Utils.idJoin(AppMan.factoryflag, tabs[i]);
			$tab = jQuery('#'+tabid);
			switchhandlers = $tab.data('switchhandlers');
			if (!(switchhandlers)){ 
				switchhandlers = [];
				$tab.data('switchhandlers', switchhandlers);
			}
			switchhandlers.push(func);			
		}
	};

	this.clearValidation = function(){
		for (var prop in this.ufoForms.els) {
			delete this.ufoForms.els[prop];			 
		}		
		for (prop in this.ufoForms.forms) {
			this.ufoForms.forms[prop].length = 0;			 
		}		
		for (prop in this.ufoForms.submits) {
			this.ufoForms.submits[prop].length = 0;			 
		}		
	};

	this.clearFormValidation = function(hash){
		for (var prop in this.ufoForms.els) {
			header = this.Utils.idSplit(prop)[0];
			if (header == hash) {
				delete this.ufoForms.els[prop];			 
			}
		}		
		if (this.ufoForms.forms[hash]) {			 
			this.ufoForms.forms[hash].length = 0;			 
		}
		if (this.ufoForms.submits[hash]) {			 
			this.ufoForms.submits[hash].length = 0;			 
		}
	};

	this.addValidation = function(config){
		config.isvalid = true;
		config.form = this.factoryflag;
		config.id = this.Utils.idJoin(this.factoryflag, config.id); 
		this.ufoForms.els[config.id] = config;
		if (!this.ufoForms.forms[config.form]) {
			this.ufoForms.forms[config.form]=[];			
		}
		this.ufoForms.forms[config.form].push(config);			
 
		var el = this.ufoForms.get(config.id);
		config.domEl = el;
		var invdiv = this.Utils.idJoin(config.id, 'invalid');  		
		var messages = [];  		
		for (var evt in config.events) {
			jQuery(el).bind(evt, function(){
				config.isvalid = AppMan.ufoForms.validateField(config, evt);
				AppMan.ufoForms.validateForm(config.form);
			});
			var types = config.events[evt]; 				
			if (!types) continue;		
			for (var i = 0; i < types.length; i++) {		
				var type = types[i];
				var msg = config[type];		
				if (!msg) continue;   
				messages.push(this.Utils.format(msg.msg, msg.args)); 
			}
		}
		var msgs = messages.join('</li><li>');  		
		if (messages.length > 1) {
			msgs = '<ul><li>'+msgs+'</li></ul>';			
		} 
		jQuery('#'+invdiv).html(msgs);  		
	};

	this.addSubmit = function(config){
		config.form = this.factoryflag;
		if (!this.ufoForms.submits[config.form]) {
			this.ufoForms.submits[config.form]=[];			
		}
		this.ufoForms.submits[config.form].push(config);
 
		config.id = this.Utils.idJoin(this.factoryflag, config.id); 
		var submit = this.ufoForms.get(config.id);
		config.domEl = submit;
	};

	this.switchtab = function(menuitem, className, id){

		jQuery('.'+className).removeClass('ufo-active');
		jQuery('.'+className+'-menu').removeClass('ufo-active');
		jQuery(menuitem).addClass('ufo-active');
		var names = this.Utils.idSplit(menuitem.id);
		var hash = names[0];
		id = this.Utils.idJoin(hash, id); 
		jQuery('#'+id).addClass('ufo-active');
		jQuery('#'+id).trigger('activated');

		var switchhandlers = jQuery('#'+id).data('switchhandlers');

		if (switchhandlers) {
			for (var i = 0; i < switchhandlers.length; i++){
				var func = switchhandlers[i];		
				func(jQuery('#'+id));		
			}
		}

	};

	this.initRedirect = function(tabid, request, filters){
		var targets, hash, $tab; 
		tabid = AppMan.Utils.idJoin(AppMan.factoryflag, tabid);

		$tab = jQuery('#'+tabid);
		$tab.addClass('ufo-view');

		if (!(targets=$tab.data('targets'))){ 
			targets=[];
			$tab.data('targets', targets);
		}

		hash = this.Utils.getRequestHash(request);

		$viewTarget = jQuery('#'+request.viewTarget); 
		var newId = AppMan.Utils.idJoin(hash, request.viewTarget); 

		if (filters){ 
			var filter = filters[0];
			$hidden=jQuery('<input type="hidden">');
			$hidden=this.Utils.prepareElement($hidden, 'specialfilter', hash);
			$hidden.val(AppMan.JSON.encode([filter]));
			$tab.append($hidden);
		}

		$viewTarget.addClass('ufo-embedded-view');
		$viewTarget.attr('id', newId);
		request.viewTarget = newId;
		request.hash = hash;
		$viewTarget.data('request', request);
		targets.push($viewTarget);

		$tab.live('activated', function(){

			if (jQuery(this).hasClass('ufo-view-activated')) return;
			jQuery(this).addClass('ufo-view-activated');		

			var targets=jQuery(this).data('targets');

			for (var i = 0; i < targets.length; i++){
				var target = targets[i];		
				request = target.data('request');		
				request.callbackfunc = function(){
					target.removeClass('ufo-embedded-view');
					target.addClass('ufo-view-target');
				};
				AppMan.request(AppMan.History.Actions.doNothing, request);		
			}

		});
	};


};
	
if (typeof(ufo) == 'undefined') {
	ufo = {};	
}

ufo.addRow = function(config){
	AppMan.Filter.addRow(AppMan.Utils.getConfig(config));
}; 

ufo.allowPBLink = function(elid) {
	var config = {t: 'ApplicationSettings'};
	config = AppMan.Utils.getConfig(config);
	var hash = config.hash;
	config.m = 'allowPBLink';
	var ajx = {};
	ajx.params = config;
	ajx.success = function(data) {
		elid = AppMan.Utils.idJoin(hash, elid); 	
		jQuery('#'+elid).fadeOut();
	};
	AppMan.Ajax.request(ajx);
};

ufo.apply = function(config){
	config = AppMan.Utils.getConfig(config);
	if (!AppMan.ufoForms.validateForm(config.hash, true)) return;
	AppMan.Filter.apply(config);
};

ufo.back = function(){
	AppMan.History.doAction(AppMan.History.Actions.back, null);		
}; 

ufo.cbDisable = function(el, isDisable, elArray){
	var disable = el.checked == isDisable;
	for (var i = 0; i < elArray.length;  i++) {
		var elId = elArray[i]; 
		elId = AppMan.Utils.siblingId(el, elId);
		jQuery('#'+elId).attr('disabled', disable);		
	} 
};

ufo.checkAll = function(el){
	var checked = jQuery(el).attr('checked') == 'checked';
	var cval = checked ? 'on' : 'off';
	var hash = AppMan.Utils.idSplit(el.id)[0];	
	var header = AppMan.Utils.idJoin(hash, '');	
	jQuery('input.ufo-deletecb[id^="'+header+'"]').each(function(){
		jQuery(this).attr('checked', checked);
		jQuery(this).val(cval);
	});	
};

ufo.copy = function(config){
	config = AppMan.Utils.getConfig(config);
	AppMan.Filter.copy(config);
};

ufo.doFilter = function(config, elem){
	var txt, rclass, aclass;
	config=AppMan.Utils.getConfig(config);
	var fid = AppMan.Utils.idJoin(config.hash, 'div'+config.t+'Filter');
	jQuery('#'+fid).slideToggle();
	var buttons = jQuery(elem).parents('.buttons')[0];
	jQuery(buttons).toggleClass('ufo-active');
	if (jQuery(buttons).hasClass('ufo-active')){
		txt = AppMan.resources.CloseFilter;
		aclass = 'icon_filter_close';
		rclass = 'icon_filter';
	} else {
		txt = AppMan.resources.Search;
		rclass = 'icon_filter_close';
		aclass = 'icon_filter';
	}
	jQuery(elem).attr('title', txt);
	jQuery(elem).removeClass(rclass);
	jQuery(elem).addClass(aclass);
};

ufo.deleteFile = function(id, request, el, callback){
	AppMan.AjaxUpload.deleteFile(id, request, el, callback);
};

ufo.filter = function(config){
	config = AppMan.Utils.getConfig(config);
	startid='#'+AppMan.Utils.idJoin(config.hash, 'start');
	jQuery(startid).val(0);
	AppMan.Filter.filter(config);
};

ufo.getListOptions = function(el, configs){
	AppMan.Filter.getListsOptions(el, configs);
};

ufo.link = function(addconfig, config){
	AppMan.Filter.link(addconfig, config);
};                                                                 

ufo.mcall = function(rdcallmap){
	AppMan.request(AppMan.History.Actions.clear, rdcallmap);		
}; 

ufo.mdelete = function(config, mdconfig){
	AppMan.Filter.mdelete(AppMan.Utils.getConfig(config), mdconfig);		
}; 

ufo.mtmdelete = function (config){
	AppMan.Filter.mtmdelete(AppMan.Utils.getConfig(config));		
}; 

ufo.moveRow = function(config, direction, id){
	AppMan.Filter.moveRow(AppMan.Utils.getConfig(config), direction, id);		
}; 

ufo.newObject = function(config){
	AppMan.History.doAction(AppMan.History.Actions.refresh, null);
	AppMan.Filter.newObject(AppMan.Utils.getConfig(config));		
}; 

ufo.plainsave = function(config){
	AppMan.Filter.plainsave(AppMan.Utils.getConfig(config));
};

ufo.redirect = function(rdcallmap, frmName, divName){
	AppMan.History.doAction(AppMan.History.Actions.refresh, null);
	AppMan.request(AppMan.History.Actions.next, rdcallmap);		
}; 

ufo.scroll = function(config,direction){
	AppMan.Filter.scroll(AppMan.Utils.getConfig(config),direction);
};

ufo.setOptionValue = function(config)	{
	config = AppMan.Utils.getConfig(config);
	config.m = 'setOptionValue';
	var ajx = {};
	ajx.params = config;
	if (config.divid) {
		ajx.success = function(data) {
			var elid = AppMan.Utils.idJoin(config.hash, config.divid); 	
			jQuery('#'+elid).fadeOut();
		};
	}
	AppMan.Ajax.request(ajx);
};

ufo.showInfo = function(config, el){
	return AppMan.AutoSuggest.showInfo(config, el); 
};

ufo.sort = function(config, field){
	AppMan.Filter.sort(AppMan.Utils.getConfig(config), field);
};

ufo.saveMainView = function(config){
	AppMan.Filter.saveMainView(AppMan.Utils.getConfig(config));		
};

ufo.save = function(config){
	config = AppMan.Utils.getConfig(config);
	if (!AppMan.ufoForms.validateForm(config.hash, true)) return;
	AppMan.Filter.save(config);
};

ufo.setupCalendar = function(elId, config){
	AppMan.Calendar.create(elId, config);
};

ufo.insertContent = function(el, icFieldId, icValue, direct, useTMCE) {
	if (!direct) {
		icFieldId = AppMan.Utils.siblingId(el, icFieldId+'_EmailTemplate');
	} else {
		icFieldId = AppMan.Utils.siblingId(el, icFieldId);
	}
	if (useTMCE){
		tinyMCE.get(icFieldId).execCommand('mceInsertContent', false, icValue);
	}
	else {
		var icField=document.getElementById(icFieldId);
		if(icField.disabled) {
			return;
		}
		if (document.selection) {
			icField.focus();
			sel = document.selection.createRange();
			sel.text = icValue;
			icField.focus();
		}
		else if (icField.selectionStart || icField.selectionStart == '0') {
			var startPos = icField.selectionStart;
			var endPos = icField.selectionEnd;
			icField.value = icField.value.substring(0, startPos) +
				icValue +
				icField.value.substring(endPos, icField.value.length);
			icField.focus();
			icField.selectionStart = startPos + icValue.length;
			icField.selectionEnd = startPos + icValue.length;
		} else {
			icField.value += icValue;
			icField.focus();
		}
	}
};

ufo.setFormPageStatisticsShowOnDashboard = function(el, show, config){};
ufo.resetFormPageStatistics = function(el, config){};


jQuery(document).ready(function(){
	jQuery('.ufomenuwrapper ul li').hover(
		function() {
			jQuery(this).addClass('ufo-active');
			jQuery(this).find('ul:first').stop(true, true);
			jQuery(this).find('ul:first').delay(400).slideDown(300);
		},
		function() {
			jQuery(this).removeClass('ufo-active');
			jQuery(this).find('ul:first').stop(true, true);
			jQuery(this).find('ul:first').slideUp(150);
	});
	jQuery('.ufomenuwrapper li:has(ul)').find('a:first').addClass('ufocontainer');
		if (typeof(appManConfig) != 'undefined') {
			AppMan.init(appManConfig);
		}
});
	
var ufoCf = new function(){

	this.counter = {};
  	this.sfPinned = false;
  	this.sfFixed = false;

	this.init = function(){
		this.hfi = {};
		this.hfi['ufo-customform-settings-showlabel']=undefined;
		this.hfi['ufo-customform-settings-showlabel-advanced']=undefined;
		this.hfi['ufo-customform-settings-showdescription']=undefined;
		this.hfi['ufo-customform-settings-showdescription-advanced']=undefined;
		this.hfi['ufo-customform-settings-showrefreshbutton']=undefined;
		this.hfi['ufo-customform-settings-showrefreshbutton-advanced']=undefined;
		this.hfi['ufo-customform-settings-setoptions']=undefined;
		this.hfi['ufo-customform-settings-setdefaultvalue']=undefined;
		this.hfi['ufo-customform-settings-setdefaultvalue-advanced']=undefined;
		this.hfi['ufo-customform-settings-required']=undefined;
		this.hfi['ufo-customform-settings-required-setrequiredsuffix']=undefined;
		this.hfi['ufo-customform-settings-required-setrequiredsuffix-advanced']=undefined;
		this.hfi['ufo-customform-settings-required-advanced']=undefined;
		this.hfi['ufo-customform-settings-validate']=undefined;
		this.hfi['ufo-customform-settings-validate-setvalidmessage']=undefined;
		this.hfi['ufo-customform-settings-validate-setvalidmessage-advanced']=undefined;
		this.hfi['ufo-customform-settings-setstyle']=undefined;
		this.hfi['ufo-customform-settings-setstyle-advanced']=undefined;
		this.hfi['ufo-customform-settings-setsize']=undefined;
		this.hfi['ufo-customform-settings-setcontactoptions']=undefined;
		this.hfi['ufo-customform-settings-setrecaptchaoptions']=undefined;
		this.hfi['ufo-customform-settings-setvcitaoptions']=undefined;
		this.hfi['ufo-customform-settings-filesettings']=undefined;
		this.hfi['ufo-customform-settings-imagesettings']=undefined;
		this.hfi['ufo-customform-settings-calendarsettings']=undefined;
		this.hfi['ufo-customform-settings-goolgemapsettings']=undefined;
		this.hfi['ufo-customform-settings-skypesettings']=undefined;

		jQuery(window).scroll(function () { 
			if (!ufoCf.sf || ufoCf.sf.length == 0) return;
			ufoCf.moveSettingsForm();
		}); 
	}; 

	this.getSFParams = function(woffset, cioffset, poffset, sfheight, sfHeaderHeight, ciHeght){
		params = {};
		params.fixed = false;
		params.top = 0;
		if (sfheight == 0) {
			return params;
		}
		if (cioffset - sfHeaderHeight < woffset) {
			params.top = Math.max(cioffset - sfHeaderHeight - poffset, 0);
			return params;
		}
		if (cioffset + ciHeght > woffset + sfheight) {
			params.top = Math.max(cioffset + ciHeght - sfheight - poffset, 0);
			return params;
		}
		if (cioffset + ciHeght < poffset + sfheight  && woffset > poffset) {
			params.fixed = true;
			return params;
		}
		if (cioffset + ciHeght < poffset + sfheight) {
			return params;
		}
		if (cioffset + ciHeght < woffset + sfheight) {
			params.fixed = true;
			return params;
		}
		return params;	
	};
	this.moveSettingsForm = function(){
		var delta = 30;
		var sfHeaderHeight = 25;
		var ciHeght = 32;
		var sfloffset = ufoCf.sf.offset().left;
		var sfwidth = ufoCf.sf.width();
		var sfheight = ufoCf.sf.height();
		var woffset = jQuery(window).scrollTop() + delta;
		
		var poffset = ufoCf.sfparent.offset().top;
		var cioffset = ufoCf.curentItem.offset().top;
		var params = this.getSFParams(woffset, cioffset, poffset, sfheight, sfHeaderHeight, ciHeght);		
		
		var newheight = cioffset + sfheight + 20;
		var parheight = ufoCf.sfparent.height();
		newheight = Math.max(newheight, parheight);
		if (newheight != parheight) {
			ufoCf.sfparent.css('height', newheight+'px');	
		}
		if (this.sfPinned) {
			return;
		}
		if (params.fixed) {
			ufoCf.sf.css('left', sfloffset+'px');
			ufoCf.sf.css('width', sfwidth+'px');
			ufoCf.sf.css('top', delta);
			ufoCf.sf.css('position', 'fixed');
		}
		else {
			ufoCf.sf.css('left', '0');
			ufoCf.sf.css('width', 'auto');
			ufoCf.sf.css('top', params.top+'px');
			ufoCf.sf.css('position', 'relative');
		}
	}; 

	this.initSettingsForm = function(){
		jQuery('.ufo-customform-settings-form').each(function(){ 
			ufoCf.sf =  jQuery(this);
			ufoCf.sfparent = ufoCf.sf.parent();
		}); 
	}; 

	this.newPage = function(){
		this.initSettingsForm();
		this.setActiveField(AppMan.factoryflag, 'first');
	}; 

	this.expandAll = function(el, expand){
		var hash = AppMan.Utils.idSplit(el.id)[0];
		for (var prop in this.hfi){
			this.hfi[prop] = expand;
		} 
		this.setItemStates(hash, true);
		ufoCf.moveSettingsForm();
	}; 

	this.setItemStates = function(hash, user){
		for (var prop in this.hfi){
			var elid = AppMan.Utils.idJoin(hash, prop);
			this.setItemState(elid, this.hfi[prop], user);
		} 
	}; 

	this.setItemState = function(elid, expand, user){
		var el = jQuery('#'+elid);
		if (el.length != 1) {
			return;				
		}
		var expander = jQuery('#'+AppMan.Utils.idJoin(elid, 'expander'));
       var fieldset = jQuery('#'+AppMan.Utils.idJoin(elid, 'fieldset'));
		if (expand == undefined) { 
			var cb = fieldset.find('input.ufo-customform-fieldform-fieldset-cb:first-child');
			expand = cb.attr('checked');
		} 
		if (expand) {
			expander.addClass('ufo-fieldset-expanded');
       	fieldset.removeClass('ufo-fieldset-collapsed');
			el.show();
		} 
		else {
        	expander.removeClass('ufo-fieldset-expanded');
        	fieldset.addClass('ufo-fieldset-collapsed');
			el.slideUp();
		} 
	}; 

	this.pinSettingForm = function(enforce){
		var pinnedClass = 'ufo-customform-fieldform-pinned';
		var upPinnedClass = 'ufo-customform-fieldform-uppinned';
		if (! enforce) {
			this.sfPinned = ! this.sfPinned;
		}
		if (this.sfPinned) {
			$el = ufoCf.sf.find('a.'+upPinnedClass+':first-child');
			$el.addClass(pinnedClass);
			$el.removeClass(upPinnedClass);
			$el.attr('title', AppMan.resources.CF_UnPin);
		}
		else {
			$el = ufoCf.sf.find('a.'+pinnedClass+':first-child');
			$el.addClass(upPinnedClass);
			$el.removeClass(pinnedClass);
			$el.attr('title', AppMan.resources.CF_Pin);
		}
		if (this.sfPinned) {
			var fixed = (this.sf.css('position') == 'fixed');
			var fixedPosition = jQuery(window).scrollTop() - ufoCf.sfparent.offset().top; 
			this.sfPinnedPosition = fixed ? fixedPosition : this.sf.css('top');		

			ufoCf.sf.css('left', '0');
			ufoCf.sf.css('width', 'auto');
			ufoCf.sf.css('top', this.sfPinnedPosition+'px');
			ufoCf.sf.css('position', 'relative');
		}
	}; 

	this.expanderClick = function(elid, fieldSet){

		jQuery('#'+elid).toggleClass('ufo-fieldset-expanded');
		var expanded = jQuery('#'+elid).hasClass('ufo-fieldset-expanded');
		this.hfi[fieldSet] = expanded;		
		var hash = AppMan.Utils.idSplit(elid)[0];
		var fieldSetId = AppMan.Utils.idJoin(hash, fieldSet);
		this.setItemState(fieldSetId, expanded, true);

	}; 

	this.scrollTo = function($el){
		var voffset = $el.offset().top;
		var woffset = jQuery(window).scrollTop();
		var wheight = jQuery(window).height();
		if (voffset > woffset + wheight - $el.height()) {
			jQuery.scrollTo($el, 500, {offset:{top:-(wheight-$el.height()-40)}});
		}

	}; 

	this.updateFieldData = function(hash){

		if (typeof(hash) == 'object'){
			hash = AppMan.Utils.idSplit(hash.id)[0]; 
		}

		if (!AppMan.ufoForms.validateForm(hash, true)) {
			return;
		}

		var config = AppMan.Utils.getConfig('CustomFormFields');

 		var oid = AppMan.Utils.idJoin(hash, 'oid');
		var $oid = jQuery('#'+oid);		
		if ( $oid.length == 0 ) return; 		

		hashPx = AppMan.Utils.idJoin(hash, '');

		var formdata = AppMan.Utils.getFormData(hashPx, 'CustomFormFields'); 	
		config.a = AppMan.JSON.encode([formdata]);
		config.m = 'updateFieldData';
 		var labelId = AppMan.Utils.idJoin(hash, 'Label');
 		labelText = jQuery('#'+labelId).val();
		ajx = {};
		ajx.params  = config;
		ajx.success = function(data){
 			var lspanid = AppMan.Utils.idJoin(hash, 'ufo', 'fld', 'label', formdata.oid);
			jQuery('#'+lspanid).text(labelText);
			ufoCf.sf.find('.ufo-formvalue').each(function(){ 
				jQuery(this).data('dbValue', jQuery(this).val());		
			}); 
			jQuery('.ufo-fieldform-option-li').each(function(){
				jQuery.removeData(this, 'dbValue');		
			});	
			jQuery('input[name="ufo-fieldform-option-default"]').each(function(){
				jQuery.removeData(this, 'dbValue');		
			});	
		};
		AppMan.Ajax.request(ajx);
		AppMan.disableInput();

	}; 
 
	this.updateRefreshFieldData = function(hash){


		if (typeof(hash) == 'object'){
			hash = AppMan.Utils.idSplit(hash.id)[0]; 
		}

		if (!AppMan.ufoForms.validateForm(hash, true)) {
			return;
		}

		var config = AppMan.Utils.getConfig('CustomFormFields');

 		var oid = AppMan.Utils.idJoin(hash, 'oid');
		var $oid = jQuery('#'+oid);		
		if ( $oid.length == 0 ) return; 		
 		oid = $oid.val();

		hashPx = AppMan.Utils.idJoin(hash, '');

		var formdata = AppMan.Utils.getFormData(hashPx, 'CustomFormFields'); 	
		config.a = AppMan.JSON.encode([formdata]);
		config.m = 'updateFieldData';
		ajx = {};
		ajx.params  = config;
		ajx.success = function(data){
			ufoCf.getFieldSettingsForm(hash, oid);
		};
		AppMan.Ajax.request(ajx);
		AppMan.disableInput();

	}; 
 
	this.addCustomField = function(el, config){

		if (jQuery(el).hasClass('ufo-customform-fieldtype-disabled')) {
       	return;
		}
		var tg = AppMan.Utils.getViewSibling(el.id, config.viewTarget);
		config = AppMan.Utils.getConfig(config);
		config.viewTarget = tg.id;
		config.hash = tg.hash;
		var fel = {};
		fel.id = AppMan.Utils.idJoin(tg.hash, 'fel');
		this.updateFieldData(fel);
		config.a = AppMan.JSON.encode(config.a);
		config.cfa = AppMan.JSON.encode(config.cfa);
		config.specialfilter = AppMan.JSON.encode(config.specialfilter);
 
		config.callbackfunc = function(){
			ufoCf.checkFieldCount();
			ufoCf.initSettingsForm();
			ufoCf.setActiveField(tg.hash, 'last');
		};
 
		AppMan.Filter.filter(config);
	}; 
 
	this.jqInit = function(){
		
		jQuery(function() {
			jQuery( '.ufo-customform-ul' ).sortable({
				placeholder: 'ufo-customform-placeholder',
				items: 'li:not(.ufo-fieldset-header)',
 
				revert: true,
				connectWith: '.ufo-customform-ul',
 
				beforeStop: function(event, ui) {
					ui.item.removeClass('ufo-li-drag');
				},
				sort : function (event, ui) {
					if (ui.item.hasClass('ufo-customform-li-active')) {
						ui.item.addClass('ufo-li-drag');
					}
				},
				update : function () {
					var order = jQuery(this).sortable('serialize');
					ufoCf.updateOrder(jQuery(this).attr('id')+':'+order);
					if (jQuery(this).children().length == 1) {
						jQuery(this).children(':first-child').removeClass('ufo-customform-fieldset-filled');
					}
					else {
						jQuery(this).children(':first-child').addClass('ufo-customform-fieldset-filled');
					}
				}
			});
		});
	}; 
 
	this.checkFieldCount = function(){
		var count = jQuery( '.ufo-customform-ul' ).length;
		if (count > 0) {
			jQuery( '.ufo-customform-fieldtype-li' ).removeClass('ufo-customform-fieldtype-disabled');
		}
		else {
			jQuery( '.ufo-customform-fieldtype-li' ).addClass('ufo-customform-fieldtype-disabled');
		}
	}; 
 
	this.updateOrder = function(order){
		var config = {};
		config.t = 'CustomFormFields';
		config.m = 'updateOrder';
		config.a = order;
		ajx = {};
		ajx.params  = config;
		AppMan.Ajax.request(ajx);
	}; 
 
 
	this.setActiveField = function(hash, id){
		var $ul, lid, $li, tid, $tab;
		if (typeof(hash) == 'object'){
			hash = AppMan.Utils.idSplit(hash.id)[0]; 
		}
		if (typeof(id) == 'string'){
			tid = AppMan.Utils.idJoin(hash, 'CustomFormFieldsDiv');			 
			$tab = jQuery('#'+tid);
			var selector = id;
			$ul = $tab.find('ul.ufo-customform-ul:' + selector + '-child');			 
			if ($ul.length == 0) {			
				$li = $tab.find('li.ufo-customform-li:' + selector + '-child');
			} else {
				$li = $ul.children(':' + selector + '-child');
			}
			if ($li.length == 0) return;			
			if (id == 'last') {
				this.scrollTo($li);
			}
			
			id = AppMan.Utils.idSplit($li.attr('id')); 
			id = id[id.length-1]; 
		}
		else {
			lid = AppMan.Utils.idJoin(hash, 'ufo', 'customform', 'fld', 'li', id); 
			$li = jQuery('#'+lid); 
		}
		jQuery('li.ufo-customform-li-active').removeClass('ufo-customform-li-active');			 
		$li.addClass('ufo-customform-li-active'); 
		ufoCf.curentItem = $li;
		ufoCf.getFieldSettingsForm(hash, id, true); 
		this.jqInit();
	}; 
 
	this.getFieldSettingsForm = function(hash, fid, update){
		if (typeof(hash) == 'object'){
			hash = AppMan.Utils.idSplit(hash.id)[0]; 
		}
		if (update){
			this.updateFieldData(hash);
		}
		var config = AppMan.Utils.getConfig('CustomFormFields');
		config.viewTarget = AppMan.Utils.idJoin(hash, 'settings-form');
		config.hash = hash;
		config.oid = fid;
		config.m = 'getSettingsForm';
		AppMan.clearIntevals(config.viewTarget);
 
		config.callbackfunc = function(){
			ufoCf.setItemStates(hash, false);
			ufoCf.removeOptionData();
			ufoCf.pinSettingForm(true);
			ufoCf.moveSettingsForm();
		};
 
		AppMan.clearFormValidation(hash);
		AppMan.request(AppMan.History.Actions.doNothing, config);		
 
	}; 
 
	this.deleteField = function(config, fid){

		config = AppMan.Utils.getConfig(config);	
		if (!confirm(AppMan.resources.ItwillDeleteRecordsAreYouSure)) return;
		config.a={};
		config.a.a=[];
		config.a.a.push(fid); 
		config.a.m='mdelete';
		config.a = AppMan.JSON.encode(config.a);
		config.m = 'deleteField';
 
 		var oid = AppMan.Utils.idJoin(config.hash, 'oid');
		var $oid = jQuery('#'+oid);		
		var clear = $oid.length != 0  && ('' + fid) == $oid.val();  		
 
       if (clear) {
			var sfid = AppMan.Utils.idJoin(config.hash, 'settings-form');
			jQuery('#'+sfid).html('');					 
		} 
 
		config.callbackfunc = function(){
			ufoCf.checkFieldCount();
		};
 
		AppMan.Filter.filter(config);
 
	}; 
 
	this.removeOptionData = function(){
		jQuery('.ufo-fieldform-option-li').each(function(){
			jQuery.removeData(this, 'dbValue');		
		});	
 
		jQuery('input[name="ufo-fieldform-option-default"]').each(function(){
			jQuery.removeData(this, 'dbValue');		
		});	
 
		jQuery( '.ufo-customform-fieldform-option-ul' ).sortable({
			update : function () {
				var index = 0;
				var hash = AppMan.Utils.idSplit(jQuery(this).attr('id'))[0];
				jQuery(this).children().each(function(){
					index++;
					ufoCf.setOptionIndex(jQuery(this), hash, index, false);
				});
			}
		});
	}; 
 
	this.unsetOptionValues = function(){
		jQuery('input[name="ufo-fieldform-option-default"]').each(function(){
			jQuery(this).val('off');		
		});	
	}; 
 
	this.addOption = function(elId){
		var hash = AppMan.Utils.idSplit(elId)[0];
		var ul = AppMan.Utils.idJoin(hash, 'ufo-customform-fieldform-option-list');
		var $ul = jQuery('#'+ul);
		var $li = $ul.children(':first-child');
		var $clone = $li.clone();
		$clone.css('display', 'none');
		
		$clone.appendTo($ul);
		$ul.children().removeClass('ufo-fieldform-option-single-child');							
 
		var index = $ul.children().length;
		this.setOptionIndex($clone, hash, index, true);	
		
		$clone.fadeIn();
		this.scrollTo($clone);
	}; 
 
	this.setOptionIndex = function($li, hash, index, init){
		$li.find('input.ufo-fieldform-option-li').each(function(){
			jQuery(this).attr('id', AppMan.Utils.idJoin(hash, 'ufo-fieldform-option-li', index));	
			if (init) {
				jQuery(this).val('Option '+index);
			}
		});
 
		$li.find('input.ufo-customform-option-default').each(function(){
			jQuery(this).attr('id', AppMan.Utils.idJoin(hash, 'ufo-fieldform-option-default', index));	
		});
 
		$li.find('a.ufo-customform-option-delete').each(function(){
			jQuery(this).attr('id', AppMan.Utils.idJoin(hash, 'ufo-fieldform-option-delete', index));	
		});
 
		$li.find('a.ufo-fieldform-option-add').each(function(){
			jQuery(this).attr('id', AppMan.Utils.idJoin(hash, 'ufo-fieldform-option-add', index));	
		});
	}; 
 
	this.deleteOption = function(el){

		var $li = jQuery(el).parent();
		var $ul = $li.parent();
		if ($ul.children().length == 1){
			return;							
		}
		$li.fadeOut(300, function(){
			jQuery(this).remove();
			if ($ul.children().length == 1){
				$ul.children().each(function(){
					jQuery(this).addClass('ufo-fieldform-option-single-child');				
				}); 							
			}
			
		});
	}; 
	
	this.unsetDefault = function(elId){
		var hash = AppMan.Utils.idSplit(elId)[0];
		var ul = AppMan.Utils.idJoin(hash, 'ufo-customform-fieldform-option-list');
		jQuery('#'+ul).children().each(function(){
			jQuery(this).find('input.ufo-customform-option-default').each(function(){
				jQuery(this).removeAttr('checked');	
				jQuery.removeData(this, 'dbValue');		
				jQuery(this).val('off');		
			});
		});
	}; 
 
	this.processEntry = function(id, config){

		config = AppMan.Utils.getConfig(config);
		config.oid = id;
		config.m = 'processEntry';
		AppMan.Filter.filter(config);
	}; 

 
	this.direct = function(config, target, isnew){
		config.ac=1;
		config.action = 'easy-contact-forms-submit'; 
		var form = document.createElement('form');
		form.setAttribute('method', 'post');
		form.setAttribute('action', AppMan.Ajax.url);
		form.setAttribute('target', target);
		document.body.appendChild(form);
		if (isnew) {
			window.open('1.html', target);
		}
		for (var prop in config) {
			input = document.createElement('input');
			input.setAttribute('name', prop);
			input.setAttribute('type', 'hidden');
			input.setAttribute('value', config[prop]);
			form.appendChild(input);	
		}
		form.submit();
		document.body.removeChild(form);
	};   
	this.preview = function(){
		config = AppMan.Utils.getConfig('CustomForms');
		config.m = 'preview';
		var hash = config.hash;
		config.oid=jQuery('#'+AppMan.Utils.idJoin(hash,'oid')).val();
		this.direct(config, 'preview', true);
	};   
	this.refreshForm = function(el, formid)	{
		config = AppMan.Utils.getConfig('CustomForms');
		jQuery(el).addClass('ufo-loading');				
		var url = {};
		url.t = 'CustomForms';
		url.m = 'refreshForm';
		url.oid = formid;
		url.ac=1;
		url.action = 'easy-contact-forms-submit'; 
		var urlstr = []; 
		for (var prop in url) {
			urlstr.push(prop + '='+ url[prop]);        	
		}
		urlstr = urlstr.join('&');        	
		var hash = config.hash;
		var target = AppMan.Utils.idJoin(hash, 'ufo-form-preview');
		$target = jQuery('#'+target);								
		jQuery('#'+target).load(function(){
			jQuery(el).removeClass('ufo-loading');	
			jQuery(el).parent().parent().find('a').each(function(){
				jQuery(this).removeClass('ufo-active');				
			});								
			jQuery(el).addClass('ufo-active');				
		});								
		$target.attr('src', AppMan.Ajax.url+'?'+urlstr);								
	};
	
	this.resetStatistics = function(el, config)	{

		config = AppMan.Utils.getConfig(config);
		var hash = config.hash;
		config.m = 'resetStatistics';
		config.oid = jQuery('#'+AppMan.Utils.idJoin(hash, 'oid')).val();
		AppMan.request(AppMan.History.Actions.reload, config);
	};
	
	this.disableContactOptions = function(el)	{
		var enable = true;
		var value = el.value;
		if (value && ('' + value) != '') {
			value = value.split('_');
			value = value[0];
			enable = (value == 'Users');
		}
		var hash = AppMan.Utils.idSplit(el.id)[0];
		var ruo = AppMan.Utils.idJoin(hash, 'RegistredUsersOptions');
		ruo = jQuery('#'+ruo);
		ruo.val('none');
		var elids = [];
		elids.push('RegistredUsersOptions-3');
		elids.push('RegistredUsersOptions-2');
		elids.push('RegistredUsersOptions-1');
		var $el = undefined;
		for (var i = 0; i < elids.length; i++) {
			var elid = AppMan.Utils.idJoin(hash, elids[i]);
			$el = jQuery('#'+elid);
			$el.removeAttr('checked');	
			$el.val('off');		
			if (enable) {
				$el.removeAttr('disabled');
				$el.parent().parent().fadeIn();
			} 
			else {
				$el.attr('disabled', true);
				$el.parent().parent().fadeOut();
			}
		}
		if (enable) {
			$el.attr('checked', true);
			$el.val('on');
		}		
	};

}; 
 
ufoCf.init();
