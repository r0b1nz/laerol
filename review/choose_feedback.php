<?php
  session_start();
  require "../db/connect.php";
  
  if (!authCheck($_SESSION['user'], $_SESSION['pass']) || $_SESSION['isHR']) {
    header('Location: ../');
    exit();
  }

  $user = $_SESSION['user'];

  $getUserLevel = 'SELECT level from emp_info where designation = \'' . $user . '\'';
  $result = $conn->query($getUserLevel);
  if ($result->num_rows != 1) {
    echo 'Error in user\'s designation levels';
    exit();
  }
  $level = $result->fetch_assoc()['level'];
  echo 'Level: ' . $level;

  $feedbackList = array();
  array_push($feedbackList, $user);

  // TODO: Add conditions for showing the feedback links
  if ($level == 0) {
    $sql = getUsersSQL(1);
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        array_push($feedbackList, $row['designation']);
      }
    }
  }


  function getUsersSQL($lvl) {
    return 'SELECT designation from emp_info where level = ' . $lvl;;
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
    <a href="/laerol/review/choose_feedback.php"><button class="btn btn-sm">Home</button></a>
    <a href="/laerol/"><button class="btn btn-sm">Log Out</button></a>
	</header>
    
  <div class="container">
      <form class="form-select" name="form1" method="post">
      	<!-- <button name="Submit" id="submit" class="btn btn-lg btn-block" type="submit">Self Feedback</button> -->
        <!-- <a href="feedback_form.php?d=BIO" class="btn btn-lg btn-block" role="button">Self Feedback</a> -->
        <?php
          foreach ($feedbackList as $designation) {
            echo '<a href="feedback_form.php?d=' . strtoupper($designation) . '" class="btn btn-lg btn-block" role="button">
            ' . strtoupper($designation) . '</a>';
          }
        ?>
      </form>
  </div> <!-- /container -->

</body>
</html>