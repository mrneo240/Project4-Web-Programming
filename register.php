<!--
Johnathan Nguyen
WEB PROGRAMMING
Project #4: Car Rental
Referenced: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
-->
<?php
// Include config file
require_once "config.php";

// Define variables user & pass and their errors
$username = "";
$password = "";
$signupUser_err = "";
$signupPass_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validation for username
    if(empty($_POST["username"])){
        $signupUser_err = "Please enter a username.";
    } else{
        // Selecting ID for username so id will auto increment
        $mysql = "SELECT id FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($link, $mysql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $insert_username);
            $insert_username = $_POST["username"];
            // execute statement that prepared earlier
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                //Check to see if username taken
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $signupUser_err = "This username is already taken.";
                } else{
                    //set username to input value
                    $username = $_POST["username"];
                }
            } else{
                echo "Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Validation for password
    if(empty($_POST["password"])){
        $signupPass_err = "Please enter a password.";
    } elseif(strlen($_POST["password"]) < 8){
        $signupPass_err = "Password must have atleast 8 characters.";
    } else{
        $password = $_POST["password"];
    }

    // If no errors, insert into database
    if(empty($signupUser_err) && empty($signupPass_err)){
        // mysql insert statement
        $mysql = "INSERT INTO users (username, password) VALUES (?, ?)";
        if($stmt = mysqli_prepare($link, $mysql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $insert_username, $insert_password);

            // Set parameters
            $insert_username = $username;
            $insert_password = password_hash($password, PASSWORD_BCRYPT); // Creates a password hash

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en-us" class="registerbg">

<head>
    <meta charset="UTF-8">
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>
    <h1>SIGN UP</h1>
    <div>
    <form id="signupForm" method="post">
        <table>
            <tr>
                <td>Create Username: </td>
                <td>
                    <span class="error"><?php echo $signupUser_err; ?></span><br>
                    <input type="text" id="signupUser" name="username" placeholder="Ex: Username123" />
                </td>
            </tr>
            <tr>
                <td>Create Password: </td>
                <td>
                    <span class="error"><?php echo $signupPass_err; ?></span><br>
                    <input type="text" id="signupPass" name="password" placeholder="At least 8 characters" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <!---JavaScript to check all fields have something-->
                    <input style="cursor: pointer;" type="submit" id="button" value="Sign Up"  />
                </td>
            </tr>
        </table>
    </form>
</div>
<div id="w3c">
    <a href="https://validator.w3.org/#validate_by_input"><img src="images/xhtml.png" alt="xhtml val"></a>
    <a href="https://jigsaw.w3.org/css-validator/#validate_by_input"><img src="images/css.png" alt="css val"></a>
</div>

</body>

</html>