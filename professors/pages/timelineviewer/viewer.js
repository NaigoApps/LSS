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

        $scope.materie = {
            content: [],
            colors: ["orange", "cyan", "red", "green", "black"],
            name: "materie",
            selected: undefined
        };

        $scope.singleMode = true;

        $scope.doneElements = true;

        $scope.loaded = 0;

        $scope.schedules = {
            content: []
        };

        $scope.subjects = {
            content: [],
            selected: undefined
        };

        $scope.onToggleSchedule = function (schedule) {
            if (!schedule.visible) {
                schedule.visible = true;
            } else {
                schedule.visible = false;
            }
            $scope.buildView();
        };

        $scope.onDoneItems = function () {
            $scope.doneElements = true;
            $scope.buildView();
        };

        $scope.onUndoneItems = function () {
            $scope.doneElements = false;
            $scope.buildView();
        };

        $scope.exit = function () {
            window.location.replace("../../main.php");
        };
        $scope.initElement = function (id, name, date) {
            var element = {
                id: id,
                content: name,
                date: date,
                start: new Date(date.year, date.month - 1, date.day),
                performed: false,
                performance: []
            };
            for (var j = 0; j < $scope.materie.length; j++) {
                element.performance.push(
                        {
                            done: false,
                            on: undefined
                        }
                );
            }
            return element;
        };
        $scope.findById = function (vector, id) {
            for (var i = 0; i < vector.length; i++) {
                if (vector[i].id === id) {
                    return i;
                }
            }
            return -1;
        };
        $scope.findByTimelineId = function (vector, id) {
            for (var i = 0; i < vector.length; i++) {
                if (vector[i].timelineId === id) {
                    return i;
                }
            }
            return -1;
        };
        $scope.findObjectByTimelineId = function (vector, id) {
            var index = $scope.findByTimelineId(vector, id);
            if (index !== -1) {
                return vector[index];
            }
            return undefined;
        };

        $scope.loadTimeline = function (schedule) {
            $http.post('../../../common/php/ajax/load-schedule.php',
                    {
                        id: schedule.id
                    }
            ).then(
                    function (rx) {
                        timeline.elements = rx.data.elements;
                        for (var i = 0; i < timeline.elements.length; i++) {
                            timeline.elements[i].timelineId = timeline.metadata.idmateria + "-" + timeline.elements[i].id;
                            var d = new Date();
                            d.setTime(timeline.elements[i].data * 1000);
                            timeline.elements[i].data = d;
                            timeline.elements[i].performance = [];
                            timeline.elements[i].performed = false;
                        }
                        $scope.reloadPerformances(timeline);
                    },
                    function (rx) {
                        swal(rx.data);
                    }
            );
        };
        $scope.loadSchedules = function () {
            $(".progress").show();
            $http.post('../../../common/php/ajax/load-schedule.php',
                    {
                        command: 'load_timeline_sameclass',
                        id: timeline_id
                    }
            ).then(
                    function (rx) {
                        $scope.timelines = [];
                        for (var i = 0; i < rx.data.length; i++) {
                            rx.data[i].anno2 = parseInt(rx.data[i].anno) + 1;
                            $scope.timelines.push({
                                metadata: rx.data[i]
                            });
                        }
                        for (var i = 0; i < $scope.timelines.length; i++) {
                            $scope.loadTimeline($scope.timelines[i]);
                        }
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data.msg);
                    }
            );
        };
        $scope.reloadPerformances = function (timeline) {
            $http.post(
                    '../timelinemanager/includes/load_data.php',
                    {
                        command: 'load_performances',
                        classe: timeline.metadata.idclasse,
                        anno: timeline.metadata.anno
                    }
            ).then(
                    function (rx) {
                        var performances = rx.data;
                        for (var i = 0; i < performances.length; i++) {
                            $scope.assignPerformance(timeline, performances[i]);
                        }
                        $scope.$emit("timeline-loaded");
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data.msg);
                    }
            );
        };
        $scope.assignPerformance = function (timeline, perf) {
            for (var i = 0; i < timeline.elements.length; i++) {
                if (timeline.elements[i].id === perf.id) {
                    var d = new Date();
                    d.setTime(perf.data * 1000);
                    timeline.elements[i].performance.push({
                        id: perf.idmateria,
                        data: d
                    });
                    if (perf.idmateria === timeline.metadata.idmateria) {
                        timeline.elements[i].performed = true;
                    }
                }
            }
        };

        $scope.$on("timeline-loaded", function () {
            $scope.loaded++;
            if ($scope.loaded === $scope.timelines.length) {
                $scope.loaded = 0;
                $scope.buildView();
                $(".progress").hide();
            }
        });

        $scope.buildContent = function (element) {
            var content = "";
            content += element.nome + " - ";
            for (var i = 0; i < $scope.materie.content.length; i++) {
                var materia = $scope.materie.content[i];
                if ($scope.findById(element.performance, materia.id) !== -1) {
                    content += '<span><div class="subject tooltip-base" style="background-color : ' + $scope.materie.colors[i] + ';">';
                    content += '<span class="tooltip">' + materia.nome + ' : ' + $scope.simpleDateFormat($scope.findObjectById(element.performance, materia.id).data) + '</span>';
                    content += '</div>';
                    content += '</span>';
                }
            }
            return content;
        };
        $scope.buildView = function () {
            var data = [];
            var groups = [];
            var count = 1;
            for (var t = 0; t < $scope.timelines.length; t++) {
                if ($scope.timelines[t].visible) {
                    var found = false;
                    for (var i = 0; i < groups.length; i++) {
                        if (groups[i].content === $scope.timelines[t].metadata.nomemateria) {
                            found = true;
                        }
                    }
                    if (!found) {
                        groups.push(
                                {
                                    content: $scope.timelines[t].metadata.nomemateria,
                                    id: $scope.timelines[t].metadata.nomemateria,
                                    value: count
                                }
                        );
                        count++;
                    }
                    for (var i = 0; i < $scope.timelines[t].elements.length; i++) {
                        if (!$scope.doneElements || $scope.timelines[t].elements[i].performance.length > 0) {
                            data.push(
                                    {
                                        id: $scope.timelines[t].elements[i].timelineId,
                                        content: $scope.buildContent($scope.timelines[t].elements[i]),
                                        start: $scope.timelines[t].elements[i].data,
                                        group: $scope.timelines[t].metadata.nomemateria
                                    }
                            );
                        }
                    }
                }
            }
            var container = document.getElementById('visualization');
            $("#visualization").html("");
            var options = {
                editable: false,
                min: new Date($scope.timelines[0].metadata.anno, 7, 1),
                max: new Date(parseInt($scope.timelines[0].metadata.anno) + 1, 6, 1),
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
            timeline = new vis.Timeline(container, data, groups, options);
            timeline.setGroups(groups);
            timeline.on('select', function (properties) {
                var found = false;
                for (var i = 0; i < $scope.timelines.length && !found; i++) {
                    $scope.voci.current = $scope.findObjectByTimelineId($scope.timelines[i].elements, properties.items[0]);
                    if ($scope.voci.current !== undefined) {
                        found = true;
                        $scope.loadAttachments();
                    }
                }
            });
            $('.vis-center>.vis-content').on('scroll', function () {
                $('.vis-left>.vis-content').scrollTop($(this).scrollTop());
            });
            $('.vis-left>.vis-content').on('scroll', function () {
                $('.vis-center>.vis-content').scrollTop($(this).scrollTop());
            });
        };

        $scope.loadParentAttachments = function () {
            $http.post(
                    '../../../common/attachments-loader.php',
                    {
                        command: 'geturl',
                        table: $scope.moduli.name,
                        obj: $scope.voci.current.module
                    }
            ).then(
                    function (rx) {
                        $scope.moduli.current.links = rx.data;
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data);
                    }
            );
            $http.post(
                    '../../../common/attachments-loader.php',
                    {
                        command: 'geturl',
                        table: $scope.argomenti.name,
                        obj: $scope.voci.current.topic
                    }
            ).then(
                    function (rx) {
                        $scope.argomenti.current.links = rx.data;
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data);
                    }
            );
            $http.post(
                    '../../../common/attachments-loader.php',
                    {
                        command: 'geturl',
                        table: $scope.voci.name,
                        obj: $scope.voci.current
                    }
            ).then(
                    function (rx) {
                        $scope.voci.current.links = rx.data;
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data);
                    }
            );
        };

        $scope.loadAttachments = function () {
            var data1 = {
                item: $scope.voci.current,
                target: $scope.voci.current,
                callback: $scope.loadParentAttachments
            };
            $rootScope.$emit('find-topics-by-item', data1);
//            $http.post(
//                    '../../../common/attachments-loader.php',
//                    {
//                        command: 'getdocs',
//                        table: table.name,
//                        obj: table.current
//                    }
//            ).then(
//                    function (rx) {
//                        table.current.docs = rx.data;
//                    },
//                    function (rx) {
//                        $scope.errorMessage(rx.data);
//                    }
//            );
        };
        $scope.errorMessage = function (message) {
            $scope.lastErrorMessage = message;
            $(".error-message").show();
        };
        $scope.successMessage = function (message) {
            $scope.lastSuccessMessage = message;
            $(".success-message").show();
        };
        $scope.simpleDateFormat = function (date) {
            return date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear();
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
        $rootScope.$emit('load-table', {
            target: $scope.materie
        });
        $scope.loadTimelines();
    }]);
$(document).ready(function () {
    $(".success-message").click(function () {
        $(this).hide();
    });
    $(".error-message").click(function () {
        $(this).hide();
    });

});