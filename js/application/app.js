var app = angular.module('AppModule', ['infinite-scroll', 'ui-rangeSlider', 'ControllersModule', 'ServicesModule', 'FiltersModule', 'DirectivesModule']);
//var app = angular.module('AppModule', ['ngRoute', 'ngResource', 'ngAnimate', 'ngSanitize' , 'ControllersModule']);


app.constant("config", {
    "url": "http://localhost/zzz/",
    "port": "80"
});


app.config(function ($routeProvider) {
    $routeProvider.
        when('/', {
            templateUrl: 'views/index.html',
            controller: 'HomeController',
        }).
        when('/trasa/:alias', {
            templateUrl: 'views/location.html',
            controller: 'LocationController',
        }).
        when('/trasy/kraje/:county', {
            templateUrl: 'views/index.html',
            controller: 'CountyLocationsController',
        }).
        when('/galeria', {
            templateUrl: 'views/galery.html'
        }).
        when('/kontakt', {
            templateUrl: 'views/contacts.html',
            controller: 'ContactController',
        }).
        otherwise({redirectTo: '/'});
});

