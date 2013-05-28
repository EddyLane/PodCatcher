$(document).ready(function() {
	initBase();
	initDrAcc();
	initFancyForm();
	initDatePicker();
	initEditor();
});
function initBase(){
	$('.bottom_tooltip').tooltip({
		placement: 'bottom'
	});
	$('.left_tooltip').tooltip({
		placement: 'left'
	});
	$('.right_tooltip').tooltip({
		placement: 'right'
	});
	$('.top_tooltip').tooltip();
	$('.dropdown-menu').click(function(event){
		event.stopPropagation();
	});
	$('a.dropdown-toggle, .dropdown-menu a').on('touchstart', function(event) {
		event.stopPropagation();
	});
	$('.spc').live('click', function(){
		$(".content_scroll").mCustomScrollbar("destroy");
		$(".content_scroll").mCustomScrollbar({
			scrollInertia:0
		});
	});
}

function initDrAcc() {
	$('.dropdown-2 .dropdown-toggle').dropdown();
	$('.dropdown-2 .dropdown-menu').each(function() {
		$(this).on('click', '.accordion-toggle', function(event) {
			event.stopPropagation();
			var $this = $(this);

			var parent = $this.data('parent');
			var actives = parent && $(parent).find('.collapse.in');

			// From bootstrap itself
			if (actives && actives.length) {
				hasData = actives.data('collapse');
				if (hasData && hasData.transitioning) return;
				actives.collapse('hide');
			}

			var target = $this.attr('data-target') || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, ''); //strip for ie7

			$(target).collapse('toggle');
			//if($this.parentsUntil('.dropdown-2 .dropdown-menu').length!==0){
			return false;
			//}
		});
	});
}

function initFancyForm(){
	$('.cu').uniform();
	$( ".cselect" ).chosen();
	$( ".chzn-select-deselect" ).chosen({allow_single_deselect:true});
}
function initDatePicker(){
	$('.dp').datepicker();
}

function initEditor(){
	$( ".full-editor" ).each(function(){
		$(this).redactor();
	});
	$( ".status-editor" ).each(function(){
		$(this).redactor({
			buttons: ['image', 'video', 'file', 'link']
		});
	});
	$( ".comment-editor" ).each(function(){
		$(this).redactor({
			buttons: ['bold', 'italic']
		});
	});
}

/*if (document.getElementById('withPieChart')) {*/
	var initPieChart = function() {
		$('.percentage').easyPieChart({
			animate: 500,
			size:120,
			lineWidth:10,
			lineCap:'square',
			barColor:'#bd362f',
			trackColor:'rgba(0,0,0,0.1)',
			scaleColor:'rgba(0,0,0,0.3)'
		});
	};
	initPieChart();
/*}*/