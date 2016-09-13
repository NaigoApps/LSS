var app = angular.module('lss-db', []);

app.controller("userController", ['$http', function ($http) {
        var scope = this;

        this.loadingCounter = 0;

        this.adminsChecked = true;
        this.studentsChecked = true;
        this.developersChecked = true;
        this.professorsChecked = true;
        this.othersChecked = true;

        this.users = [];

        this.visibleUsers = [];

        this.newUser = {};

        this.setNewUser = function (user) {
            if (user !== undefined) {
                scope.newUser.id = user.id;
                scope.newUser.email = user.email;
            } else {
                scope.newUser.id = -1;
                scope.newUser.email = "";
            }
        };

        this.addUser = function () {
            $http.post(
                    './usr-manager.php',
                    {command: 'add-user',
                        user: scope.newUser}
            ).then(function (rx) {
                scope.loadUsers();
            }
            );
        };

        this.delUser = function (userID) {
            $http.post(
                    './usr-manager.php',
                    {command: 'delete-user',
                        id: userID}
            ).then(function (rx) {
                scope.loadUsers();
            }
            );
        };

        this.updateView = function () {
            scope.visibleUsers = [];
            var users = scope.users;
            for (var i = 0; i < users.length; i++) {
                scope.visibleUsers.push(users[i]);
            }
        };

        this.isAdmin = function (user) {
            return (parseInt(user.type) & 4) !== 0;
        };

        this.isStudent = function (user) {
            return (parseInt(user.type) & 2) !== 0;
        };

        this.isProfessor = function (user) {
            return (parseInt(user.type) & 1) !== 0;
        };

        this.toggleProfessor = function (usr) {
            if (!scope.isProfessor(usr)) {
                cmd = 'add-professor';
            } else {
                cmd = 'remove-professor';
            }
            $http.post(
                    './usr-manager.php',
                    {command: cmd,
                        user: usr}
            ).then(function (rx) {
                scope.loadUsers();
            }
            );
        };

        this.toggleStudent = function (usr) {
            if (!scope.isStudent(usr)) {
                cmd = 'add-student';
            } else {
                cmd = 'remove-student';
            }
            $http.post(
                    './usr-manager.php',
                    {command: 'add-student',
                        user: usr}
            ).then(function (rx) {
                scope.loadUsers();
            }
            );
        };

        this.toggleAdmin = function (usr) {
            if (!scope.isAdmin(usr)) {
                cmd = 'add-admin';
            } else {
                cmd = 'remove-admin';
            }
            $http.post(
                    './usr-manager.php',
            {command: 'add-admin',
                    user: usr}
            ).then(function (rx) {
                scope.loadUsers();
            });
        };

        this.loadUsers = function () {
            $http.post(
                    './usr-manager.php',
                    {command: 'get-users'}
            ).then(function (rx) {
                scope.users = rx.data;
                scope.updateView();
            }
            );
        };


        this.loadUsers();
    }]);
