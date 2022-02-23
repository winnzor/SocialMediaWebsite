<?php
session_start();
include "CONFIG/init.php";

$userid = $_SESSION["id"];
$commentid = $_POST["id"];
$vote = $_POST["vote"];

$sql = mysql_query("SELECT id FROM votes ORDER BY id DESC") or die(mysql_error());
$row = mysql_fetch_array($sql);
$voteid = $row["id"];
$voteid = $voteid + 1;

$sql2 = mysql_query("SELECT * FROM votes WHERE userid='$userid' AND cid='$commentid'") or die(mysql_error());
if(mysql_num_rows($sql2) == 0){
	$sql3 = mysql_query("INSERT INTO votes(id, userid, cid, vote) VALUES ('$voteid', '$userid', '$commentid', '$vote')") or die(mysql_error());
	
	if($vote == 1){
        	$sql4 =  mysql_query("UPDATE comments SET votes=votes+1 WHERE id='$commentid'") or die(mysql_error());
	}

	if($vote == 0){
        	$sql4 =  mysql_query("UPDATE comments SET votes=votes-1 WHERE id='$commentid'") or die(mysql_error());
	}


}
else{
	$sql3 = mysql_query("UPDATE votes SET vote='$vote' WHERE userid='$userid' AND cid='$commentid'") or die(mysql_error());
	if($vote == 1){
      $sql4 =  mysql_query("UPDATE comments SET votes=votes+2 WHERE id='$commentid'") or die(mysql_error());
	}

	if($vote == 0){
        	$sql4 =  mysql_query("UPDATE comments SET votes=votes-2 WHERE id='$commentid'") or die(mysql_error());
	}


}

$sql5 =  mysql_query("SELECT votes FROM comments WHERE id='$commentid'") or die(mysql_error());
$rows = mysql_fetch_array($sql5);
$votes = $rows["votes"];
echo $votes;
?>


