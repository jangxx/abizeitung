var wElecMain, wElecHeader, wElecBody, wElecInfo, wElecId;
var vPerson1 = 0, vPerson2 = 0, vPersons = {};
var tResults = {}, curElec = -1, curTeacher = -1, tImages = {};
var tSaveButton = false, cSaveButton = true;

function openElections() {
	_popup = openPopup("Wahlen",true);
	if (_popup == false) {
		return false;
	}
	wElecId = _popup["id"];
	registerReset(wElecId,resetElectionsPopup);
	wElecMain = _popup["node"];
	wElecHeader = document.getElementById(wElecId + "-header");
	wElecBody = document.getElementById(wElecId + "-body");
	wElecInfo = document.getElementById(wElecId + "-info");
	
	wElecMain.id = "elections-main";
	
	wElecBody.appendChild(finalizePopupTemplate(wElecId,document.getElementById("elections-template")));
	addTabBar(wElecId, finalizePopupTemplate(wElecId, document.getElementById("elections-template-tabbar")));
	registerTabFuncOnce(wElecId, "tab1", loadCoupleData);
	registerTabFuncOnce(wElecId, "tab2", loadTeacherData);
	openFirstTab(wElecId);

	movePopup(wElecId,(window.innerWidth / 2) - (wElecMain.offsetWidth / 2),(window.innerHeight / 2) - (wElecMain.offsetHeight / 2));
}

function resetElectionsPopup() {
	wElecMain = undefined;
	wElecHeader = undefined;
	wElecBody = undefined;
	wElecInfo = undefined;
	wElecId = undefined;
}

function loadCoupleData() {
	sendData("","api/getelections.php?type=couple",update_couple);
}

function update_couple() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		var nameslist1 = document.getElementById(wElecId + "-elections-couple-nameslist1");
		var nameslist2 = document.getElementById(wElecId + "-elections-couple-nameslist2");
		
		var searchItem = document.createElement("div");
		searchItem.className = "elections-name-search-entry";
		searchItemInput = document.createElement("input");
		searchItemInput.type = "text";
		searchItemInput.placeholder = "Suchen...";
		searchItemInput.className = "input-field elections-name-search-input";
		searchItemInput.onkeyup = searchElectionNameOnChange;
		searchItem.appendChild(searchItemInput);
		
		var searchItem2 = document.createElement("div");
		searchItem2.className = "elections-name-search-entry";
		searchItemInput2 = document.createElement("input");
		searchItemInput2.type = "text";
		searchItemInput2.placeholder = "Suchen...";
		searchItemInput2.className = "input-field elections-name-search-input";
		searchItemInput2.onkeyup = searchElectionNameOnChange;
		searchItem2.appendChild(searchItemInput2);
		
		nameslist1.appendChild(searchItem);
		nameslist2.appendChild(searchItem2);
		for (var i = 0; i < answer.users.length; i++) {
			var item1 = document.createElement("div");
			item1.className = "elections-name-entry";
			item1.id = "el1-" + answer.users[i].id;
			item1.innerHTML = answer.users[i].fullname;
			item1.onclick = markName;
			nameslist1.appendChild(item1);
			var item2 = document.createElement("div");
			item2.className = "elections-name-entry";
			item2.id = "el2-" + answer.users[i].id;
			item2.innerHTML = answer.users[i].fullname;
			item2.onclick = markName;
			nameslist2.appendChild(item2);
		}
		if (answer.results.person1 != null) {
			vPerson1 = answer.results.person1;
			vPersons["person1"] = answer.results.person1;
			markNameByUID("el1-", answer.results.person1, true);
			document.getElementById(wElecId + "-elections-couple-p1").innerHTML = getNameByUID("el1-", answer.results.person1);
		} else {
			document.getElementById(wElecId + "-elections-couple-p1").innerHTML = "<< auswählen";
		}
		if (answer.results.person2 != null) {
			vPerson2 = answer.results.person2;
			vPersons["person2"] = answer.results.person2;
			markNameByUID("el2-", answer.results.person2, true);
			document.getElementById(wElecId + "-elections-couple-p2").innerHTML = getNameByUID("el2-", answer.results.person2);
		} else {
			document.getElementById(wElecId + "-elections-couple-p2").innerHTML = "auswählen >>";
		}
		if (vPerson1 == vPersons["person1"] && vPerson2 == vPersons["person2"]) {
			document.getElementById(wElecId + "-elections-couple-savebutton").className = "button_1 button_disabled";
			cSaveButton = false;
		}
		requestReady = true;
	}
}

function loadTeacherData() {
	sendData("","api/getelections.php?type=teacher",update_teachers);
}

/*
<div class="name-entry" id="name-search-entry">
	<input type="text" placeholder="Suchen..." class="input-field" id="name-search-entry-input" onkeypress="searchNameOnEnter(event)">
</div>
*/

