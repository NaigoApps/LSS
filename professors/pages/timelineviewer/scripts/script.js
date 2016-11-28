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




app.controller("timelineController", ['$http', '$scope', '$rootScope', function ($http, $scope, $rootScope) {

        $scope.argomenti = {
            content: [],
            name: 'argomenti',
            selected: undefined,
            current: {},
            newURL:
                    {
                        nome: "",
                        link: ""
                    },
            searchString: ""
        };
        $scope.voci = {
            content: [],
            name: 'voci',
            selected: undefined,
            current: {},
            newURL:
                    {
                        nome: "",
                        link: ""
                    },
            searchString: ""
        };
        $scope.moduli = {
            content: [],
            name: 'moduli',
            selected: undefined,
            current: {},
            newURL:
                    {
                        nome: "",
                        link: ""
                    },
            searchString: ""
        };
        $scope.materie = {
            content: [],
            colors: ["orange", "cyan", "red", "green-lemon", "black"],
            name: "materie",
            selected: undefined
        };
        $scope.elements = [];
        $scope.timeline = [];
        $scope.selected = {
            name: "voci",
            current: undefined
        };

        $scope.exit = function () {
            window.location.replace("./index.php");
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
        $scope.findObjectById = function (vector, id) {
            var index = $scope.findById(vector, id);
            if (index !== -1) {
                return vector[index];
            }
            return undefined;
        };

        $scope.errorMessage = function (message) {
            $scope.lastErrorMessage = message;
            $(".error-message").show();
        };
        $scope.successMessage = function (message) {
            $scope.lastSuccessMessage = message;
            $(".success-message").show();
        };
        $scope.loadTimeline = function () {
            $http.post(
                    '../timelinemanager/includes/load_data.php',
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
        $scope.reloadPerformances = function () {
            $http.post(
                    '../timelinemanager/includes/load_data.php',
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
                        $scope.buildView();
                        $(".progress").hide();
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data.msg);
                    }
            );
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
            for (var i = 0; i < $scope.elements.length; i++) {
                data.push(
                        {
                            id: $scope.elements[i].id,
                            content: $scope.buildContent($scope.elements[i]),
                            start: $scope.elements[i].data
                        }
                );
            }

            var container = document.getElementById('visualization');
            var options = {
                editable: false,
                min: new Date($scope.timeline.anno, 7, 1),
                max: new Date(parseInt($scope.timeline.anno) + 1, 6, 1),
                zoomMin: 1000 * 60 * 60 * 24 * 12
            };
            timeline = new vis.Timeline(container, data, options);
            timeline.on('select', function (properties) {
                $scope.selected.current = $scope.findObjectById($scope.elements, properties.items[0]);
                $scope.loadAttachments($scope.selected);
            });
        };
        $scope.loadAttachments = function (table) {
            
            var data1 = {
                item: table.current,
                target: table.current
            };
            $rootScope.$emit('find-topics-by-item', data1);
            $http.post(
                    '../../../common/attachments-loader.php',
                    {
                        command: 'geturl',
                        table: table.name,
                        obj: table.current
                    }
            ).then(
                    function (rx) {
                        table.current.links = rx.data;
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data);
                    }
            );
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
