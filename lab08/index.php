<?php
/***************************** 
*
*	Info-W21- 3175 - Lab 08
*	index.php
*	
******************************/
	session_start();

	if(isset($_SESSION['dbUser']))
	{
		header( "Location: ./php_triller8.php");
		exit();
	}
	else
	{
		header( "Location: ./php_login.php");
	}
?>
