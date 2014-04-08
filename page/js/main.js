var scrolled = 0;
var scrollspeed = 100;
var dragscroll = 0;
var drag = 0;
var spinners = new Array();
//var hashParams = parseHash();
var getParams = parseGET();
var CurrentUser = false;
var nameslist, backgroundCanvas, backgroundCanvasCtx;
var parScroll = 0, parImgReady = false;
var comNode = null, comLastY = 0;
var PicWidth = 100;
var kaInterval;
var MOBILE = {};
MOBILE.is = (window.innerWidth <= 550) || ((window.innerWidth <= 1280) && (window.devicePixelRatio == 2));
//MIGHT BE IMPLEMENTED AT ONE POINT
/*var LIVE = _StringToBool(getParams["live"]);
if (LIVE == true && ("WebSocket" in window)) {
	socket = new WebSocket("ws://jangxx.com:6200");
	socket.onopen = function(evt) {
		evt.target.send(JSON.stringify({"command":"renew", "arg1": getCookie("WSSESSID")}));
	}
	socket.onmessage = function(evt) {
		console.log(evt.data);
	}
}*/

//Caching images for faster display
var cachedImages = [
'images/header_a-hover.png',
'images/cross.png',
'images/cross-hover.png',
'images/button_1.png',
'images/button_1-m.png',
'images/disabled_box-m.png',
'images/disabled_box.png',
'images/delete_element.png',
'images/delete_element-hover.png',
'images/edit_element.png',
'images/edit_element-hover.png',
'images/edit_element-done.png',
'images/poll_addvote.png',
'images/poll_addvote-hover.png',
'images/poll_addvote-n.png',
'images/poll_addvote-n-hover.png',
'images/poll_addvote-p.png',
'images/poll_addvote-p-hover.png',
'images/success_box.png',
'images/error_box.png',
'images/drag_button.png',
'images/spinner.png',
'images/spinner_small.png',
'images/spinner_verysmall.png'
];
for (var i = 0; i < cachedImages.length; i++) {
	var cImg = new Image();
	cImg.src = cachedImages[i];
}
var bgImg = new Image();
bgImg.src = 'images/background.png';
bgImg.onload = function() {parImgReady = true;}

function scrollParallax(evt) {
	renderBackground((-evt.target.scrollTop) / 6);
	/*try { //ALL THIS IS JUST FOR PREVENTING A SUPER ANNOYING BUG IN GOOGLE CHROME
		var _op = document.getElementById("polls-area").firstChild.style.opacity;
		document.getElementById("polls-area").firstChild.style.opacity = "0.1";
		document.getElementById("polls-area").firstChild.style.opacity = _op;
	} catch(err) {
	}*/
}

function renderBackground(shift) {
	if (parImgReady == true && backgroundCanvas != null) {
		var _top = shift % bgImg.height;
		var _x = Math.ceil(backgroundCanvas.offsetWidth / bgImg.width);
		var _y = Math.ceil((backgroundCanvas.offsetHeight + _top )/ bgImg.height);
		for (var i = 0; i <= _x; i++) {
			for (var j = 0; j <= _y; j++) {
				try {
					backgroundCanvasCtx.drawImage(bgImg,i * bgImg.width,_top + (j * bgImg.height));
				} catch(ex) {}
			}
		}
	}
}

function wheelscrolling(evt) {
	if (document.getElementById("entry-container").offsetHeight > document.getElementById("nameslist").offsetHeight) {
		if (evt.wheelDelta > 0) {
			scrolled += scrollspeed;
		} 
		if (evt.wheelDelta < 0) {
			scrolled -= scrollspeed;
		}
		if (scrolled > 0) {
			scrolled = 0;
		} 
		if (scrolled < -(document.getElementById("entry-container").offsetHeight - document.getElementById("nameslist").offsetHeight)) {
			scrolled = -(document.getElementById("entry-container").offsetHeight - document.getElementById("nameslist").offsetHeight);
		}
		document.getElementById("entry-container").style.top = (scrolled) + "px";
	}
}

function mouseStart(evt) {
	evt.preventDefault();
	evt.stopPropagation();
	drag = evt.clientY;
	nameslist.onmousemove = mouseMove;
	document.onselectstart = function() {return false;}
	document.onmouseup = mouseStop;
}

