<?php if ($_SESSION["SESS_ADMIN"] != 1) {exit();} ?>
<div class="template" id="admin-template">
	<div class="popup-window-sectioninfo">Benutzer bearbeiten</div>
	<div class="popup-window-sectionblock">
		<div style="margin-bottom: 10px">
			<div class="button_1" style="float: right; margin-left: 10px; margin-top: 0px;" onclick="searchOnClick()">Suchen</div>
			<div class="input" style="height: 30px; overflow: hidden;">
				<input class="input-field" id="{id}-input_search" type="text" placeholder="Benutzer suchen" style="padding-top: 0px; padding-bottom: 0px" onkeypress="searchOnEnter(event)">
			</div>
		</div>
		<div id="{id}-admin-search-display" class="admin-search-display" style="display: none;"></div>
	</div>
	<div class="popup-window-sectioninfo">Benutzer hinzuf&uuml;gen</div>
	<div class="popup-window-sectionblock">
		<div style="overflow: hidden; margin-bottom: 10px;">
			<div class="button_1" style="float: right; margin-left: 10px; margin-top: 0px;" onclick="addUser()">
				Hinzuf&uuml;gen
			</div>
			<div style="overflow: hidden";>
				<div class="input" style="display: inline-block; height: 30px; width: 49%;">
					<input id="{id}-new_firstnameinput" class="input-field admin-add-input" type="text" style="height: auto; padding: 0px 2px;" placeholder="Vorname">
				</div>
				<div class="input" style="display: inline-block; height: 30px; width: 49%; float:right">
					<input id="{id}-new_lastnameinput" class="input-field admin-add-input" type="text" style="height: auto; padding: 0px 2px;" placeholder="Nachname">
				</div>
			</div>
		</div>
		<div class="admin-add-info-display" id="{id}-info-display">
		</div>
	</div>
	<div class="popup-window-sectioninfo">Datei mit Namen einlesen</div>
	<div class="popup-window-sectionblock">
		<div style="overflow: hidden; margin-bottom: 10px;">
			<input id="{id}-admin-parse-fileselector" class="admin-parse-fileselector" type="file">
			<div class="button_1" onclick="parseFile()" style="float: right; margin-top: 0px;">Einlesen</div>
		</div>
		<textarea id="{id}-admin-parse-textarea" class="admin-parse-textarea"></textarea>
	</div>
	<div class="popup-window-sectioninfo">Umfragen & Wahlen</div>
	<div class="popup-window-sectionblock">
		<div style="margin-bottom: 10px">
			<div class="button_1" style="float: right; margin-left: 10px; margin-top: 0px;" onclick="addPollOnClick()">Hinzuf&uuml;gen</div>
			<div class="input" style="height: 30px; overflow: hidden;">
				<input class="input-field" id="{id}-poll_add" type="text" placeholder="Name der Umfrage" style="padding-top: 0px; padding-bottom: 0px" onkeypress="addPollOnEnter(event)">
			</div>
		</div>
		<div style="margin-bottom: 10px">
			<div class="button_1" style="float: right; margin-left: 10px; margin-top: 0px;" onclick="addTeacherElectionOnClick()">Hinzuf&uuml;gen</div>
			<div class="input" style="height: 30px; overflow: hidden;">
				<input class="input-field" id="{id}-elec_add" type="text" placeholder="Name der Wahl" style="padding-top: 0px; padding-bottom: 0px" onkeypress="addTeacherElectionOnEnter(event)">
			</div>
		</div>
		<div class="button_1" style="margin-top: 0px; margin-bottom: 10px;" onclick="openElectionManager()">Umfrage- & Wahlergebnisse anzeigen</div>
	</div>
	<div class="popup-window-sectioninfo">News</div>
	<div class="popup-window-sectionblock">
		<div style="margin-bottom: 10px">
			<div class="button_1" style="float: right; margin-left: 10px; margin-top: 0px;" onclick="addNewsOnClick()">Hinzuf&uuml;gen</div>
			<div style="overflow: hidden;">
				<textarea class="input-textarea" id="{id}-news_add" type="text" placeholder="Newstext" style="width: 98%; padding-top: 0px; padding-bottom: 0px; height: 100px;"></textarea>
			</div>
		</div>
		<div class="button_1" style="margin-top: 0px; margin-bottom: 10px;" onclick="openNewsOnClick()">Gespeicherte News anzeigen</div>
	</div>
	<div class="popup-window-sectioninfo">Einstellungen</div>
	<div class="popup-window-sectionblock">
		<input type="checkbox" id="{id}-admin-disablelogin-checkbox">
		<div style="display: inline-block;">Login deaktivieren (für Nicht-Administratoren)</div>
		<br/>
		<input type="checkbox" id="{id}-admin-hiddencomments-checkbox">
		<div style="display: inline-block;">Versteckte Kommentare anzeigen (dem Empfänger)</div>
		<br/>
		<input type="checkbox" id="{id}-admin-disablecommentsort-checkbox">
		<div style="display: inline-block;">"Kommentare sortieren" deaktivieren</div>
		<br/>
		<input type="checkbox" id="{id}-admin-deletecomments-checkbox">
		<div style="display: inline-block;">Benutzern das Löschen aller sichtbaren an sie gerichteten Kommentare erlauben</div>
		<br/>
		<input type="checkbox" id="{id}-admin-disablecomments-checkbox">
		<div style="display: inline-block;">"Kommentar schreiben" deaktivieren</div>
		<br/>
		<input type="checkbox" id="{id}-admin-disabledescription-checkbox">
		<div style="display: inline-block;">"Steckbrief ändern"  deaktivieren</div>
		<br/>
		<input type="checkbox" id="{id}-admin-disablepolls-checkbox">
		<div style="display: inline-block;">Umfragen deaktivieren</div>
		<br/>
		<input type="checkbox" id="{id}-admin-disableelections-checkbox">
		<div style="display: inline-block;">Lehrerwahlen deaktivieren</div>
		<br/>
		<input type="checkbox" id="{id}-admin-disablecoupleelections-checkbox">
		<div style="display: inline-block;">Stufenpärchenwahl deaktivieren</div>
	</div>
