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

function saveData() {
    prettyConfirm("Esci", "Salvare i dati?", function (ok) {
        if (ok) {
            $.ajax(
                    {
                        type: "POST",
                        dataType: 'json',
                        async: false,
                        url: 'includes/timeline/save_data.php',
                        data: {data: JSON.stringify(data, null, 2)},
                        success: swal("File", "salvato correttamente", "success"),
                        failure: function () {
                            alert("Error!");
                        }
                    }
            );
        }
    });
}
function saveDataExit() {
    prettyConfirm("Esci", "Salvare i dati ed uscire?", function (ok) {
        if (ok) {
            $.ajax
                    ({
                        type: "POST",
                        dataType: 'json',
                        async: false,
                        url: 'includes/timeline/save_data.php',
                        data: {data: JSON.stringify(data, null, 2)},
                        success: window.location.replace("../.."),
                        failure: function () {
                            alert("Error!");
                        }
                    });
        }
    });
}
function exit() {
    prettyConfirm("Esci", "I dati non salvati saranno persi", function (ok) {
        if (ok) {
            window.location.replace("../..");
        }
    });
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

        $scope.currentElement = undefined;
        $scope.currentURL = {nome: "", link: ""};

        $scope.srcModName = "";
        $scope.srcTopName = "";
        $scope.srcIteName = "";

        $scope.candidateModules = [];
        $scope.linkedModules = [];

        $scope.candidateTopics = [];


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
            $scope.itemsToAdd = [item];
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
            if ($scope.itemsToAdd.length > 0) {
                alert("Aggiungere " + $scope.itemsToAdd.length + " elementi a " + $scope.monthToAdd.nome);
                if ($scope.monthToAdd.numero < 6) {
                    data.push({
                        id: $scope.itemsToAdd[0].id, content: $scope.itemsToAdd[0].nome, start: to + '-' + $scope.monthToAdd.numero + '-1'
                    });
                } else {
                    data.push({
                        id: $scope.itemsToAdd[0].id, content: $scope.itemsToAdd[0].nome, start: from + '-' + $scope.monthToAdd.numero + '-1'
                    });
                }
                timeline.setItems(data);
            } else {
                swal("Errore", "Selezionare le voci da aggiungere", "warning");
            }
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

        $(document).ready(function () {

            g_globalObject = new JsDatePick({
                useMode: 2,
                target: "inputField",
                dateFormat: "%d-%M-%Y"
            });

            g_globalObject2 = new JsDatePick({
                useMode: 2,
                target: "inputField2",
                dateFormat: "%d-%M-%Y"
            });

// note that months are zero-based in the JavaScript Date object, so month 3 is April
            var btnSave = document.getElementById('save');
// hide the "loading..." message
            document.getElementById('loading').style.display = 'none';
// DOM element where the Timeline will be attached
            var container = document.getElementById('visualization');
// Create a DataSet (allows two way data-binding)
            var items = new vis.DataSet(data);
            var min = new Date(from, 8, 1); // 1 april
            var max = new Date(to, 6, 1); // 30 april

            var container = document.getElementById('visualization');
            var options = {
                editable: true,
                min: new Date(from, 8, 1),
                max: new Date(to, 6, 1),
                zoomMin: 1000 * 60 * 60 * 24 * 12,
                onAdd:
                        function (item, callback) {
                            if ($scope.itemsToAdd.length === 0) {
                                ok = false;
                                swal("Errore", "Selezionare la voce da aggiungere", "warning");
                            } else if ($scope.itemsToAdd.length === 1) {

                                prettyConfirm('Aggiunta elemento', 'Vuoi davvero aggiungere l\'elemento ' + $scope.itemsToAdd[0].nome + '?', function (ok) {
                                    if (ok) {
                                        item.id = $scope.itemsToAdd[0].id;
                                        item.content = $scope.itemsToAdd[0].nome;
                                        callback(item); // send back adjusted new item}
                                    } else {
                                        callback(null); // cancel deletion
                                    }
                                });
                            } else {
                                prettyConfirm('Aggiunta elementi', 'Vuoi davvero aggiungere ' + $scope.itemsToAdd.length + ' elementi?', function (ok) {
                                    if (ok) {
                                        for (var i = 0; i < $scope.itemsToAdd.length; i++) {
                                            item.id = $scope.itemsToAdd[i].id;
                                            item.content = $scope.itemsToAdd[i].nome;
                                            callback(item); // send back adjusted new item}
                                        }
                                    } else {
                                        callback(null); // cancel deletion
                                    }
                                });
                            }
                        },
                onMove: function (item, callback) {
                    var title = 'Vuoi davvero spostare l\'elemento? \n';
                    prettyConfirm('Modifica Elemento', title, function (ok) {
                        if (ok) {
                            callback(item); // send back item as confirmation (can be changed)
                        } else {
                            callback(null); // cancel editing item
                        }
                    });
                },
                onMoving: function (item, callback) {
                    if (item.start < min)
                        item.start = min;
                    if (item.start > max)
                        item.start = max;
                    if (item.end > max)
                        item.end = max;
                    callback(item); // send back the (possibly) changed item

                }/*,
                 onUpdate: function (item, callback) {
                 prettyPrompt('Update item', 'Edit items text:', item.content, function (value) {
                 if (value) {
                 item.content = value;
                 callback(item); // send back adjusted item
                 } else {
                 callback(null); // cancel updating the item
                 }
                 });
                 }*/,
                onRemove: function (item, callback) {
                    prettyConfirm('Rimozione elemento', 'Vuoi davvero rimuovere l\'elemento ' + item.content + '?', function (ok) {
                        if (ok) {
                            callback(item); // confirm deletion
                        }
                        else {
                            callback(null); // cancel deletion
                        }
                    });
                }
            };
            timeline = new vis.Timeline(container, items, options);


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
            document.getElementById('focus').onclick = function () {
                var obj1 = g_globalObject.getSelectedDay();
                var obj2 = g_globalObject2.getSelectedDay();
                //alert(obj1.month + "-" + obj1.day + "-" + obj1.year);
                //alert(obj2.month + "-" + obj2.day + "-" + obj2.year);
                timeline.setWindow(obj1.month + "-" + obj1.day + "-" + obj1.year, obj2.month + "-" + obj2.day + "-" + obj2.year);
            };

            items.on('*', function (event, properties) {
                logEvent(event, properties);
            });
            var selection = document.getElementById('selection');
            var select = document.getElementById('select');
            select.onclick = function () {
                var ids = selection.value.split(',').map(function (value) {
                    return value.trim();
                });
                timeline.setSelection(ids, {focus: true});
            };
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
