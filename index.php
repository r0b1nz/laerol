
<?php
session_start();
require "db/connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!isset($_POST['username'], $_POST['password'])) {
		return;
	}

	$user = $_POST['username'];
	$pass = $_POST['password'];

	// echo 'USer: ' . $user . $pass;
	$sql = 'SELECT * FROM emp_info where designation = \'' . $user . '\'';
	$result = $conn->query($sql);
	$result = $result->fetch_assoc();
	if ($result['password'] == $pass) {
		$_SESSION['user'] = $user;
		$_SESSION['pass'] = $pass;
		header('Location: Review');
	} else {
		echo '<div class="alert alert-danger">
		  <strong>Wrong!</strong> username or password.
		</div>';
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
    <div class="container">

      <form class="form-signin" name="form1" method="post">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input name="username" id="username" type="text" class="form-control" placeholder="Username" autofocus>
        <input name="password" id="password" type="password" class="form-control" placeholder="Password">
        <button name="Submit" id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->
  </body>
</html>