</div>

<div class="template" id="news-template">
	<div id="{id}-news-search-display" class="admin-search-display polls-search-display" style="display: none; margin-bottom: 0px;"></div>
	<div id="{id}-news-spinnercontainer" class="news-spinnercontainer"><div class="spinner_1" id="{id}-news-spinner" style="display: inline-block;"></div></div>
</div>

<div class="template" id="admin-elec-template">
	<div class="popup-window-tabbar-content" id="{id}-tabbar-content">
		<div class="popup-window-tabbar-template" id="{id}-tab3-content">
			<div style="width: 100%;">
				<div class="admin-electionlist" id="{id}-admin-pollslist"></div>
				<div class="admin-elections-info">
					Ergebnisse:<br/>
					<span class="admin-election-vote" id="{id}-admin-polls-result1"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-polls-result2"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-polls-result3"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-polls-result4"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-polls-result5"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-polls-result6"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-polls-result7"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-polls-result8"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-polls-result9"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-polls-result10"></span><br/>
					<br/>
					<span class="admin-election-vote" id="{id}-admin-polls-votes"></span><br/>
					<br/>
					<div class="button_1" style="display: inline-block; width: 170px;" onclick="deletePoll()">Umfrage löschen</div>
				</div>
			</div>
		</div>
		<div class="popup-window-tabbar-template" id="{id}-tab4-content">
			<div style="width: 100%;">
				<div class="admin-elections-info" style="margin-left: 0px; width: 600px;">
					Ergebnisse:<br/>
					<span class="admin-election-vote" id="{id}-admin-couple-result1"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-couple-result2"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-couple-result3"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-couple-result4"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-couple-result5"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-couple-result6"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-couple-result7"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-couple-result8"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-couple-result9"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-couple-result10"></span><br/>
					<br/>
					<span class="admin-election-vote" id="{id}-admin-couple-votes"></span><br/>
				</div>
			</div>
		</div>
		<div class="popup-window-tabbar-template" id="{id}-tab5-content">
			<div style="width: 100%;">
				<div class="admin-electionlist" id="{id}-admin-electionslist"></div>
				<div class="admin-elections-info">
					Ergebnisse:<br/>
					<span class="admin-election-vote" id="{id}-admin-elections-result1"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-elections-result2"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-elections-result3"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-elections-result4"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-elections-result5"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-elections-result6"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-elections-result7"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-elections-result8"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-elections-result9"></span><br/>
					<span class="admin-election-vote" id="{id}-admin-elections-result10"></span><br/>
					<br/>
					<span class="admin-election-vote" id="{id}-admin-elections-votes"></span><br/>
					<br/>
					<div class="button_1" style="display: inline-block; width: 170px;" onclick="deleteTeacherElection()">Wahl löschen</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="template"  id="admin-elec-template-tabbar">
	<div class="popup-window-tabbar" id="{id}-tabbar">
		<div class="popup-window-tabbar-tab" id="{id}-tab3" onclick="openTab(event)">Umfragen</div><!--
		--><div class="popup-window-tabbar-tab" id="{id}-tab4" onclick="openTab(event)">Stufenpärchen</div><!--
		--><div class="popup-window-tabbar-tab" id="{id}-tab5" onclick="openTab(event)">Lehrerwahlen</div>
	</div>
</div>