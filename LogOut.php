<?php
//also a part of the connection in where dire ka nag start sa session and when logout button is pressed ma redirect ka s login page!!
@include 'config.php';

session_start();
session_unset();
session_destroy();

header('location:Login.php');

?>