function update_teachers() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		var electionslist = document.getElementById(wElecId + "-elections-teacher-elections");
		var teachernameslist = document.getElementById(wElecId + "-elections-teacher-nameslist");
		for (var i = 0; i < answer.results.length; i++) {
			tResults[answer.results[i].election] = answer.results[i].teacher;
		}
		for (var i = 0; i < answer.elections.length; i++) {
			var eitem = document.createElement("div");
			eitem.className = "elections-name-entry";
			if (tResults[answer.elections[i].id] != undefined) eitem.style.color = "#1EBA2B";
			eitem.id = "tel-" + answer.elections[i].id;
			eitem.innerHTML = unescape(answer.elections[i].name);
			eitem.onclick = markTeacher;
			electionslist.appendChild(eitem);
		}
		for (var i = 0; i < answer.teachers.length; i++) {
			var titem = document.createElement("div");
			titem.className = "elections-name-entry";
			titem.id = "tna-" + answer.teachers[i].id;
			titem.innerHTML = answer.teachers[i].fullname;
			titem.onclick = markTeacher;
			teachernameslist.appendChild(titem);
			tImages[answer.teachers[i].id] = answer.teachers[i].image;
		}
		if (electionslist.children.length == 0) {
			var _element = document.createElement("div");
			_element.className = "elections-name-entry";
			_element.innerHTML = "Keine Wahlen eingetragen";
			electionslist.appendChild(_element);
		}
		requestReady = true;
	}
}

function searchElectionNameOnChange(evt) {
	search_elec_name_js(evt.target.parentNode.parentNode, evt.target.value);
}

function search_elec_name_js(obj, searchTerms) {
	var list = obj.children;
	for (var i = 0; i < list.length; i++) {
		if (searchTerms != "") {
			if (list[i].innerHTML.toLowerCase().indexOf(searchTerms.toLowerCase()) == -1 && list[i].className != "elections-name-search-entry") {
				list[i].style.display = "none";
			} else {
				list[i].style.display = "block";
			}
		} else {
			list[i].style.display = "block";
		}
	}
}

function saveTeacherVote() {
	if (curTeacher == -1 || curElec == -1 || tSaveButton == false) {
		return;
	}
	var _toserver = {};
	_toserver["teacher_id"] = curTeacher;
	_toserver["elec_id"] = curElec;
	_toserver["type"] = "teacher";
	sendData("data=" + JSON.stringify(_toserver),"api/voteelection.php",checkTeacherVote);
}

function checkTeacherVote() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			tResults[answer.result.elec] = answer.result.teacher;
			document.getElementById("tel-" + answer.result.elec).style.color = "#1EBA2B";
			 if (tResults[curElec] != curTeacher) {
				document.getElementById(wElecId + "-elections-teacher-savebutton").className = "button_1";
				tSaveButton = true;
			} else {
				tSaveButton = false;
				document.getElementById(wElecId + "-elections-teacher-savebutton").className = "button_1 button_disabled";
			}
			showSuccess(wElecId,"Wahl erfolgreich gespeichert");
		} else {
			switch (answer.code) {
				case 1:
					showError(wElecId,"Ein unerwarteter Fehler ist aufgetreten (" + answer.error + ")");
					break;
				case 2:
					showError(wElecId,"Keine Wahl angegeben");
					break;
				case 3:
					showError(wElecId,"Kein Lehrer angegeben");
					break;
				case 4:
					showError(wElecId,"Lehrerwahlen sind deaktiviert");
					break;
				default:
					showError(wElecId,"Ein unbekannter Fehler ist aufgetreten");
					break;
			}
		}
		requestReady = true;
	}
}

function saveCoupleVote() {
	if (cSaveButton == false) return;
	switch (0) {
		case vPerson1:
			showError(wSettId,"Keine erste Person angegeben.");
			break;
		case vPerson2:
			showError(wSettId,"Keine zweite Person angegeben.");
			break;
	}
	var _toserver = {};
	_toserver["person1"] = vPerson1;
	_toserver["person2"] = vPerson2;
	_toserver["type"] = "couple";
	sendData("data=" + JSON.stringify(_toserver),"api/voteelection.php",checkCoupleVote);
}

function checkCoupleVote() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			showSuccess(wElecId,"Wahl erfolgreich gespeichert.");
			vPersons["person1"] = answer.result.person1;
			vPersons["person2"] = answer.result.person2;
			if (vPerson1 == vPersons["person1"] && vPerson2 == vPersons["person2"]) {
				document.getElementById(wElecId + "-elections-couple-savebutton").className = "button_1 button_disabled";
				cSaveButton = false;
			} else {
				document.getElementById(wElecId + "-elections-couple-savebutton").className = "button_1";
				cSaveButton = true;
			}
		} else {
			switch (answer.code) {
				case 1:
					showError(wElecId,"Ein unerwarteter Fehler ist aufgetreten. (" + answer.error + ")");
					break;
				case 2:
					showError(wElecId,"Keine erste Person angegeben.");
					break;
				case 3:
					showError(wElecId,"Keine zweite Person angegeben.");
					break;
				case 4:
					showError(wElecId,"Stufenpärchenwahlen sind deaktiviert");
					break;
				default:
					showError(wElecId,"Ein unbekannter Fehler ist aufgetreten");
					break;
			}
		}
		requestReady = true;
	}
}

