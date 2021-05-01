<?php
//Referenced: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
/*
Johnathan Nguyen
WEB PROGRAMMING 
Project #4: Car Rental
*/
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_DATABASE', 'project4');
    
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    
    // Check connection
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
?>