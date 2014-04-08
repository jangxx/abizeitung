var popups = new Array();
var currentDragId;

/*function Popup() {
	var Id = Math.floor((Math.random() * 10) + (Math.random() * 100) + (Math.random() * 1000) + (Math.random() * 10000));
	var domNode;
	var posX = 0, posY = 0;
	var domParts = {};
	var Tabs = {};
	
	this.getTabs = function() {
		return Tabs;
	}
	
	this.getdomParts = function() {
		return domParts;
	}
	
	this.getdomNode = function() {
		return domNode;
	}
	
	this.getId = function() {
		return Id;
	}
	
	this.setTabbar = function(template) {
		if (domParts.tabarea != undefined) {
			domParts.tabarea.style.height = "auto";
			domParts.tabarea.appendTemplate(template);
			var _list = domParts.tabarea.children;
			for (var i = 0; i < _list.length; i++) {
				var _name = _list[i].id.split("-")[1];
				Tabs[_name].tabNode = _list[i];
				Tabs[_name].contentNode = document.getElementById(Id + "-" + _name + "-content").cloneNode(true);
			}
		}
	}
	
	this.setTabFunction = function(name, callback) {
		Tabs[name].callback = callback;
		Tabs[name].calledonce = false;
	}
	
	this.setTemplate = function(template) {
		domNode = template.cloneNode(true);
		domNode.innerHTML = domNode.innerHTML.replace(new RegExp("{id}","g"), Id);
		domNode.className = "";
	}
	
	this.open = function(title) {
		if (domNode != undefined) {
			domNode.innerHTML = domNode.innerHTML.replace(new RegExp("{title}","g"), title);
			document.body.appendChild(domNode);
			
			domParts.main = domNode;
			domParts.header = makePopupObject(document.getElementById(Id + "-header"));
			domParts.body = makePopupObject(document.getElementById(Id + "-body"));
			domParts.info = makePopupObject(document.getElementById(Id + "-info"));
			domParts.tabarea = makePopupObject(document.getElementById(Id + "-tabarea"));
			domParts.closeButton = makePopupObject(document.getElementById(Id + "-closebutton"));
			domParts.header.onmousedown = this.dragWindowStart;
			domParts.closeButton.onclick = this.close;
			domParts.closeButton.onmousedown = this.close;
			
			if (domParts.tabarea.children.length > 0) {
				openTab(tabarea.children[0].id.split("-")[1]);
			}
		}
	}
	
	function makePopupObject(object) {
		object.constructor.prototype.appendTemplate = function(template) {
			var _template = template.cloneNode(true);
			_template.className = "";
			_template.innerHTML = _template.innerHTML.replace(new RegExp("{id}","g"), Id);
			this.appendChild(_template);
		}
		return object;
	}
	
	this.openTab = function(name) {
		if (Tabs[name].tabNode != undefined) {
			Tabs.forEach(function(tab) {
				tab.tabNode.className = "popup-window-tabbar-tab";
			});
			Tabs[name].tabNode.className = "popup-window-tabbar-tab-active";
			if (Tabs[name].contentNode != undefined) {
				Tabs.forEach(function(tab) {
					tab.contentNode.style.display = "none";
				});
				Tabs[name].contentNode.style.display = "block";
				if (Tabs[name].callback != undefined) {
					if (Tabs[name].calledonce == false) {
						Tabs[name].callback.call();
					}
				}
			}
		}
	}
	
	this.close = function() {
		domNode.parentNode.removeChild(domNode);
	}
	
	this.dragWindowStart = function(evt) {
		console.log("WIN DRAG START");
		evt.preventDefault();
		document.onmousemove = this.dragWindowMove;
		document.onmouseup = this.drawWindowStop;
		document.onselectstart = function() {return false;}
	}
	
	this.dragWindowStop = function(evt) {
		console.log("WIN DRAG STOP");
		document.onmousemove = null;
		document.onmouseup = null;
		document.onselectstart = null;
	}
	
	this.dragWindowMove = function(evt) {
		console.log("WIN DRAG MOVE");
		this.movePopup(evt.clientX, evt.clientY);
	}
	
	this.movePopup = function(x, y) {
		posX = x;
		posY = y;
		domNode.style.top = posY + "px";
		domNode.style.left = posX + "px";
	}
	
	function showInfo(p, text) {
		domParts.info.style.height = p + "px";
		if (p < 30) {
			setTimeout("showInfo(" + (p+10) + ",'" + text + "')",30);
		} else {
			domParts.info.innerHTML = text;
		}
	}
	
	this.showError = function (text) {
		domParts.info.className = "popup-window-error";
		domParts.info.innerHTML = "";
		showInfo(0, text);
	}
	
	this.showSuccess = function (text) {
		domParts.info.className = "popup-window-success";
		domParts.info.innerHTML = "";
		showInfo(0, text);
	}
}*/

