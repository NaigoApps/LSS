/* global angular */

var app = angular.module('lss-db', []);

app.controller("userController", ['$http', '$scope', function ($http, $scope) {
        $scope.loadingCounter = 0;

        $scope.adminsChecked = true;
        $scope.studentsChecked = true;
        $scope.developersChecked = true;
        $scope.professorsChecked = true;
        $scope.othersChecked = true;

        $scope.users = {
            content: []
        };

        $scope.visibleUsers = {
            content: []
        };

        $scope.removeUser = function (user) {
            $http.post('./ajax/remove-user.php', {id: user.id}).then(
                    function (rx) {
                        swal("Eliminazione", "completata");
                        $scope.loadUsers();
                    },
                    function (rx) {
                        swal("Errore", rx.data);
                    });
        };

        $scope.updateView = function () {
            $scope.visibleUsers.content = [];
            for (var i = 0; i < $scope.users.content.length; i++) {
                $scope.visibleUsers.content.push($scope.users.content[i]);
            }
        };

        $scope.isAdmin = function (user) {
            return (parseInt(user.type) & 4) !== 0;
        };

        $scope.isStudent = function (user) {
            return (parseInt(user.type) & 2) !== 0;
        };

        $scope.isProfessor = function (user) {
            return (parseInt(user.type) & 1) !== 0;
        };

        $scope.toggleProfessor = function (usr) {
            var grant, revoke;
            if (!$scope.isProfessor(usr)) {
                grant = 1;
                revoke = 0;
            } else {
                grant = 0;
                revoke = 1;
            }
            $http.post(
                    './ajax/update-user-type.php',
                    {
                        id: usr.id,
                        grant: grant,
                        revoke: revoke
                    }
            ).then(
                    function (rx) {
                        swal("Modifica", "effettuata con successo");
                        $scope.loadUsers();
                    },
                    function (rx) {
                        swal("Errore", rx.data);
                    }
            );
        };

        $scope.toggleStudent = function (usr) {
            var grant, revoke;
            if (!$scope.isStudent(usr)) {
                grant = 2;
                revoke = 0;
            } else {
                grant = 0;
                revoke = 2;
            }
            $http.post(
                    './ajax/update-user-type.php',
                    {
                        id: usr.id,
                        grant: grant,
                        revoke: revoke
                    }
            ).then(
                    function (rx) {
                        swal("Modifica", "effettuata con successo");
                        $scope.loadUsers();
                    },
                    function (rx) {
                        swal("Errore", rx.data);
                    }
            );
        };

        $scope.toggleAdmin = function (usr) {
            var grant, revoke;
            if (!$scope.isAdmin(usr)) {
                grant = 4;
                revoke = 0;
            } else {
                grant = 0;
                revoke = 4;
            }
            $http.post(
                    './ajax/update-user-type.php',
                    {
                        id: usr.id,
                        grant: grant,
                        revoke: revoke
                    }
            ).then(
                    function (rx) {
                        swal("Modifica", "effettuata con successo");
                        $scope.loadUsers();
                    },
                    function (rx) {
                        swal("Errore", rx.data);
                    }
            );
        };

        $scope.loadUsers = function () {
            $http.post('../../../common/php/ajax/load-users.php').then(
                    function (rx) {
                        $scope.users.content = rx.data;
                        $scope.updateView();
                    },
                    function (rx) {
                        swal("Errore", rx.data);
                    }
            );
        };


        $scope.loadUsers();
    }]);
