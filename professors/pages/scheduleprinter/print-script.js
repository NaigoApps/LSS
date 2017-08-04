
/* global angular */
/* global schedule_id */

var app = angular.module('lss-db', []);
app.controller("timelineController", ['$http', '$scope', function ($http, $scope) {

        $scope.showTodo = true;
        $scope.showAssigned = true;
        $scope.showDone = true;

        $scope.months = {
            content: [
                {name: 'Settembre', number: 9},
                {name: 'Ottobre', number: 10},
                {name: 'Novembre', number: 11},
                {name: 'Dicembre', number: 12},
                {name: 'Gennaio', number: 1},
                {name: 'Febbraio', number: 2},
                {name: 'Marzo', number: 3},
                {name: 'Aprile', number: 4},
                {name: 'Maggio', number: 5},
                {name: 'Giugno', number: 6},
                {name: 'Luglio', number: 7},
                {name: 'Agosto', number: 8}
            ]
        };

        $scope.schedule = {
            content: []
        };

        $scope.exit = function () {
            window.location.replace("../../main.php");
        };

        $scope.print = function () {
            window.print();
        };

        $scope.onShowTodo = function () {
            $scope.showTodo = true;
            $scope.doUpdateView();
        };

        $scope.onHideTodo = function () {
            $scope.showTodo = false;
            $scope.doUpdateView();
        };

        $scope.onShowAssigned = function () {
            $scope.showAssigned = true;
            $scope.doUpdateView();
        };

        $scope.onHideAssigned = function () {
            $scope.showAssigned = false;
            $scope.doUpdateView();
        };

        $scope.onShowDone = function () {
            $scope.showDone = true;
            $scope.doUpdateView();
        };

        $scope.onHideDone = function () {
            $scope.showDone = false;
            $scope.doUpdateView();
        };

        $scope.merge = function (left, right) {
            var result = [];
            while (left.length && right.length) {
                if (left[0].date <= right[0].date) {
                    result.push(left.shift());
                } else {
                    result.push(right.shift());
                }
            }
            while (left.length) {
                result.push(left.shift());
            }
            while (right.length) {
                result.push(right.shift());
            }
            return result;
        };
        $scope.mergeSort = function (arr) {
            if (arr.length < 2) {
                return arr;
            }
            var middle = parseInt(arr.length / 2);
            var left = arr.slice(0, middle);
            var right = arr.slice(middle, arr.length);
            return $scope.merge($scope.mergeSort(left), $scope.mergeSort(right));
        };
        $scope.sortSchedule = function () {
            $scope.schedule.elements = $scope.mergeSort($scope.schedule.elements);
        };
        $scope.loadSchedule = function () {
            $http.post('../../../common/php/ajax/load-schedule.php', {id: schedule_id})
                    .then(
                            function (rx) {
                                $scope.schedule = rx.data;
                                $scope.schedule.year2 = parseInt($scope.schedule.year) + 1;
                                $scope.schedule.elements.forEach(function (element, index) {
                                    var d = new Date();
                                    d.setTime(element.date * 1000);
                                    element.date = d;
                                });
                                $scope.doUpdateView();
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };

        $scope.isElementVisible = function (element) {
            return element.status === "todo" && $scope.showTodo ||
                    element.status === "assigned" && $scope.showAssigned ||
                    element.status === "done" && $scope.showDone;
        };

        $scope.doUpdateView = function () {
            $scope.sortSchedule();

            $scope.months.content.forEach(function (month) {
                month.elements = 0;
            });

            var lastModule = -1;
            var lastTopic = -1;
            var lastModuleMonth = -1;
            var lastTopicMonth = -1;

            $scope.schedule.elements.forEach(function (element) {
                if ($scope.isElementVisible(element)) {
                    $scope.months.content.forEach(function (month) {
                        if (month.number === element.date.getMonth() + 1) {
                            month.elements++;
                        }
                    });
                    if (element.date.getMonth() !== lastModuleMonth ||
                            element.element.parent.parent.id !== lastModule) {
                        element.moduleVisible = true;
                        lastModuleMonth = element.date.getMonth();
                        lastModule = element.element.parent.parent.id;
                    }
                    if (element.date.getMonth() !== lastTopicMonth ||
                            element.element.parent.id !== lastTopic) {
                        element.topicVisible = true;
                        lastTopicMonth = element.date.getMonth();
                        lastTopic = element.element.parent.id;
                    }
                }
            });


        };

        $scope.loadSchedule();
    }]);
