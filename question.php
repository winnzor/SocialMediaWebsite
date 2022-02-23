<?php
session_start();
include "CONFIG/init.php";
include "CONFIG/redirect.php";

$question = $_GET["question"];
$sql = mysql_query("SELECT id, userid, subject FROM questions WHERE question='$question'") or die(mysql_error());
$row = mysql_fetch_array($sql);	
$questionid = $row["id"];
$userid = $row["userid"];
$subject = $row["subject"];

$usersql = mysql_query("SELECT fname, lname FROM users WHERE id='$userid'") or die(mysql_error());
$rows = mysql_fetch_array($usersql);	
$fname = $rows["fname"];
$lname = $rows["lname"];
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Homework Helper</title>
        <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,600,700,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="style.css">
        <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <script type="text/javascript">
            function upvote(id) {
                
                
                up = "up" + id
                down = "down" + id
                vote = "vote" + id
                

                updoc = document.getElementsByName(up)[0]
                downdoc = document.getElementsByName(down)[0]
                votedoc = document.getElementsByName(vote)[0]
                
                addAttribute = updoc.getAttribute('class');
                
                $('#'+up).removeClass();
                $('#'+up).addClass("fa fa-thumbs-o-up fa-3x green");

                if (addAttribute == "fa fa-thumbs-o-up fa-3x green"){
                    return;
                }

                $.ajax({
                    type: "POST",
                    data: "vote=1&id=" + id,
                    url: 'vote.php',
                    complete: function (response) {
                        $('#'+down).removeClass();
                        $('#'+down).addClass("fa fa-thumbs-o-down fa-3x");
                        
                        
                        $('p[name=' + vote + ']').html(response.responseText);
                    },

                    error: function () {
                        $(vote).html('Bummer: there was an error!');
                    }
                });
            }

            function downvote(id) {
                
                up = "up" + id
                down = "down" + id
                vote = "vote" + id

                updoc = document.getElementsByName(up)[0]
                downdoc = document.getElementsByName(down)[0]
                votedoc = document.getElementsByName(vote)[0]
                
                addAttribute = downdoc.getAttribute('class');
                
                $('#'+down).removeClass();
                $('#'+down).addClass("fa fa-thumbs-o-down fa-3x red");

                if (addAttribute == "fa fa-thumbs-o-down fa-3x red") {
                    return;
                }

                $.ajax({
                    type: "POST",
                    data: "vote=0&id=" + id,
                    url: 'vote.php',
                    complete: function (response) {
                        $('#'+up).removeClass();
                        $('#'+up).addClass("fa fa-thumbs-o-up fa-3x");
                        $('p[name=' + vote + ']').html(response.responseText);
                    },

                    error: function () {
                        $(vote).html('Bummer: there was an error!');
                    }
                });

            }
        </script>
    </head>

    <body>
        <div id="main" class="group">
            <!-- header div -->
            <div id="header" class="group">
                <h1 class="title">Homework Helper</h1>
            </div>
            <div id="navbar" class="group">
                <p>Hello,
                    <?php echo $_SESSION['name']; ?>
                </p>
                <form class="nav" action="home.php">
                    <input id="home" type="button" value="Home" onclick="window.location.href='home.php'">
                </form>
                <form class="nav" action="logout.php">
                    <input id="logout" type="button" value="Logout" onclick="window.location.href='logout.php'">
                </form>
            </div>

            <!-- answers div -->
            <div id="answers">
                <div class="answers">
                    <h2><?php echo $question; ?></h2>
                    <p>
                        <sub><?php echo $subject; ?></sub>
                        <br>
                        <asker>
                            <?php echo $fname . " " . $lname;?>
                        </asker>
                    </p>
                </div>
            </div>

            <hr>

            <!-- new comment div -->
            <div id="newcomments" class="group">
                <h2>Leave an answer</h2>
                <div id="newcontent" class="group">
                    <form action="sumbitComment.php" method="post" class="newcom group">
                        <!-- textarea to input answer -->
                        <textarea name="comment" placeholder="The answer is..." class="ask question"></textarea>
                        <input type="hidden" name="questionid" value="<?php echo $questionid;?>">
                        <input type="hidden" name="question" value="<?php echo $question;?>">
                        <!-- bsubmit button -->
                        <input id="askbutton" class="ask" type="submit" value="Submit">
                    </form>
                </div>
            </div>
            <!-- end new comment div -->

            <!-- commentscroll div -->
            <div id="commentscroll">
                <?php
				$sql = mysql_query("SELECT * FROM comments WHERE qid='$questionid' ORDER BY votes DESC") or die(mysql_error());
				
				while($row = mysql_fetch_array($sql)){	
					$commentid = $row["id"];
					$usercommentid = $row["userid"];
					$votes = $row["votes"];
					$comment = $row["comment"];
					
					$sql2 = mysql_query("SELECT * FROM users WHERE id='$usercommentid'") or die(mysql_error());
					$rows = mysql_fetch_array($sql2);
					$fname = $rows["fname"];
                    $lname = $rows["lname"];
                    $thisID = $_SESSION['id'];
                    $sql3 = mysql_query("SELECT * FROM votes WHERE cid='$commentid' AND userid='$thisID'") or die(mysql_error());
                    $num_rows = mysql_num_rows($sql3);
                    if($num_rows > 0){
                        $rows2 = mysql_fetch_array($sql3);
                        $vote = $rows2["vote"];
                    }else{
                        $vote = -1;
                    }
            ?>

                    <!-- recent comment div -->
                    <div id="recentcomments" class="group">
                        <div class="col1">

                            <div class="commentedanswer">
                                <?php echo $comment;?>
                            </div>
                            <br>
                            <div class="fname">
                                <?php echo $fname . " " . $lname;?>
                            </div>
                        </div>

                        <!-- upvote / downvote -->
                        <div class="col3">
                            <?php if($vote == 1){ ?>
                               <a href="javascript:upvote(<?php echo $commentid;?>)"><i id="up<?php echo $commentid;?>" name="up<?php echo $commentid;?>" class="fa fa-thumbs-o-up fa-3x green"></i></a>
                            <?php }else{ ?>
                                <a href="javascript:upvote(<?php echo $commentid;?>)"><i id="up<?php echo $commentid;?>" name="up<?php echo $commentid;?>" class="fa fa-thumbs-o-up fa-3x"></i></a>
                            <?php } ?>

                                <p id="vote" name="vote<?php echo $commentid;?>">
                                    <?php echo $votes;?>
                                </p>

                            <?php if($vote == 0){ ?>
                                <a href="javascript:downvote(<?php echo $commentid;?>)"><i id="down<?php echo $commentid;?>" name="down<?php echo $commentid;?>" class="fa fa-thumbs-o-down fa-3x red"></i></a>
                            <?php }else{ ?>
                                <a href="javascript:downvote(<?php echo $commentid;?>)"><i id="down<?php echo $commentid;?>" name="down<?php echo $commentid;?>" class="fa fa-thumbs-o-down fa-3x"></i></a>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- end recent comment div -->
                    <?php } ?>
            </div>
            <!-- end commentscroll div -->

        </div>
        <!-- end main div -->
    </body>

    </html>