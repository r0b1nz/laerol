<?php
  session_start();
  require "../db/connect.php";
  if (!authCheck($_SESSION['user'], $_SESSION['pass']) || !isset($_SESSION['isHR'])) {
    header('Location: ../');
    exit();
  }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loreal: Feedback form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
	<header>
    <h1 style="color:white;font-weight:bold;margin-bottom: 0px;padding-bottom: 0px;">L'ORÉAL</h1><br><h4 style="color:white;font-weight:bold;margin-top: 0px;margin-bottom: 20px;">India</h4>
    <a href="choose_function.php"><button class="btn btn-sm">Home</button></a>
    <a href="../"><button class="btn btn-sm">Log Out</button></a>
	</header>

  <div class="container text-center">
      	<a href="credentials.php"><button style="width:400px;" class="btn btn-lg">View Employee Credentials</button></a><br><br>
        <a href="view_feedback.php"><button style="width:400px;" class="btn btn-lg">View Employee Feedback</button></a><br><br>
        <a href="review_cycle.php"><button style="width:400px;" class="btn btn-lg">Start New Review Cycle</button></a><br><br>

  </div> <!-- /container -->

</body>
</html>