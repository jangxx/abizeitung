var wAdminMain, wAdminHeader, wAdminBody, wAdminInfo, wAdminId;
var wOverviewMain, wOverviewHeader, wOverviewBody, wOverviewInfo, wOverviewId;
var pActive, pResults = {}, eActive, eResults = {};
var wNewsMain, wNewsHeader, wNewsBody, wNewsInfo, wNewsId;
var searchinput, new_firstnameinput, new_lastnameinput;
var ChangeCache = {};

function openAdminPage() {
	var _popup = openPopup("Administration",true);
	if (_popup == false) {
		return false;
	}
	wAdminId = _popup["id"];
	registerReset(wAdminId,resetAdminPopup);
	wAdminMain = _popup["node"];
	wAdminHeader = document.getElementById(wAdminId + "-header");
	wAdminBody = document.getElementById(wAdminId + "-body");
	wAdminInfo = document.getElementById(wAdminId + "-info");
	
	wAdminMain.id= "admin-main";
	
	wAdminBody.appendChild(finalizePopupTemplate(wAdminId,document.getElementById("admin-template")));
	
	searchinput = document.getElementById(wAdminId + "-input_search");
	new_firstnameinput = document.getElementById(wAdminId + "-new_firstnameinput");
	new_lastnameinput = document.getElementById(wAdminId + "-new_lastnameinput");
	
	_showhiddencommentsCheckbox = document.getElementById(wAdminId + "-admin-hiddencomments-checkbox");
	_showhiddencommentsCheckbox.onchange = function(evt) {saveAdminSettings("showhiddencomments",(evt.target.checked)); }
	_showhiddencommentsCheckbox.checked = _StringToBool(getCookie("showhiddencomments"));
	
	_deletecommentstomeCheckbox = document.getElementById(wAdminId + "-admin-deletecomments-checkbox");
	_deletecommentstomeCheckbox.onchange = function(evt) {saveAdminSettings("deletecommentstome",(evt.target.checked)); }
	_deletecommentstomeCheckbox.checked = _StringToBool(getCookie("deletecommentstome"));
	
	_disablecommentstomeCheckbox = document.getElementById(wAdminId + "-admin-disablecomments-checkbox");
	_disablecommentstomeCheckbox.onchange = function(evt) {saveAdminSettings("disablecomments",(evt.target.checked)); }
	_disablecommentstomeCheckbox.checked = _StringToBool(getCookie("disablecomments"));
	
	_disablecommentsortCheckbox = document.getElementById(wAdminId + "-admin-disablecommentsort-checkbox");
	_disablecommentsortCheckbox.onchange = function(evt) {saveAdminSettings("disablecommentsort",(evt.target.checked)); }
	_disablecommentsortCheckbox.checked = _StringToBool(getCookie("disablecommentsort"));
	
	_disableloginCheckbox = document.getElementById(wAdminId + "-admin-disablelogin-checkbox");
	_disableloginCheckbox.onchange = function(evt) {saveAdminSettings("disablelogin",(evt.target.checked)); }
	_disableloginCheckbox.checked = _StringToBool(getCookie("disablelogin"));
	
	_disabledescriptionCheckbox = document.getElementById(wAdminId + "-admin-disabledescription-checkbox");
	_disabledescriptionCheckbox.onchange = function(evt) {saveAdminSettings("disabledescription",(evt.target.checked)); }
	_disabledescriptionCheckbox.checked = _StringToBool(getCookie("disabledescription"));
	
	_disablepollsCheckbox = document.getElementById(wAdminId + "-admin-disablepolls-checkbox");
	_disablepollsCheckbox.onchange = function(evt) {saveAdminSettings("disablepolls",(evt.target.checked)); }
	_disablepollsCheckbox.checked = _StringToBool(getCookie("disablepolls"));
	
	_disableelectionsCheckbox = document.getElementById(wAdminId + "-admin-disableelections-checkbox");
	_disableelectionsCheckbox.onchange = function(evt) {saveAdminSettings("disableelections",(evt.target.checked)); }
	_disableelectionsCheckbox.checked = _StringToBool(getCookie("disableelections"));
	
	_disablecoupleelectionsCheckbox = document.getElementById(wAdminId + "-admin-disablecoupleelections-checkbox");
	_disablecoupleelectionsCheckbox.onchange = function(evt) {saveAdminSettings("disablecoupleelections",(evt.target.checked)); }
	_disablecoupleelectionsCheckbox.checked = _StringToBool(getCookie("disablecoupleelections"));
	
	movePopup(wAdminId,(window.innerWidth / 2) - (wAdminMain.offsetWidth / 2),(window.innerHeight / 2) - (wAdminMain.offsetHeight / 2));
}

