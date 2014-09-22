/**
 *  author:		Timothy Groves - http://www.brandspankingnew.net
 *	version:	1.2 - 2006-11-17
 *              1.3 - 2006-12-04
 *              2.0 - 2007-02-07
 *
 *	GV: fader was restructured
 *	GV: list class parameter added
 *	GV: connected to jQuery Ajax implementation
 */

var aslist=null;

var useBSNns;

if (useBSNns)
{
	if (typeof(bsn) == "undefined")
		bsn = {}
	_bsn = bsn;
}
else
{
	_bsn = this;
}

if (typeof(_bsn.Autosuggest) == "undefined")
	_bsn.Autosuggest = {}


_bsn.AutoSuggest = function (fldID, params, config)
{
	if (!document.getElementById) return false;
	this.valueId = fldID;
	this.fld = _bsn.DOM.getElement(fldID+'input');
	if (!this.fld) return false;
	
	this.sInput 		= "";
	this.nInputChars 	= 0;
	this.aSuggestions 	= [];
	this.iHighlighted 	= 0;
	
	this.oP = params;
	this.config = config;
	
	var pointer = this;
	
	this.fld.onkeypress 		= function(ev){ return pointer.onKeyPress(ev); }
	this.fld.onkeyup 		= function(ev){ return pointer.onKeyUp(ev); }
	
	this.fld.setAttribute("autocomplete","off");
}

_bsn.AutoSuggest.prototype.onKeyPress = function(ev)
{
	
	var key = (window.event) ? window.event.keyCode : ev.keyCode;
	//
	var RETURN = 13;
	var TAB = 9;
	var ESC = 27;
	
	var bubble = true;

	switch(key)
	{
		case RETURN:
			this.setHighlightedValue();
			bubble = false;
			break;
		case ESC:
			this.clearSuggestions();
			break;
	}
	return bubble;
}

_bsn.AutoSuggest.prototype.onKeyUp = function(ev)
{
	var key = (window.event) ? window.event.keyCode : ev.keyCode;
	
	var ARRUP = 38;
	var ARRDN = 40;
	
	var bubble = true;

	switch(key)
	{
		case ARRUP:
			this.changeHighlight(key);
			bubble = false;
			break;
		case ARRDN:
			this.changeHighlight(key);
			bubble = false;
			break;
		default:
			this.getSuggestions(this.fld.value);
	}

	return bubble;
}

_bsn.AutoSuggest.prototype.getSuggestions = function (val)
{
	if (val == this.sInput)
		return false;
	//
	if (val.length < this.oP.minchars)
	{
		this.sInput = "";
		return false;
	}
	if (val.length>this.nInputChars && this.aSuggestions.length && this.oP.cache)
	{
		var arr = [];
		for (var i=0;i<this.aSuggestions.length;i++)
		{
			if (this.aSuggestions[i].value.substr(0,val.length).toLowerCase() == val.toLowerCase())
				arr.push( this.aSuggestions[i] );
		}
		
		this.sInput = val;
		this.nInputChars = val.length;
		this.aSuggestions = arr;
		
		this.createList(this.aSuggestions);
		
		return false;
	}
	else
	{
		this.sInput = val;
		this.nInputChars = val.length;
		var pointer = this;
		clearTimeout(this.ajID);

		this.ajID = setTimeout( function() { pointer.doAjaxRequest() }, this.oP.delay );
	}

	return false;
}

_bsn.AutoSuggest.prototype.doAjaxRequest = function ()
{
	var pointer = this;
	this.config[this.oP.varname]=escape(this.fld.value);

	var ajx = {};
	ajx.success = function (data ) { pointer.setSuggestions(data) };
	ajx.params = this.config;

	AppMan.Ajax.request(ajx);
}

_bsn.AutoSuggest.prototype.setSuggestions = function (data)
{
	this.aSuggestions = [];
	
	var jsondata = AppMan.JSON.decode(data);
	for (var i=0;i<jsondata.results.length;i++){
		this.aSuggestions.push({ 
			'id':jsondata.results[i].id, 
			'value':jsondata.results[i].value, 
			'info':jsondata.results[i].info 
		});
	}
	
	this.idAs = "as_"+this.fld.id;

	this.createList(this.aSuggestions);

}