function openPopup(title,unique) {
	if (unique) {
		var _check = titleAlreadyExists(title);
		if (typeof(_check) == "number") {
			closePopup(_check);
			return false;
		}
	}
	var new_popup = {};
	var _template = document.getElementById("popup-template");
	new_popup["node"] = _template.getElementsByTagName("div")[0].cloneNode(true);
	if (getSetting("enHwAccWin") && WebKitDetect.isWebKit()) new_popup["node"].style.webkitTransform = "translate3d(0,0,0)";
	new_popup["title"] = title;
	new_popup["x"] = 0;
	new_popup["y"] = 0;
	new_popup["drag"] = [0, 0];
	var _id = popups.push(new_popup) - 1;
	new_popup["node"].innerHTML = new_popup["node"].innerHTML.replace(new RegExp("{id}","g"),_id);
	new_popup["node"].innerHTML = new_popup["node"].innerHTML.replace(new RegExp("{title}","g"),title);
	document.body.appendChild(new_popup["node"]);
	
	return {"id":_id, "node":new_popup["node"]};
}

function registerReset(id,callback) {
	popups[id]["reset"] = callback;
}

function registerTabFuncOnce(id, tabid, callback) {
	if (popups[id]["tabs"] == undefined) popups[id]["tabs"] = {};
	if (popups[id]["tabs"][tabid] == undefined) popups[id]["tabs"][tabid] = {};

	popups[id]["tabs"][tabid]["once"] = callback;
	popups[id]["tabs"][tabid]["oncecalled"] = false;
}

function registerTabFuncAlways(id, tabid, callback) {
	if (popups[id]["tabs"] == undefined) popups[id]["tabs"] = {};
	if (popups[id]["tabs"][tabid] == undefined) popups[id]["tabs"][tabid] = {};

	popups[id]["tabs"][tabid]["always"] = callback;
}

function finalizePopupTemplate(id,template) {
	var _template = template.cloneNode(true);
	_template.className = "";
	_template.innerHTML = _template.innerHTML.replace(new RegExp("{id}","g"),id);
	return _template;
}

function titleAlreadyExists(title) {
	for (var i = 0; i < popups.length; i++) {
		if (popups[i] != null) {
			if (popups[i]["title"] == title) {
				return i;
			}
		}
	}
	return false;
}

function closeOnClick(evt,id) {
	evt.stopPropagation();
	closePopup(id);
}

function showError(id,text) {
	var _info = document.getElementById(id + "-info");
	_info.className = "popup-window-error";
	_info.innerHTML = "";
	showInfo(id, 0, text);
}

function showSuccess(id,text) {
	var _info = document.getElementById(id + "-info");
	_info.className = "popup-window-success";
	_info.innerHTML = "";
	showInfo(id, 0, text);
}

function showInfo(id, p, text) {
	var _info = document.getElementById(id + "-info");
	_info.style.height = p + "px";
	if (p < 30) {
		setTimeout("showInfo(" + id + "," + (p+10) + ",'" + text + "')",30);
	} else {
		_info.innerHTML = text;
	}
}

function addTabBar(winId, template) {
	var tabbar = document.getElementById(winId + "-tabs");
	tabbar.style.height = "auto";
	tabbar.appendChild(template);
}