function resetAdminPopup() {
	wAdminMain = undefined;
	wAdminHeader = undefined;
	wAdminBody = undefined;
	wAdminInfo = undefined;
	wAdminId = undefined;
}

function searchOnClick() {
	searchUsers(searchinput.value);
}

function searchOnEnter(evt) {
	if (evt.keyCode == 13) { 
		searchUsers(searchinput.value); 
	} 
}

function addPollOnClick() {
	addPoll(document.getElementById(wAdminId + "-poll_add").value);
}

function addPollOnEnter(evt) {
	if (evt.keyCode == 13) { 
		addPoll(evt.target.value);
	}
}

function addNewsOnClick() {
	addNews(document.getElementById(wAdminId + "-news_add").value);
}

function addTeacherElectionOnClick() {
	addTeacherElection(document.getElementById(wAdminId + "-elec_add").value);
}

function addTeacherElectionOnEnter(evt) {
	if (evt.keyCode == 13) {
		addTeacherElection(document.getElementById(wAdminId + "-elec_add").value);
	}
}

function addNewsOnEnter(evt) {
	if (evt.keyCode == 13) { 
		addNews(evt.target.value);
	}
}

function openNewsOnClick() {
	openNews();
}

function saveAdminSettings(key,value) {
	switch(undefined) {
		case key:
			return false;
		case value:
			return false;
	}
	var _toserver = {};
	_toserver["operation"] = "admin";
	_toserver["key"] = key;
	_toserver["value"] = value.toString();
	sendData("data=" + JSON.stringify(_toserver), "api/changesettings.php", check_settings);
}

function check_settings() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error != "success") {
			showError(wAdminId,"Ein unerwarteter Fehler ist aufgetreten");
		} else {
			setCookie(answer.key, answer.value);
		}
		requestReady = true;
	}
}

function openElectionManager() {
	var _popup = openPopup("Umfrage- & Wahlergebnisse",true);
	if (_popup == false) {
		return false;
	}
	wOverviewId = _popup["id"];
	wOverviewMain = _popup["node"];
	wOverviewHeader = document.getElementById(wOverviewId + "-header");
	wOverviewBody = document.getElementById(wOverviewId + "-body");
	wOverviewInfo = document.getElementById(wOverviewId + "-info");
	
	wOverviewMain.id= "overview-main";
	
	wOverviewBody.appendChild(finalizePopupTemplate(wOverviewId,document.getElementById("admin-elec-template")));
	addTabBar(wOverviewId, finalizePopupTemplate(wOverviewId, document.getElementById("admin-elec-template-tabbar")));
	registerTabFuncOnce(wOverviewId, "tab3", loadPollData);
	registerTabFuncOnce(wOverviewId, "tab4", loadCoupleElectionData);
	registerTabFuncOnce(wOverviewId, "tab5", loadTeacherElectionData);
	openFirstTab(wOverviewId);
	movePopup(wOverviewId,(window.innerWidth / 2) - (wOverviewMain.offsetWidth / 2),(window.innerHeight / 2) - (wOverviewMain.offsetHeight / 2));
}

function loadPollData() {
	_toserver = {};
	_toserver["operator"] = "all";
	_toserver["deep"] = 10;
	sendData("data=" + JSON.stringify(_toserver),"api/getpolls.php",displayPolls);
}

