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
$username;
$password;
$signupUser_err;
$signupPass_err;
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate username
    if(empty(trim($_POST["username"]))){
        $signupUser_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = trim($_POST["username"]);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                //Check to see if username taken
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $signupUser_err = "This username is already taken.";
                } else{
                    //set username to input value
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Validate password
    if(empty(trim($_POST["password"]))){
        $signupPass_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 8){
        $signupPass_err = "Password must have atleast 8 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Check input errors before inserting in database
    if(empty($signupUser_err) && empty($signupPass_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
<html lang="en-us">

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
                    <input type="text" id="signupUser" name="username" placeholder="Ex: Username123" />
                    <span class="invalid-feedback"><?php echo $signupUser_err; ?></span><br>

                </td>
            </tr>
            <tr>
                <td>Create Password: </td>
                <td>
                    <input type="text" id="signupPass" name="password" placeholder="At least 8 characters" />
                    <span class="invalid-feedback"><?php echo $signupPass_err; ?></span><br>

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