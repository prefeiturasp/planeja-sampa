
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

if (typeof(_bsn) == 'undefined') {
	_bsn = {}
	_bsn.Fader = {}

	_bsn.Fader = function (ele, from, to, fadetime, callback) {	
		if (!ele) return false;
	
		this.ele = ele;
		this.from = from;
		this.to = to;
		this.callback = callback;
		this.nDur = fadetime;
		this.nInt = 50;
		this.nTime = 0;
		var p = this;
		this.nID = setInterval(function() { p._fade() }, this.nInt);
	};

	_bsn.Fader.prototype._fade = function() {
		this.nTime += this.nInt;
		function tween(t,b,c,d)	{
			return b + ( (c-b) * (t/d) );
		}

		var ieop = Math.round( tween(this.nTime, this.from, this.to, this.nDur) * 100 );
		_bsn.Fader._setOpacity(this.ele, ieop);
	
		if (this.nTime == this.nDur) {
			clearInterval( this.nID );
			if (this.callback != undefined)
				this.callback(this.ele);
		}
	};

	_bsn.Fader._setOpacity = function(el, ieop) {
		var op = ieop/100;
		if (el.filters) {
			try {
				el.filters.item('DXImageTransform.Microsoft.Alpha').opacity = ieop;
			} catch (e) { 
				el.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity='+ieop+')';
			}
		}
		else {
			el.style.opacity = op;
		}
	};
}

ufoForms.docReady(function(){ufoForms.initValidation()});
