var wQuoteMain, wQuoteHeader, wQuoteBody, wQuoteInfo, wQuoteId;
ChangeCacheQuotes = {};

function openQuotes() {
	_popup = openPopup("Zitate",true);
	if (_popup == false) {
		return false;
	}
	wQuoteId = _popup["id"];
	registerReset(wQuoteId,resetQuotesPopup);
	wQuoteMain = _popup["node"];
	wQuoteHeader = document.getElementById(wQuoteId + "-header");
	wQuoteBody = document.getElementById(wQuoteId + "-body");
	wQuoteInfo = document.getElementById(wQuoteId + "-info");
	
	wQuoteMain.id = "quotes-main";
	
	wQuoteBody.appendChild(finalizePopupTemplate(wQuoteId,document.getElementById("quotes-template")));
	addTabBar(wQuoteId, finalizePopupTemplate(wQuoteId, document.getElementById("quotes-template-tabbar")));
	registerTabFuncAlways(wQuoteId, "tab2", loadmyQuotes);
	openFirstTab(wQuoteId);

	movePopup(wQuoteId,(window.innerWidth / 2) - (wQuoteMain.offsetWidth / 2),(window.innerHeight / 2) - (wQuoteMain.offsetHeight / 2));
}

function resetQuotesPopup() {
	wQuoteMain = undefined;
	wQuoteHeader = undefined;
	wQuoteBody = undefined;
	wQuoteInfo = undefined;
	wQuoteId = undefined;
	ChangeCacheQuotes = {};
}

function loadmyQuotes() {
	sendData("","api/getquotes.php",display_quotes);
}

function display_quotes() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		var _display = document.getElementById(wQuoteId + "-myquotes-list");
		_display.innerHTML = "";
		if (answer.length > 0) {
			for (var i = 0; i < answer.length; i++) {
				var _element = document.createElement("div");
				_element.className = "quotes-quote-element";
				_element.id = "qi" + answer[i].id;
				
				var _displaycontainer = document.createElement("div");
				_displaycontainer.id = "qi" + answer[i].id + "-displaycontainer";
				_displaycontainer.style.color = "#EEE";
				
				var _textinput = document.createElement("textarea");
				_textinput.className = "quotes-quote-element-textinput";
				_textinput.value = _decodeSafeString(answer[i].text);
				_textinput.prototype = "Zitat";
				_textinput.onchange = function(evt) {updateQuoteData(evt,"text");};
				_textinput.id = "qi" + answer[i].id + "-textinput";
				
				var _contextinput = document.createElement("input");
				_contextinput.className = "input-field quotes-quote-element-input";
				_contextinput.id = wQuoteId + "-quote_context";
				_contextinput.type = "text";
				_contextinput.placeholder = "Kontext (optional)";
				_contextinput.id = "qi" + answer[i].id + "-contextinput";
				_contextinput.value = _decodeSafeString(answer[i].context);
				_contextinput.onchange = function(evt) {updateQuoteData(evt,"context");};
				
				var _deletebutton = document.createElement("div");
				_deletebutton.className = "quotes-quote-element-delete";
				_deletebutton.onclick = deleteQuote;
				
				var _editbutton = document.createElement("div");
				_editbutton.className = "quotes-quote-element-edit";
				_editbutton.style.display = "none";
				_editbutton.id = "qi" + answer[i].id + "-editbutton";
				_editbutton.onclick = applyQuoteEdit;
				
				_element.appendChild(_displaycontainer);
				_element.appendChild(_deletebutton);
				_element.appendChild(_textinput);
				_element.appendChild(_contextinput);
				_element.appendChild(_editbutton);
				
				_display.appendChild(_element);
			}
		} else {
			var _element = document.createElement("div");
			_element.className = "quotes-quote-element";
			_element.innerHTML = "Keine Zitate gespeichert";
			_display.appendChild(_element);
		}
		requestReady = true;
	}
}


