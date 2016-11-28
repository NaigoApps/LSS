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

        /*
         * nome, descrizione, data, performance
         */

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
        $scope.itemsToAdd = [];
        $scope.materie = {
            content: [],
            colors: ["orange", "cyan", "red", "green", "black"],
            name: "materie",
            selected: undefined
        };
        $scope.elements = [];
        $scope.timeline = {};
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
        $scope.saveData = function () {
            prettyConfirm("Salvataggio", "Salvare i dati?", function (ok) {
                if (ok) {
                    $http.post(
                            'includes/save_data.php',
                            {
                                command: 'save_timeline',
                                id: timeline_id,
                                timeline: $scope.elements
                            }
                    ).then(
                            function (rx) {
                                swal("Programmazione", "salvata correttamente", "success");
                            },
                            function (rx) {
                                $scope.errorMessage(rx.data.msg);
                            }
                    );
                }
            });
        };
        $scope.saveDataExit = function () {
            prettyConfirm("Esci", "Salvare i dati ed uscire?", function (ok) {
                if (ok) {
                    $http.post(
                            'includes/save_data.php',
                            {
                                command: 'save_timeline',
                                id: timeline_id,
                                timeline: $scope.elements
                            }
                    ).then(
                            function (rx) {
                                swal("Programmaione", "salvata correttamente", "success");
                                window.location.replace("./index.php");
                            },
                            function (rx) {
                                $scope.errorMessage(rx.data.msg);
                            }
                    );
                }
            });
        };
        $scope.exit = function () {
            prettyConfirm("Esci", "I dati non salvati saranno persi", function (ok) {
                if (ok) {
                    window.location.replace("./index.php");
                }
            });
        };
        /**
         * 
         * @param {object} module Selected module
         * @returns {undefined}
         */
        $scope.onSelectModule = function (module) {
            $scope.moduli.selected = module;
            $scope.argomenti.selected = undefined;
            $rootScope.$emit('load-linked-table',
                    {
                        target: $scope.argomenti,
                        source: $scope.moduli
                    });
            $scope.voci.content.splice(0, $scope.voci.content.length);
        };
        /**
         * 
         * @param {object} topic Selected topic
         * @returns {undefined}
         */
        $scope.onSelectTopic = function (topic) {
            $scope.argomenti.selected = topic;
            $scope.voci.selected = undefined;
            $rootScope.$emit('load-linked-table',
                    {
                        target: $scope.voci,
                        source: $scope.argomenti
                    });
        };
        /**
         * 
         * @param {object} item Selected item
         * @returns {undefined}
         */
        $scope.onSelectItem = function (item) {
            $scope.voci.selected = item;
        };
        /**
         * 
         * @param {object} month Selected month
         * @returns {undefined}
         */
        $scope.onSelectMonth = function (month) {
            $scope.mesi.selected = month;
            $scope.monthToAdd = month;
        };
        /**
         * Searches a matching module
         * @returns {undefined}
         */
        $scope.onSearchModule = function () {
            $rootScope.$emit('search-object',
                    {
                        target: $scope.moduli
                    });
            $scope.moduli.selected = undefined;
        };
        /**
         * Searches a matching topic
         * @returns {undefined}
         */
        $scope.onSearchTopic = function () {
            $rootScope.$emit('search-object',
                    {
                        target: $scope.argomenti
                    });
            $scope.moduli.selected = undefined;
            $scope.argomenti.selected = undefined;
        };
        /**
         * Searches a matching item
         * @returns {undefined}
         */
        $scope.onSearchItem = function () {
            $rootScope.$emit('search-object',
                    {
                        target: $scope.voci
                    });
            $scope.moduli.selected = undefined;
            $scope.argomenti.selected = undefined;
            $scope.voci.selected = undefined;
        };
        $scope.onSetDate = function (index) {
            if ($scope.elements[index].settingDate) {
                $("#picker" + index).datepicker("destroy");
                $scope.elements[index].settingDate = false;
            } else {
                $scope.elements[index].settingDate = true;
                $("#picker" + index).datepicker();
                $("#picker" + index).datepicker("setDate", $scope.elements[index].data);
                $("#picker" + index).datepicker("option", "minDate", new Date($scope.timeline.anno, 9 - 1, 1));
                $("#picker" + index).datepicker("option", "maxDate", new Date(parseInt($scope.timeline.anno) + 1, 6 - 1, 30));
                $("#picker" + index).css("top", $("#picker" + index).parent().css("height"));
                $("#picker" + index).datepicker("option","onSelect",
                         function (date, picker) {
                                var d = $("#picker" + index).datepicker("getDate");
                                swal({
                                    title: "Aggiornamento data",
                                    text: 'La data sara\' settata al ' + d.getDate() + "/" + (d.getMonth() + 1) + '/' + d.getFullYear(),
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "Ok",
                                    cancelButtonText: "Annulla"
                                },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        $scope.elements[index].settingDate = false;
                                        $scope.elements[index].data = d;
                                        var performance = $scope.findObjectById($scope.elements[index].performance, $scope.timeline.idmateria);
                                        if (performance !== undefined) {
                                            performance.data = d;
                                        }
                                        $("#picker" + index).datepicker("destroy");
                                        $scope.sortTimeline();
                                        $scope.$apply();
                                    }
                                });
                            }
                        );
            }
        };
        $scope.setUndone = function (index) {
            swal({
                title: "Svolgimento",
                text: "Cancellare lo svolgimento?",
                type: "warning",
                showCancelButton: true,
                animation: "slide-from-top"},
            function () {
                var i = $scope.findById($scope.elements[index].performance, $scope.timeline.idmateria);
                if (i >= 0) {
                    $scope.elements[index].performance.splice(i, 1);
                    $scope.elements[index].performed = false;
                }
                $scope.$apply();
            });
        };
        $scope.setDone = function (index) {
            swal({
                title: "Svolgimento",
                text: "Settare l'argomento come svolto?",
                type: "warning",
                showCancelButton: true,
                animation: "slide-from-top"},
            function () {
                $scope.elements[index].performance.push({
                    id: $scope.timeline.idmateria,
                    data: $scope.elements[index].data
                });
                $scope.elements[index].performed = true;
                $scope.$apply();
            });
        };
        $scope.onAddToTimeline = function () {
            $scope.itemsToAdd = [];
            if ($scope.argomenti.selected) {
                if ($scope.voci.selected) {
                    $scope.itemsToAdd = [$scope.voci.selected];
                } else {
                    $scope.itemsToAdd = $scope.itemsToAdd.concat($scope.voci.content);
                }
            }
            if ($scope.itemsToAdd.length > 0) {
                var str = "Confermare l'aggiunta dei seguenti elementi? ";
                var atLeastOne = false;
                for (var i = 0; i < $scope.itemsToAdd.length; i++) {
                    if ($scope.findById($scope.elements, $scope.itemsToAdd[i].id) === -1) {
                        str += " - " + $scope.itemsToAdd[i].nome;
                        atLeastOne = true;
                    }
                }
                if (atLeastOne) {
                    prettyConfirm("Aggiunta elementi", str, function () {
                        for (var i = 0; i < $scope.itemsToAdd.length; i++) {
                            if ($scope.findById($scope.elements, $scope.itemsToAdd[i].id) === -1) {
                                var year;
                                if ($scope.monthToAdd.numero < 6) {
                                    year = parseInt($scope.timeline.anno) + 1;
                                } else {
                                    year = $scope.timeline.anno;
                                }
                                var date = new Date(year, $scope.monthToAdd.numero - 1, 1);
                                var element = $scope.initElement($scope.itemsToAdd[i].id, $scope.itemsToAdd[i].nome, date);
                                $scope.elements.push(element);
                            }
                        }
                        $scope.reloadPerformances();
                        $scope.sortTimeline();
                        $scope.$apply();
                    });
                } else {
                    swal("Errore", "Le voci sono gia' tutte presenti", "warning");
                }

            } else {
                swal("Errore", "Selezionare le voci da aggiungere", "warning");
            }
        };
        $scope.initElement = function (id, name, date) {
            var element = {
                id: id,
                nome: name,
                data: date,
                performance: [],
                performed: false
            };
            return element;
        };
        $scope.onRemoveFromTimeline = function (index) {
            prettyConfirm('Rimozione elemento', 'Vuoi davvero rimuovere l\'elemento?', function (ok) {
                if (ok) {
                    $scope.elements.splice(index, 1);
                    $scope.$apply();
                }
            });
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
                        $scope.sortTimeline();
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
        $scope.errorMessage = function (message) {
            $scope.lastErrorMessage = message;
            $(".error-message").show();
        };
        $scope.successMessage = function (message) {
            $scope.lastSuccessMessage = message;
            $(".success-message").show();
        };
        $scope.merge = function (left, right) {
            var result = [];
            while (left.length && right.length) {
                if (left[0].data <= right[0].data) {
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
        $scope.sortTimeline = function () {
            $scope.elements = $scope.mergeSort($scope.elements);
        };
        $scope.loadTimeline = function () {
            $http.post(
                    'includes/load_data.php',
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
        }

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
