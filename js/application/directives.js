var directives = angular.module('DirectivesModule', []);

directives.directive('animation', function () {
    return {
        restrict: 'E',
        link: function (scope, elem, attr) {
            var className = elem.children()[0].className;
            $('.' + className).removeClass(attr.animation);
            var target = $(elem);

            if (attr.for) {
                className = attr.for;
            }

            var offset = '50%';
            if (attr.offset) {
                offset = attr.offset;
            }

            var waypoint = new Waypoint({
                element: target,
                handler: function () {
                    $('.' + className).addClass(attr.animation);
                },
                offset: offset
            });
        }
    }
});



directives.directive('location', function () {
    return {
        restrict: 'E',
        replace: true,
        template: '<div class="locationItem">' +
                '<div class="row">' +
                '<a ng-href="#/trasa/{{ location.alias}}">' +
                '<img class="img-responsive locationThumbnail" ng-src="images/locations/{{ location.alias }}_0.jpg" alt="">' +
                '</a>' +
                '</div>' +
                '<div class="row locationThumbDetails">' +
                '<div class="col-md-12 col-sm-12">' +
                '<a ng-href="#/trasa/{{ location.alias}}">' +
                '<h3 class="locationNameThumb">{{ location.name}}</h3>' +
                '</a>' +
                '</div>' +
                '</div>' +
                '</div>'
    };
});

directives.directive('locationGallery', function () {
    return {
        restrict: 'E',
        replace: true,
        template: '<div class="row locationImages">' +
                '<div class="container-fluid">' +
                '<div class="row">' +
                '<div class="col-md-8 col-md-offset-1">' +
                '<div class="row">' +
                '<div class="col-md-4 col-sm-6" ng-repeat="n in countOfImages">' +
                '<animation animation="locationGallery" offset="50%">' +
                '<div class="pic">' +
                '<a ng-href="http://localhost/zzz/images/locations/{{ location.alias }}_{{ $index }}.jpg" class="gallery-item">' +
                '<img class="img-responsive img-thumbnail locationPicture" ' +
                'ng-src="http://localhost/zzz/images/locations/{{ location.alias }}_{{ $index }}.jpg" alt="">' +
                '<div class="img_overlay "></div>' +
                '</a>' +
                '</div>' +
                '</animation>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>'
    };
});

directives.directive('gallery', function () {
    return {
        restrict: 'A',
        link: function ()
        {
            $('.gallery-item').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        }
    };
});

directives.directive('renderGallery', function ($timeout) {
    return {
        link: function postLink(elem, attrs, transclude) {
            $timeout(function () {
                $timeout(function () {

                    $('.gallery-item').magnificPopup({
                        type: 'image',
                        gallery: {
                            enabled: true
                        }
                    });


                }, 500);
            }, 500);
        }
    };
});


directives.directive('slider', function ($timeout) {
    return {
        restrict: 'AE',
        replace: true,
        scope: {
            images: '='
        },
        link: function (scope, elem, attrs) {
            scope.currentIndex = 0;

            scope.next = function () {
                scope.currentIndex < scope.images.length - 1 ? scope.currentIndex++ : scope.currentIndex = 0;
            };

            scope.prev = function () {
                scope.currentIndex > 0 ? scope.currentIndex-- : scope.currentIndex = scope.images.length - 1;
            };

            scope.$watch('currentIndex', function () {

                if (scope.images) {
                    scope.images.forEach(function (image) {
                        image.visible = false;
                    });
                }
                if (scope.images && scope.images[scope.currentIndex]) {
                    scope.images[scope.currentIndex].visible = true;
                }
            });

            /* Start: For Automatic slideshow*/

            var timer;

            var sliderFunc = function () {
                timer = $timeout(function () {
                    scope.next();
                    timer = $timeout(sliderFunc, 50000);
                }, 50000);
            };

            sliderFunc();

            scope.$on('$destroy', function () {
                $timeout.cancel(timer);
            });

            /* End : For Automatic slideshow*/

        },
        templateUrl: 'templates/templateurl.html'
    }
});


directives.directive('formValidator', function ($timeout) {
    return {
        //    templateUrl: 'partials/timeline.html',
        link: function postLink(elem, attrs, transclude) {
            $timeout(function () {
                $timeout(function () {

                    // form validation
                    if ($("input,textarea")) {
                        /*
                         Jquery Validation using jqBootstrapValidation
                         example is taken from jqBootstrapValidation docs 
                         */
                        $(function () {

                            $("input,textarea").jqBootstrapValidation({
                                preventSubmit: true,
                                submitError: function ($form, event, errors) {
                                    // something to have when submit produces an error ?
                                    // Not decided if I need it yet
                                },
                                submitSuccess: function ($form, event) {
                                    event.preventDefault(); // prevent default submit behaviour
                                    // get values from FORM
                                    var name = $("input#name").val();
                                    var email = $("input#email").val();
                                    var message = $("textarea#message").val();
                                    var firstName = name; // For Success/Failure Message
                                    // Check for white space in name for Success/Fail message
                                    if (firstName.indexOf(' ') >= 0) {
                                        firstName = name.split(' ').slice(0, -1).join(' ');
                                    }
                                    $.ajax({
                                        url: "contact_me.php",
                                        type: "POST",
                                        data: {
                                            name: name,
                                            email: email,
                                            message: message
                                        },
                                        cache: false,
                                        success: function () {
                                            // Success message
                                            $('#success').html("<div class='alert alert-success'>");
                                            $('#success > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                                                    .append("</button>");
                                            $('#success > .alert-success')
                                                    .append("<strong>Your message has been sent. </strong>");
                                            $('#success > .alert-success')
                                                    .append('</div>');

                                            //clear all fields
                                            $('#contactForm').trigger("reset");
                                        },
                                        error: function () {
                                            // Fail message
                                            $('#success').html("<div class='alert alert-danger'>");
                                            $('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                                                    .append("</button>");
                                            $('#success > .alert-danger').append("<strong>Sorry " + firstName + " it seems that my mail server is not responding...</strong> Could you please email me directly to <a href='mailto:me@example.com?Subject=Message_Me from myprogrammingblog.com;>me@example.com</a> ? Sorry for the inconvenience!");
                                            $('#success > .alert-danger').append('</div>');
                                            //clear all fields
                                            $('#contactForm').trigger("reset");
                                        },
                                    })
                                },
                                filter: function () {
                                    return $(this).is(":visible");
                                },
                            });

                            $("a[data-toggle=\"tab\"]").click(function (e) {
                                e.preventDefault();
                                $(this).tab("show");
                            });
                        });


                        /*When clicking on Full hide fail/success boxes */
                        $('#name').focus(function () {
                            $('#success').html('');
                        });
                    }
                    // end of form validation

                }, 100);
            }, 100);
        }
    };
});