<?php
/***************************** 
*
*   Info-W21- 3175 - Lab 08
*   php_logout.php
*   
******************************/
    session_start();
    $_SESSION = array();
    session_destroy();
    header("Location: ./");
?>