function mouseMove(evt) {
	if (document.getElementById("entry-container").offsetHeight > document.getElementById("nameslist").offsetHeight) {
		var scrollDelta = drag - evt.clientY;
		if (scrollDelta < 0) {
			scrolled -= scrollDelta;
		} 
		if (scrollDelta > 0) {
			scrolled -= scrollDelta;
		}
		if (scrolled > 0) {
			scrolled = 0;
		} 
		if (scrolled < -(document.getElementById("entry-container").offsetHeight - document.getElementById("nameslist").offsetHeight)) {
			scrolled = -(document.getElementById("entry-container").offsetHeight - document.getElementById("nameslist").offsetHeight);
		}
		document.getElementById("entry-container").style.top = (scrolled) + "px";
		drag = evt.clientY;
	}
}

function mouseStop(evt) {
	evt.preventDefault();
	evt.stopPropagation();
	drag = 0;
	nameslist.onmousemove = null;
	document.onselectstart = null;
	document.onmouseup = null;
}

function resize() {
	var paneContainer = document.getElementById("pane-container");
	paneContainer.style.height = (window.innerHeight - document.getElementById("header").offsetHeight) + "px";
	var _changed = MOBILE.is;
	MOBILE.is = (window.innerWidth < 550);
	if (_changed != MOBILE.is) {
		switchMobile()
	}
}

function switchMobile() {
	if (MOBILE.is) {
		if (document.getElementById("middle-pane") != undefined) {
			document.getElementById("middle-pane").parentNode.removeChild(document.getElementById("middle-pane"));
		}
		if (CurrentUser != false) {
				load_page(CurrentUser);
		}
	} else {
		if (MOBILE.popup != undefined) {
			closePopup(MOBILE.popup["id"]);
			document.getElementById("pane-container").appendChild(MOBILE.bkupmiddlePane);
			if (CurrentUser == false) {
				load_startpage();
			} else {
				load_page(CurrentUser);
			}
		} else {
			document.getElementById("pane-container").appendChild(MOBILE.bkupmiddlePane);
			load_startpage();
		}
	}
}

function load() {
	nameslist = document.getElementById("nameslist");
	if (!!window.HTMLCanvasElement) {
		backgroundCanvas = document.getElementById("background-canvas");
		backgroundCanvas.width = document.body.offsetWidth;
		backgroundCanvas.height = document.body.offsetHeight;
		backgroundCanvasCtx = backgroundCanvas.getContext("2d");
		renderBackground(0);
	}
	load_nameslist();
	window.onresize = resize;
	document.getElementById("middle-commentsection").appendChild(addSpinner("comment-spinner"));
	document.getElementById("header-image-ak").onmouseover = function() {
		document.getElementById("header-image-a").className = "header-image-a-hover";
	}
	document.getElementById("header-image-ak").onmouseout = function() {
		document.getElementById("header-image-a").className = "";
	}
	loadSettings();
	
	MOBILE.middlePane = document.getElementById("middle-pane").cloneNode(true);
	MOBILE.middlePane.style.float = "none";
	MOBILE.middlePane.style.overflowY = "auto";
	MOBILE.middlePane.style.width = "100%";
	MOBILE.bkupmiddlePane = document.getElementById("middle-pane").cloneNode(true);
	if (MOBILE.is) {
		document.getElementById("middle-pane").parentNode.removeChild(document.getElementById("middle-pane"));
	}
	if (getParams["id"] != undefined) {
		load_page(getParams["id"]);
	} else {
		if (!MOBILE.is) {
			load_startpage();
		}
	}
	window.onpopstate = function(evt) {
		if (evt.state != null) {
			load_page(evt.state);
		}
	}
}

function loadSettings() {
	if (!getSetting("disParScr")) {
		document.getElementById("middle-pane").onscroll = scrollParallax;
	} else {
		document.getElementById("middle-pane").onscroll = null;
	}
	if (!getSetting("disKeepAlive")) {
		kaInterval = setInterval("keepSessionAlive()",1000*60*20);
	} else {
		clearInterval(kaInterval);
	}
	if (!getSetting("disJsSearch")) {
		document.getElementById("name-search-entry-input").onkeyup = searchNameOnChange;
		document.getElementById("name-search-entry-input").onkeypress = undefined;
	} else {
		document.getElementById("name-search-entry-input").onkeyup = undefined;
		document.getElementById("name-search-entry-input").onkeypress = searchNameOnEnter;
	}
}