function loadCoupleElectionData() {
	_toserver = {};
	_toserver["operator"] = "results";
	sendData("data=" + JSON.stringify(_toserver),"api/getelections.php?type=couple",displayCoupleResults);
}

function loadTeacherElectionData() {
	_toserver = {};
	_toserver["operator"] = "results";
	_toserver["deep"] = 10;
	sendData("data=" + JSON.stringify(_toserver),"api/getelections.php?type=teacher",displayElections);
}

function displayElections() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		var _display = document.getElementById(wOverviewId + "-admin-electionslist");
		answer.forEach(function(item, no) {
			var _element = document.createElement("div");
			_element.className = "admin-elections-name-entry";
			_element.id = "atel-" + item.id;
			_element.onclick = function(evt) {markTElection(evt, item.id);}
			_element.innerHTML = unescape(item.name);
			
			_display.appendChild(_element);
			eResults[item.id] = answer[no];
		});
		if (_display.children.length == 0) {
			var _element = document.createElement("div");
			_element.className = "admin-elections-name-entry";
			_element.innerHTML = "Keine Wahlen eingetragen";
			_display.appendChild(_element);
		}
		requestReady = true;
	}
}

function deleteTeacherElection() {
	if (tActive != undefined && confirm("Sind sie sicher?") == true) {
		var _toserver = {};
		_toserver["delete"] = true;
		_toserver["id"] = tActive;
		sendData("data=" + JSON.stringify(_toserver),"api/changeelection.php",updateElectionList);
	}
}

function updateElectionList() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		document.getElementById("atel-" + answer.id).parentNode.removeChild(document.getElementById("atel-" + answer.id));
		tActive = undefined;
		requestReady = true;
	}
}

function markTElection(evt, id) {
	tActive = id;
	var _list = evt.target.parentNode.children;
	for (var i = 0; i < _list.length; i++) {
		_list[i].className = "admin-elections-name-entry";
	}
	evt.target.className = "admin-elections-name-entry-marked";
	
	for (var i = 1; i <= 10; i++) {
		try {
			var _content = "#" + i + " " + eResults[id].result[i - 1].name + " (" + eResults[id].result[i - 1].votes + ") (" + Math.round(eResults[id].result[i-1].percent * 100) + "%)";
			document.getElementById(wOverviewId + "-admin-elections-result" + i).innerHTML = _content;
		} catch(err) {
			document.getElementById(wOverviewId + "-admin-elections-result" + i).innerHTML = "";
		}
	}
	document.getElementById(wOverviewId + "-admin-elections-votes").innerHTML = "Gesamt: " + eResults[id].votes;
}

function displayCoupleResults() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			for (var i = 1; i <= 10; i++) {
				try {
					var _content = "#" + i + " " + answer.results[i].person1 + " & " + answer.results[i].person2 + " (" + answer.results[i].votes + ") (" + Math.round(answer.results[i].percent * 100) + "%)";
					document.getElementById(wOverviewId + "-admin-couple-result" + i).innerHTML = _content;
				} catch(err) {}
			}
			document.getElementById(wOverviewId + "-admin-couple-votes").innerHTML = "Gesamt: " + answer.votes;
		} else {
			switch (answer.code) {
				case 1:
					showError(wOverviewId,"Ein unerwarteter Fehler ist aufgetreten. (" + answer.error + ")");
					break;
				default:
					showError(wOverviewId,"Ein unbekannter Fehler ist aufgetreten");
					break;
			}
		}
		requestReady = true;
	}
}

function displayPolls() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		var _display = document.getElementById(wOverviewId + "-admin-pollslist");
		answer.forEach(function(item, no) {
			var _element = document.createElement("div");
			_element.className = "admin-elections-name-entry";
			_element.id = "pi-" + item.id;
			_element.onclick = function(evt) {markPoll(evt, item.id);}
			_element.innerHTML = item.name;
			
			_display.appendChild(_element);
			pResults[item.id] = answer[no];
		});
		if (_display.children.length == 0) {
			var _element = document.createElement("div");
			_element.className = "admin-elections-name-entry";
			_element.innerHTML = "Keine Umfragen eingetragen";
			_display.appendChild(_element);
		}
		requestReady = true;
	}
}

