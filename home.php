<?php
session_start();
include "CONFIG/init.php";
include "CONFIG/redirect.php";

$myfile = fopen("subjects.txt", "r") or die("Unable to open file!");
$count = 0;
$subject_array = array();
while($line = fgets($myfile)){
	$line = str_replace("\n", "", $line);
	$line = str_replace("\r", "", $line);
	$subject_array[$count] = $line;
	$count++;
}
fclose($myfile);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homework Helper</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,600,700,300' rel='stylesheet' type='text/css'>
    <script type="text/javascript">
        function ReplaceContentInContainer(contentTitle) {
            $.ajax({
                type: "POST",
                data: "subject=" + contentTitle,
                url: 'homefunctions.php',
                complete: function (response) {
                    $('#recentcontent').html(response.responseText);
                    var questionTitle = document.getElementById("questions");
                    questionTitle.innerHTML = contentTitle;
                    flipAskDiv(true);
                },
                error: function () {
                    $('#recentcontent').html('Bummer: there was an error!');
                }
            });
        }

        function flipAskDiv(isVisible) {
            if (isVisible) {
                document.getElementById("ask").style.display = "none"
                document.getElementById("recent").style.display = "inline-block"
                $(window).scrollTop(0);
            } else {
                document.getElementById("recent").style.display = "none"
                document.getElementById("ask").style.display = "inline-block"
                $(window).scrollTop(0);
            }
        }

        function subjectPicked() {
            if (document.getElementById("dropdown").value == "Select") {
                alert("Please Select a Subject")
                return false
            }
            return true

        }
    </script>
</head>

<body>
    <div id="main">
        <!-- header div -->
        <div id="header" class="group">
            <h1 class="title">Homework Helper</h1>
        </div>
        <div id="navbar" class="group">
            <p>Hello,
                <?php echo $_SESSION['name']; ?>
            </p>
            <form class="nav" action="home.php">
                <!-- <input id="home" type="image" src="icons/home.png"> -->
                <input id="home" type="button" value="Home" onclick="window.location.href='home.php'">
            </form>
            <form class="nav" action="logout.php">
                <input id="logout" type="button" value="Logout" onclick="window.location.href='logout.php'">
            </form>
        </div>

       
        <div class="wrapper group">
           <!-- ask a question div -->
            <div id="ask" class="group" style="display: none;">
                <h2>Ask a Question</h2>
                <form method="POST" action="submitQuestions.php" onSubmit="return subjectPicked()" class="askform group">
                    <!-- textbox -->
                    <textarea name="question" placeholder="Ask a question" class="ask question"></textarea>
                    <!-- drop down menu to select a subject -->
                    <select id="dropdown" name="subject" class="ask">
                        <option value="Select" disabled selected>Select a subject</option>
                        <?php foreach($subject_array as $subject){ ?>
                            <option value="<?php echo $subject; ?>">
                                <?php echo $subject; ?>
                            </option>
                            <?php } ?>
                    </select>
                    <!-- submit button -->
                    <input id="askbutton" class="ask" type="submit" value="Submit">
                </form>
            </div>
            <!-- end ask a question div -->
            
           <!-- recent questions div -->
            <div id="recent" class="group">
                <h2 id="questions">Recently Asked Questions</h2>
                <!-- recently asked questions -->
                <div id="recentcontent">
                
                    <?php
					$sql = mysql_query("SELECT * FROM questions ORDER BY id DESC") or die(mysql_error());
					while($row = mysql_fetch_array($sql)){	
						$question = $row["question"];
						$question_id = $row["id"];
				?>
                        <a href='question.php?question=<?php echo $question; ?>'>
                            <?php echo $question; ?>
                        </a>
                        <br>
                        <br>
                        <?php } ?>
                </div>
            </div>
            <!-- end recent questions div -->
            
            <!-- subjects div -->
            <div id="subjects" class="group">
                <h2>Select a Subject</h2>
                
                <span id="subjectcontent" class="group">
                
                <p>Choose a subject from the list below to only see questions of that type.</p><br>
                <ul class="group">
                    <!-- list of subjects-->
                    <?php foreach($subject_array as $subject){ ?>
                        <li>
                            <a href="javascript:ReplaceContentInContainer('<?php echo $subject; ?>')">
                                <?php echo $subject; ?>
                            </a>
                        </li>
                        <?php } ?>
                </ul>
                <br>
                <a href="javascript:flipAskDiv(false)">Click here</a> to submit a question.
                </span>
            </div>
            <!-- end subjects div -->

            

            
        </div>
        <!-- wrapper -->

    </div>
    <!-- end main div -->
</body>

</html>