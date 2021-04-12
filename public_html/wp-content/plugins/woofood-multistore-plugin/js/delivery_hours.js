(function($) { 
"use strict"; 
$(document).ready(function($){
$('.ui-timepicker-input').timepicker({ timeFormat: 'H:i:s',  maxHour: 20, maxHour: 24,  show2400: true  
 });
$( document ).ajaxComplete(function($) {
	$('.ui-timepicker-input').timepicker({ timeFormat: 'H:i:s',  maxHour: 20, maxHour: 24,  show2400: true  
 });
 });
});
})(jQuery);
