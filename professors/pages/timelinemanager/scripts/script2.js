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
        for (var i = 0; i < $scope.timeline.length; i++) {
            $scope.timeline[i].start = new Date($scope.timeline[i].start);
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

        $scope.currentElement = undefined;
        $scope.currentURL = {nome: "", link: ""};

        $scope.srcModName = "";
        $scope.srcTopName = "";
        $scope.srcIteName = "";

        $scope.candidateModules = [];
        $scope.linkedModules = [];

        $scope.candidateTopics = [];


        $scope.saveData = function () {
            prettyConfirm("Esci", "Salvare i dati?", function (ok) {
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
                                success: window.location.replace("../.."),
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
                    window.location.replace("../..");
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
            $scope.itemsToAdd = [];
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
            $scope.itemsToAdd = [];
        };
        /**
         * 
         * @param {object} item Selected item
         * @returns {undefined}
         */
        $scope.onSelectItem = function (item) {
            $scope.voci.selected = item;
            $scope.itemsToAdd = [];
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

        $scope.loadAttachments = function (table) {
            $rootScope.$emit('load-urls',
                    {
                        target: table
                    });
            $rootScope.$emit('load-docs',
                    {
                        target: table
                    });
        };

        /**
         * Sets a module as current module
         * @param {object} module Chosen module
         * @returns {undefined}
         */
        $scope.onCurrentModule = function (module) {
            $scope.moduli.current = new elementCopy(module);
            $scope.loadAttachments(moduli);
            $scope.moduli.newURL = {
                link: "",
                nome: ""
            };
        };

        /**
         * Sets a topic as current topic
         * @param {type} topic Chosen topic
         * @returns {undefined}
         */
        $scope.onCurrentTopic = function (topic) {
            $scope.argomenti.current = new elementCopy(topic);
            $scope.setCandidateModules($scope.argomenti.current);
            $scope.loadAttachments(argomenti);
            $scope.argomenti.newURL = {
                link: "",
                nome: ""
            };
        };

        /**
         * Sets an item as current item
         * @param {type} item Chosen item
         * @returns {undefined}
         */
        $scope.onCurrentItem = function (item) {
            $scope.voci.current = new elementCopy(item);
            $scope.setCandidateTopics($scope.voci.current);
            $scope.loadAttachments(voci);
            $scope.voci.newURL = {
                link: "",
                nome: ""
            };
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
                                    $scope.timeline.push({
                                        id: $scope.itemsToAdd[i].id, content: $scope.itemsToAdd[i].nome, start: new Date(to, $scope.monthToAdd.numero, 1)
                                    });
                                } else {
                                    $scope.timeline.push({
                                        id: $scope.itemsToAdd[i].id, content: $scope.itemsToAdd[i].nome, start: new Date(from, $scope.monthToAdd.numero, 1)
                                    });
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

    }]);

$(document).ready(function () {
    $(".success-message").click(function () {
        $(this).hide();
    });
    $(".error-message").click(function () {
        $(this).hide();
    });
});
