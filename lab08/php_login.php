<?php
/***************************** 
*
*	Info-W21-3175 - Lab 08
*	php_login.php
*	
******************************/
	require_once("dbTriller.php");
	
	session_start();

	if(isset($_SESSION["dbUser"])) // Không cần phải Login nữa
	{
		header( "Location: /lab08/php_triller8.php");
		exit();
	}

	$trillDB = new dbTriller();

	if( !$trillDB->connected() ) exit("Database Error");

	if(isset($_POST['user'])) //if the user enter something in Username section ...
	{
		$usr = trim($_POST["user"]);
		$pas = trim($_POST["pass"]);
		/*
		* 
		* 	For different authentication methods in dbTriller class...
		*	call authenticate with first argument set to 1,2 or 3 
		*	any other value = no authentication.
		*
		*/	
		$res = $trillDB->authenticate(1, $usr, $pas); /// AUTHENTICATION STEP!!!

		if($res) // có chả ra res ,, meaning there is a valid credentials that are returned.
		{
			$_SESSION['dbUser'] = $res['name'];		//authenticated user name from DB
			$_SESSION['dbPass'] = $res['pass'];
			$_SESSION['dbSalt'] = $res['salt'];

			$_SESSION['htUser'] = $usr;				//authenticated user name from web page

			if(!empty($_POST["remem"])) 
			{
				/*
				* It is NEVER safe to store credentials, ids, passwords in cookies
				* We do so here for lab screenshots only
				*/	
				setcookie ('triller', $usr . '|' . $pas, 0, '/');
			} 
			else 
			{
				setcookie ('triller','', 0, '/');
			}

			header( "Location: /lab08/php_triller8.php"); // allow  to login 
			unset($_POST); // to save memory ...
			exit();
		}
		else
		{
			echo("<script> var note = 1; </script>"); // fail to login due to invalid credentials

		}	
	}
?>
<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link 	rel="stylesheet" 
			href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
			integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
			crossorigin="anonymous">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,500;0,600;1,600;1,700&display=swap" rel="stylesheet">

	<title>Duc Thien An Vu</title>		<!-- your first, last name -->
</head>

<body style="font-family:'Archivo', sans-serif;" class="bg-light">

	<script type="text/javascript">
		function notify(msg) // for invalid login credentials 
		{
			x = document.getElementById("trillMsg");
			if(x) // if there is that element
			{
				x.innerHTML = "<b>" + msg + "</b>";
				x.style.display = "block";
				setTimeout(function(){ x.style.display="none"; }, 5000)
			}
		}
		function sign_up()
		{
			window.location.assign("http://localhost/lab08/php_signup.php")
		}
			

		
	</script>

<main role="main" class="container">
	
	<nav class="navbar navbar-expand-lg navbar-light bg-white">
		<a class="navbar-brand" href="#"><img src="triller.png" width="125" alt=""></a>
		
		<div class="col col-lg-8">
			<p style="color:CornflowerBlue; font-size: 60px;">Welcome Guys!</p>
		</div>

	</nav>

	<div class="container">
		<div class="row">
			<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
				<div class="card card-signin my-5" style="border-radius: 1rem;box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);">
					<div class="card-body">
						<h5 class="card-title text-center"></h5>

						<form id="post" method="post" class="form-signin">
							<div class="form-label-group" style="position: relative;margin-bottom:1rem">
								<input type="text" name="user" id="user" class="form-control" placeholder="Phone, email or user name" required autofocus>
							</div>
							<div class="form-label-group" style="position: relative;margin-bottom:1rem">
								<input type="password" name="pass" id="pass" class="form-control" placeholder="Password" required>
							</div>
							<div class="custom-control custom-checkbox mb-3">
								<input type="checkbox" name="remem" id="remem" class="custom-control-input">
								<label class="custom-control-label" for="remem">Remember Me</label>
							</div>
							<div class="row justify-content-center">
								<button class="btn btn-lg btn-primary btn-block" type="submit" style="width:80%;font-size: 80%;border-radius: 5rem;">Log in</button>
							</div>								
							<hr class="my-4">
							<div class="row justify-content-center">
								<button class="btn btn-lg btn-primary btn-block" type="button" style="width:80%;font-size: 80%;border-radius: 5rem;" onclick="sign_up()">Don't have an account yet? Sign up today!</button>
							</div>								
						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
	<footer class="fixed-bottom text-white bg-primary">
		<div class="container text-center">
			<span>&copy; <?php echo date("Y"); ?>  INFO-3175-W21 Scripting for Web Security</span>
		</div>
		<div class="alert alert-success alert-dismissable" style="display:none" id="trillMsg"></div>
	</footer>

	<!-- check if user is remembered 

		It is NEVER safe to store user ids, passwords in cookies
		We do so here for lab screenshots only

	-->
	<script type="text/javascript">
		var cs = document.cookie.split(';');

		for(var i = 0; i < cs.length; i++) 
		{
        	var cookie = cs[i].split("=");
        	if(cookie[0].trim() == "triller")
        	{
        		var val = decodeURIComponent(cookie[1]).split('|');
        		document.getElementById("user").setAttribute('value', val[0]);
        		document.getElementById("pass").setAttribute('value', val[1]);
        		document.getElementById("remem").setAttribute('checked', 'true');
				break;
			}
		}
	</script>
	<!-- END of user is remembered -->

<script> 
	if(typeof note !== 'undefined') notify('The Username or Password is incorrect'); 
</script>

</main>


<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>
