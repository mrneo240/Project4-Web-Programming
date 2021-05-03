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

    /* Attempt to connect to MySQL server */
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

    // Check connection
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $project_db = mysqli_select_db($link, DB_DATABASE);

    if (!$project_db) {
        $sql = 'CREATE DATABASE '.DB_DATABASE;

        if (mysqli_query($link, $sql)) {
            /* Created successfully now add tables*/
            $users = 'CREATE TABLE '.DB_DATABASE.'.users ( id INT NOT NULL AUTO_INCREMENT, username VARCHAR(20) NOT NULL , password VARCHAR(60) NOT NULL , created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id))';
            if(!mysqli_query($link, $users)){
                mysqli_query($link, 'DROP DATABASE '.DB_DATABASE);
                die("ERROR: Could not create table. " . mysqli_error($link));
            }
        } else {
            die("ERROR: Could not create database. " . mysqli_error($link));
        }
    }
