<?php

require_once("../consts.php");
$connection = mysqli_connect(HOST, USER, PASS, DB);

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
if ($request != null && isset($request->command)) {
    if ($request->command === "get-classes") {
        $query = "SELECT * FROM classi ORDER BY nome";
        $result = mysqli_query($connection, $query);
        if ($result) {
            $rows = array();
            while ($r = mysqli_fetch_assoc($result)) {
                $rows[] = $r;
            }
            echo json_encode($rows);
        } else
            echo '[]';
    }elseif ($request->command === "add-class") {
        $nome = $request->class->nome;
        $query = "INSERT INTO classi(nome) VALUES ('$nome')";
        $result = mysqli_query($connection, $query);
    } elseif ($request->command == "edit-class") {
        $id = $request->class->id;
        $nome = $request->class->nome;
        $query = "UPDATE classi SET nome = '$nome' WHERE id = '$id'";
        $result = mysqli_query($connection, $query);
    } elseif ($request->command == "delete-class") {
        $id = $request->id;
        $query = "DELETE FROM classi WHERE id='$id'";
        $result = mysqli_query($connection, $query);
        echo mysqli_error($connection);
    }
}
mysqli_close($connection);
?>