function keepSessionAlive() {
	sendData("", "api/keepalive.php", function() {
		if (request.readyState == 4) {
			requestReady = true;
		}
	});
}

function load_nameslist() {
	sendData("","api/getusers.php",update_nameslist);
}

function searchNameOnEnter(evt) {
	if (evt.keyCode == 13) {
		search_name(document.getElementById("name-search-entry-input").value);
	} 
}

function searchNameOnChange(evt) {
	search_name_js(document.getElementById("entry-container"), document.getElementById("name-search-entry-input").value); 
}

function search_name_js(obj, searchTerms) {
	var list = obj.children;
	for (var i = 0; i < list.length; i++) {
		if (searchTerms != "") {
			if (list[i].innerHTML.toLowerCase().indexOf(searchTerms.toLowerCase()) == -1 && list[i].id != "name-search-entry") {
				list[i].style.display = "none";
			} else {
				list[i].style.display = "block";
			}
		} else {
			list[i].style.display = "block";
		}
	}
}

function search_name(searchTerms) {
	if (searchTerms == "") {
		load_nameslist();
		return false;
	}
	_toserver = {};
	_toserver["search"] = searchTerms;
	sendData("data=" + JSON.stringify(_toserver),"api/getusers.php",update_nameslist);
}

function update_nameslist() {
	if (request.readyState == 4) {
		try {
			var nameslist = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (document.getElementById("nameslist-spinner") != null) {
			document.getElementById("nameslist-spinner").parentNode.removeChild(document.getElementById("nameslist-spinner"));
		}
		document.getElementById("entry-container").innerHTML = "";
		
		for (var i = 0; i < nameslist.length; i++) {
			var _div = document.createElement("div");
			var _fullname = nameslist[i].firstname + " " + nameslist[i].lastname;
			var _id = nameslist[i].id;
			_div.className = "name-entry";
			_div.innerHTML = _fullname;
			_div.id = "c" + _id;
			_div.onclick = function(evt) { load_page(evt.target.id.replace("c","") / 1); };
			document.getElementById("entry-container").appendChild(_div);
		}
	}
	requestReady = true;
}

function resetMobilePopup() {
	MOBILE.popup = undefined;
	//location.hash = "";
}

function load_page(id) {
	if(id == undefined || id == "") return;
	var _toserver = {};
	_toserver["id"] = id;
	sendData("data=" + JSON.stringify(_toserver), "api/getcomments.php", update_page);
	CurrentUser = id;
}

function update_page() {
	if (request.readyState == 4) {
		try {
			var comments = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (comments.error_code == 1) {
			logout();
		}
		if (MOBILE.is && MOBILE.popup == undefined) {
			MOBILE.popup = openPopup("Kommentare",true);
			registerReset(MOBILE.popup["id"],resetMobilePopup);
			MOBILE.popup["node"].id = "display-main";
			document.getElementById(MOBILE.popup["id"] + "-body").style.padding = "0";
			document.getElementById(MOBILE.popup["id"] + "-body").appendChild(MOBILE.middlePane);
		}
		
		var middlePane = document.getElementById("middle-pane");
		var middleCommentsection = document.getElementById("middle-commentsection");
		
		if (comments.error == "success") {
			document.getElementById("middle-header").innerHTML = comments.user.fullname;
			if (comments.user.pic != "") {
				if (comments.user.pic.search("fb:") > -1) {
					drawProfilePic("https://graph.facebook.com/" + comments.user.pic.replace("fb:","") + "/picture?type=normal&return_ssl_resources=1",document.getElementById("middle-header-pic"));
				} else {
					drawProfilePic(comments.user.pic,document.getElementById("middle-header-pic"));
				}
			} else {
				document.getElementById("middle-header-pic").innerHTML = "";
			}
			middleCommentsection.innerHTML = "";
			
			for (var i = 0; i < comments.comments.length; i++) {
				var _main = document.createElement("div");
				_main.className = "middle-comment";
				_main.id = "co" + comments.comments[i].id;
				_main.elementName = "comment";
				_main.dataset.importance = comments.comments[i].importance;
				
				var _headline = document.createElement("div");
				_headline.className = "middle-comment-headline";
				if (comments.comments[i].hidden) {
					_headline.className += " comment-head-hidden";
				}
				_headline.innerHTML = '<span class="comment-count">' + (i+1) + ".)</span> <i>" + comments.comments[i].date + "</i>";
				if (comments.comments[i].from != undefined) {_headline.title = "Von " + comments.comments[i].from;}
				
				if (comments.user.id == USER && _StringToBool(getCookie("disablecommentsort")) == false) {
					var _dragButton = document.createElement("div");
					_dragButton.className = "comment-drag-button";
					_dragButton.onmousedown = commentDragStart;
					_headline.appendChild(_dragButton);
				}
				
				if (comments.comments[i].delete) {
					var _deleteButton = document.createElement("div");
					_deleteButton.className = "comment-delete-button";
					_deleteButton.onclick = commentDelete;
					_headline.appendChild(_deleteButton);
				}
				
				_main.appendChild(_headline);
				
				var _body = document.createElement("div");
				_body.className = "middle-comment-body";
				if (comments.comments[i].hidden) {
					_body.className += " comment-body-hidden";
				}
				_body.innerHTML = comments.comments[i].text;
				_main.appendChild(_body);
				
				middleCommentsection.appendChild(_main);
			}
			middleCommentsection.appendChild(generateCommentWrite(comments.user.id));
			load_polls(comments.user.id);
			
			window.pageTitle = "abizeitung Kommentare | " + comments.user.fullname;
			window.history.pushState(CurrentUser, window.pageTitle, location.pathname + "?" + updateGET("id", CurrentUser));
		} else {
			document.getElementById("middle-header").innerHTML = "Der angegebene User existiert nicht oder konnte nicht geladen werden";
			middleCommentsection.innerHTML = "";
		}
		requestReady = true;
	}
}

function drawProfilePic(url,container) {
	scaleWidth = (MOBILE.is) ? PicWidth / 1.5 : PicWidth;
	
	container.innerHTML = "";
	var _img = new Image();
	_img.src = url;
	_img.onload = function(evt) {
		container.innerHTML = "";
		var _canvas = document.createElement("canvas");
		var _sX = (MOBILE.is) ? evt.target.width / 1.5 : evt.target.width;
		var _sY = (MOBILE.is) ? evt.target.height / 1.5 : evt.target.height;
		_sY = scaleWidth * (_sY / _sX);
		_canvas.width = scaleWidth;
		_canvas.height = container.parentNode.offsetHeight;
		container.style.height = container.parentNode.offsetHeight + "px";
		var _ctx = _canvas.getContext("2d");
		_ctx.drawImage(evt.target,0,(_canvas.height - _sY) / 2,scaleWidth, _sY);
		container.appendChild(_canvas);
	}
}

function load_startpage() {
	if (!MOBILE.is) {
		sendData("", "api/getstartpage.php", update_startpage);
		CurrentUser = false;
		//location.hash = "";
		window.pageTitle = "abizeitung Kommentare | Startseite";
		window.history.pushState("", window.pageTitle, location.pathname + "?" + deleteGETparam("id"));
	}
}

function update_startpage() {
	if (request.readyState == 4) {
		try {		
			var comments = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (comments.error_code == 1) {
			logout();
		}
		document.getElementById("middle-header").innerHTML = "Neueste Kommentare";
		var pollsArea = document.getElementById("polls-area");
		var middlePane = document.getElementById("middle-pane");
		var middleCommentsection = document.getElementById("middle-commentsection");
		middleCommentsection.innerHTML = "";
		pollsArea.innerHTML = "";
		if (!MOBILE.is) {document.getElementById("middle-header-pic").innerHTML = "";}
		
		for (var i = 0; i < comments.comments.length; i++) {
			var _main = document.createElement("div");
			_main.className = "middle-comment";
			
			var _headline = document.createElement("div");
			_headline.className = "middle-comment-headline";
			if (comments.comments[i].from != undefined) {_headline.title = "Von " + comments.comments[i].from;}
			
			if (comments.comments[i].hidden) {
				_headline.className += " comment-head-hidden";
			}
			_headline.innerHTML = "Am <i>" + comments.comments[i].date + "</i> an <b>" + comments.comments[i].to + "</b>";
			_main.appendChild(_headline);
			
			var _body = document.createElement("div");
			_body.className = "middle-comment-body";
			if (comments.comments[i].hidden) {
				_body.className += " comment-body-hidden";
			}
			_body.innerHTML = comments.comments[i].text;
			_main.appendChild(_body);
			
			middleCommentsection.appendChild(_main);
		}
		for (var i = 0; i < comments.news.length; i++) {
			var _date = new Date(comments.news[i].date * 1000);
			var _newsItem = document.createElement("div");
			_newsItem.className = "news-item";
			var _newsItemText = document.createElement("div");
			_newsItemText.className = "news-item-text";
			_newsItemText.innerHTML = comments.news[i].text;
			var _newsItemDate = document.createElement("div");
			_newsItemDate.className = "news-item-date";
			_newsItemDate.innerHTML = (new String(_date.getDate())).fill(2,0) + "." + ("" + (_date.getMonth() + 1)).fill(2,0) + "." + _date.getFullYear() + " " + _date.getHours() + ":" + _date.getMinutes() + " Uhr";
			_newsItem.appendChild(_newsItemDate);
			_newsItem.appendChild(_newsItemText);
			
			pollsArea.appendChild(_newsItem);
		}
	}
	requestReady = true;
}

function load_polls(id) {
	var _toserver = {};
	_toserver["operator"] = "specific";
	_toserver["id"] = id;
	sendData("data=" + JSON.stringify(_toserver), "api/getpolls.php", update_polls);
}

function update_polls() {
	if (request.readyState == 4) {
		try {		
			var polls = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		var _pollsarea = document.getElementById("polls-area");
		_pollsarea.innerHTML = "";
		for (var i = 0; i < polls.length; i++) {
			var disabled = (polls[i].state == "disabled");
			if (disabled) {var al_voted = polls[i].voted;} else {var al_voted = undefined;}
			var voted = (polls[i].state == "voted");
			_pollsarea.appendChild(generatePollItem(polls[i].id,polls[i].name,voted,disabled,al_voted));
		}
	}
	requestReady = true;
}

function generatePollItem(id,label,checked,disabled,voted) {
	var _container = document.createElement("div");
	_container.className = "polls-item";
	_container.id = "p" + id;
	
	var _label = document.createElement("div");
	_label.className = "polls-item-label";
	_label.innerHTML = label;
	_label.id = "p" + id + "-label";
	
	var _button = document.createElement("div");
	_button.className = "polls-item-button";
	_button.id = "p" + id + "-button";
	if (checked) {
		_button.className += "-positive";
	}
	
	if (disabled) {
		_container.className += "-disabled";
		_button.title = "Du hast bereits für " + voted + " abgestimmt";
		_button.className += "-disabled";
	} else {
		_button.onclick = function(evt) { voteForPoll(CurrentUser,id); }
	}
	
	_container.appendChild(_label);
	_container.appendChild(_button);
	
	return _container;
}

function update_voted_poll() {
	if (request.readyState == 4) {
		try {		
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			switch (answer.operation) {
				case "unvote":
					document.getElementById("p" + answer.poll + "-button").className = "polls-item-button";
					break;
				case "vote":
					document.getElementById("p" + answer.poll + "-button").className = "polls-item-button-positive";
					break;
			}
		} else {
			switch (answer.code) {
				case 1:
					alert("Ein unerwarteter Fehler ist aufgetreten (" + answer.error + ")");
					break;
				case 2:
					alert("Umfragen sind deaktiviert");
					break;
				default:
					alert("Ein unbekannter Fehler ist aufgetreten");
					break;
			}
		}
	}
	requestReady = true;
}

function voteForPoll(user,poll) {
	var _toserver = {};
	_toserver["poll"] = poll;
	_toserver["user"] = user;
	_toserver["operation"] = "auto";
	sendData("data=" + JSON.stringify(_toserver), "api/votepoll.php", update_voted_poll);
}

function generateCommentWrite(id) {
	var _main = document.createElement("div");
	_main.id = "middle-comment-write";
	
	var _headline = document.createElement("div");
	_headline.className = "middle-comment-headline";
	_headline.innerHTML = "<b>Kommentar schreiben</b>";
	_main.appendChild(_headline);
	
	var _body = document.createElement("div");
	_body.id = "middle-comment-write-body";
	
	if (_StringToBool(getCookie("disablecomments")) == false) {
		var _textarea = document.createElement("textarea");
		_textarea.id = "middle-comment-textarea";
		_body.appendChild(_textarea);
		
		var _footerContainer = document.createElement("div");
		_footerContainer.id = "comment-footer-container";
		
		var _button = document.createElement("div")
		_button.className = "button_1";
		_button.innerHTML = "Absenden";
		_button.onclick = function() { addComment(id, _textarea.value); }
		_button.style.display = "inline-block";
		
		var _spinnerContainer = document.createElement("div");
		_spinnerContainer.id = "comment-spinner-container";
		
		var _hiddenCheckbox = document.createElement("input");
		_hiddenCheckbox.type = "checkbox";
		_hiddenCheckbox.id = "comment-hidden-checkbox";
		_hiddenCheckbox.onchange = function(evt) {saveSetting("disHidCom",(!evt.target.checked));}
		_hiddenCheckbox.checked = !getSetting("disHidCom");
		var _hiddenCheckboxLabel = document.createElement("div");
		_hiddenCheckboxLabel.innerHTML = "Kommentar nicht für jeden sichtbar";
		_hiddenCheckboxLabel.style.display = "inline-block";
		_hiddenCheckboxLabel.style.marginRight = "10px";
		
		_footerContainer.appendChild(_hiddenCheckbox);
		_footerContainer.appendChild(_hiddenCheckboxLabel);
		_footerContainer.appendChild(_spinnerContainer);
		_footerContainer.appendChild(_button);
		
		_body.appendChild(_footerContainer);
	} else {
		_body.innerHTML = "Kommentare sind deaktiviert.";
	}
	
	_main.appendChild(_body);
	
	return _main;
}

function addCommentSpinner() {
	_spinner = document.createElement("div");
	_spinner.className = "spinner_3";
	_spinner.id = "comment-spinner";
	_spinner.innerHTML = "&nbsp;";
	return _spinner;
}

function removeCommentSpinner(container) {
	container.removeChild(document.getElementById("comment-spinner"));
}

function addComment(id, text) {
	document.getElementById("comment-spinner-container").appendChild(addCommentSpinner());
	if (text == "") {
		removeCommentSpinner(document.getElementById("comment-spinner-container"));
		return false;
	}
	_toserver = {};
	_toserver["id"] = id;
	_toserver["text"] = encodeURIComponent(text.replace(new RegExp("'","g"),"&lsquo;").replace(new RegExp('"',"g"),"&rdquo;").replace(new RegExp("\n","g"),"<br/>"));
	if (document.getElementById("comment-hidden-checkbox").checked == true) {
		_toserver["hidden"] = true;
	}
	sendData("data=" + JSON.stringify(_toserver), "api/addcomment.php", update_comments);
}

function update_comments() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			requestReady = true;
			removeCommentSpinner(document.getElementById("comment-spinner-container"));
			load_page(answer.id);
		} else {
			requestReady = true;
			removeCommentSpinner(document.getElementById("comment-spinner-container"));
			alert('Bei der Übertragung trat ein Fehler auf (' + answer.error + ')');
		}
		requestReady = true;
	}
}

function addSpinner(id) {
	_container = document.createElement("div");
	_container.id = id;
	_container.className = "spinner-container";
	
	_spinner = document.createElement("div");
	_spinner.className = "spinner_1";
	
	_container.appendChild(_spinner);
	return _container;
}

function commentDelete(evt) {
	var _id = evt.target.parentNode.parentNode.id.replace("co","");
	var _toserver = {};
	_toserver["operation"] = "delete";
	_toserver["id"] = _id;
	sendData("data=" + JSON.stringify(_toserver), "api/changecomments.php", delete_comment);
}

function delete_comment() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error == "success") {
			document.getElementById("co" + answer.id).parentNode.removeChild(document.getElementById("co" + answer.id));
			_renumber();
		} else {
			switch (answer.code) {
				case 1:
					alert('Ein unerwarteter Fehler ist aufgetreten (' + answer.error + ')');
					break;
				default:
					alert('Ein unbekannter Fehler ist aufgetreten:');
					break;
			}
		}
		requestReady = true;
	}	
}

