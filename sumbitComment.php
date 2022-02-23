<?php
session_start();
include "CONFIG/init.php";

$userid = $_SESSION["id"];
$comment = $_POST["comment"];
$questionid = $_POST["questionid"];
$question = $_POST["question"];

$sql = mysql_query("SELECT id FROM comments ORDER BY id DESC") or die(mysql_error());
$row = mysql_fetch_array($sql);
$id = $row["id"];
$id = $id + 1;

$sql = mysql_query("INSERT INTO comments(id, userid, qid, votes, comment) VALUES ('$id', '$userid', '$questionid', 0, '$comment')") or die(mysql_error());
header("Location:question.php?question=$question");
?>