_bsn.AutoSuggest.prototype.createList = function(arr)
{
	var pointer = this;
	
	_bsn.DOM.removeElement(this.idAs);
	this.killTimeout();
	// create holding div
	//
	var div = _bsn.DOM.createElement("div", {id:this.idAs, className:this.oP.className});	
	
	var hcorner = _bsn.DOM.createElement("div", {className:"as_corner"});
	var hbar = _bsn.DOM.createElement("div", {className:"as_bar"});
	var header = _bsn.DOM.createElement("div", {className:"as_header"});
	header.appendChild(hcorner);
	header.appendChild(hbar);
	div.appendChild(header);
	
	var ul = _bsn.DOM.createElement("ul", {id:"as_ul"});
	//
	for (var i=0;i<arr.length;i++)
	{
		// format output with the input enclosed in a EM element
		// (as HTML, not DOM)
		//
		var val = arr[i].value;
		var st = val.toLowerCase().indexOf( this.sInput.toLowerCase() );
		var output = val.substring(0,st) + "<em>" + val.substring(st, st+this.sInput.length) + "</em>" + val.substring(st+this.sInput.length);
		
		
		var span = _bsn.DOM.createElement("span", {}, output, true);
		if (arr[i].info != "")
		{
			var br			= _bsn.DOM.createElement("br", {});
			span.appendChild(br);
			var liConfig = this.oP.listItemClass ? {className:this.oP.listItemClass} : {};
			var small		= _bsn.DOM.createElement("span", liConfig, arr[i].info, true);
			span.appendChild(small);
		}
		
		var a 			= _bsn.DOM.createElement("a", { href:"#" });
		
		var tl 		= _bsn.DOM.createElement("span", {className:"tl"}, " ");
		var tr 		= _bsn.DOM.createElement("span", {className:"tr"}, " ");
		a.appendChild(tl);
		a.appendChild(tr);
		
		a.appendChild(span);
		
		a.name = i+1;
		a.onclick = function () { pointer.setHighlightedValue(); return false; }
		a.onmouseover = function () { pointer.setHighlight(this.name); }
		
		var li 			= _bsn.DOM.createElement(  "li", {}, a  );
		
		ul.appendChild( li );
	}
	
	if (arr.length == 0)
	{
		var li 	= _bsn.DOM.createElement(  "li", {className:"as_warning"}, this.oP.noresults  );
		
		ul.appendChild( li );
	}
	
	div.appendChild( ul );
	
	var fcorner = _bsn.DOM.createElement("div", {className:"as_corner"});
	var fbar = _bsn.DOM.createElement("div", {className:"as_bar"});
	var footer = _bsn.DOM.createElement("div", {className:"as_footer"});
	footer.appendChild(fcorner);
	footer.appendChild(fbar);
	div.appendChild(footer);
	
	var pos = _bsn.DOM.getPos(this.fld);
	
	div.style.left 		= pos.x + "px";
	div.style.top 		= ( pos.y + this.fld.offsetHeight + this.oP.offsety ) + "px";
	//div.style.width 	= this.fld.offsetWidth + "px";
	
	div.onmouseover 	= function(){ pointer.killTimeout() }
	div.onmouseout 		= function(){ pointer.resetTimeout() }

	document.getElementsByTagName("body")[0].appendChild(div);
	//
	this.iHighlighted = 0;
	
	//
	var pointer = this;
	this.toID = setTimeout(function () { pointer.clearSuggestions() }, this.oP.timeout);
}

_bsn.AutoSuggest.prototype.changeHighlight = function(key)
{	
	var list = _bsn.DOM.getElement("as_ul");
	if (!list)
		return false;
	
	var n;

	if (key == 40)
		n = this.iHighlighted + 1;
	else if (key == 38)
		n = this.iHighlighted - 1;
	
	if (n > list.childNodes.length)
		n = list.childNodes.length;
	if (n < 1)
		n = 1;
	
	this.setHighlight(n);
}



_bsn.AutoSuggest.prototype.setHighlight = function(n)
{
	var list = _bsn.DOM.getElement("as_ul");
	if (!list)
		return false;
	
	if (this.iHighlighted > 0)
		this.clearHighlight();
	
	this.iHighlighted = Number(n);
	
	list.childNodes[this.iHighlighted-1].className = "as_highlight";

	this.killTimeout();
}


_bsn.AutoSuggest.prototype.clearHighlight = function()
{
	var list = _bsn.DOM.getElement("as_ul");
	if (!list)
		return false;
	
	if (this.iHighlighted > 0)
	{
		list.childNodes[this.iHighlighted-1].className = "";
		this.iHighlighted = 0;
	}
}


_bsn.AutoSuggest.prototype.setHighlightedValue = function ()
{
	if (this.iHighlighted)
	{
		this.sInput = this.fld.value = this.aSuggestions[ this.iHighlighted-1 ].value;
		
		// move cursor to end of input (safari)
		//
		this.fld.focus();
		if (this.fld.selectionStart)
			this.fld.setSelectionRange(this.sInput.length, this.sInput.length);
		

		this.clearSuggestions();
		
		// pass selected object to callback function, if exists
		//
		if (typeof(this.oP.callback) == "function")
			this.oP.callback( this.aSuggestions[this.iHighlighted-1] );
	}
}

_bsn.AutoSuggest.prototype.killTimeout = function()
{
	clearTimeout(this.toID);
}

_bsn.AutoSuggest.prototype.resetTimeout = function()
{
	clearTimeout(this.toID);
	var pointer = this;
	this.toID = setTimeout(function () { pointer.clearSuggestions() }, 1000);
}