function deletePoll() {
	if (pActive != undefined && confirm("Sind sie sicher?") == true) {
		var _toserver = {};
		_toserver["delete"] = true;
		_toserver["id"] = pActive;
		sendData("data=" + JSON.stringify(_toserver),"api/changepoll.php",updatePollList);
	}
}

function updatePollList() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		document.getElementById("pi-" + answer.id).parentNode.removeChild(document.getElementById("pi-" + answer.id));
		pActive = undefined;
		requestReady = true;
	}
}

function markPoll(evt, id) {
	pActive = id;
	var _list = evt.target.parentNode.children;
	for (var i = 0; i < _list.length; i++) {
		_list[i].className = "admin-elections-name-entry";
	}
	evt.target.className = "admin-elections-name-entry-marked";
	
	for (var i = 1; i <= 10; i++) {
		try {
			var _content = "#" + i + " " + pResults[id].result[i - 1].name + " (" + pResults[id].result[i - 1].votes + ") (" + Math.round(pResults[id].result[i - 1].percent * 100) + "%)";
			document.getElementById(wOverviewId + "-admin-polls-result" + i).innerHTML = _content;
		} catch(err) {
			document.getElementById(wOverviewId + "-admin-polls-result" + i).innerHTML = "";
		}
	}
	document.getElementById(wOverviewId + "-admin-polls-votes").innerHTML = "Gesamt: " + pResults[id].votes;
}

function openNews() {
	var _popup = openPopup("News",true);
	if (_popup == false) {
		return false;
	}
	wNewsId = _popup["id"];
	wNewsMain = _popup["node"];
	wNewsHeader = document.getElementById(wNewsId + "-header");
	wNewsBody = document.getElementById(wNewsId + "-body");
	wNewsInfo = document.getElementById(wNewsId + "-info");
	
	wNewsMain.id= "news-main";
	
	wNewsBody.appendChild(finalizePopupTemplate(wNewsId,document.getElementById("news-template")));
	
	getNews();
	movePopup(wNewsId,(window.innerWidth / 2) - (wNewsMain.offsetWidth / 2),(window.innerHeight / 2) - (wNewsMain.offsetHeight / 2));
}

function getNews() {
	sendData("","api/getnews.php",displayNews);
}

function deleteNews(evt) {
	var _removeId = evt.target.parentNode.id.replace("ni","");
	var _removeElement = document.getElementById("ni" + _removeId);
	var _editbutton = document.getElementById("ni" + _removeId + "-editbutton");
	_editbutton.style.display = "block";
	_editbutton.className = "admin-search-element-edit";
	_removeElement.className = "admin-search-element ase-big element-delete";
	addToCache("ni" + _removeId,"delete",true);
	document.getElementById("ni" + _removeId + "-inputcontainer").style.display = "none";
	document.getElementById("ni" + _removeId + "-displaycontainer").innerHTML = "Diese News wird gel&ouml;scht. ";
	_undolink = document.createElement("a");
	_undolink.innerHTML = "<u>R&uuml;ckg&auml;nging machen</u>";
	_undolink.href = "javascript:void(0)";
	_undolink.onclick = function() { UNDOdeleteNews(_removeId); }
	document.getElementById("ni" + _removeId + "-displaycontainer").appendChild(_undolink);
}

function UNDOdeleteNews(id) {
	document.getElementById("ni" + id).className = "admin-search-element ase-big";
	removeFromCache("ni" + id,"delete");
	document.getElementById("ni" + id + "-inputcontainer").style.display = "inline-block";
	document.getElementById("ni" + id + "-displaycontainer").innerHTML = "";
	document.getElementById("ni" + id + "-editbutton").style.display = "none";
}

function applyNewsEdit(evt) {
	var _id = evt.target.parentNode.id.replace("ni","");
	var _toserver = {};
	_toserver = ChangeCache["ni" + _id];
	_toserver["id"] = _id;
	sendData("data=" + JSON.stringify(_toserver),"api/changenews.php",updateNewsView);
	ChangeCache["ni" + _id] = undefined;
}

