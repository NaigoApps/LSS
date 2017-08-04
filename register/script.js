
/* global angular */

var app = angular.module('lss-db', []);
app.controller("newUserController", ['$http', '$scope', function ($http, $scope) {

        $scope.name = "";
        $scope.surname = "";

        $scope.classes = {
            content: [],
            selected: undefined
        };

        $scope.onSelectClass = function (classroom) {
            $scope.classes.selected = classroom;
        };

        $scope.requestUser = function () {
            if ($scope.name && $scope.surname && $scope.classes.selected) {
                $http.post(
                        './ajax/create-user.php',
                        {
                            name: $scope.name,
                            surname: $scope.surname,
                            classroom: $scope.classes.selected.id
                        }
                ).then(
                        function (rx) {
                            swal({
                                title: "Registrazione completata",
                                text: "Attendere l'approvazione",
                                type: "success",
                                closeOnConfirm: false
                            },
                                    function () {
                                        window.location.replace("../index.php");
                                    });
                        },
                        function (rx) {
                            swal(rx.data);
                        }
                );
            } else {
                swal({
                    title: "Parametri",
                    text: "non corretti",
                    type: "error"});
            }
        };

        $scope.loadClasses = function () {
            $http.post('../common/php/ajax/load-classes.php')
                    .then(
                            function (rx) {
                                $scope.classes.content = rx.data;
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };

        //MAIN
        $scope.loadClasses();
    }]);