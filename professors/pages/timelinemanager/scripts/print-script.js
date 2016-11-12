var app = angular.module('lss-db', []);

app.controller("timelineController", ['$http', '$scope', '$rootScope', function ($http, $scope, $rootScope) {

        $scope.mesi = [
            {nome: 'Settembre', numero: 9},
            {nome: 'Ottobre', numero: 10},
            {nome: 'Novembre', numero: 11},
            {nome: 'Dicembre', numero: 12},
            {nome: 'Gennaio', numero: 1},
            {nome: 'Febbraio', numero: 2},
            {nome: 'Marzo', numero: 3},
            {nome: 'Aprile', numero: 4},
            {nome: 'Maggio', numero: 5},
            {nome: 'Giugno', numero: 6}
        ];

        $scope.exit = function () {
            window.location.replace("../..");
        };

        $scope.print = function () {
            window.print();
        };

        $scope.errorMessage = function (message) {
            $scope.lastErrorMessage = message;
            $(".error-message").show();
        };
        $scope.successMessage = function (message) {
            $scope.lastSuccessMessage = message;
            $(".success-message").show();
        };

        $scope.assignPerformance = function (perf) {
            for (var i = 0; i < $scope.elements.length; i++) {
                if ($scope.elements[i].id === perf.id) {
                    var d = new Date();
                    d.setTime(perf.data * 1000);
                    $scope.elements[i].performance.push({
                        id: perf.idmateria,
                        data: d
                    });
                    if (perf.idmateria === $scope.timeline.idmateria) {
                        $scope.elements[i].performed = true;
                    }
                }
            }
        };
        
        $scope.reloadPerformances = function () {
            $http.post(
                    'includes/timeline/load_data.php',
                    {
                        command: 'load_performances',
                        classe: $scope.timeline.idclasse,
                        anno: $scope.timeline.anno
                    }
            ).then(
                    function (rx) {
                        var performances = rx.data;
                        for (var i = 0; i < performances.length; i++) {
                            $scope.assignPerformance(performances[i]);
                        }
                        $scope.sortTimeline();
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data.msg);
                    }
            );
        };

        $scope.sortTimeline = function () {
            $scope.elements.sort(function (a, b) {
                if (a.data <= b.data) {
                    return -1;
                }
                return 1;
            });
        };

        $scope.loadTimeline = function () {
            $http.post(
                    'includes/timeline/load_data.php',
                    {
                        command: 'load_timeline',
                        id: timeline_id
                    }
            ).then(
                    function (rx) {
                        $scope.timeline = rx.data.timeline;
                        $scope.elements = rx.data.elements;
                        for (var i = 0; i < $scope.elements.length; i++) {
                            var d = new Date();
                            d.setTime($scope.elements[i].data * 1000);
                            $scope.elements[i].data = d;
                            $scope.elements[i].performance = [];
                            $scope.elements[i].performed = false;
                        }
                        $scope.reloadPerformances();
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data.msg);
                    }
            );
        };
        
        $scope.loadTimeline();
    }]);

$(document).ready(function () {
    $(".success-message").click(function () {
        $(this).hide();
    });
    $(".error-message").click(function () {
        $(this).hide();
    });
});
