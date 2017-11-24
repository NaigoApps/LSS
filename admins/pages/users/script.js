/* global angular */


function prettyConfirm(title, text, callback) {
    swal({
        title: title,
        text: text,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55"
    }, callback);
}


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
        
        $scope.areAdminsVisible = false;
        $scope.areProfessorsVisible = false;
        $scope.areStudentsVisible = false;
        $scope.areUnassignedVisible = true;

        $scope.exit = function () {
            prettyConfirm("Esci", "Tornare al menu principale?", function (ok) {
                if (ok) {
                    window.location.replace("../../main.php");
                }
            });
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
                var user = $scope.users.content[i];
                if($scope.areAdminsVisible && $scope.isAdmin(user) ||
                        $scope.areProfessorsVisible && $scope.isProfessor(user) ||
                        $scope.areStudentsVisible && $scope.isStudent(user) ||
                        $scope.areUnassignedVisible && $scope.isUnassigned(user)){
                    $scope.visibleUsers.content.push(user);
                }
            }
        };
        
        $scope.showAdmins = function(val){
            $scope.areAdminsVisible = val;
            $scope.updateView();
        };
        
        $scope.showProfessors = function(val){
            $scope.areProfessorsVisible = val;
            $scope.updateView();
        };
        
        $scope.showStudents = function(val){
            $scope.areStudentsVisible = val;
            $scope.updateView();
        };
        
        $scope.showUnassigned = function(val){
            $scope.areUnassignedVisible = val;
            $scope.updateView();
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

        $scope.isUnassigned = function (user) {
            return !($scope.isAdmin(user) || 
                    $scope.isProfessor(user) || 
                    $scope.isStudent(user));
        };
        
        $scope.shownUsers = function (){
            var strings = [];
            if($scope.areAdminsVisible){
                strings.push("Amministratori");
            }
            if($scope.areProfessorsVisible){
                strings.push("Docenti");
            }
            if($scope.areStudentsVisible){
                strings.push("Studenti");
            }
            if($scope.areUnassignedVisible){
                strings.push("Non assegnati");
            }
            return strings.join(", ");
        }

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
