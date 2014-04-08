document.onkeypress = processKeypress;
var savedLogin = false;
if(typeof(Storage)!=="undefined") {
	savedLogin = _StringToBool(localStorage.getItem("loginsaved"));
}

var cachedImages = [
'images/button_1-m.png',
'images/spinner_verysmall.png'
];
for (var i = 0; i < cachedImages.length; i++) {
	var cImg = new Image();
	cImg.src = cachedImages[i];
}

function load() {
	if(typeof(Storage)!=="undefined") {
		var _sli = document.createElement("input");
		_sli.id = "save_login";
		_sli.type = "checkbox";
		document.getElementById("save-login-container").appendChild(_sli);
		var _sll = document.createElement("label");
		_sll.innerHTML = "Logindaten speichern";
		document.getElementById("save-login-container").appendChild(_sll);
		
		if (savedLogin) {
			_sli.checked = "true";
			document.getElementById("input_username").value = localStorage.getItem("savedname");
			document.getElementById("input_password").value = "savedpw";
		}
	}
}

function processKeypress(evt) {
	switch (evt.keyCode) {
		case (13):
			login();
			break;
	}
}

function login() {
	document.getElementById("login-spinner-container").appendChild(add_spinner());
	var _username = document.getElementById("input_username").value;
	var _password = document.getElementById("input_password").value;
	if(typeof(Storage)!=="undefined" && _password == "savedpw") {
		_password = (localStorage.getItem("savedpw") != undefined) ? window.atob(localStorage.getItem("savedpw")) : _password;
	}
	if(typeof(Storage)!=="undefined" && document.getElementById("save_login").checked == true) {
		localStorage.setItem("savedname",_username);
		localStorage.setItem("savedpw",window.btoa(_password));
		localStorage.setItem("loginsaved","true");
	} else {
		localStorage.setItem("savedname","");
		localStorage.setItem("savedpw","");
		localStorage.setItem("loginsaved","false");
	}
	switch ("") {
		case _username:
			remove_spinner(document.getElementById("login-spinner-container"));
			return false;
		case _password:
			remove_spinner(document.getElementById("login-spinner-container"));
			return false;
	}
	_toserver = {};
	_toserver["username"] = _username;
	_toserver["password"] = _password;
	//_toserver["ws"] = true;
	sendData("data=" + JSON.stringify(_toserver),"api/login.php", check_login);
	hide_error(0);
}

function add_spinner() {
	_spinner = document.createElement("div");
	_spinner.className = "spinner_3";
	_spinner.id = "login-spinner";
	return _spinner;
}

function remove_spinner(container) {
	container.removeChild(document.getElementById("login-spinner"));
}

function check_login() {
	if (request.readyState == 4) {
		//console.log(request.responseText);
		remove_spinner(document.getElementById("login-spinner-container"));
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		//console.log(answer);
		if (answer.error == "success") {
			window.location = "index.php";
		} else {
			switch (answer.code) {
				case 1:
					show_error(0,"Ein unerwarteter Fehler ist aufgetreten (" + answer.error + ")");
					break;
				case 2:
					show_error(0,"Der Benutzername oder das Passwort ist falsch");
					break;
				case 3:
					show_error(0,"Login ist deaktiviert");
					break;
				default:
					show_error(0,"Ein unbekannter Fehler ist aufgetreten");
					break;
			}
		}
	}
	requestReady = true;
}

function show_error(p,text) {
	document.getElementById("login-box-info").style.height = p + "px";
	if (p < 30) {
		setTimeout("show_error(" + (p+10) + ",'" + text + "')",30);
	} else {
		document.getElementById("login-box-info").innerHTML = text;
	}
}

function hide_error(p) {
	document.getElementById("login-box-info").style.height = p + "px";
	if (p > 0) {
		setTimeout("hide_error(" + (p-10) + ")",30);
	} else {
		document.getElementById("login-box-info").innerHTML = "";
	}
}