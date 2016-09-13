<?php
session_start();

$year = $_SESSION['timeline-year'];
$subject = $_SESSION['timeline-subject'];
$class = $_SESSION['timeline-class'];
$folder = $_SESSION['timeline-folder'];
$file = "../../../../".$folder."/$year-$class-$subject.json";

$fh = fopen($file, 'w') or die("can't open file");
$stringData = $_POST["data"];
//$stringData = iconv("UTF-8", "ASCII//TRANSLIT", $stringData);
fwrite($fh, $stringData);
fclose($fh)
?>