var wSettMain, wSettHeader, wSettBody, wSettInfo, wSettId;
var oldpwinput, newpwinput, retpwinput, fbinput;
/*var wSettings;

function Settings() {
	var wSettPopup = new Popup();
	wSettPopup.setTemplate(document.getElementById("popup-template"));
	
	this.open = function() {
		wSettPopup.open("Einstellungen");
		wSettPopup.getdomParts().body.appendTemplate(document.getElementById("settings-loading-template"));
		wSettPopup.getdomNode().id = "settings-main";
		console.log(wSettPopup.getdomNode());
	}
	
	this.getPopup = function() {
		return wSettPopup;
	}
}

function openSettings() {
	wSettings = new Settings();
	wSettings.open();
	wSettings.getPopup().movePopup((window.innerWidth / 2) - (wSettings.getPopup().getdomNode().offsetWidth / 2),(window.innerHeight / 2) - (wSettings.getPopup().getdomNode().offsetHeight / 2));
}*/

function openSettings() {
	_popup = openPopup("Einstellungen",true);
	if (_popup == false) {
		return false;
	}
	wSettId = _popup["id"];
	registerReset(wSettId,resetSettingsPopup);
	wSettMain = _popup["node"];
	wSettHeader = document.getElementById(wSettId + "-header");
	wSettBody = document.getElementById(wSettId + "-body");
	wSettInfo = document.getElementById(wSettId + "-info");
	
	wSettMain.id = "settings-main";
	
	wSettBody.appendChild(finalizePopupTemplate(wSettId,document.getElementById("settings-loading-template")));
	
	getSettings();
	movePopup(wSettId,(window.innerWidth / 2) - (wSettMain.offsetWidth / 2),(window.innerHeight / 2) - (wSettMain.offsetHeight / 2));
}

function resetSettingsPopup() {
	wSettMain = undefined;
	wSettHeader = undefined;
	wSettBody = undefined;
	wSettInfo = undefined;
	wSettId = undefined;
}

function getSettings() {
	sendData("","api/getsettings.php",updateSettings);
}

function updateSettings() {
	if (request.readyState == 4) {
		wSettBody.innerHTML = "";
		wSettBody.appendChild(finalizePopupTemplate(wSettId,document.getElementById("settings-template")));
		movePopup(wSettId,(window.innerWidth / 2) - (wSettMain.offsetWidth / 2),(window.innerHeight / 2) - (wSettMain.offsetHeight / 2));
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			oldpwinput = document.getElementById(wSettId + "-input_oldpw");
			newpwinput = document.getElementById(wSettId + "-input_newpw");
			retpwinput = document.getElementById(wSettId + "-input_retpw");
			picinput = document.getElementById(wSettId + "-input_pic");
			
			var _parallaxCheckbox = document.getElementById(wSettId + "-settings-parallax-checkbox");
			_parallaxCheckbox.onchange = function(evt) {saveSetting("disParScr",(!evt.target.checked)); loadSettings();}
			_parallaxCheckbox.checked = !getSetting("disParScr");
			
			var _keepaliveCheckbox = document.getElementById(wSettId + "-settings-keepalive-checkbox");
			_keepaliveCheckbox.onchange = function(evt) {saveSetting("disKeepAlive",(!evt.target.checked)); loadSettings();}
			_keepaliveCheckbox.checked = !getSetting("disKeepAlive");
			
			var _jssearchCheckbox = document.getElementById(wSettId + "-settings-jssearch-checkbox");
			_jssearchCheckbox.onchange = function(evt) {saveSetting("disJsSearch",(!evt.target.checked)); loadSettings();}
			_jssearchCheckbox.checked = !getSetting("disJsSearch");
			
			var _hwaccwinCheckbox = document.getElementById(wSettId + "-settings-hwaccwin-checkbox");
			if (WebKitDetect.isWebKit()) {
				_hwaccwinCheckbox.onchange = function(evt) {saveSetting("enHwAccWin",(evt.target.checked)); loadSettings();}
				_hwaccwinCheckbox.checked = getSetting("enHwAccWin");
			} else {
				_hwaccwinCheckbox.style.display = "none";
				document.getElementById(wSettId + "-settings-hwaccwin-label").style.display = "none";
			}
			
			picinput.value = (answer.pic == undefined) ? "" : answer.pic;
		} else {
			switch (answer.code) {
				case 1:
					showError(wSettId,"Ein unerwarteter Fehler ist aufgetreten. (" + answer.error + ")");
					break;
				default:
					showError(wSettId,"Ein unbekannter Fehler ist aufgetreten");
					break;
			}
		}
		requestReady = true;
	} 
}

