<?php
include "CONFIG/init.php";
$subject = $_POST["subject"];
$sql = mysql_query("SELECT * FROM questions WHERE subject='$subject' ORDER BY id DESC") or die(mysql_error());
$question_array = array();
$id_array = array();
$count = 0;
while($row = mysql_fetch_array($sql)){
	$question = $row["question"];
	$question_id = $row["id"];
	$question_array[$count] = $question;
	$id_array[$count] = $question_id;
	$count++;
}	
$returnString = "";
for($x = 0; $x < $count; $x++){
	$returnString .= "<a href='question.php?question=$question_array[$x]'>$question_array[$x]</a><br><br>";
}
echo $returnString;
?>
