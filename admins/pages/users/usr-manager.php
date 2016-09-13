<?php

require_once("../../../consts.php");
$connection = mysqli_connect(HOST, USER, PASS, DB);

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
if ($request != null && isset($request->command)) {
    if ($request->command == "get-users") {
        $query = "SELECT * FROM utenti";
        $result = mysqli_query($connection, $query);
        if ($result) {
            $rows = array();
            while ($r = mysqli_fetch_assoc($result)) {
                $rows[] = $r;
            }
            echo json_encode($rows);
        } else
            echo '[]';
    }else if ($request->command === "add-user") {
        $query = "INSERT INTO utenti(email) VALUES ('$email')";
        $result = mysqli_query($connection, $query);
    } else if ($request->command === "delete-user") {
        $id = $request->id;
        $query = "DELETE FROM utenti WHERE id='$id'";
        $result = mysqli_query($connection, $query);
    }else if (stripos($request->command, "add-") === 0 || stripos($request->command, "remove-") === 0) {
        $type = intval($request->user->type);
        $id = intval($request->user->id);
        if ($request->command === "add-admin") {
            $type = $type + 4;
        }else if ($request->command === "remove-admin") {
            $type = $type - 4;
        }else if ($request->command === "add-professor") {
            $type = $type + 1;
        }else if ($request->command === "remove-professor") {
            $type = $type - 1;
        }else if ($request->command === "add-student") {
            $type = $type + 2;
        }else if ($request->command === "remove-student") {
            $type = $type - 2;
        }
        $query = "UPDATE utenti SET type = $type WHERE id = $id";
        $result = mysqli_query($connection, $query);
    } 
}
mysqli_close($connection);
?>