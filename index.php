<?php
session_start();
include "CONFIG/init.php";
mysql_select_db($mysql_db);

//If already logged in, send them to home
if(isset($_SESSION["name"])){
	header("Location:home.php");
}
	
//Login script
if(isset($_POST["login"])){
	$email = $_POST["email"];
	$pass = $_POST["pass"];
	$sql = mysql_query("SELECT * FROM users WHERE email='$email'") or die(mysql_error());   
	$row = mysql_fetch_array($sql);	
	$id = $row["id"];
	$pass2 = $row["password"];
	$first = $row["fname"];
	$last = $row["lname"];
	
    if(!empty($_POST['email']) && !empty($_POST['pass'])){
        if($pass == $pass2){
            $_SESSION["name"] = $first . " " . $last;
            $_SESSION["id"] = $id;
            header("Location:home.php");
            return;
        }else{
            echo "<script> alert('Invalid credentials, please try again.')</script>";
        }
    } else {
        echo "<script> alert('Please fill out both fields!')</script>";
    }
}
	
//Register Script
if(isset($_POST["register"])){
	$sql = mysql_query("SELECT id FROM users ORDER BY id DESC") or die(mysql_error());
	$row = mysql_fetch_array($sql);
	$id = $row["id"];	
	$id = $id + 1;
    
	$email = $_POST["email"];
	$firstname = $_POST["firstname"];
	$lastname = $_POST["lastname"];
	$password = $_POST["pass"];
    
	$sql = mysql_query("INSERT INTO kelly80_homeworkhelper.users (id, email, password, fname, lname) VALUES ('$id' , '$email', '$password', '$firstname', '$lastname')", $connect) or die(mysql_error()); 
    
    $_SESSION["name"] = $first . " " . $last;
    $_SESSION["id"] = $id;
    echo "<script>alert('You have been successfully registered - welcome!');window.location.href='http://ashleykellydesign.com/homeworkhelper/home.php';</script>";
}

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Homework Helper</title>
        <link rel="stylesheet" href="style.css">
        <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,600,700,300' rel='stylesheet' type='text/css'>
        <script type="text/javascript">
            function checkfields() {
                var error = 0;
                if (!validatefname(document.getElementById('fname').value)) {
                    error++;
                }
                
                if (!validatelname(document.getElementById('lname').value)) {
                    error++;
                }

                if (!validateemail(document.getElementById('useremail').value)) {
                    error++;
                }

                if (!validatepassword(document.getElementById('password').value)) {
                    error++;
                }

                // Don't submit form if there are errors
                if (error > 0) {
                    return false;
                }
            }

            function validatefname(fname) {
                var namepattern = /^[a-zA-Z']+$/;
                if (namepattern.test(fname)) {
                    //document.getElementById("ex1").style.display = "none";
                    document.getElementById("fname").style.boxShadow = "0 0 6px #006666";
                    return true;
                } else {
                    //document.getElementById("check1").style.display = "none";
                    document.getElementById("fname").style.boxShadow = "0 0 6px #660033";
                    return false;
                }
            }
            
            function validatelname(lname) {
                var namepattern = /^[a-zA-Z']+$/;
                if (namepattern.test(lname)) {
                    //document.getElementById("ex1").style.display = "none";
                    document.getElementById("lname").style.boxShadow = "0 0 6px #006666";
                    return true;
                } else {
                    //document.getElementById("check1").style.display = "none";
                    document.getElementById("lname").style.boxShadow = "0 0 6px #660033";
                    return false;
                }
            }

            function validateemail(useremail) {
                var emailpattern = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
                if (emailpattern.test(useremail)) {
                    //document.getElementById("ex1").style.display = "none";
                    document.getElementById("useremail").style.boxShadow = "0 0 6px #006666";
                    return true;
                } else {
                    //document.getElementById("check1").style.display = "none";
                    document.getElementById("useremail").style.boxShadow = "0 0 6px #660033";
                    return false;
                }
            }

            function validatepassword(password) {
                var passwordpattern = /^(.)+$/;
                if (passwordpattern.test(password)) {
                    //document.getElementById("ex1").style.display = "none";
                    document.getElementById("password").style.boxShadow = "0 0 6px #006666";
                    return true;
                } else {
                    //document.getElementById("check1").style.display = "none";
                    document.getElementById("password").style.boxShadow = "0 0 6px #660033";
                    return false;
                }
            }
        </script>
    </head>

    <body>
        <div id="main" class="group">

            <!-- header div -->
            <div id="header" class="group">
                <h1 class="title">Homework Helper</h1>
            </div>


            <!-- wrap div -->
            <div id="wrap" class="group">
                <!-- end header div -->
                <div id="logindiv" class="group">
                    <form action="index.php" method="POST">
                        <!-- email field -->
                        <input class="login" name="email" type="text" placeholder="Email">
                        <br>
                        <!-- password field -->
                        <input class="login" name="pass" type="password" placeholder="Password">
                        <br>
                        <!-- login button -->
                        <input class="login" name="login" id="login" type="submit" value="Sign In">
                    </form>
                </div>
                <hr>
                <!-- register div -->
                <div id="register">
                    <!-- registration area -->
                    <form action="index.php" method="POST" onsubmit="return reg_alert()">
                        <!-- first name field -->
                        <input class="reg" name="firstname" type="text" id="fname" placeholder="First name" onblur="validatefname(value);">
                        <br>
                        <!-- last name field -->
                        <input class="reg" name="lastname" type="text" id="lname" placeholder="Last name" onblur="validatelname(value);">
                        <br>
                        <!-- email field -->
                        <input id="useremail" class="reg" name="email" type="text" placeholder="Email address" onblur="validateemail(value);">
                        <br>
                        <!-- password field -->
                        <input class="reg" name="pass" type="password" id="password" placeholder="Password" onblur="validatepassword(value);">
                        <br>
                        <!-- register button -->
                        <input id="regbutton" name="register" type="submit" value="Sign Up">
                    </form>
                </div>
                <!-- end register div -->

            </div>
            <!-- end wrap div -->

        </div>
        <!-- end main div -->
    </body>

    </html>