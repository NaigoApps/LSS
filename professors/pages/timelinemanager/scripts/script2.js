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
        $scope.itemsToAdd = [];
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
        $scope.saveData = function () {
            prettyConfirm("Salvataggio", "Salvare i dati?", function (ok) {
                if (ok) {
                    $.ajax(
                            {
                                type: "POST",
                                dataType: 'json',
                                async: false,
                                url: 'includes/timeline/save_data.php',
                                data: {data: JSON.stringify($scope.timeline.serialize(), null, 1)},
                                success: swal("File", "salvato correttamente", "success"),
                                failure: function () {
                                    alert("Error!");
                                }
                            }
                    );
                }
            });
        };
        $scope.saveDataExit = function () {
            prettyConfirm("Esci", "Salvare i dati ed uscire?", function (ok) {
                if (ok) {
                    $.ajax
                            ({
                                type: "POST",
                                dataType: 'json',
                                async: false,
                                url: 'includes/timeline/save_data.php',
                                data: {data: JSON.stringify($scope.timeline.serialize(), null, 1)},
                                success: window.location.replace("./index.php"),
                                failure: function () {
                                    alert("Error!");
                                }
                            });
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
        $scope.setUndone = function (index) {
            swal({
                title: "Svolgimento",
                text: "Cancellare lo svolgimento?",
                type: "warning",
                showCancelButton: true,
                animation: "slide-from-top"},
                    function () {
                        $scope.timeline[index].performance[subject_id - 1] = {
                            done: false,
                            on: undefined
                        };
                        $scope.timeline[index].performed = false;
                        $scope.timeline[index].date.day = 1;
                        $scope.$apply();
                    });
        };
        $scope.setDone = function (index) {
            var today = new Date();
            swal({
                title: "Svolgimento",
                text: "Inserisci la data di svolgimento",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputValue: today.getDate() + "/" + (today.getMonth() + 1)},
                    function (input) {
                        if (input === false) {
                            return false;
                        }
                        var fields = input.split("/");
                        if (fields.length === 2) {
                            fields[0] = parseInt(fields[0]);
                            fields[1] = parseInt(fields[1]);
                            var date;
                            if (fields[1] <= 6) {
                                date = {
                                    day: fields[0],
                                    month: fields[1],
                                    year: to
                                };
                            } else {
                                date = {
                                    day: fields[0],
                                    month: fields[1],
                                    year: from
                                };
                            }
                            var testDate = new Date(date.year, date.month - 1, date.day);
                            if (testDate && testDate.getMonth() === (fields[1] - 1)) {
                                $scope.timeline[index].performance[subject_id - 1] = {
                                    done: true,
                                    on: date
                                };
                                $scope.timeline[index].date = date;
                                $scope.timeline[index].performed = true;
                                $scope.$apply();
                            } else {
                                swal.showInputError("Data non valida!");
                                return false;
                            }
                        } else {
                            swal.showInputError("Data non valida!");
                            return false;
                        }
                        swal("Registrazione completata");
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
                    if ($scope.findById($scope.timeline, $scope.itemsToAdd[i].id) === -1) {
                        str += " - " + $scope.itemsToAdd[i].nome;
                        atLeastOne = true;
                    }
                }
                if (atLeastOne) {
                    prettyConfirm("Aggiunta elementi", str, function () {
                        for (var i = 0; i < $scope.itemsToAdd.length; i++) {
                            if ($scope.findById($scope.timeline, $scope.itemsToAdd[i].id) === -1) {
                                var year;
                                if ($scope.monthToAdd.numero < 6) {
                                    year = to;
                                } else {
                                    year = from;
                                }
                                var date = {
                                    day: 1,
                                    month: $scope.monthToAdd.numero,
                                    year: year
                                };
                                var element = $scope.initElement($scope.itemsToAdd[i].id, $scope.itemsToAdd[i].nome, date);
                                $scope.timeline.push(element);
                            }
                        }
                        $scope.reloadPerformances();
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
                content: name,
                date: date,
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


        $scope.removeFromTimeline = function (index) {
            prettyConfirm('Rimozione elemento', 'Vuoi davvero rimuovere l\'elemento?', function (ok) {
                if (ok) {
                    $scope.timeline.splice(index, 1);
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
                    'includes/timeline/load_data.php',
                    {
                        command: 'load_timeline'
                    }
            ).then(
                    function (rx) {
                        $scope.performs = rx.data;
                        for (var i = 0; i < $scope.performs.length; i++) {
                            $scope.assignPerformance($scope.performs[i]);
                        }
                    },
                    function (rx) {
                        $scope.errorMessage(rx.data.msg);
                    }
            );
        };
        $scope.assignPerformance = function (perf) {
            for (var i = 0; i < $scope.timeline.length; i++) {
                if ($scope.timeline[i].id === perf.idvoce) {
                    var date = perf.data.split("-");
                    $scope.timeline[i].performance[perf.idmateria - 1] = {
                        done: true,
                        on: 
                                {
                                    year : date[0],
                                    month : date[1],
                                    day : date[2],
                                }
                    };
                    if (parseInt(perf.idmateria) === subject_id) {
                        $scope.timeline[i].performed = true;
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

        //MAIN
        $scope.timeline.deserialize(data);
        $scope.reloadPerformances();


    }]);
$(document).ready(function () {
    $(".success-message").click(function () {
        $(this).hide();
    });
    $(".error-message").click(function () {
        $(this).hide();
    });
});