function commentDragStart(evt) {
	evt.preventDefault();
	comLastY = evt.clientY;
	comNode = evt.target.parentNode.parentNode;
	comNode.style.borderBottomColor = "#1052be";
	comNode.style.borderBottomWidth = "5px";
	comNode.style.borderTopColor = "#1052be";
	comNode.style.borderTopWidth = "5px";
	var _id = comNode.id;
	var _list = document.getElementById("middle-commentsection").childNodes;
	for (var i = 0; i < _list.length; i++) {
		if (_list[i].id != _id && _list[i].className == "middle-comment") {_list[i].onmouseover = commentDragMove; }
	}
	document.onmouseup = commentDragStop;
	document.onselectstart = function(evt) {return false;}
}

function commentDragStop(evt) {
	document.onmousemove = null;
	document.onmouseup = null;
	document.onselectstart = null;
	var _id = comNode.id;
	var _list = document.getElementById("middle-commentsection").childNodes;
	for (var i = 0; i < _list.length; i++) {
		if (_list[i].id != _id && _list[i].className == "middle-comment") {_list[i].onmouseover = null;}
	}
	comLastY = 0;
	comNode.style.borderBottomColor = "#777";
	comNode.style.borderBottomWidth = "1px";
	comNode.style.borderTopColor = "#AAA";
	comNode.style.borderTopWidth = "2px";
	comNode = null;
	saveCommentImportance(_id);
}

