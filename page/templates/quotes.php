<div class="template" id="quotes-template">
	<div class="popup-window-tabbar-content" id="{id}-tabbar-content">
		<div class="popup-window-tabbar-template" id="{id}-tab1-content">
			<textarea class="input-textarea" id="{id}-quote_text" type="text" placeholder="Zitat" style="width: 99%; padding-top: 0px; padding-bottom: 0px; height: 100px;"></textarea>
			<div class="input" style="height: 30px; overflow: hidden;">
				<input class="input-field" id="{id}-quote_context" type="text" placeholder="Lehrerkürzel + Kursnummer / Kontext (optional)" style="padding-top: 0px; padding-bottom: 0px">
			</div>
			<div class="popup-window-general-footer">
				<div class="popup-window-spinner-container" id="{id}-quotes-spinner-container"></div>
				<div class="button_1" style="display: inline-block;" onclick="saveQuote()">Speichern</div>
			</div>
		</div>
		<div class="popup-window-tabbar-template" id="{id}-tab2-content">
			<div class="quotes-myquotes-list" id="{id}-myquotes-list"></div>
		</div>
	</div>
	
</div>

<div class="template" id="quotes-template-tabbar">
	<div class="popup-window-tabbar" id="{id}-tabbar">
		<div class="popup-window-tabbar-tab" id="{id}-tab1" onclick="openTab(event)">Neues Zitat</div><!--
		--><div class="popup-window-tabbar-tab" id="{id}-tab2" onclick="openTab(event)">Meine Zitate</div>
	</div>
</div>