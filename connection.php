<?php
session_start();
$conn=mysqli_connect("localhost","root","","attendance_system");
if(mysqli_connect_error()){
    echo "Failed to connect to MySQL ";
}

?>