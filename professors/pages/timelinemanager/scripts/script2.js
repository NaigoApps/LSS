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
        $scope.timeline = data;

        $scope.materie = [
            {nome: "Fisica", color: "orange"},
            {nome: "Chimica", color: "cyan"},
            {nome: "Scienze della terra", color: "red"},
            {nome: "Biologia", color: "green-lemon"},
        ];

        //Init items performances
        for (var i = 0; i < $scope.timeline.length; i++) {
            $scope.timeline[i].start = new Date($scope.timeline[i].start);
            $scope.timeline[i].performance = [];
            for (var j = 0; j < $scope.materie.length; j++) {
                $scope.timeline[i].performance.push({done: false});
            }
            $scope.timeline[i].performed = false;
        }

        $scope.monthToAdd = undefined;

        $scope.mesi = [
            {nome: 'Settembre', numero: 8},
            {nome: 'Ottobre', numero: 9},
            {nome: 'Novembre', numero: 10},
            {nome: 'Dicembre', numero: 11},
            {nome: 'Gennaio', numero: 0},
            {nome: 'Febbraio', numero: 1},
            {nome: 'Marzo', numero: 2},
            {nome: 'Aprile', numero: 3},
            {nome: 'Maggio', numero: 4},
            {nome: 'Giugno', numero: 5}
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
                                data: {data: JSON.stringify($scope.timeline, null, 2)},
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
                                data: {data: JSON.stringify($scope.timeline, null, 2)},
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
                        $scope.timeline[index].start.setDate(1);
                        $scope.timeline[index].performed = false;

                        var done = false;
                        for (var i = 0; i < $scope.performs.length && !done; i++) {
                            if ($scope.performs[i].idvoce === $scope.timeline[index].id && $scope.performs[i].idmateria === subject_id) {
                                $scope.performs.splice(i, 1);
                                done = true;
                            }
                        }

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
                            var testDate;
                            if (fields[1] < 6) {
                                testDate = new Date(to, fields[1] - 1, fields[0]);
                            } else {
                                testDate = new Date(from, fields[1] - 1, fields[0]);
                            }
                            if (testDate && testDate.getMonth() === fields[1] - 1) {
                                $scope.timeline[index].performance[subject_id - 1] = {
                                    done: true,
                                    on: testDate
                                };
                                $scope.timeline[index].start = testDate;
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
                                if ($scope.monthToAdd.numero < 6) {
                                    var newItem = {
                                        id: $scope.itemsToAdd[i].id,
                                        content: $scope.itemsToAdd[i].nome,
                                        start: new Date(to, $scope.monthToAdd.numero, 1),
                                        performance: [],
                                        performed: false
                                    };
                                } else {
                                    var newItem = {
                                        id: $scope.itemsToAdd[i].id,
                                        content: $scope.itemsToAdd[i].nome,
                                        start: new Date(from, $scope.monthToAdd.numero, 1),
                                        performance: [],
                                        performed: false
                                    };
                                }
                                for (var j = 0; j < $scope.materie.length; j++) {
                                    newItem.performance.push({done: false});
                                }
                                $scope.timeline.push(newItem);
                                for (var j = 0; j < $scope.performs.length; j++) {
                                    $scope.assignPerformance($scope.performs[j]);
                                }
                                $scope.$apply();
                            }
                        }
                    });
                } else {
                    swal("Errore", "Le voci sono gia' tutte presenti", "warning");
                }

            } else {
                swal("Errore", "Selezionare le voci da aggiungere", "warning");
            }
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
        $scope.assignPerformance = function (perf) {
            for (var i = 0; i < $scope.timeline.length; i++) {
                if ($scope.timeline[i].id === perf.idvoce) {
                    $scope.timeline[i].performance[perf.idmateria - 1] = {
                        done: true,
                        on: new Date(perf.data)
                    };
                    if(parseInt(perf.idmateria) === subject_id){
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
    }]);
$(document).ready(function () {
    $(".success-message").click(function () {
        $(this).hide();
    });
    $(".error-message").click(function () {
        $(this).hide();
    });
});