function deleteQuote(evt) {
	var _removeId = evt.target.parentNode.id.replace("qi","");
	var _removeElement = document.getElementById("qi" + _removeId);
	var _editbutton = document.getElementById("qi" + _removeId + "-editbutton");
	_editbutton.style.display = "block";
	_editbutton.className = "quotes-quote-element-edit";
	_removeElement.className += " element-delete";
	_removeElement.style.height = (_removeElement.offsetHeight - (window.getComputedStyle(_removeElement, null).getPropertyValue("padding-top").replace("px","") / 1)) + "px";
	addToQuotesCache("qi" + _removeId,"delete",true);
	document.getElementById("qi" + _removeId + "-textinput").style.display = "none";
	document.getElementById("qi" + _removeId + "-contextinput").style.display = "none";
	document.getElementById("qi" + _removeId + "-displaycontainer").innerHTML = "Dieses Zitat wird gel&ouml;scht.";
	_undolink = document.createElement("a");
	_undolink.innerHTML = "<u>R&uuml;ckg&auml;nging machen</u>";
	_undolink.href = "javascript:void(0)";
	_undolink.onclick = function() { UNDOdeleteQuote(_removeId); }
	document.getElementById("qi" + _removeId + "-displaycontainer").appendChild(_undolink);
}

function applyQuoteEdit(evt) {
	var _id = evt.target.parentNode.id.replace("qi","");
	var _toserver = {};
	_toserver = ChangeCacheQuotes["qi" + _id];
	_toserver["id"] = _id;
	sendData("data=" + JSON.stringify(_toserver),"api/changequote.php",updateQuoteView);
	ChangeCacheQuotes["qi" + _id] = undefined;
}

function updateQuoteView() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			if (answer.procedure == "delete") {
				document.getElementById("qi" + answer.id).parentNode.removeChild(document.getElementById("qi" + answer.id));
			} else {
				document.getElementById("qi" + answer.id + "-editbutton").className = "quotes-quote-element-edit element-edit-success";
			}
		} else {
			switch (answer.code) {
				case 1:
					showError(wQuoteId,"Ein unerwarteter Fehler ist aufgetreten (" + answer.error + ")");
					break;
				default:
					showError(wQuoteId,"Ein unbekannter Fehler ist aufgetreten");
			}
		}
		requestReady = true;
	}
}

function updateQuoteData(evt,type) {
	//if (type == "text" && evt.target.value == "") return;	
	var _id = evt.target.parentNode.id.replace("qi","");
	addToQuotesCache("qi" + _id,type,_makeStringSafe(evt.target.value));
	var _editbutton = document.getElementById("qi" + _id + "-editbutton");
	_editbutton.style.display = "block";
	_editbutton.className = "quotes-quote-element-edit";
}

function UNDOdeleteQuote(id) {
	document.getElementById("qi" + id).className = "quotes-quote-element";
	removeFromCache("qi" + id,"delete");
	document.getElementById("qi" + id + "-textinput").style.display = "inline-block";
	document.getElementById("qi" + id + "-contextinput").style.display = "inline-block";
	document.getElementById("qi" + id + "-displaycontainer").innerHTML = "";
	document.getElementById("qi" + id + "-editbutton").style.display = "none";
}

function addToQuotesCache(id,key,value) {
	if (ChangeCacheQuotes[id] == undefined) {
		ChangeCacheQuotes[id] = {};
	}
	ChangeCacheQuotes[id][key] = value;
}

function removeFromQuotesCache(id,key) {
	if (ChangeCacheQuotes[id] == undefined) {
		return;
	}
	ChangeCacheQuotes[id][key] = undefined;
}

function saveQuote() {
	switch("") {
		case document.getElementById(wQuoteId + "-quote_text").value:
			return;
			break;
		case document.getElementById(wQuoteId + "-quote_context").value:
			if (!confirm("Du hast keinen Kontext angegeben. Bist du sicher, dass du speichern möchtest?")) {
				return;
			}
			break;
	}
	var _toserver = {};
	_toserver["text"] = _makeStringSafe(document.getElementById(wQuoteId + "-quote_text").value);
	_toserver["context"] = _makeStringSafe(document.getElementById(wQuoteId + "-quote_context").value);
	sendData("data=" + JSON.stringify(_toserver),"api/addquote.php",checkQuote);
}

function checkQuote() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			showSuccess(wQuoteId,"Zitat erfolgreich gespeichert");
		} else {
			switch (answer.code) {
				case 1:
					showError(wQuoteId,"Ein unerwarteter Fehler ist aufgetreten (" + answer.error + ")");
					break;
				default:
					showError(wQuoteId,"Ein unbekannter Fehler ist aufgetreten");
					break;
			}
		}
		requestReady = true;
	}
}