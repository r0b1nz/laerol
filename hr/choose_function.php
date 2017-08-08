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
		<h1 class="heading choose">L'OREAL</h1>
    <a href="/laerol/hr/choose_function.php"><button class="btn btn-sm">Home</button></a>
    <a href="/laerol/"><button class="btn btn-sm">Log Out</button></a>
	</header>
    
  <div class="container text-center">
      	<a href="/laerol/hr/credentials.php"><button type ="button" class="btn btn-lg">View Employee Credentials</button></a><br><br>
        <a href="/laerol/hr/feedback.php"><button type ="button" class="btn btn-lg">View Employee Feedback</button></a><br><br>
        <a href="/laerol/hr/review_cycle.php"><button type ="button" class="btn btn-lg">Start New Review Cycle</button></a><br><br>

        <script type="text/javascript">
        function alertFunc()
        {
          alert("Successfully Started New Cycle");
        }
        </script>
  </div> <!-- /container -->

</body>
</html>