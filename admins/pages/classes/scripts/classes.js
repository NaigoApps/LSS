function prettyConfirm(title, text, callback) {
    swal({
        title: title,
        text: text,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55"
    }, callback);
}


app.controller("classesController", ['$http', '$scope', '$rootScope', function ($http, $scope, $rootScope) {

        $scope.classi = {
            content: [],
            name: "classi"
        };

        $scope.studenti = [];

        /**
         * 
         * @param {object} classe Selected class
         * @returns {undefined}
         */
        $scope.onSelectClass = function (classe) {
            $scope.classi.selected = classe;
            $scope.netLoadStudents($scope.classi.selected);
        };


        /**
         * Searches a matching topic
         * @returns {undefined}
         */
        $scope.onSearchStudent = function () {
            $scope.searchObject('studenti', $scope.studenti.search);
        };

        /**
         * Adds module to database
         * @returns {undefined}
         */
        $scope.onAddClass = function () {
            if ($scope.newClass && $scope.newClass.sezione && $scope.newClass.anno) {
                $scope.classi.current = {
                    anno: $scope.newClass.anno,
                    sezione: $scope.newClass.sezione
                };
                $rootScope.$emit('add-class', {target: $scope.classi});
            }
        };

        $scope.onEditClass = function (classe) {
            $scope.classi.current = classe;
            $rootScope.$emit('commit-class-edit', {target: $scope.classi});
        };

        $scope.onDeleteClass = function (classe) {
            prettyConfirm("Cancellazione", "Sicuro di voler cancellare la classe?", function () {
                $scope.classi.current = classe;
                $rootScope.$emit('commit-delete', {target: $scope.classi});
            })
        };

        /**
         * Adds topic to database
         * @returns {undefined}
         */
        $scope.onConfirmAddStudentToClass = function (studente, classe) {
            if (studente !== undefined && classe !== undefined) {
                $scope.netAddLinkedElement('argomenti', 'moduli', $scope.argomenti.current, $scope.moduli.selected);
                $scope.netSetClass(studente, classe);
            }
        };



        /**
         * Resets all current elements
         * @returns {undefined}
         */
        $scope.initCurrentElement = function () {
            var element = {};
            element.nome = "";
            element.descrizione = "";
            element.links = [];
            element.docs = [];

            $scope.moduli.current = {};
            $scope.argomenti.current = {};
            $scope.voci.current = {};

            $scope.copyObject(element, $scope.moduli.current);
            $scope.copyObject(element, $scope.argomenti.current);
            $scope.copyObject(element, $scope.voci.current);
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

        $scope.loadAttachments = function (table, element) {
            $scope.netGetURLs(table, element);
            $scope.netGetDocs(table, element);
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
        $rootScope.$emit('load-table', {target: $scope.classi});
    }]);

$(document).ready(function () {
    $(".success-message").click(function () {
        $(this).hide();
    });
    $(".error-message").click(function () {
        $(this).hide();
    });
});
