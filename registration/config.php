<!---
Referenced: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
Johnathan Nguyen
WEB PROGRAMMING
Project #4: Car Rental

-->
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'project4');

/* Attempt to connect to MySQL server */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Check connection
if ($link === false) {
    die("ERROR: Could not connect to database. " . mysqli_connect_error());
}

if (!mysqli_select_db($link, DB_DATABASE)) {
    $sql = 'CREATE DATABASE ' . DB_DATABASE;

    if (mysqli_query($link, $sql)) {
        /* Created successfully now add tables*/
        $users = 'CREATE TABLE ' . DB_DATABASE . '.users ( id INT NOT NULL AUTO_INCREMENT, username VARCHAR(20) NOT NULL , password VARCHAR(60) NOT NULL , created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id))';
        if (!mysqli_query($link, $users)) {
            die("ERROR: Could not create table: Users. " . mysqli_error($link));
            mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
        }

        /* Now create inventory table */
        $inventory = 'CREATE TABLE ' . DB_DATABASE . '.inventory ( id INT NOT NULL AUTO_INCREMENT , name VARCHAR(256) NOT NULL , type VARCHAR(64) NOT NULL, price INT NOT NULL , seats INT NOT NULL , transmission VARCHAR(32) NOT NULL , img VARCHAR(256) NOT NULL , PRIMARY KEY (id))';
        if (!mysqli_query($link, $inventory)) {
            die("ERROR: Could not create table: Inventory. " . mysqli_error($link));
            mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
        }

        /* Now Tables for each cities inventory */
        $atlanta = 'CREATE TABLE ' . DB_DATABASE . '.atlanta ( id INT NOT NULL , available INT NOT NULL )';
        if (!mysqli_query($link, $atlanta)) {
            die("ERROR: Could not create table: Atlanta. " . mysqli_error($link));
            mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
        }
        $la = 'CREATE TABLE ' . DB_DATABASE . '.losangeles ( id INT NOT NULL , available INT NOT NULL )';
        if (!mysqli_query($link, $la)) {
            die("ERROR: Could not create table: Los Angeles. " . mysqli_error($link));
            mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
        }
        $chicago = 'CREATE TABLE ' . DB_DATABASE . '.chicago ( id INT NOT NULL , available INT NOT NULL )';
        if (!mysqli_query($link, $chicago)) {
            die("ERROR: Could not create table: Chicago. " . mysqli_error($link));
            mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
        }

        /* Finally a table to hold orders */
        $orders = 'CREATE TABLE ' . DB_DATABASE . '.orders ( id INT NOT NULL AUTO_INCREMENT , user_id INT NOT NULL , date_start TIMESTAMP NOT NULL , date_end TIMESTAMP NOT NULL , cart VARCHAR(256) NOT NULL , price INT NOT NULL , PRIMARY KEY (id))';
        if (!mysqli_query($link, $orders)) {
            die("ERROR: Could not create table: Inventory. " . mysqli_error($link));
            mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
        }

        /* fill tables with data */
        seed_demo_data();
    } else {
        die("ERROR: Could not create database. " . mysqli_error($link));
    }
}

$stmt_vehicle = 0;
$stmt_city = 0;
function insert_vehicle($name, $type, $price, $seats, $trans, $img)
{
    global $stmt_vehicle, $link;
    if (!mysqli_stmt_bind_param($stmt_vehicle, 'ssiiss', $name, $type, $price, $seats, $trans, $img)) {
        die("ERROR: Could not bind vehicle" . $name . ". " . mysqli_error($link));
        mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
        return;
    }
    if (!mysqli_stmt_execute($stmt_vehicle)) {
        die("ERROR: Could not add vehicle" . $name . ". " . mysqli_error($link));
        mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
    }
}

function insert_inventory($id, $num)
{
    global $stmt_city, $link;
    if (!mysqli_stmt_bind_param($stmt_city, 'ii', $id, $num)) {
        die("ERROR: Could not bind inventory" . $id . ". " . mysqli_error($link));
        mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
        return;
    }
    if (!mysqli_stmt_execute($stmt_city)) {
        die("ERROR: Could not add inventory" . $id . ". " . mysqli_error($link));
        mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
    }
}

