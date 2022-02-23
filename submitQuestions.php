<?php
session_start();
include "CONFIG/init.php";

$userid = $_SESSION["id"];
$question = $_POST["question"];
$subject = $_POST["subject"];

$sql = mysql_query("SELECT id FROM questions ORDER BY id DESC") or die(mysql_error());
$row = mysql_fetch_array($sql);
$id = $row["id"];
$id = $id + 1;

$sql = mysql_query("INSERT INTO questions(id, userid, question, subject) VALUES ('$id', '$userid', '$question','$subject')") or die(mysql_error());
header("Location:question.php?question=$question");
?>
