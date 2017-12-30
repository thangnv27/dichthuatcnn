jQuery(document).ready(function($) {

	$('#content-slider2').cycle({
		fx:     'scrollUp', 
        prev:   '#prev2', 
        next:   '#next2',
		speed:  'fast', 
    	timeout: 0
	});
	if($('#slider').get().length){	$('#slider ul.slide') 	.before('<div id="nav"><p>') 	.cycle({ 		fx:     'fade', 		speed:  '3000', 		timeout: 1000, 		pager:  '#nav p',		next:   '#next',		prev:   '#prev'	});	}		if($('#ykienkhachh').get().length) {
	$('#ykienkhachh ul') 	.before('<div class="nav">') 	.cycle({ 		fx:     'turnDown', 		speed:  'fast', 		timeout: 0, 		pager:  '.nav' 
	});	}
		if($('#partnerct').get().length) {
	$('#partnerct ul') 
	.before('<div class="navpro">') 
	.cycle({ 
		fx:     'scrollUp', 
		speed:  'fast', 
		timeout: 0, 
		pager:  '.navpro' 
	});	}
    $('#flag_l').bind('click', function(e){
		$(this).toggleClass('m_active');
		e.stopPropagation();
	});
	$('#flag_l .flag_m').bind('click', function(e){
		e.stopPropagation();
	});
	$(document).bind('click', function(){
		$('#flag_l').removeClass('m_active');
	});
    
});