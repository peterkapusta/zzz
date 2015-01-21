// ***********************************
// totop
// ***********************************

$(function(){
	$().UItoTop({ easingType: 'easeOutQuart' });
});	


// ***********************************
// pageloader
// ***********************************
$(window).load(function(){
	if($(".preloader").length > 0){
		$('.preloader').fadeOut(1000); // set duration in brackets
	}
});


// ***********************************
// stop video when modal close
// ***********************************

$(function(){
  $("#video-modal").on('hide.bs.modal', function(evt){
    var player = $(evt.target).find('iframe'),
        vidSrc = player.prop('src');
    player.prop('src', ''); // to force it to pause
    player.prop('src', vidSrc);
  });
});

	
// ***********************************
// Backstretch - Slider on Background
//
//  Note :  make sure  use this  http://bootstrapwizard.info/Theme/Fullscreen/images/bg4.jpg"  when on server  but you can simply use  "images/bg1.jpg"  if you are on localhost
//
// ***********************************								  
			 
$("body.bg-slider").backstretch([
   "images/bg_1-velky.jpg",
], {duration: 5000, fade: 1000});
							  


// ****************************************************************
// counterUp
// ****************************************************************

$(function( $ ) {
	if($("span.count").length > 0){	
		$('span.count').counterUp({
			delay: 10, // the delay time in ms
			time: 1000 // the speed time in ms
		});
	}
});



