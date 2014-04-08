<div class="template" id="desc-template">
	<div class="popup-window-sectioninfo">Allgemein</div>
	<div class="popup-window-sectionblock">
		<div class="input">
			<input class="input-field" id="{id}-input_nickname" type="text" placeholder="Spitzname" onkeypress="nextOnEnter(event)">
		</div>
		<div class="input">
			<input class="input-field" id="{id}-input_lifemotto" type="text" placeholder="Lebensmotto" onkeypress="nextOnEnter(event)">
		</div>
		<div class="input">
			G8<input type="radio" style="width: 30%;" name="{id}-desc-g89-radio" value="g8"><br/>
			G9<input type="radio" style="width: 30%;" name="{id}-desc-g89-radio" value="g9">
		</div>
		<div class="input">
			<input class="input-field" style="width: 29%;" id="{id}-input_dayob" type="text" placeholder="TT" onkeypress="nextOnEnter(event)" maxlength="2">:
			<input class="input-field" style="width: 29%;" id="{id}-input_monthob" type="text" placeholder="MM" onkeypress="nextOnEnter(event)" maxlength="2">:
			<input class="input-field" style="width: 29%;" id="{id}-input_yearob" type="text" placeholder="JJJJ" onkeypress="nextOnEnter(event)" maxlength="4">
		</div>
	</div>
	<div class="popup-window-sectioninfo">Zukunftspläne</div>
	<div class="popup-window-sectionblock">
		<div class="desc-futureplan">
			<div class="input desc-fpa" style="width: 90%;">
				<input class="input-field" id="{id}-input_f1" type="text" placeholder="Zukunftsplan" onkeypress="nextOnEnter(event)" name="{id}-futureplan">
			</div><!--
			--><div class="desc-mini-button desc-fpa" style="margin-left: 10px;" onclick="addFuturePlanField()">+</div>
		</div>
		<div id="{id}-desc-futureplan-collection" class="desc-futureplan-collection">
		</div>
	</div>
	<div class="popup-window-sectioninfo">Abiturfächer</div>
	<div class="popup-window-sectionblock">
		<div class="input">
			<input class="input-field" id="{id}-input_ac1" type="text" placeholder="Abiturfach 1 (LK)" onkeypress="nextOnEnter(event)">
			<input class="input-field" id="{id}-input_ac2" type="text" placeholder="Abiturfach 2 (LK)" onkeypress="nextOnEnter(event)">
			<input class="input-field" id="{id}-input_ac3" type="text" placeholder="Abiturfach 3 (GK)" onkeypress="nextOnEnter(event)">
			<input class="input-field" id="{id}-input_ac4" type="text" placeholder="Abiturfach 4 (GK)" onkeypress="nextOnEnter(event)">
		</div>
	</div>
	<div class="popup-window-sectioninfo">Lieblings...</div>
	<div class="popup-window-sectionblock">
		<div class="input">
			<input class="input-field" style="width: 45%;" id="{id}-input_ld1" type="text" placeholder="Lieblings..." onkeypress="nextOnEnter(event)">:
			<input class="input-field" style="width: 45%;" id="{id}-input_l1" type="text" placeholder="" onkeypress="nextOnEnter(event)">
			<input class="input-field" style="width: 45%;" id="{id}-input_ld2" type="text" placeholder="Lieblings..." onkeypress="nextOnEnter(event)">:
			<input class="input-field" style="width: 45%;" id="{id}-input_l2" type="text" placeholder="" onkeypress="nextOnEnter(event)">
			<input class="input-field" style="width: 45%;" id="{id}-input_ld3" type="text" placeholder="Lieblings..." onkeypress="nextOnEnter(event)">:
			<input class="input-field" style="width: 45%;" id="{id}-input_l3" type="text" placeholder="" onkeypress="nextOnEnter(event)">
			<input class="input-field" style="width: 45%;" id="{id}-input_ld4" type="text" placeholder="Lieblings..." onkeypress="nextOnEnter(event)">:
			<input class="input-field" style="width: 45%;" id="{id}-input_l4" type="text" placeholder="" onkeypress="nextOnEnter(event)">
			<input class="input-field" style="width: 45%;" id="{id}-input_ld5" type="text" placeholder="Lieblings..." onkeypress="nextOnEnter(event)">:
			<input class="input-field" style="width: 45%;" id="{id}-input_l5" type="text" placeholder="" onkeypress="nextOnEnter(event)">
		</div>
	</div>
	<div class="popup-window-sectioninfo">Was ich schon immer loswerden wollte</div>
	<div class="popup-window-sectionblock">
		<!--<div class="input">-->
			<textarea class="input-textarea" id="{id}-input_aboutme" placeholder="Was ich schon immer loswerden wollte..." style="width:98%; padding-top: 0px; padding-bottom: 0px; height: 100px;" onkeypress="nextOnEnter(event)"></textarea>
		<!--</div>-->
	</div>
	<div class="popup-window-general-footer" style="line-height: 30px;">
		<div class="popup-window-spinner-container" id="{id}-desc-spinner-container" style="margin-top: 0px;"></div>
		<div class="button_1" style="display: inline-block; margin-top: 0px;" onclick="saveDesc()">Speichern</div>
	</div>
</div>

<div class="template" id="desc-loading-template">
	<div id="{id}-desc-spinnercontainer" class="desc-spinnercontainer"><div class="spinner_1" id="{id}-desc-spinner" style="display: inline-block;"></div></div>
</div>