function markTeacher(evt) {
	var list = evt.target.parentNode.children;
	for (var i = 0; i < list.length; i++) {
		if (list[i].className != "elections-name-search-entry") list[i].className = "elections-name-entry";
	}
	evt.target.className = "elections-name-entry-marked";
	var data = evt.target.id.split("-");
	if (data[0] == "tel") {
		document.getElementById(wElecId + "-elections-vote").innerHTML = evt.target.innerHTML;
		markTeacherByID("tna-", tResults[data[1]], true);
		curElec = data[1];
		if (tResults[curElec] != curTeacher) {
			document.getElementById(wElecId + "-elections-teacher-savebutton").className = "button_1";
			tSaveButton = true;
		} else {
			tSaveButton = false;
			document.getElementById(wElecId + "-elections-teacher-savebutton").className = "button_1 button_disabled";
		}
	} else {
		document.getElementById(wElecId + "-elections-teacher").innerHTML = evt.target.innerHTML;
		curTeacher = data[1];
		if (tResults[curElec] != curTeacher) {
			document.getElementById(wElecId + "-elections-teacher-savebutton").className = "button_1";
			tSaveButton = true;
		} else {
			tSaveButton = false;
			document.getElementById(wElecId + "-elections-teacher-savebutton").className = "button_1 button_disabled";
		}
		drawTeacherImage(data[1]);
	}
}

function markTeacherByID(prefix, id, scroll) {
	var item = document.getElementById(prefix + id);
	if (item != undefined) {
		var list = item.parentNode.children;
		if (list != undefined) {
			for (var i = 0; i < list.length; i++) {
				if (list[i].className != "elections-name-search-entry") list[i].className = "elections-name-entry";
			}
			item.className = "elections-name-entry-marked";
			if (scroll) item.parentNode.scrollTop = item.offsetTop - 35*2;
		}
		var data = item.id.split("-");
		if (data[0] == "tel") {
			document.getElementById(wElecId + "-elections-vote").innerHTML = item.innerHTML;
		} else {
			document.getElementById(wElecId + "-elections-teacher").innerHTML = item.innerHTML;
			curTeacher = data[1];
			drawTeacherImage(data[1]);
		}
	}
}

function drawTeacherImage(tid) {
	var scaleWidth = 100;
	var container = document.getElementById(wElecId + "-elections-vote-image");
	container.innerHTML = "";
	var _img = new Image();
	_img.src = tImages[tid];
	_img.onload = function(evt) {
		var _canvas = document.createElement("canvas");
		var _sX = evt.target.width;
		var _sY = evt.target.height;
		_sY = scaleWidth * (_sY / _sX);
		_canvas.width = scaleWidth;
		_canvas.height = 100;
		var _ctx = _canvas.getContext("2d");
		_ctx.drawImage(evt.target,0,(_canvas.height - _sY) / 2,scaleWidth, _sY);
		container.appendChild(_canvas);
	}
}

function markName(evt) {
	var list = evt.target.parentNode.children;
	for (var i = 0; i < list.length; i++) {
		if (list[i].className != "elections-name-search-entry") list[i].className = "elections-name-entry";
	}
	evt.target.className = "elections-name-entry-marked";
	var data = evt.target.id.split("-");
	if (data[0] == "el1") {
		document.getElementById(wElecId + "-elections-couple-p1").innerHTML = evt.target.innerHTML;
		vPerson1 = data[1];
	} else {
		document.getElementById(wElecId + "-elections-couple-p2").innerHTML = evt.target.innerHTML;
		vPerson2 = data[1];
	}
	if (vPerson1 == vPersons["person1"] && vPerson2 == vPersons["person2"]) {
		document.getElementById(wElecId + "-elections-couple-savebutton").className = "button_1 button_disabled";
		cSaveButton = false;
	} else {
		document.getElementById(wElecId + "-elections-couple-savebutton").className = "button_1";
		cSaveButton = true;
	}
}

function markNameByUID(prefix, uid, scroll) {
	var item = document.getElementById(prefix + uid);
	if (item != undefined) {
		var list = item.parentNode.children;
		if (list != undefined) {
			for (var i = 0; i < list.length; i++) {
				if (list[i].className != "elections-name-search-entry") list[i].className = "elections-name-entry";
			}
			item.className = "elections-name-entry-marked";
			if (scroll) item.parentNode.scrollTop = item.offsetTop - 35*2;
		}
		if (item.id.split("-")[0] == "el1") {
			document.getElementById(wElecId + "-elections-couple-p1").innerHTML = item.innerHTML;
		} else {
			document.getElementById(wElecId + "-elections-couple-p2").innerHTML = item.innerHTML;
		}
	}
}

function getNameByUID(prefix, uid) {
	return document.getElementById(prefix + uid).innerHTML;
}

function addElectionsSpinner() {
	_spinner = document.createElement("div");
	_spinner.className = "spinner_3";
	_spinner.id = "elections-spinner";
	_spinner.innerHTML = "&nbsp;";
	return _spinner;
}

function removeElectionsSpinner(container) {
	container.removeChild(document.getElementById("elections-spinner"));
}