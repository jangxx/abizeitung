var wDescMain, wDescHeader, wDescBody, wDescInfo, wDescId;

function openDescription() {
	var _popup = openPopup("Steckbrief",true);
	if (_popup == false) {
		return false;
	}
	wDescId = _popup["id"];
	registerReset(wDescId,resetDescPopup);
	wDescMain = _popup["node"];
	wDescHeader = document.getElementById(wDescId + "-header");
	wDescBody = document.getElementById(wDescId + "-body");
	wDescInfo = document.getElementById(wDescId + "-info");
	
	wDescMain.id= "desc-main";
	
	wDescBody.appendChild(finalizePopupTemplate(wDescId,document.getElementById("desc-loading-template")));
	
	getDesc();
	movePopup(wDescId,(window.innerWidth / 2) - (wDescMain.offsetWidth / 2),(window.innerHeight / 2) - (wDescMain.offsetHeight / 2));
}

function resetDescPopup() {
	wDescMain = undefined;
	wDescHeader = undefined;
	wDescBody = undefined;
	wDescInfo = undefined;
	wDescId = undefined;
}

function getDesc() {
	sendData("","api/getdescription.php",updateDesc);
}

function updateDesc() {
	if (request.readyState == 4) {
		wDescBody.innerHTML = "";
		wDescBody.appendChild(finalizePopupTemplate(wDescId,document.getElementById("desc-template")));
		movePopup(wDescId,(window.innerWidth / 2) - (wDescMain.offsetWidth / 2),(window.innerHeight / 2) - (wDescMain.offsetHeight / 2));
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			document.getElementById(wDescId + "-input_nickname").value = _fix(answer.nn);
			document.getElementById(wDescId + "-input_lifemotto").value = _fix(answer.lm);
			for (var i = 1; i <= 4; i++) {
				document.getElementById(wDescId + "-input_ac" + i).value = _fix(answer["a" + i]);
			}
			selectRadio(wDescId + "-desc-g89-radio",answer.g89);
			document.getElementById(wDescId + "-input_dayob").value = _fix(answer.dob);
			document.getElementById(wDescId + "-input_monthob").value = _fix(answer.mob);
			document.getElementById(wDescId + "-input_yearob").value = _fix(answer.yob);
			for (var i = 1; i <= 5; i++) {
				document.getElementById(wDescId + "-input_ld" + i).value = _fix(answer["ld" + i]);
				document.getElementById(wDescId + "-input_l" + i).value = _fix(answer["l" + i]);
			}
			for (var i = 0; i < answer.fp.length; i++) {
				_futureplans = document.getElementsByName(wDescId + "-futureplan");
				_futureplans[i].value = answer.fp[i + 1];
				if (i < answer.fp.length -1) {
					addFuturePlanField();
				}
			}
			document.getElementById(wDescId + "-input_aboutme").value = _fix(answer.abm).replace(new RegExp("<br/>","g"),"\n").replace(new RegExp("&lsquo;","g"),"'").replace(new RegExp('&rdquo;',"g"),'"');
		} else {
			switch (answer.code) {
				case 1:
					showError(wDescId,"Ein unerwarteter Fehler ist aufgetreten. (" + answer.error + ")");
					break;
				default:
					showError(wDescId,"Ein unbekannter Fehler ist aufgetreten");
					break;
			}
		}
		requestReady = true;
	} 
}

function _fix(string) {
	return (string != undefined) ? string : "";
}