function seed_demo_data()
{
    global $stmt_vehicle, $stmt_city, $link;
    $insert_vehicle = 'INSERT INTO inventory (name, type, price, seats, transmission, img) VALUES (?,?,?,?,?,?)';
    mysqli_select_db($link, DB_DATABASE);
    $stmt_vehicle = mysqli_prepare($link, $insert_vehicle);
    if (!$stmt_vehicle) {
        die("ERROR: Could not add prepare insert. " . mysqli_error($link));
        mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
    }
    /* Add fleet vehicles */
    insert_vehicle('Mercedes G-Wagon', 'Luxury', 200, 5, 'Automatic', 'images/mercedes_PNG1861.png');
    insert_vehicle('Chevrolet Spark', 'Compact', 40, 5, 'Automatic', 'images/spark_lrg.jpg');
    insert_vehicle('Jeep Grand Cherokee', 'SUV', 70, 5, 'Automatic', 'images/8476eeee97debca61deb8265ea9f0214x.png');
    insert_vehicle('Toyota Camry', 'Midsize', 60, 5, 'Automatic', 'images/302-3021721_toyota-camry-toyota-yaris-hybrid-luna.png');

    insert_vehicle('Toyota Corolla', 'Compact', 40, 5, 'Automatic', 'images/533-5339230_toyota-corolla-png-transparent-png.png');
    insert_vehicle('Range Rover', 'Luxury', 190, 5, 'Automatic', 'images/range.png');
    insert_vehicle('Nissan Maxima', 'Midsize', 60, 5, 'Automatic', 'images/374-3749932_finance-a-car-midnight-blue-nissan-maxima.png');
    insert_vehicle('Ford Explorer', 'SUV', 60, 5, 'Automatic', 'images/14-147031_new-2020-ford-explorer-xlt-2019-chevrolet-equinox.png');

    insert_vehicle('Fiat 500', 'Compact', 40, 5, 'Automatic', 'images/2015-fiat-500-r.png');
    insert_vehicle('Chrysler 300', 'Midsize', 60, 5, 'Automatic', 'images/2021-Chrysler-300S-V8-trim.png');
    insert_vehicle('Rolls Royce Phantom', 'Luxury', 70, 5, 'Automatic', 'images/rolls-royce-phantom.jpg');

    /* Setup location inventory */
    $insert_inventory = 'INSERT INTO atlanta (id, available) VALUES (?,?)';
    mysqli_select_db($link, DB_DATABASE);
    $stmt_city = mysqli_prepare($link, $insert_inventory);
    if (!$stmt_city) {
        die("ERROR: Could not add prepare Atlanta. " . mysqli_error($link));
        mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
    }
    /* Add Atlanta Inventory */
    insert_inventory(1, 1);
    insert_inventory(2, 3);
    insert_inventory(3, 3);
    insert_inventory(4, 2);

    $insert_inventory = 'INSERT INTO losangeles (id, available) VALUES (?,?)';
    mysqli_select_db($link, DB_DATABASE);
    $stmt_city = mysqli_prepare($link, $insert_inventory);
    if (!$stmt_city) {
        die("ERROR: Could not add prepare Los Angeles. " . mysqli_error($link));
        mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
    }
    /* Add Los Angeles Inventory */
    insert_inventory(9, 2);
    insert_inventory(6, 3);
    insert_inventory(10, 3);
    insert_inventory(11, 1);

    $insert_inventory = 'INSERT INTO chicago (id, available) VALUES (?,?)';
    mysqli_select_db($link, DB_DATABASE);
    $stmt_city = mysqli_prepare($link, $insert_inventory);
    if (!$stmt_city) {
        die("ERROR: Could not add prepare Chicago. " . mysqli_error($link));
        mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
    }
    /* Add Chicago Inventory */
    insert_inventory(5, 3);
    insert_inventory(6, 2);
    insert_inventory(7, 5);
    insert_inventory(8, 2);

}
