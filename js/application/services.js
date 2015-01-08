
var services = angular.module('ServicesModule', []);

services.factory('Locations', ['$resource', function ($resource) {
        return $resource(
                'locations/:id',
                {id: '@id', name: '@name'},
        {
            getSuggested: {method: "GET", params: {type: "odporucane"}, isArray: true},
            getByCounties: {method: "GET", params: {type: "county", name: '@name'}, isArray: true},
        }
        );
    }]);


services.factory('Helper', function () {
    var res = {};
    res.numberVal = function (n) {
        var res = [];
        for (var i = 0; i < n; i++) {
            res.push(i);
        }
        return res;
    };


    res.arrayShuffle = function (array) {
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
    };

    res.validateForm = function () {
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
    };

    return res;
});

services.factory('Counties', function () {
    var res = {
        counties: [
            {name: "Banskobystrický", nameInForm: "Banskobystrickom", alias: "banskobystricky"},
            {name: "Bratislavský", nameInForm: "Bratislavskom", alias: "bratislavsky"},
            {name: "Košický", nameInForm: "Košickom", alias: "kosicky"},
            {name: "Nitriansky", nameInForm: "Nitrianskom", alias: "nitriansky"},
            {name: "Prešovský", nameInForm: "Prešovskom", alias: "presovsky"},
            {name: "Trenčiansky", nameInForm: "Trenčianskom", alias: "trenciansky"},
            {name: "Trnavský", nameInForm: "Trnavskom", alias: "trnavsky"},
            {name: "Žilinský", nameInForm: "Žilinskom", alias: "zilinsky"},
        ],
        getCounties: function () {
            return this.counties;
        },
        getCounty: function (alias) {
            for (var i = 0; i < this.counties.length; i++) {
                if (this.counties[i].alias === alias) {
                    return this.counties[i];
                }
            }

        }

    };
    return res;
});

