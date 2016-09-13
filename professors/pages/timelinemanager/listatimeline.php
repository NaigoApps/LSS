<?php
session_start();

$year = $_SESSION['timeline-year'];
$subject = $_SESSION['timeline-subject'];
$class = $_SESSION['timeline-class'];
$folder = $_SESSION['timeline-folder'];
$file = "../../" . $folder . "/empty.json";
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Timeline | Basic demo</title>

        <style type="text/css">
            body, html {
                font-family: sans-serif;
            }
        </style>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="includes/timeline/alert/dist/sweetalert.min.js"></script>
        <link href="includes/timeline/alert/dist/sweetalert.css" rel="stylesheet" type="text/css"/>
        <script src="includes/timeline/dist/timeline.js"></script>
        <link rel="stylesheet" href="../../../common/styles/bootstrap.min.css">
        <link rel="stylesheet" href="../../../common/styles/style.css">
        <script type="text/javascript" src="../../../common/scripts/bootstrap.min.js"></script>
        <script type="text/javascript" src="../../../common/scripts/angular.min.js"></script>
        <script src="scripts/links.js"></script>
        <link href="includes/timeline/dist/timeline.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <p>
        <p>
            Cerca voce: <input type="text" id="selection" value=""><input type="button" id="select" value="CERCA"><br>
        </p>
        <div class="buttons">
            <input type="button" id="save" value="&uarr; Save" title="SALVA">
        </div>
    </p>

    <div id="visualization"></div>
    <div id="loading">loading...</div>
    <p></p>
    <div id="log"></div>

    <script type="text/javascript">



        // note that months are zero-based in the JavaScript Date object, so month 3 is April
        $.ajax({
        url: '<?php echo $file; ?>',
        success: function (data) {
            var btnSave = document.getElementById('save');
            // hide the "loading..." message
            document.getElementById('loading').style.display = 'none';
            // DOM element where the Timeline will be attached
            var container = document.getElementById('visualization');
            // Create a DataSet (allows two way data-binding)
            var items = new vis.DataSet(data);
            var min = new Date(2015, 1, 1); // 1 april
            var max = new Date(2016, 11, 30, 23, 59, 59); // 30 april

            var container = document.getElementById('visualization');
            var options = {
                editable: true,
                min: new Date(<?php echo $year; ?>, 8, 1),
                max: new Date(<?php echo ($year + 1); ?>, 6, 1),
                zoomMin: 1000 * 60 * 60 * 24 * 12,
                onAdd: function (item, callback) {
                    prettyConfirm('Aggiunta elemento', 'Vuoi davvero aggiungere l\'elemento ' + itemToAdd.nome + '?', function (ok) {
                        if (ok) {
                            item.id = itemToAdd.id;
                            item.content = itemToAdd.nome;
                            callback(item); // send back adjusted new item}
                        } else {
                            callback(null); // cancel deletion
                        }
                    });
                },
                onMove: function (item, callback) {
                    var title = 'Vuoi davvero spostare l\'elemento? \n';
                    prettyConfirm('Modifica Elemnto', title, function (ok) {
                        if (ok) {
                            callback(item); // send back item as confirmation (can be changed)
                        }else {
                            callback(null); // cancel editing item
                        }
                    });
                },
                onMoving: function (item, callback) {
                    if (item.start < min) item.start = min;
                    if (item.start > max) item.start = max;
                    if (item.end > max) item.end = max;
                    callback(item); // send back the (possibly) changed item

                },
                onUpdate: function (item, callback) {
                    prettyPrompt('Update item', 'Edit items text:', item.content, function (value) {
                        if (value) {
                            item.content = value;
                            callback(item); // send back adjusted item
                        }else {
                            callback(null); // cancel updating the item
                        }
                    });
                },
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
                    var timeline = new vis.Timeline(container, items, options);
                    items.on('*', function (event, properties) {
                    logEvent(event, properties);
                    });
                    var selection = document.getElementById('selection');
                    var select = document.getElementById('select');
                    select.onclick = function () {
                    var ids = selection.value.split(',').map(function (value) {
                    return value.trim();
                    });
                            timeline.setSelection(ids, {focus:true});
                    };
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
            var data = items.get({
            type: {
            start: 'ISODate',
                    end: 'ISODate'
            }
            });
                    $.ajax
                    ({
                    type: "POST",
                            dataType : 'json',
                            async: false,
                            url: 'includes/timeline/save_data.php',
                            data: { data: JSON.stringify(data, null, 2) },
                            success: swal("File", "salvato correttamente", "success"),
                            failure: function() {alert("Error!"); }
                    });
            }
            btnSave.onclick = saveData;
            },
            error: function (err) {
            console.log('Error', err);
                    if (err.status === 0) {
            alert('Failed to load data/basic.json.\nPlease run this example on a server.');
            }
            else {
            alert('Failed to load data/basic.json.');
            }
                }
        });

    </script>
</body>
</html>