
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

        $scope.updateUser = function () {
            if ($scope.name && $scope.surname) {
                $http.post(
                        './ajax/update-user.php',
                        {
                            name: $scope.name,
                            surname: $scope.surname,
                            classroom: ($scope.classes.selected !== undefined) ? $scope.classes.selected.id : undefined
                        }
                ).then(
                        function (rx) {
                            swal({
                                title: "Aggiornamento completato",
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

        $scope.loadCurrentUser = function () {
            $http.post('./ajax/load-user.php')
                    .then(
                            function (rx) {
                                var user = rx.data;
                                $scope.name = user.name;
                                $scope.surname = user.surname;
                                $scope.classes.selected = $scope.findById($scope.classes.content, user.classroom.id);
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };

        $scope.findById = function (vector, id) {
            for (var i = 0; i < vector.length; i++) {
                if (vector[i].id === id) {
                    return vector[i];
                }
            }
            return -1;
        };

        //MAIN
        $scope.loadClasses();
        $scope.loadCurrentUser();
    }]);