function saveDesc() {
	document.getElementById(wDescId + "-desc-spinner-container").appendChild(addDescSpinner());
	var _toserver = {};
	_toserver["nn"] = encodeURIComponent(document.getElementById(wDescId + "-input_nickname").value);
	_toserver["lm"] = encodeURIComponent(document.getElementById(wDescId + "-input_lifemotto").value);
	for (var i = 1; i <= 4; i++) {
		_toserver["a" + i] = encodeURIComponent(document.getElementById(wDescId + "-input_ac" + i).value);
	}
	_toserver["g89"] = getSelectedRadio(wDescId + "-desc-g89-radio");
	_toserver["dob"] = encodeURIComponent(document.getElementById(wDescId + "-input_dayob").value);
	_toserver["mob"] = encodeURIComponent(document.getElementById(wDescId + "-input_monthob").value);
	_toserver["yob"] = encodeURIComponent(document.getElementById(wDescId + "-input_yearob").value);
	for (var i = 1; i <= 5; i++) {
		_toserver["ld" + i] = encodeURIComponent(document.getElementById(wDescId + "-input_ld" + i).value);
		_toserver["l" + i] = encodeURIComponent(document.getElementById(wDescId + "-input_l" + i).value);
	}
	_toserver["fp"] = new Array();
	var _futureplans = document.getElementsByName(wDescId + "-futureplan");
	for (var i = 0; i < _futureplans.length; i++) {
		_toserver["fp"].push(encodeURIComponent(_futureplans[i].value));
	}
	_toserver["abm"] = encodeURIComponent(document.getElementById(wDescId + "-input_aboutme").value.replace(new RegExp("'","g"),"&lsquo;").replace(new RegExp('"',"g"),"&rdquo;").replace(new RegExp("\n","g"),"<br/>"));
	sendData("data=" + JSON.stringify(_toserver),"api/changedescription.php",checkDesc);
}

function checkDesc(evt) {
	if (request.readyState == 4) {
		removeDescSpinner(document.getElementById(wDescId + "-desc-spinner-container"));
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			showSuccess(wDescId,"Steckbrief erfolgreich gespeichert.");
		} else {
			switch (answer.code) {
				case 1:
					showError(wDescId,"Ein unerwarteter Fehler ist aufgetreten (" + answer.error + ")");
					break;
				case 2:
					showError(wDescId,"Steckbriefänderungen sind deaktiviert");
					break;
				default:
					showError(wDescId,"Ein unbekannter Fehler ist aufgetreten");
					break;
			}
		}
		requestReady = true;
	} 
}

function addFuturePlanField(content) {
	var _collection = document.getElementById(wDescId + "-desc-futureplan-collection");
	if (_collection.childNodes.length > 1) {
		var _id = _collection.childNodes[_collection.childNodes.length - 1].id.replace("fpi","")/1 + 1;
	} else {
		var _id = 1;
	}
	if (_collection.childNodes.length > 10) {
		return false;
	}
	var _container = document.createElement("div");
	_container.className = "desc-futureplan";
	_container.id = "fpi" + _id;
	
	var _input = document.createElement("div");
	_input.className = "input desc-fpa";
	_input.style.width = "90%";
	
	var _inputfield = document.createElement("input");
	_inputfield.className = "input-field";
	_inputfield.id = wDescId + "-input_f" + _id;
	_inputfield.type = "text";
	_inputfield.name = wDescId + "-futureplan";
	_inputfield.placeholder = "Zukunftsplan";
	_inputfield.onkeypress = nextOnEnter;
	_inputfield.value = (content != undefined) ? content : "";
	
	_input.appendChild(_inputfield);
	
	var _removebutton = document.createElement("div");
	_removebutton.className = "desc-mini-button desc-fpa";
	_removebutton.onclick = function(evt) {removeFutureplan(evt.target.parentNode.id.replace("fpi","")/1);}
	_removebutton.innerHTML = "-";
	_removebutton.style.marginLeft = "10px";
	
	_container.appendChild(_input);
	_container.appendChild(_removebutton);
	
	_collection.appendChild(_container);
}

function removeFutureplan(id) {
	document.getElementById("fpi" + id).parentNode.removeChild(document.getElementById("fpi" + id));
}

function addDescSpinner() {
	_spinner = document.createElement("div");
	_spinner.className = "spinner_3";
	_spinner.id = "desc-spinner";
	_spinner.innerHTML = "&nbsp;";
	return _spinner;
}

function removeDescSpinner(container) {
	container.removeChild(document.getElementById("desc-spinner"));
}