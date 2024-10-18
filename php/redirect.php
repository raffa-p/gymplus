<?php
require_once(dirname(__FILE__) . "/config.php");

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION["username"])){
    header("Location: ./log-in.php");
}

if($_SESSION["coach"]){
    header("Location: ./coach-dash.php");
}
else{
    header("Location: ../index.php");
}
?>