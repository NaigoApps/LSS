var app = angular.module('lss-db', []);

app.controller("timelineController", ['$http', '$scope', '$rootScope', function ($http, $scope, $rootScope) {

        $scope.timeline = data;
        for (var i = 0; i < $scope.timeline.length; i++) {
            $scope.timeline[i].start = new Date($scope.timeline[i].start);
        }

        $scope.mesi = [
            {nome: 'Settembre', numero: 8},
            {nome: 'Ottobre', numero: 9},
            {nome: 'Novembre', numero: 10},
            {nome: 'Dicembre', numero: 11},
            {nome: 'Gennaio', numero: 0},
            {nome: 'Febbraio', numero: 1},
            {nome: 'Marzo', numero: 2},
            {nome: 'Aprile', numero: 3},
            {nome: 'Maggio', numero: 4},
            {nome: 'Giugno', numero: 5}
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

        //MAIN
        $rootScope.$emit('load-table',
                {
                    target: $scope.moduli
                });
    }]);

$(document).ready(function () {
    $(".success-message").click(function () {
        $(this).hide();
    });
    $(".error-message").click(function () {
        $(this).hide();
    });
});
