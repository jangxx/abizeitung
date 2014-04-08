var request = null;
var requestReady = true;
IE = {};
IE.is = (navigator.appName == 'Microsoft Internet Explorer') ? true : false;

if (IE.is) {
	Element.prototype.dataset = {};
	window.history.pushState = function(arg1, arg2, ar3) {}
	window.btoa = function(string) {
		return string.split('').reverse().join('');
	}
	window.atob = function(string) {
		return string.split('').reverse().join('');
	}
}

function sendData(data, address, callback) {
	if (requestReady != true) {
		setTimeout(function() {sendData(data, address, callback); }, 100);
		return false;
	}
	requestReady = false;
	request = new XMLHttpRequest();
	request.open("POST",address,true);
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	if (WebKitDetect.isWebKit()) {
		request.onreadystatechange = callback;
	} else {
		request.onload = callback;
	}
	request.send(data);
}

function parseGET() {
	var query = location.search;
	query = query.replace("?","");
	var parameters = new Array();
	var tempArray = new Array();
	var tempQueryArray = new Array();
	tempQueryArray = query.split("&");
	for (var i = 0;i <= tempQueryArray.length - 1;i++)
	{
		tempArray = tempQueryArray[i].split("=");
		parameters[tempArray[0]] = tempArray[1];
	}
	return parameters;
}

function updateGET(param, value) {
	var search = location.search;
	search = search.replace("?","");
	var found = false;
	var parameters = new Array();
	var tempArray = new Array();
	var allparams = new String();	
	var tempSearchArray = new Array();
	tempSearchArray = search.split("&");
	if (tempSearchArray[0] == "" && search.length > 0) {
		tempSearchArray[0] = search;
	}
	if (search.length > 0) {
		for (var i = 0;i < tempSearchArray.length;i++)
		{
			var tempArray = tempSearchArray[i].split("=");
			if (tempArray[0] == param) {
				tempArray[1] = value;
				found = true;
			}
			allparams = allparams + tempArray[0] + "=" + tempArray[1] + "&";
		}
		allparams = allparams.slice(0, allparams.length - 1);
	}
	if (found == false && allparams.length > 0) {
		allparams = allparams + "&" + param + "=" + value;
	} else if (found == false) {
		allparams = param + "=" + value;
	}
	return allparams;
}

function deleteGETparam(param, input) {
	var search = (input != "") ? location.search : input;
	search = search.replace("?","");
	var parameters = new Array();
	var tempArray = new Array();
	var allparams = new String();	
	var tempSearchArray = new Array();
	tempSearchArray = search.split("&");
	if (tempSearchArray[0] == "" && search.length > 0) {
		tempSearchArray[0] = search;
	}
	if (search.length > 0) {
		for (var i = 0;i < tempSearchArray.length;i++)
		{
			tempArray = tempSearchArray[i].split("=");
			if (tempArray[0] != param) {
				allparams = allparams + tempArray[0] + "=" + tempArray[1] + "&";
			}
		}
		allparams = allparams.slice(0, allparams.length - 1);
	}
	return allparams;
}

function parseHash() {
	var hash = location.hash;
	hash = hash.replace("?","");
	hash = hash.replace("#","");
	var parameters = new Array();
	var tempArray = new Array();
	var tempHashArray = new Array();
	tempHashArray = hash.split("&");
	for (var i = 0;i <= tempHashArray.length - 1;i++)
	{
		tempArray = tempHashArray[i].split("=");
		parameters[tempArray[0]] = tempArray[1];
	}
	return parameters;
}

function updateHash(param, value) {
	var hash = location.hash;
	hash = hash.replace("?","");
	hash = hash.replace("#","");
	var found = false;
	var parameters = new Array();
	var tempArray = new Array();
	var allparams = new String();	
	var tempHashArray = new Array();
	tempHashArray = hash.split("&");
	for (var i = 0;i < tempHashArray.length - 1;i++)
	{
		tempArray = tempHashArray[i].split("=");
		if (tempArray[0] == param) {
			tempArray[1] = value;
			found = true;
		}
		allparams = allparams + tempArray[0] + "=" + tempArray[1] + "&";
	}
	if (found == false) {
		allparams = allparams + param + "=" + value;
	}
	return allparams;
}

function saveSetting(name,value) {
	if(typeof(Storage)!=="undefined") {
		localStorage.setItem(name,value);
	} else {
		setCookie(name,value,356);
	}
}

function getSetting(name) {
	if(typeof(Storage)!=="undefined") {
		var _return = _StringToBool(localStorage.getItem(name));
	} else {
		var _return = getCookie(name);
	}
	if (_return == null) {
		return "not set";
	} else {
		return _return;
	}
}

function finalizeTemplate(template,replace) {
	var _template = template.cloneNode(true);
	_template.className = "";
	for (var i = 0; i < replace.length; i++) {
		_template.innerHTML = _template.innerHTML.replace(new RegExp("{" + replace[i]["name"] + "}","g"),replace[i]["value"]); 
	}
	return _template;
}

function _StringToBool(string) {
	return (string == "true");
}

function getCookie(c_name)
{
	//source: http://www.w3schools.com/js/js_cookies.asp
	//credits to them
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
		{
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");
		if (x==c_name)
		{
			return unescape(y);
		}
	}
	return false;
}

function setCookie(c_name,value,exdays)
{
	//source: http://www.w3schools.com/js/js_cookies.asp
	//credits to them
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function insertAfter(referenceNode, newNode) {
	//source: http://stackoverflow.com/questions/4793604/how-to-do-insert-after-in-javascript-without-using-a-library
	//credits to karim79
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

function strCountRows(str) {
	return str.split("\n").length;
}

function logout() {
	window.location = "../api/logout.php";
}

function nextOnEnter(evt) {
	if (evt.keyCode == 13) {
		var _inputs = document.getElementsByTagName("input");
		for (var i = 0; i < _inputs.length; i++) {
			if (_inputs[i] == evt.target && (i+1) < _inputs.length) {
				var _node = _inputs[i + 1];
				break;
			}
		}
		if (_node == undefined) {
			return;
		}
		_node.focus();
	}
}

function getSelectedRadio(name) {
	var radios = document.getElementsByName(name);
	for (var i = 0; i < radios.length; i++) {
		if (radios[i].checked == true) {
			return radios[i].value;
		}
	}
	return;
}

function selectRadio(name,value) {
	var radios = document.getElementsByName(name);
	for (var i = 0; i < radios.length; i++) {
		if (radios[i].value == value) {
			radios[i].checked = true;
			return radios[i];
		}
	}
	return;
}

String.prototype.fill = function(count, char) {
	var _r = this;
	for (var i = 1; i <= count - this.length; i++) {
		_r = "" + char + _r;
	}
	return _r;
}

function _makeStringSafe(string) {
	return encodeURIComponent(string.replace(new RegExp("'","g"),"&lsquo;").replace(new RegExp('"',"g"),"&rdquo;").replace(new RegExp("\n","g"),"<br/>"));
}

function _decodeSafeString(safestring) {
	return safestring.replace(new RegExp("&lsquo;","g"),"'").replace(new RegExp("&rdquo;","g"),'"').replace(new RegExp("<br/>","g"),"\n");
}