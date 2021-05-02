<!--
Johnathan Nguyen
WEB PROGRAMMING 
Project #4: Car Rental
Referenced: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
-->
<?php
   session_start();
   if(session_destroy()) {
      header("Location: login.php");
   }
?>