function updateNewsData(evt) {
	var _id = evt.target.parentNode.parentNode.id.replace("ni","");
	addToCache("ni" + _id,"text",evt.target.value);
	var _editbutton = document.getElementById("ni" + _id + "-editbutton");
	_editbutton.style.display = "block";
	_editbutton.className = "admin-search-element-edit";
}

function updateNewsView() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			if (answer.procedure == "delete") {
				document.getElementById("ni" + answer.id).parentNode.removeChild(document.getElementById("ni" + answer.id));
				if (CurrentUser == false) {
					load_startpage();
				}
			} else {
				document.getElementById("ni" + answer.id + "-editbutton").className = "admin-search-element-edit element-edit-success";
				if (CurrentUser == false) {
					load_startpage();
				}
			}
		}
		requestReady = true;
	}
}

function displayNews() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		document.getElementById(wNewsId + "-news-spinnercontainer").removeChild(document.getElementById(wNewsId + "-news-spinner"));
		var _display = document.getElementById(wNewsId + "-news-search-display");
		_display.style.display = "block";
		if (answer.length > 0) {
			for (var i = 0; i < answer.length; i++) {
				var _element = document.createElement("div");
				_element.className = "admin-search-element ase-big";
				_element.id = "ni" + answer[i].id;
				
				var _displaycontainer = document.createElement("div");
				_displaycontainer.id = "ni" + answer[i].id + "-displaycontainer";
				_displaycontainer.style.color = "#EEE";
				
				var _inputcontainer = document.createElement("div");
				_inputcontainer.id = "ni" + answer[i].id + "-inputcontainer";
				_inputcontainer.className = "admin-search-element-inputcontainer";
				
				var _textinput = document.createElement("textarea");
				_textinput.className = "admin-search-element-textinput";
				_textinput.value = answer[i].text;
				_textinput.onchange = updateNewsData;
				
				var _deletebutton = document.createElement("div");
				_deletebutton.className = "admin-search-element-delete";
				_deletebutton.onclick = deleteNews;
				
				var _editbutton = document.createElement("div");
				_editbutton.className = "admin-search-element-edit";
				_editbutton.style.display = "none";
				_editbutton.id = "ni" + answer[i].id + "-editbutton";
				_editbutton.onclick = applyNewsEdit;
				
				_inputcontainer.appendChild(_textinput);
				_element.appendChild(_displaycontainer);
				_element.appendChild(_inputcontainer);
				_element.appendChild(_deletebutton);
				_element.appendChild(_editbutton);
				
				_display.appendChild(_element);
			}
		} else {
			var _element = document.createElement("div");
			_element.className = "admin-search-element";
			_element.innerHTML = "Keine News gespeichert";
			_display.appendChild(_element);
		}
		requestReady = true;
	}
}

function parseFile(evt) {
	var _fileselector = document.getElementById(wAdminId + "-admin-parse-fileselector");
	var _file = _fileselector.files[0];
	var _reader = new FileReader();
	_reader.onload = uploadFile;
	if (_file != null) {
		if (_file.type == "text/plain") {
			_reader.readAsText(_file);
		}
	}
}

function uploadFile(evt) {
	var _filecontents = evt.target.result;
	if (_filecontents == "") {
		showInfoFile("Die angegebene Datei ist leer.");
		return false;
	}
	var _rows = _filecontents.split("\n");
	var _firstnames = new Array();
	var _lastnames = new Array();
	if (_rows.length < 1) {
		showInfoFile("Die angegebene Datei enthält keine Zeilenumbrüche.");
		return false;
	}
	for (var i = 0; i < _rows.length; i++) {
		if(_rows[i] == "") continue;
		var _name = _rows[i].split(":");
		_firstnames[i] =_name[0];
		_lastnames[i] = _name[1].replace("\r","");
	}
	if (_firstnames.length < 1 || _lastnames.length < 1) {
		showInfoFile("Die angegebene Datei enthält keine richtig formatierten Namen.");
		return false;
	}
	var _toserver = {};
	_toserver["firstnames"] = _firstnames;
	_toserver["lastnames"] = _lastnames;
	sendData("data=" + JSON.stringify(_toserver),"api/addusers.php",showInfoFile);
}

