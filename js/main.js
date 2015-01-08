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
   "images/bg_0.jpeg",
   "images/bg_1.jpg",
   "images/bg_2.jpg",
   "images/bg_3.jpg",
   "images/bg_4.jpg",
   "images/bg_6.jpg",
   "images/bg_7.jpg",
   "images/bg_8.jpg",
   "images/bg_9.jpg",
   "images/bg_10.jpg",
   "images/bg_11.jpg",
   "images/bg_12.jpg",
   "images/bg_13.jpg",
   "images/bg_14.jpg",
   "images/bg_15.jpg",
   "images/bg_16.jpg",
   "images/bg_17.jpg",
   "images/bg_18.jpg",
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



