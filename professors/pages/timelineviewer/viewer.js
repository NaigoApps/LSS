var timeline;
/**
 * Move the timeline a given percentage to left or right
 * @param {Number} percentage   For example 0.1 (left) or -0.1 (right)
 */
function move(percentage) {
    var range = timeline.getWindow();
    var interval = range.end - range.start;

    timeline.setWindow({
        start: range.start.valueOf() - interval * percentage,
        end: range.end.valueOf() - interval * percentage
    });
}

/**
 * Zoom the timeline a given percentage in or out
 * @param {Number} percentage   For example 0.1 (zoom out) or -0.1 (zoom in)
 */
function zoom(percentage) {
    var range = timeline.getWindow();
    var interval = range.end - range.start;

    timeline.setWindow({
        start: range.start.valueOf() - interval * percentage,
        end: range.end.valueOf() + interval * percentage
    });
}


function prettyConfirm(title, text, callback) {
    swal({
        title: title,
        text: text,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55"
    }, callback);
}

function prettyPrompt(title, text, inputValue, callback) {
    swal({
        title: title,
        text: text,
        type: 'input',
        showCancelButton: true,
        inputValue: inputValue
    }, callback);
}

/* global angular */

var app = angular.module('lss-db', []);
app.controller("timelineController", ['$http', '$scope', '$rootScope', function ($http, $scope, $rootScope) {

        $scope.modules = {
            content: [],
            selected: undefined,
            searchString: ""
        };
        $scope.topics = {
            content: [],
            selected: undefined,
            searchString: ""
        };
        $scope.items = {
            content: [],
            selected: undefined,
            searchString: ""
        };

        $scope.materials = {
            content: []
        };

        $scope.files = {
            content: []
        };

        $scope.subjects = {
            content: []
        };

        $scope.singleMode = true;

        $scope.doneElements = true;
        $scope.todoElements = true;
        $scope.assignedElements = true;

        $scope.loaded = 0;

        $scope.timeline = undefined;

        $scope.schedules = {
            content: []
        };

        $scope.onToggleSchedule = function (schedule) {
            if (!schedule.visible) {
                schedule.visible = true;
            } else {
                schedule.visible = false;
            }
            $scope.buildView();
        };

        $scope.onShowDone = function () {
            $scope.doneElements = true;
            $scope.buildView();
        };

        $scope.onHideDone = function () {
            $scope.doneElements = false;
            $scope.buildView();
        };

        $scope.onShowAssigned = function () {
            $scope.assignedElements = true;
            $scope.buildView();
        };

        $scope.onHideAssigned = function () {
            $scope.assignedElements = false;
            $scope.buildView();
        };

        $scope.onShowTodo = function () {
            $scope.todoElements = true;
            $scope.buildView();
        };

        $scope.onHideTodo = function () {
            $scope.todoElements = false;
            $scope.buildView();
        };

        $scope.exit = function () {
            window.location.replace("../../main.php");
        };

        $scope.findById = function (vector, id) {
            for (var i = 0; i < vector.length; i++) {
                if (vector[i].id === id) {
                    return i;
                }
            }
            return -1;
        };
        $scope.findObjectById = function (vector, id) {
            var index = $scope.findById(vector, id);
            if (index !== -1) {
                return vector[index];
            }
            return undefined;
        };

        $scope.loadSubjects = function () {
            $http.post('../../../common/php/ajax/load-subjects.php')
                    .then(
                            function (rx) {
                                $scope.subjects.content = rx.data;
                                $scope.buildView();
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };

        $scope.loadSchedules = function () {
            $(".progress").show();
            $http.post('../../../common/php/ajax/load-related-schedules.php',
                    {
                        id: timeline_id
                    }
            ).then(
                    function (rx) {
                        $scope.schedules.content = rx.data;
                        $scope.schedules.content.forEach(function (schedule) {
                            if (parseInt(schedule.id) === timeline_id) {
                                schedule.visible = true;
                            }
                            schedule.year2 = parseInt(schedule.year) + 1;
                            schedule.elements.forEach(function (element) {
                                var d = new Date();
                                d.setTime(element.date * 1000);
                                element.date = d;
                            });
                        });
                        $scope.loadSubjects();
                        $(".progress").hide();
                    },
                    function (rx) {
                        swal(rx.data);
                    }
            );
        };

        $scope.subjectStatus = function (element, subject) {
            for (var i = 0; i < element.fullStatus.length; i++) {
                if (element.fullStatus[i].subject === subject.id) {
                    return element.fullStatus[i].status;
                }
            }
            return undefined;
        };
        $scope.subjectDate = function (element, subject) {
            for (var i = 0; i < element.fullStatus.length; i++) {
                if (element.fullStatus[i].subject === subject.id) {
                    return element.fullStatus[i].date;
                }
            }
            return -1;
        };

        $scope.buildContent = function (element) {
            var content = "";
            content += element.element.name + " - ";
            $scope.subjects.content.forEach(function (subject) {
                if ($scope.subjectStatus(element, subject) === "todo") {
                    content += '<span><div class="subject tooltip-base" style="background-color : gray;">';
                    content += '<span class="tooltip">' + subject.name + ', previsto : ' + $scope.simpleDateFormat($scope.subjectDate(element, subject)) + '</span>';
                    content += '</div>';
                    content += '</span>';
                } else if ($scope.subjectStatus(element, subject) === "assigned") {
                    content += '<span><div class="subject tooltip-base" style="background-color : lightGray;">';
                    content += '<span class="tooltip">' + subject.name + ', assegnato : ' + $scope.simpleDateFormat($scope.subjectDate(element, subject)) + '</span>';
                    content += '</div>';
                    content += '</span>';
                } else if ($scope.subjectStatus(element, subject) === "done") {
                    content += '<span><div class="subject tooltip-base" style="background-color :   #' + subject.color + ';">';
                    content += '<span class="tooltip">' + subject.name + ', svolto : ' + $scope.simpleDateFormat($scope.subjectDate(element, subject)) + '</span>';
                    content += '</div>';
                    content += '</span>';
                }
//                } else {
//                    content += '<span><div class="subject tooltip-base" style="background-color : black;">';
//                    content += '<span class="tooltip">' + subject.name + ', non previsto</span>';
//                    content += '</div>';
//                    content += '</span>';
//                }
            });
            return content;
        };
        $scope.buildView = function () {
            var data = [];
            var groups = [];
            var count = 1;
            $scope.schedules.content.forEach(function (schedule) {
                if (schedule.visible) {
                    var found = false;
                    for (var i = 0; i < groups.length; i++) {
                        if (groups[i].content === schedule.subject.name) {
                            found = true;
                        }
                    }
                    if (!found) {
                        groups.push(
                                {
                                    content: schedule.subject.name,
                                    id: schedule.subject.name,
                                    value: count
                                }
                        );
                        count++;
                    }
                    schedule.elements.forEach(function (element) {
                        if ($scope.doneElements && element.status === "done" ||
                                $scope.assignedElements && element.status === "assigned" ||
                                $scope.todoElements && element.status === "todo") {
                            data.push(
                                    {
                                        id: element.id,
                                        content: $scope.buildContent(element),
                                        start: element.date,
                                        group: schedule.subject.name
                                    }
                            );
                        }
                    });
                }
            });
            var container = document.getElementById('visualization');
            $("#visualization").html("");
            var options = {
                editable: false,
                min: new Date($scope.schedules.content[0].year, 7, 1),
                max: new Date(parseInt($scope.schedules.content[0].year) + 1, 6, 1),
                zoomMin: 1000 * 60 * 60 * 24 * 7,
                zoomMax: 1000 * 60 * 60 * 24 * 30,
                minHeight: 350,
                maxHeight: 650,
                groupOrder: function (a, b) {
                    return a.value - b.value;
                },
                groupOrderSwap: function (a, b, groups) {
                    var v = a.value;
                    a.value = b.value;
                    b.value = v;
                },
                groupEditable: true
            };
            $scope.timeline = new vis.Timeline(container, data, groups, options);
            $scope.timeline.setGroups(groups);
            $scope.timeline.on('select', function (properties) {
                $scope.schedules.content.forEach(function (schedule) {
                    var element = $scope.findObjectById(schedule.elements, properties.items[0]);
                    if (element !== undefined) {
                        $scope.currentElement = element.element;
                    }
                });
                if ($scope.currentElement !== undefined) {
                    $scope.updateCurrentElement();
                }
            });
            $('.vis-center>.vis-content').on('scroll', function () {
                $('.vis-left>.vis-content').scrollTop($(this).scrollTop());
            });
            $('.vis-left>.vis-content').on('scroll', function () {
                $('.vis-center>.vis-content').scrollTop($(this).scrollTop());
            });
        };

        $scope.updateCurrentElement = function () {
            $http.post('../../../common/php/ajax/load-all-materials.php', {element: $scope.currentElement.id})
                    .then(
                            function (rx) {
                                $scope.materials.content = rx.data;
                                $scope.materials.content.forEach(function (element) {
                                    if (element.file) {
                                        element.file.url = "http://localhost/LSS/materials/" + element.file.name;
                                    }
                                });
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
        };

        $scope.simpleDateFormat = function (date) {
            date = new Date(parseInt(date) * 1000);
            return date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear();
        };

        $scope.replaceCurrentElement = function (element) {
            $scope.currentElement = element;
            $http.post('../../../common/php/ajax/load-children-elements.php', {parent: $scope.currentElement.id})
                    .then(
                            function (rx) {
                                $scope.currentElement.children = rx.data;
                            },
                            function (rx) {
                                swal(rx.data);
                            }
                    );
            $scope.updateCurrentElement();
        };
// attach events to the navigation buttons
//        document.getElementById('zoomIn').onclick = function () {
//            zoom(-0.2);
//        };
//        document.getElementById('zoomOut').onclick = function () {
//            zoom(0.2);
//        };
//        document.getElementById('moveLeft').onclick = function () {
//            move(0.2);
//        };
//        document.getElementById('moveRight').onclick = function () {
//            move(-0.2);
//        };

        //alert(obj1.month + "-" + obj1.day + "-" + obj1.year);
        //alert(obj2.month + "-" + obj2.day + "-" + obj2.year);
        //timeline.setWindow(obj1.month + "-" + obj1.day + "-" + obj1.year, obj2.month + "-" + obj2.day + "-" + obj2.year);

        //MAIN

        $scope.loadSchedules();
    }]);
