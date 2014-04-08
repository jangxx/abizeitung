<div class="template" id="popup-template">
	<div class="popup-window" id="{id}-main" draggable="true">
		<div class="popup-window-header" id="{id}-header" onmousedown="winDragStart(event)">
			{title}
			<div class="popup-window-header-close" onclick="closeOnClick(event,{id})" onmousedown="closeHelper(event)"></div>
		</div>
		<div class="popup-window-error" id="{id}-info"></div>
		<div class="popup-window-tabarea" id="{id}-tabs"></div>
		<div class="popup-window-body" id="{id}-body"></div>
	</div>
</div>