<div class="template" id="elections-template">
	<div class="popup-window-tabbar-content" id="{id}-tabbar-content">
		<div class="popup-window-tabbar-template" id="{id}-tab1-content">
			<div style="width: 100%;">
				<div class="elections-nameslist-left" id="{id}-elections-couple-nameslist1"></div>
				<div class="elections-nameslist-right" id="{id}-elections-couple-nameslist2"></div>
				<div class="elections-info">
					Stufenpärchen:<br/>
					<span class="elections-vote" id="{id}-elections-couple-p1"></span><br/>
					&<br/>
					<span class="elections-vote" id="{id}-elections-couple-p2"></span><br/>
					<div class="button_1" id="{id}-elections-couple-savebutton" style="display: inline-block;" onclick="saveCoupleVote()">Speichern</div>
				</div>
			</div>
		</div>
		<div class="popup-window-tabbar-template" id="{id}-tab2-content">
			<div class="elections-nameslist-left" id="{id}-elections-teacher-elections"></div>
			<div class="elections-nameslist-right" id="{id}-elections-teacher-nameslist"></div>
			<div class="elections-info">
				Stimme für<br/>
				<span class="elections-vote" id="{id}-elections-vote"><< auswählen</span><br/>
				ab, mit<br/>
				<span class="elections-vote" id="{id}-elections-teacher">auswählen >></span><br/>
				<div class="button_1 button_disabled"  id="{id}-elections-teacher-savebutton" style="display: inline-block;" onclick="saveTeacherVote()">Speichern</div>
				<div class="elections-vote-image" id="{id}-elections-vote-image"></div>
			</div>
		</div>
	</div>
	
</div>

<div class="template" id="elections-template-tabbar">
	<div class="popup-window-tabbar" id="{id}-tabbar">
		<div class="popup-window-tabbar-tab" id="{id}-tab1" onclick="openTab(event)">Stufenpärchen</div><!--
		--><div class="popup-window-tabbar-tab" id="{id}-tab2" onclick="openTab(event)">Lehrerwahlen</div>
	</div>
</div>