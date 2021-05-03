<!--
Johnathan Nguyen
WEB PROGRAMMING
Project #4: Car Rental
Referenced: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
-->

<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.html");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = "";
$password = "";
$loginUser_err = "";
$loginPass_err = "";
$login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check username and password to ensure not empty
    if(empty($_POST["username"])){
        $loginUser_err = "Please enter username.";
    } else{
        $username = $_POST["username"];
    }
    if(empty($_POST["password"])){
        $loginPass_err = "Please enter your password.";
    } else{
        $password = $_POST["password"];
    }

    // If both username and password errors are not empty
    if(empty($loginUser_err) && empty($loginPass_err)){
        $mysql = "SELECT id, username, password FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($link, $mysql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $insert_username);
            $insert_username = $username;
            // Execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
               mysqli_stmt_store_result($stmt);
                // Check if username exists within database, then check its hashed password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Store data in session variables to store car rental info
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            // Redirect user to home page
                            header("location: home.html");
                        } else{
                            $login_err = "Username and Password mismatched";
                        }
                    }
                } else{
                    $login_err = "Username and Password mismatched";
                }
            } else{
                echo "Try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en" class="loginbg">

<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>
    <h1>ENTER LOGIN</h1>
    <div>
        <form id="loginForm" method="post">

            <table>
                <tr>
                    <td>Enter Username: </td>
                    <td>
                        <label id="loginUserErr" class="error"></label><br>
                        <input type="text" id="loginUser" name="username" placeholder="Username" />
                    </td>
                </tr>
                <tr>
                    <td>Enter Password: </td>
                    <td>
                        <label id="loginPassErr" class="error"></label><br>
                        <input type="text" id="loginPass" name="password" placeholder="Password" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input style="cursor: pointer;" type="submit" id="button" value="Login" />
                    </td>
                </tr>
            </table>
            <div id="w3c">
                <a href="https://validator.w3.org/#validate_by_input"><img src="images/xhtml.png" alt="xhtml val"></a>
                <a href="https://jigsaw.w3.org/css-validator/#validate_by_input"><img src="images/css.png" alt="css val"></a>
            </div>
        </form>
    </div>

</body>

</html>