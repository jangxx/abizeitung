<div class="template" id="settings-template">
	<div class="popup-window-sectioninfo">Passwort &auml;ndern</div>
	<div class="popup-window-sectionblock">
		<div class="input">
			<input class="input-field" id="{id}-input_oldpw" type="password" placeholder="Altes Passwort" onkeypress="saveOnEnter(event)">
		</div>
		<div class="input">
			<input class="input-field" id="{id}-input_newpw" type="password" placeholder="Neues Passwort" onkeypress="saveOnEnter(event)">
		</div>
		<div class="input">
			<input class="input-field" id="{id}-input_retpw" type="password" placeholder="Neues Passwort wiederholen" onkeypress="saveOnEnter(event)">
		</div>
		<div class="popup-window-general-footer">
			<div class="popup-window-spinner-container" id="{id}-settings-spinner-container"></div>
			<div class="button_1" style="display: inline-block;" onclick="savePW()">Speichern</div>
		</div>
	</div>
	<div class="popup-window-sectioninfo">Profilbild</div>
	<div class="popup-window-sectionblock">
		<div class="input">
			<input class="input-field" id="{id}-input_pic" type="test" placeholder="Facebook ID in der Form 'fb:&lt;id&gt;' oder URL">
		</div>
		<div class="popup-window-general-footer">
			<div class="popup-window-spinner-container" id="{id}-settings-spinner-container-2"></div>
			<div class="button_1" style="display: inline-block;" onclick="savePic()">Speichern</div>
		</div>
	</div>
	<div class="popup-window-sectioninfo">Andere Einstellungen</div>
	<div class="popup-window-sectionblock">
		<input type="checkbox" id="{id}-settings-parallax-checkbox">
		<div style="display: inline-block;">Parallaxes Scrolling</div><br/>
		<input type="checkbox" id="{id}-settings-keepalive-checkbox">
		<div style="display: inline-block;">Session aufrecht erhalten</div><br/>
		<input type="checkbox" id="{id}-settings-jssearch-checkbox">
		<div style="display: inline-block;">Javascript Suche</div><br/>
		<input type="checkbox" id="{id}-settings-hwaccwin-checkbox">
		<div id="{id}-settings-hwaccwin-label" style="display: inline-block;">Hardwarebeschleunigte Fenster</div>
	</div>
</div>

<div class="template" id="settings-loading-template">
	<div id="{id}-settings-loading-spinnercontainer" class="settings-spinnercontainer"><div class="spinner_1" id="{id}-settings-spinner" style="display: inline-block;"></div></div>
</div>