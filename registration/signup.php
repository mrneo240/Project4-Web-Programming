<!--
Johnathan Nguyen
WEB PROGRAMMING
Project #4: Car Rental
Referenced: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
Referenced: https://www.php.net/manual/en/mysqli-statement.bind-param.php
-->
<?php
// Include config file
require_once "config.php";
// Define variables user & pass and their errors
$username = "";
$password = "";
$signupUser_err = "";
$signupPass_err = "";
$validationFail = 0;
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validation for username
    if(empty($_POST["username"])){
        $signupUser_err = "Username not entered";
        $validationFail++;
    } else{
        // Selecting ID for username so id will auto increment
        $mysql = "SELECT id FROM users WHERE username = ?";
        if($statement = mysqli_prepare($link, $mysql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($statement, "s", $insert_username);
            $insert_username = $_POST["username"];
            // execute statement that prepared earlier
            if(mysqli_stmt_execute($statement)){
                mysqli_stmt_store_result($statement);
                //Check to see if username taken
                if(mysqli_stmt_num_rows($statement) == 1){
                    $signupUser_err = "Username already exists.";
                } else{
                    //set username to input value
                    $username = $_POST["username"];
                }
            } else{
                echo "Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($statement);
        }
    }
    // Validation for password
    if(empty($_POST["password"])){
        $signupPass_err = "Password not entered"; 
        $validationFail++;
    } elseif(strlen($_POST["password"]) < 8){
        $signupPass_err = "Password must be > 7 characters";
        $validationFail++;
    } else{
        $password = $_POST["password"];
    }

    // If no errors, insert into database
    if($validationFail == 0){
        // mysql insert statement
        $mysql = "INSERT INTO users (username, password) VALUES (?, ?)";
        if($statement = mysqli_prepare($link, $mysql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($statement, "ss", $insert_username, $insert_password);
            // Set parameters
            $insert_username = $username;
            $insert_password = password_hash($password, PASSWORD_DEFAULT);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($statement)){
                // Redirect to login page
                header("location: login.php");
            } 
            // Close statement
            mysqli_stmt_close($statement);
        }
    }
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en-us" class="registerbg">

<head>
    <meta charset="UTF-8">
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="../style.css" type="text/css">
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
    <a href="https://validator.w3.org/#validate_by_input"><img src="../images/xhtml.png" alt="xhtml val"></a>
    <a href="https://jigsaw.w3.org/css-validator/#validate_by_input"><img src="../images/css.png" alt="css val"></a>
</div>

</body>

</html>