function showInfoFile(str) {
	if (typeof(str) == "string") {
		var _textarea = document.getElementById(wAdminId + "-admin-parse-textarea");
		_textarea.style.display = "block";
		_textarea.value = str;
		_rows = strCountRows(str);
		if (_rows < 20) {
			_textarea.rows = _rows;
		} else {
			_textarea.rows = 20;
		}
	} else {
		if (request.readyState == 4) {
			try {
				var answer = JSON.parse(request.responseText);
			} catch(err) {
				requestReady = true;
				return false;
			}
			var _textarea = document.getElementById(wAdminId + "-admin-parse-textarea");
			_textarea.style.display = "block";
			var _output = "";
			for (var i = 0; i < answer.firstnames.length; i++) {
				_output += answer.firstnames[i].replace(/(\r\n|\n|\r)/gm,"") + " : " + answer.lastnames[i].replace(/(\r\n|\n|\r)/gm,"") + " : " + answer.usernames[i].replace(/(\r\n|\n|\r)/gm,"") + " - " + answer.passwords[i].replace(/(\r\n|\n|\r)/gm,"") + "\n";
			}
			_textarea.innerHTML = _output;
			_rows = strCountRows(_textarea.value);
			if (_rows < 20) {
				_textarea.rows = _rows;
			} else {
				_textarea.rows = 20;
			}
			requestReady = true;
			load_nameslist();
		}
	}
}
function addPoll(name) {
	if (name == "") {
		return false;
	}
	_toserver = {};
	_toserver["name"] = name;
	sendData("data=" + JSON.stringify(_toserver),"api/addpoll.php",checkPoll);
}

function checkPoll() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error != "success") {
			showError(wAdminId,"Ein unerwarteter Fehler ist aufgetreten");
		} else {
			showSuccess(wAdminId,"Umfrage erfolgreich hinzugef&uuml;gt");
			if (CurrentUser != false) {
				load_polls(CurrentUser);
			}
		}
		requestReady = true;
	}
}

function addNews(text) {
	if (text == "") {
		return false;
	}
	_toserver = {};
	_toserver["text"] = encodeURIComponent(text.replace(new RegExp("'","g"),"&lsquo;").replace(new RegExp('"',"g"),"&rdquo;").replace(new RegExp("\n","g"),"<br/>"));
	sendData("data=" + JSON.stringify(_toserver),"api/addnews.php",checkNews);
}

function checkNews() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error != "success") {
			switch(answer.code) {
				case 1:
					showError(wAdminId,"Ein unerwarteter Fehler ist aufgetreten (" + answer.error + ")");
					break;
				default:
					showError(wAdminId,"Ein unbekannter Fehler ist aufgetreten");
					break;
			}
		} else {
			showSuccess(wAdminId,"News erfolgreich hinzugef&uuml;gt");
			if (CurrentUser == false) {
				load_startpage();
			}
		}
		requestReady = true;
	}
}

function addTeacherElection(name) {
	if (name == "") {
		return false;
	}
	_toserver = {};
	_toserver["name"] = name;
	sendData("data=" + JSON.stringify(_toserver),"api/addelection.php",checkTeacherElection);
}

function checkTeacherElection() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error != "success") {
			showError(wAdminId,"Ein unerwarteter Fehler ist aufgetreten (" + answer.error + ")");
		} else {
			showSuccess(wAdminId,"Wahl erfolgreich hinzugef&uuml;gt");
			if (CurrentUser == false) {
				load_startpage();
			}
		}
		requestReady = true;
	}
}

