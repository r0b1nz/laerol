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
    <button class="btn btn-sm"><a href="/laerol/hr/choose_function.php">Home</a></button>
    <button class="btn btn-sm"><a href="/laerol/">Log Out</a></button>
	</header>
    
  <div class="container">
      	<button name="Submit" id="submit" class="btn btn-lg btn-block" type="submit"><a href="/laerol/hr/credentials.php">View Employee Credentials</a></button>
        <button name="Submit" id="submit" class="btn btn-lg btn-block" type="submit"><a href="/laerol/hr/feedback.php">View Employee Feedback</a></button>
        <button name="Submit" id="submit" class="btn btn-lg btn-block" type="submit"><a href="/laerol/hr/review_cycle.php">Start New Review Cycle</a></button>

        <script type="text/javascript">
        function alertFunc()
        {
          alert("Successfully Started New Cycle");
        }
        </script>
  </div> <!-- /container -->

</body>
</html>