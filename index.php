<?php
session_start();
session_unset();
require "db/connect.php";
$HR_LEVEL = -2;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!isset($_POST['username'], $_POST['password'])) {
		return;
	}

	$user = $_POST['username'];
	$pass = $_POST['password'];

	if (is_null($user) || is_null($pass)) {
		invalid();
		return;
	}

	//Check SQL Injection 
	$user = str_replace("'", "", $user);
	$user = str_replace('"', "", $user);
	$pass = str_replace("'", "", $pass);
	$pass = str_replace('"', "", $pass);

	$sql = 'SELECT * FROM emp_info where designation = \'' . $user . '\'';
	$result = $conn->query($sql);
	if ($result->num_rows < 1) {
		invalid();
	} else {
		$result = $result->fetch_assoc();
		if ($result['password'] == $pass) {
			$_SESSION['user'] = $user;
			$_SESSION['pass'] = $pass;
			if ($result['level'] == $HR_LEVEL) {
				$_SESSION['isHR'] = true;
				header('Location: hr/choose_function.php');
			} else {
				header('Location: Review/choose_feedback.php');
			}
			
		} else {
			invalid();
		}		
	}

}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Loreal: Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>

  <body>
  	<header>
		<h1 style="color:white;font-weight:bold;margin-bottom: 0px;padding-bottom: 0px;">L'ORÉAL</h1><br><h4 style="color:white;font-weight:bold;margin-top: 0px;margin-bottom: 20px;">India</h4>
	</header>
    <div class="container">

      <form class="form-signin" name="form1" method="post">
        <h2 style="color:white;font-weight:bold;text-align: center;">Sign In</h2>
        <input name="username" id="username" type="text" class="form-control" placeholder="Username" autofocus>
        <input name="password" id="password" type="password" class="form-control" placeholder="Password">
        <button name="Submit" id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->
  </body>
</html>