function saveOnEnter(evt) { 
	if (evt.keyCode == 13) { 
		savePW(); 
	} 
}

function savePW() {
	document.getElementById(wSettId + "-settings-spinner-container").appendChild(addSettingsSpinner());
	var _oldpw = oldpwinput.value;
	var _newpw = newpwinput.value;
	var _retpw = retpwinput.value;
	switch ("") {
		case _oldpw:
			removeSettingsSpinner(document.getElementById(wSettId + "-settings-spinner-container"));
			return false;
		case _newpw:
			removeSettingsSpinner(document.getElementById(wSettId + "-settings-spinner-container"));
			return false;
		case _retpw:
			removeSettingsSpinner(document.getElementById(wSettId + "-settings-spinner-container"));
			return false;
	}
	if (_newpw != _retpw) {
		removeSettingsSpinner(document.getElementById(wSettId + "-settings-spinner-container"));
		showError(wSettId,"Die neuen Passw&ouml;rter stimmen nicht &uuml;berein.");
		return false;
	}
	var _toserver = {};
	_toserver["operation"] = "password";
	_toserver["oldpw"] = _oldpw;
	_toserver["newpw"] = _newpw;
	_toserver["retpw"] = _retpw;
	sendData("data=" + JSON.stringify(_toserver),"api/changesettings.php",checkPW);
}

function checkPW() {
	if (request.readyState == 4) {
		removeSettingsSpinner(document.getElementById(wSettId + "-settings-spinner-container"));
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			showSuccess(wSettId,"Passwort erfolgreich ge&auml;ndert.");
		} else {
			switch (answer.code) {
				case 2:
					showError(wSettId,"Das aktuelle Passwort wurde falsch eingegeben.");
					break;
				case 1:
					showError(wSettId,"Ein unerwarteter Fehler ist aufgetreten. (" + answer.error + ")");
					break;
				default:
					showError(wSettId,"Ein unbekannter Fehler ist aufgetreten");
					break;
			}
		}
		requestReady = true;
	}
}

function savePic() {
	document.getElementById(wSettId + "-settings-spinner-container-2").appendChild(addSettingsSpinner());
	var _pic = picinput.value;
	
	var _toserver = {};
	_toserver["operation"] = "pic";
	_toserver["pic"] = _pic;
	sendData("data=" + JSON.stringify(_toserver),"api/changesettings.php",checkPic);
}

function checkPic() {
	if (request.readyState == 4) {
		removeSettingsSpinner(document.getElementById(wSettId + "-settings-spinner-container-2"));
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			console.log(request.responseText);
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			showSuccess(wSettId,"Profilbild Einstellungen erfolgreich ge&auml;ndert.");
		} else {
			switch (answer.code) {
				case 3:
					showError(wSettId,"Ein unerwarteter Fehler ist aufgetreten. (" + answer.error + ")");
					break;
				default:
					showError(wSettId,"Ein unbekannter Fehler ist aufgetreten");
					break;
			}
		}
		requestReady = true;
	}
}

function addSettingsSpinner() {
	_spinner = document.createElement("div");
	_spinner.className = "spinner_3";
	_spinner.id = "settings-spinner";
	_spinner.innerHTML = "&nbsp;";
	return _spinner;
}

function removeSettingsSpinner(container) {
	container.removeChild(document.getElementById("settings-spinner"));
}