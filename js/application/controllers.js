var controllers = angular.module('ControllersModule', ['ngRoute', 'ngResource', 'ngAnimate', 'ngSanitize']);


controllers.controller('NavigationController', ['$scope', 'Counties',
    function ($scope, Counties) {
        $scope.counties = Counties.getCounties();

    }]);

controllers.controller('HomeController', ['$scope', 'Locations', 'Helper',
    function ($scope, Locations, Helper) {
        $scope.sectionHeader = "Náhodné trasy";

        Locations.query(function (data) {
            $scope.locations = Helper.arrayShuffle(data);


            $scope.images = [];
            $.each(data, function (index, location) {
                var image = {};
                image.src = 'images/locations/' + location.alias + '_0.jpg';
                image.title = '';
                $scope.images.push(image);
            });
        });
    }]);


controllers.controller('CountyLocationsController', ['$scope', '$routeParams', 'Locations', 'Counties', 'Helper',
    function ($scope, $routeParams, Locations, Counties, Helper) {
        window.scrollTo(0, 0);

        var county = Counties.getCounty($routeParams.county);
        $scope.sectionHeader = "Trasy v " + county.nameInForm + " kraji";
        Locations.getByCounties({
            name: $routeParams.county
        }, function (data) {
            $scope.locations = data;
            var countOfColumns = 3;
            $scope.columns = Helper.numberVal(countOfColumns);
            var locationsListRows = Math.ceil($scope.locations.length / countOfColumns);
            $scope.rows = Helper.numberVal(locationsListRows);
        });

        $scope.filter = true;
        $scope.lengthFilter = false;
        $scope.countyFilter = false;

        $scope.counties = Counties.getCounties();

        var lengthFrom = 1500;
        var lengthTo = 60000;
        $scope.length = {
            min: lengthFrom,
            max: lengthTo,
            range: {
                min: 0,
                max: 200000,
            }
        };

        $scope.filterLength = function () {
            return function (location) {
                if (location.length > $scope.length.min && location.length < $scope.length.max) {
                    return location;
                }
                return null;
            };
        };

        $scope.changeColor = function (id, color) {
            var select = $("#" + id);
            select.attr('style', 'color:' + color + ' !important');
        };

        $scope.toggleFilter = function (type) {
            if (type === 'county') {
                $scope.countyFilter = !$scope.countyFilter;
                if (!$scope.countyFilter) {
                    $scope.search.county_name = '';
                } else {
                    $scope.changeColor('selectCountyFilter', 'rgb(160, 160, 160)');
                }
            } else if (type === 'difficulty') {
                $scope.difficultyFilter = !$scope.difficultyFilter;
                if (!$scope.difficultyFilter) {
                    $scope.search.difficulty = '';
                } else {
                    $scope.changeColor('selectDiffFilter', 'rgb(160, 160, 160)');
                }
            } else if (type === 'length') {
                $scope.lengthFilter = !$scope.lengthFilter;
                if (!$scope.lengthFilter) {
                    //$scope.search.difficulty = '';
                    $scope.length.min = lengthFrom;
                    $scope.length.max = lengthTo;

                } else {
                    //$scope.changeColor('selectDiffFilter', 'rgb(160, 160, 160)');
                }
            }
            ;
        };

    }]);


controllers.controller('LocationController', ['$scope', '$routeParams', '$sce', 'Locations', 'Helper',
    function ($scope, $routeParams, $sce, Locations, Helper) {
        window.scrollTo(0, 0);
        
        Locations.get({id: $routeParams.alias}, function (data) {
            $scope.location = data;
            var map = $scope.location.map;
            $scope.map = $sce.trustAsHtml(map);
            
            $scope.location.seen++;
            Locations.save({id:11}, $scope.location);
            var resizeMap = function () {
                var map = $scope.location.map;

                console.log(map);

                var width = '';
                var height = '';
                var iframeHeight = '';

                if (window.innerWidth < 800) {
                    height = '300';
                    iframeHeight = '572';
                } else if (window.innerWidth >= 800 && window.innerWidth < 1100) {
                    height = '350';
                    iframeHeight = '663';
                } else if (window.innerWidth >= 1100 && window.innerWidth < 1300) {
                    height = '480';
                    iframeHeight = '628';
                } else {
                    height = '600';
                    iframeHeight = '775';
                }

                width = document.getElementById('mapOuterWrapper').offsetWidth;
                console.log(width);

                var changedMap = map.replace(/640/g, width);

                changedMap = changedMap.replace('480', height);
                changedMap = changedMap.replace('628', iframeHeight);
                $scope.map = $sce.trustAsHtml(changedMap);
            }
            
            resizeMap();

            $(window).resize(function () {
                $scope.$apply(function () {
                    resizeMap();
                });
            });

            $scope.difficulty = function () {
                switch ($scope.location.difficulty) {
                    case "easy":
                        return {name: "Ľahká", style: $scope.location.difficulty};
                    case "medium":
                        return {name: "Stredne ťažká", style: $scope.location.difficulty};
                    default:
                        return {name: "Ťažká", style: $scope.location.difficulty};
                }
            }

            var count = parseInt($scope.location.images);
            $scope.countOfImages = Helper.numberVal(count);

            $('.gallery-item').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        });
    }]);


controllers.controller('SearchForLocationController', ['$scope', '$routeParams', 'Locations', 'Counties',
    function ($scope, $routeParams, Locations, Counties) {

        $scope.sectionHeader = "Nájdi svoju trasu";
        window.scrollTo(0, 0);

        $scope.filter = true;
        $scope.lengthFilter = false;
        $scope.countyFilter = false;

        $scope.counties = Counties.getCounties();

        var lengthFrom = 0;
        var lengthTo = 100000;
        $scope.length = {
            min: lengthFrom,
            max: lengthTo,
            range: {
                min: 0,
                max: 100000
            }
        };

        $scope.filterLength = function () {
            return function (location) {
                if (location.length > $scope.length.min && location.length < $scope.length.max) {
                    return location;
                }
                return null;
            };
        }

        $scope.changeColor = function (id, color) {
            var select = $("#" + id);
            select.attr('style', 'color:' + color + ' !important');
        };

        $scope.toggleFilter = function (type) {
            if (type === 'county') {
                $scope.countyFilter = !$scope.countyFilter;
                if (!$scope.countyFilter) {
                    $scope.search.county_name = '';
                } else {
                    $scope.changeColor('selectCountyFilter', 'rgb(160, 160, 160)');
                }
            } else if (type === 'difficulty') {
                $scope.difficultyFilter = !$scope.difficultyFilter;
                if (!$scope.difficultyFilter) {
                    $scope.search.difficulty = '';
                } else {
                    $scope.changeColor('selectDiffFilter', 'rgb(160, 160, 160)');
                }
            } else if (type === 'length') {
                $scope.lengthFilter = !$scope.lengthFilter;
                if (!$scope.lengthFilter) {
                    //$scope.search.difficulty = '';
                    $scope.length.min = lengthFrom;
                    $scope.length.max = lengthTo;

                } else {
                    //$scope.changeColor('selectDiffFilter', 'rgb(160, 160, 160)');
                }
            }
            ;
        };

        Locations.query(function (data) {
            $scope.locations = data;
        });

    }]);


controllers.controller('ContactController', ['$scope', 'Helper', '$http',
    function ($scope, Helper, $http) {
        window.scrollTo(0, 0);
        Helper.validateForm();
    }]);