_bsn.AutoSuggest.prototype.clearSuggestions = function ()
{
	
	this.killTimeout();
	
	var ele = _bsn.DOM.getElement(this.idAs);
	var pointer = this;
	if (ele)
	{
		var fade = new _bsn.Fader(ele,1,0,250,function () { _bsn.DOM.removeElement(pointer.idAs) });
	}
}


// AJAX PROTOTYPE _____________________________________________


if (typeof(_bsn.Ajax) == "undefined")
	_bsn.Ajax = {}



_bsn.Ajax = function ()
{
	this.req = {};
	this.isIE = false;
}



_bsn.Ajax.prototype.makeRequest = function (url, meth, onComp, onErr)
{
	
	if (meth != "POST")
		meth = "GET";
	
	this.onComplete = onComp;
	this.onError = onErr;
	
	var pointer = this;
	
	// branch for native XMLHttpRequest object
	if (window.XMLHttpRequest)
	{
		this.req = new XMLHttpRequest();
		this.req.onreadystatechange = function () { pointer.processReqChange() };
		this.req.open("GET", url, true); //
		this.req.send(null);
	// branch for IE/Windows ActiveX version
	}
	else if (window.ActiveXObject)
	{
		this.req = new ActiveXObject("Microsoft.XMLHTTP");
		if (this.req)
		{
			this.req.onreadystatechange = function () { pointer.processReqChange() };
			this.req.open(meth, url, true);
			this.req.send();
		}
	}
}


_bsn.Ajax.prototype.processReqChange = function()
{
	
	// only if req shows "loaded"
	if (this.req.readyState == 4) {
		// only if "OK"
		if (this.req.status == 200)
		{
			this.onComplete( this.req );
		} else {
			this.onError( this.req.status );
		}
	}
}


// DOM PROTOTYPE _____________________________________________


if (typeof(_bsn.DOM) == "undefined")
	_bsn.DOM = {}




_bsn.DOM.createElement = function ( type, attr, cont, html )
{
	var ne = document.createElement( type );
	if (!ne)
		return false;
		
	for (var a in attr)
		ne[a] = attr[a];
		
	if (typeof(cont) == "string" && !html)
		ne.appendChild( document.createTextNode(cont) );
	else if (typeof(cont) == "string" && html)
		ne.innerHTML = cont;
	else if (typeof(cont) == "object")
		ne.appendChild( cont );

	return ne;
}





_bsn.DOM.clearElement = function ( id )
{
	var ele = this.getElement( id );
	
	if (!ele)
		return false;
	
	while (ele.childNodes.length)
		ele.removeChild( ele.childNodes[0] );
	
	return true;
}

_bsn.DOM.removeElement = function ( ele )
{
	var e = this.getElement(ele);
	
	if (!e)
		return false;
	else if (e.parentNode.removeChild(e))
		return true;
	else
		return false;
}

_bsn.DOM.replaceContent = function ( id, cont, html )
{
	var ele = this.getElement( id );
	
	if (!ele)
		return false;
	
	this.clearElement( ele );
	
	if (typeof(cont) == "string" && !html)
		ele.appendChild( document.createTextNode(cont) );
	else if (typeof(cont) == "string" && html)
		ele.innerHTML = cont;
	else if (typeof(cont) == "object")
		ele.appendChild( cont );
}


_bsn.DOM.getElement = function ( ele )
{
	if (typeof(ele) == "undefined")
	{
		return false;
	}
	else if (typeof(ele) == "string")
	{
		var re = document.getElementById( ele );
		if (!re)
			return false;
		else if (typeof(re.appendChild) != "undefined" ) {
			return re;
		} else {
			return false;
		}
	}
	else if (typeof(ele.appendChild) != "undefined")
		return ele;
	else
		return false;
}


_bsn.DOM.appendChildren = function ( id, arr )
{
	var ele = this.getElement( id );
	
	if (!ele)
		return false;
	
	
	if (typeof(arr) != "object")
		return false;
		
	for (var i=0;i<arr.length;i++)
	{
		var cont = arr[i];
		if (typeof(cont) == "string")
			ele.appendChild( document.createTextNode(cont) );
		else if (typeof(cont) == "object")
			ele.appendChild( cont );
	}
}

_bsn.DOM.getPos = function ( ele )
{
	var ele = this.getElement(ele);

	var obj = ele;

	var curleft = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curleft += obj.offsetLeft
			obj = obj.offsetParent;
		}
	}
	else if (obj.x)
		curleft += obj.x;


	var obj = ele;
	
	var curtop = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curtop += obj.offsetTop
			obj = obj.offsetParent;
		}
	}
	else if (obj.y)
		curtop += obj.y;

	return {x:curleft, y:curtop}
}

if (typeof(_bsn.Fader) == "undefined")
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
}

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
}

_bsn.Fader._setOpacity = function(el, ieop) {
	var op = ieop/100;
	if (el.filters) {
		try {
			el.filters.item("DXImageTransform.Microsoft.Alpha").opacity = ieop;
		} catch (e) { 
			el.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity='+ieop+')';
		}
	}
	else {
		el.style.opacity = op;
	}
}
