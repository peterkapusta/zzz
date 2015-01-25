// ***********************************
// totop
// ***********************************

$(function () {
    $().UItoTop({easingType: 'easeOutQuart'});
});


// ***********************************
// pageloader
// ***********************************
$(window).load(function () {
    if ($(".preloader").length > 0) {
        $('.preloader').fadeOut(1000); // set duration in brackets
    }
});


// ***********************************
// stop video when modal close
// ***********************************

$(function () {
    $("#video-modal").on('hide.bs.modal', function (evt) {
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


// Shuffles array elements
function shuffle(array) {
    var currentIndex = array.length, temporaryValue, randomIndex;

    // While there remain elements to shuffle...
    while (0 !== currentIndex) {

        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        // And swap it with the current element.
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }

    return array;
}

// Randomizes background images.
function randomBgImages() {
    var backgroundImages = [
        "images/bg/0.jpg",
        "images/bg/1.jpg",
        "images/bg/2.jpg",
        "images/bg/3.jpg",
        "images/bg/4.jpg",
    ];
    
    return shuffle(backgroundImages);
}


$("body.bg-slider").backstretch(randomBgImages(), {duration: 10000, fade: 1000});



// ****************************************************************
// counterUp
// ****************************************************************

$(function ($) {
    if ($("span.count").length > 0) {
        $('span.count').counterUp({
            delay: 10, // the delay time in ms
            time: 1000 // the speed time in ms
        });
    }
});