function addUser() {
	var _display = document.getElementById(wAdminId + "-info-display");
	var _firstname = new_firstnameinput.value;
	var _lastname = new_lastnameinput.value;
	switch ("") {
		case _firstname:
			return false;
		case _lastname:
			return false;
	}
	_display.style.display = "block";
	_display.appendChild(addAdminSpinner());
	var _toserver = {};
	_toserver["firstnames"] = new Array();
	_toserver["lastnames"] = new Array();
	_toserver["firstnames"][0] = _firstname;
	_toserver["lastnames"][0] = _lastname;
	sendData("data=" + JSON.stringify(_toserver),"api/addusers.php",checkUser);
}

function checkUser() {
	if (request.readyState == 4) {
		var _display = document.getElementById(wAdminId + "-info-display");
		removeAdminSpinner(_display);
		_display.innerHTML = "";
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error != "success") {
			showError(wAdminId,"Ein unerwarteter Fehler ist aufgetreten");
		}
		_display.innerHTML = "Benutzername: " + answer.usernames[0] + "<br/>" + "Passwort: " + answer.passwords[0];
		requestReady = true;
		load_nameslist();
	}
}

function addToCache(id,key,value) {
	if (ChangeCache[id] == undefined) {
		ChangeCache[id] = {};
	}
	ChangeCache[id][key] = value;
}

function removeFromCache(id,key) {
	if (ChangeCache[id] == undefined) {
		return;
	}
	ChangeCache[id][key] = undefined;
}

function searchUsers(searchTerms) {
	if (searchTerms == "") {
		return false;
	}
	_toserver = {};
	_toserver["search"] = searchTerms;
	sendData("data=" + JSON.stringify(_toserver),"api/getusers.php",displayResults);
}

function displayResults() {
	if (request.readyState == 4) {
		try {
			var resultlist = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (resultlist.length > 0) {
			var _display = document.getElementById(wAdminId + "-admin-search-display");
			_display.innerHTML = "";
			_display.style.display = "block";
			hideInfo(wAdminId,0);
			for (var i = 0; i < resultlist.length; i++) {
				_element = document.createElement("div");
				_element.className = "admin-search-element";
				_element.id = resultlist[i].id;
				
				_displaycontainer = document.createElement("span");
				_displaycontainer.id = resultlist[i].id + "-displaycontainer";
				_displaycontainer.style.color = "#EEE";
				
				_inputcontainer = document.createElement("span");
				_inputcontainer.id = resultlist[i].id + "-inputcontainer";
	
				_firstnameinput = document.createElement("input");
				_firstnameinput.className = "admin-search-element-nameinput";
				_firstnameinput.size = resultlist[i].firstname.length - 2;
				_firstnameinput.value = resultlist[i].firstname;
				_firstnameinput.onchange = function(evt) {updateAdminSettings(evt,0);}
				
				_lastnameinput = document.createElement("input");
				_lastnameinput.className = "admin-search-element-nameinput";
				_lastnameinput.size = resultlist[i].lastname.length - 2;
				_lastnameinput.value = resultlist[i].lastname;
				_lastnameinput.onchange = function(evt) {updateAdminSettings(evt,1);}
				
				_usernameinput = document.createElement("input");
				_usernameinput.className = "admin-search-element-nameinput";
				_usernameinput.size = resultlist[i].username.length - 2;
				_usernameinput.value = resultlist[i].username;
				_usernameinput.onchange = function(evt) {updateAdminSettings(evt,2);}
				
				if (resultlist[i].defaultpassword != "") {
					_defpwinput = document.createElement("input");
					_defpwinput.className = "admin-search-element-nameinput";
					_defpwinput.size = resultlist[i].username.length - 2;
					_defpwinput.readOnly = "readonly";
					_defpwinput.value = resultlist[i].defaultpassword;
				}
				
				_deletebutton = document.createElement("div");
				_deletebutton.className = "admin-search-element-delete";
				_deletebutton.onclick = deleteUser;
				
				_editbutton = document.createElement("div");
				_editbutton.className = "admin-search-element-edit";
				_editbutton.style.display = "none";
				_editbutton.id = resultlist[i].id + "-editbutton";
				_editbutton.onclick = applySettings;
				
				_inputcontainer.appendChild(_firstnameinput);
				_inputcontainer.appendChild(_lastnameinput);
				_inputcontainer.appendChild(_usernameinput);
				_inputcontainer.appendChild(_defpwinput);
				_element.appendChild(_displaycontainer);
				_element.appendChild(_inputcontainer);
				_element.appendChild(_deletebutton);
				_element.appendChild(_editbutton);
				
				_display.appendChild(_element);
			}
		} else {
			var _display = document.getElementById(wAdminId + "-admin-search-display");
			_display.innerHTML = "";
			_display.style.display = "none";
			showError(wAdminId,"Keine Ergebnisse gefunden");
		}
		if (WebKitDetect.isMobile()) {movePopup(wAdminId,(window.innerWidth / 2) - (wAdminMain.offsetWidth / 2),(window.innerHeight / 2) - (wAdminMain.offsetHeight / 2));}
		requestReady = true;
	}
}

function applySettings(evt) {
	var _id = evt.target.parentNode.id;
	var _toserver = {};
	_toserver = ChangeCache[_id];
	_toserver["id"] = _id;
	sendData("data=" + JSON.stringify(_toserver),"api/changeuser.php",updateView);
	ChangeCache[_id] = undefined;
}

function updateView() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			if (answer.procedure == "delete") {
				document.getElementById(answer.id).parentNode.removeChild(document.getElementById(answer.id));
			} else {
				document.getElementById(answer.id + "-editbutton").className = "admin-search-element-edit element-edit-success";
			}
		}
		requestReady = true;
		load_nameslist();
	}
}