function commentDragMove(evt) {
	var _delta = comLastY - evt.clientY;
	if (_delta > 0) {
		this.parentNode.insertBefore(comNode,this);
	} else {
		insertAfter(this, comNode);
	}
	comLastY = evt.clientY;
}

function saveCommentImportance(id) {
	if (id == undefined) {
		return false;
	}
	var _element = document.getElementById(id);
	_element.dataset.importance = (_element.nextSibling.dataset.importance != undefined) ? (_element.nextSibling.dataset.importance/1) + 1 : 1;
	var _r = true;
	var _chkelem = _element;
	var _change = new Array();
	var _c = {};
	_c["id"] = _element.id.replace("co","");
	_c["imp"] = _element.dataset.importance;
	_change.push(_c);
	while (_r == true) {
		if (_chkelem.previousSibling != undefined) {
			if (_chkelem.previousSibling.dataset.importance <= _chkelem.dataset.importance) {
				_chkelem.previousSibling.dataset.importance = (_chkelem.dataset.importance/1) + 1;
				var _c = {};
				_c["id"] = _chkelem.previousSibling.id.replace("co","");
				_c["imp"] = (_chkelem.previousSibling.dataset.importance/1);
				_change.push(_c);
				_chkelem = _chkelem.previousSibling;
			} else {
				_r = false;
			}
		} else {
			_r = false;
		}
	}
	var _toserver = {};
	_toserver["operation"] = "importance-multi";
	_toserver["ids"] = new Array();
	_toserver["imps"] = new Array();
	for (var i = 0; i < _change.length; i++) {
		_toserver["ids"].push(_change[i]["id"]);
		_toserver["imps"].push(_change[i]["imp"]);
	}
	sendData("data=" + JSON.stringify(_toserver), "api/changecomments.php", check_comments);
	_renumber();
}

function _renumber() {
	var _commentcounter = document.getElementsByClassName("comment-count");
	for (var i = 0; i < _commentcounter.length; i++) {
		_commentcounter[i].innerHTML = (i+1) + ".)";
	}
}

function check_comments() {
	if (request.readyState == 4) {
		try {
			var answer = JSON.parse(request.responseText);
		} catch(err) {
			requestReady = true;
			return false;
		}
		if (answer.error != "success") {
			switch (answer.code) {
				case 1:
					alert('Ein unerwarteter Fehler ist aufgetreten (' + answer.error + ')');
					break;
				case 2:
					alert('Das Sortieren der Kommentare ist deaktiviert');
					break;
				default:
					alert('Ein unbekannter Fehler ist aufgetreten:');
					break;
			}
		}
		requestReady = true;
	}
}