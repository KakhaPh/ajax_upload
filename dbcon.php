<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'Ajax';

$con = new mysqli($servername, $username, $password, $dbname);

if($con->connect_error) {
    die("Connection failed: . $connect_error");
}

?>