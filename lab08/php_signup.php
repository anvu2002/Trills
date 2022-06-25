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


if (isset($_POST['user']) && isset($_POST['pass']))
{
	$usr=trim($_POST['user']);
	$pass=trim($_POST['pass']);
	$email = trim($_POST['email']);


	$a = $trillDB->sign_up($usr,$pass,$email);
	if ($a == 1) 
	{
		echo("<script> var note = 2; </script>"); //notify the sucess of creating the new user
	}
	elseif ($a == 2) 
	{
		echo("<script> var note = 3; </script>"); // fail to insert the new user
	}
	//echo("<script> var note = 2; </script>"); // Thank you message

	
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
		function sign_in()
		{
			window.location.assign("http://localhost/lab08/php_login.php")
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
								<input type="text" name="user" id="user" class="form-control" placeholder="Cool username" required autofocus>
							</div>
							<div class="form-label-group" style="position: relative;margin-bottom:1rem">
								<input type="text" name="email" id="email" class="form-control" placeholder="Your email address" required autofocus>
							</div>
							<div class="form-label-group" style="position: relative;margin-bottom:1rem">
								<input type="password" name="pass" id="pass" class="form-control" placeholder="Password" required>
							</div>
							<div class="custom-control custom-checkbox mb-3">
								<input type="checkbox" name="remem" id="remem" class="custom-control-input">
								<label class="custom-control-label" for="remem">Remember Me</label>
							</div>
							<div class="row justify-content-center">
								<button class="btn btn-lg btn-primary btn-block" type="submit" style="width:80%;font-size: 80%;border-radius: 5rem;">Sign up</button>
							</div>								
							<hr class="my-4">
							<div class="row justify-content-center">
								<button class="btn btn-lg btn-primary btn-block" type="button" style="width:80%;font-size: 80%;border-radius: 5rem;" onclick="sign_in()">Already have an account? Let's go!</button>
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
	switch (note) 
            {
                case 1:
						notify('Thank you ❤'); 
                break;
				
				case 2:
                	notify('Insert succdeed ❤'); 
                break;
                
                case 3:
                	notify('Failed to insert new user :( '); 
                break;

                default:
                  
                break;
            }   
	//if( note == 1 ) notify('Thank you ❤'); 
</script>

</main>


<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>