function updateAdminSettings(evt, no) {
	var _id = evt.target.parentNode.parentNode.id;
	switch (no) {
		case 0:
			addToCache(_id,"firstname",evt.target.value);
			break;
		case 1:
			addToCache(_id,"lastname",evt.target.value);
			break;
		case 2:
			addToCache(_id,"username",evt.target.value);
			break;
		default:
			return false;
	}
	var _editbutton = document.getElementById(_id + "-editbutton");
	_editbutton.style.display = "block";
	_editbutton.className = "admin-search-element-edit";
}

function deleteUser(evt) {
	var _removeId = evt.target.parentNode.id;
	var _removeElement = document.getElementById(_removeId);
	var _editbutton = document.getElementById(_removeId + "-editbutton");
	_editbutton.style.display = "block";
	_removeElement.className = "admin-search-element element-delete";
	addToCache(_removeId,"delete",true);
	document.getElementById(_removeId + "-inputcontainer").style.display = "none";
	document.getElementById(_removeId + "-displaycontainer").innerHTML = "Dieser Benutzer wird gel&ouml;scht. ";
	_undolink = document.createElement("a");
	_undolink.innerHTML = "<u>R&uuml;ckg&auml;nging machen</u>";
	_undolink.href = "#";
	_undolink.onclick = function() { UNDOdeleteUser(_removeId); }
	document.getElementById(_removeId + "-displaycontainer").appendChild(_undolink);
}

function UNDOdeleteUser(id) {
	document.getElementById(id).className = "admin-search-element";
	removeFromCache(id,"delete");
	document.getElementById(id + "-inputcontainer").style.display = "inline";
	document.getElementById(id + "-displaycontainer").innerHTML = "";
	document.getElementById(id + "-displaycontainer").display = "none";
}

function addAdminPageSpinner() {
	_spinner = document.createElement("div");
	_spinner.className = "spinner_3";
	_spinner.id = "adminpage-spinner";
	_spinner.innerHTML = "&nbsp;";
	return _spinner;
}

function removeAdminPageSpinner(container) {
	container.removeChild(document.getElementById("adminpage-spinner"));
}

function addAdminSpinner() {
	_spinner = document.createElement("div");
	_spinner.className = "spinner_3";
	_spinner.id = "admin-spinner";
	return _spinner;
}

function removeAdminSpinner(container) {
	container.removeChild(document.getElementById("admin-spinner"));
}