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

function logEvent(event, properties) {
    var log = document.getElementById('log');
    var msg = document.createElement('div');
    /*msg.innerHTML = 'event=' + JSON.stringify(event) + ', ' +
     'properties=' + JSON.stringify(properties); */
    log.firstChild ? log.insertBefore(msg, log.firstChild) : log.appendChild(msg);
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

        $scope.materie = [
            {nome: "Fisica", color: "orange"},
            {nome: "Chimica", color: "cyan"},
            {nome: "Scienze della terra", color: "red"},
            {nome: "Biologia", color: "green-lemon"},
        ];
        $scope.timeline = [];
        $scope.timeline.serialize = function () {
            var serialized = [];
            for (var i = 0; i < this.length; i++) {
                var element = {
                    id: this[i].id,
                    content: this[i].content
                };
                element.date = this[i].date;
                element.start = new Date(this[i].date.year, this[i].date.month - 1, this[i].date.day);
                element.performed = this[i].performed;
                serialized.push(element);
            }
            return serialized;
        };
        $scope.timeline.deserialize = function (data) {
            for (var i = 0; i < data.length; i++) {
                var element = $scope.initElement(data[i].id, data[i].content, data[i].date);
                this.push(element);
            }
        };


        $scope.monthToAdd = undefined;
        $scope.mesi = [
            {nome: 'Settembre', numero: 9},
            {nome: 'Ottobre', numero: 10},
            {nome: 'Novembre', numero: 11},
            {nome: 'Dicembre', numero: 12},
            {nome: 'Gennaio', numero: 1},
            {nome: 'Febbraio', numero: 2},
            {nome: 'Marzo', numero: 3},
            {nome: 'Aprile', numero: 4},
            {nome: 'Maggio', numero: 5},
            {nome: 'Giugno', numero: 6}
        ];

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
        $scope.copyObject = function (source, destination) {
            destination.id = source.id;
            destination.nome = source.nome;
            destination.descrizione = source.descrizione;
            destination.links = source.links;
            if (destination.links === undefined) {
                destination.links = [];
            }
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
        $scope.reloadPerformances = function () {
            $http.post(
                    'includes/load_data.php',
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

        $scope.buildView = function () {
            var data = [];
            for (var i = 0; i < $scope.elements.length; i++) {
                data.push(
                        {
                            id: $scope.elements[i].id,
                            content: $scope.elements[i].nome,
                            start: $scope.elements[i].date
                        }
                );
            }

            var container = document.getElementById('visualization');

            var options = {
                editable: false,
                min: new Date($scope.timeline.anno, 8, 1),
                max: new Date(parseInt($scope.timeline.anno) + 1, 6, 1),
                zoomMin: 1000 * 60 * 60 * 24 * 12
            };
            timeline = new vis.Timeline(container, data, options);
        };

        $scope.errorMessage = function (message) {
            $scope.lastErrorMessage = message;
            $(".error-message").show();
        };
        $scope.successMessage = function (message) {
            $scope.lastSuccessMessage = message;
            $(".success-message").show();
        };



// attach events to the navigation buttons
        document.getElementById('zoomIn').onclick = function () {
            zoom(-0.2);
        };
        document.getElementById('zoomOut').onclick = function () {
            zoom(0.2);
        };
        document.getElementById('moveLeft').onclick = function () {
            move(0.2);
        };
        document.getElementById('moveRight').onclick = function () {
            move(-0.2);
        };

        //alert(obj1.month + "-" + obj1.day + "-" + obj1.year);
        //alert(obj2.month + "-" + obj2.day + "-" + obj2.year);
        //timeline.setWindow(obj1.month + "-" + obj1.day + "-" + obj1.year, obj2.month + "-" + obj2.day + "-" + obj2.year);

        items.on('*', function (event, properties) {
            logEvent(event, properties);
        });

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