function openTab(evt) {
	var id = evt.target.id.split("-")[0];
	var content = document.getElementById(evt.target.id + "-content");
	for (var i = 0; i < evt.target.parentNode.children.length; i++) {
		evt.target.parentNode.children[i].className = "popup-window-tabbar-tab";
	}
	evt.target.className = "popup-window-tabbar-tab-active";
	if (content != undefined) {
		var othertabs = document.getElementById(id + "-tabbar-content").children;
		for (var i = 0; i < othertabs.length; i++) {
			if (othertabs[i].className == "popup-window-tabbar-template") {
				othertabs[i].style.display = "none";
			}
		}
		content.style.display = "block";
		var uniquetabid = evt.target.id.split("-")[1];
		if (popups[id]["tabs"][uniquetabid] != undefined) {
			var tabdata = popups[id]["tabs"][uniquetabid];
			if (tabdata["once"] != undefined && tabdata["oncecalled"] != true) {
				tabdata["once"].call();
				tabdata["oncecalled"] = true;
			}
			if (tabdata["always"] != undefined) {
				tabdata["always"].call();
			}
		}
	}
}

function openFirstTab(winId) {
	var tabbar = document.getElementById(winId + "-tabbar");
	if (tabbar != undefined) {
		var tab = tabbar.children[0];
		var content = document.getElementById(tab.id + "-content");
		tab.className = "popup-window-tabbar-tab-active";
		if (content != undefined) {
			var othertabs = document.getElementById(winId + "-tabbar-content").children;
			for (var i = 0; i < othertabs.length; i++) {
				if (othertabs[i].className == "popup-window-tabbar-template") {
					othertabs[i].style.display = "none";
				}
			}
			content.style.display = "block";
			var uniquetabid = tab.id.split("-")[1];
			if (popups[winId]["tabs"][uniquetabid] != undefined) {
				var tabdata = popups[winId]["tabs"][uniquetabid];
				if (tabdata["once"] != undefined && tabdata["oncecalled"] != true) {
					tabdata["once"].call();
					tabdata["oncecalled"] = true;
				}
				if (tabdata["always"] != undefined) {
					tabdata["always"].call();
				}
			}
		}
	}
}

function hideInfo(id, p) {
	var _info = document.getElementById(id + "-info");
	if (_info.offsetHeight > 0) {
		_info.style.height = p + "px";
	}
	if (p > 0) {
		setTimeout("hideInfo("+ id + "," + (p-10) + ")",30);
	}
}

function getRelatedElementId(node) {
	for (var i = 0; i < popups.length; i++) {
		if (popups[i] != null) {
			if (popups[i]["node"] == node) {
				return i;
			}
		}
	}
	return false;
}

function closeHelper(evt) {
	evt.stopPropagation();
	return false;
}

function winDragStart(evt) {
	evt.preventDefault();
	currentDragId = getRelatedElementId(evt.target.parentNode);
	popups[currentDragId]["drag"] = [evt.clientX, evt.clientY];
	document.onmousemove = winDragMove;
	document.onmouseup = winDragStop;
	document.onselectstart = function() {return false;}
}

 function winDragStop(evt) {
	document.onmousemove = null;
	document.onmouseup = null;
	document.onselectstart = null;
	popups[currentDragId]["drag"] = [0, 0];
	movePopup(currentDragId, Math.max(0, Math.min(window.innerWidth - popups[currentDragId].node.offsetWidth, popups[currentDragId].x)), Math.max(0, Math.min(window.innerHeight - popups[currentDragId].node.offsetHeight, popups[currentDragId].y)));
}

 function winDragMove(evt) {
	movePopup(currentDragId,popups[currentDragId]["x"] + (evt.clientX - popups[currentDragId]["drag"][0]), popups[currentDragId]["y"] + (evt.clientY - popups[currentDragId]["drag"][1]));
	popups[currentDragId]["drag"] = [evt.clientX, evt.clientY];
}


function movePopup(id,x,y) {
	popups[id]["node"].style.top = y + "px";
	popups[id]["node"].style.left = x + "px";
	popups[id]["x"] = x;
	popups[id]["y"] = y;
} 

function closePopup(id) {
	popups[id]["node"].parentNode.removeChild(popups[id]["node"]);
	if (popups[id]["reset"] != undefined) {
		popups[id]["reset"].call();
	}
	popups[id] = null;
	//popups.splice(id,1);
}