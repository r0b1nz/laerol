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
  // echo 'Level: ' . $level;

  $feedbackList = array();
  $isUserManager = 'SELECT count(*) as c from emp_info where manager = \'' . $user . '\'';
  $result = $conn->query($isUserManager);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['c'] > 0) {
      // User manages more than 0 employees, therefore user is a manager. 
      array_push($feedbackList, $user);
    }
  }
  

  // TODO: Add conditions for showing the feedback links

  // LEVLE 0
  // Give feedback only for Level 1
  if ($level == 0) {
    $sql = getUsersSQL(1);
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        array_push($feedbackList, $row['designation']);
      }
    }
  }

  // LEVEL 1
  // Give feedback for level 0, 1, and 2(if any managers under the user's team)
  if ($level == 1) {

    // Add Level 1 peers
    $sql = getUsersSQL(1);
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        if ($row['designation'] != $user){
          array_push($feedbackList, $row['designation']);
        }
      }
    }

    // Add Level 0
    $sql = getUsersSQL(0);
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        if ($row['designation'] != $user){
          array_push($feedbackList, $row['designation']);
        }
      }
    }


    // Add level 2, if user manages the emp, and emp is a manager. 
    // SELECT a.designation FROM emp_info a, emp_info b WHERE a.level = 2 AND a.manager = 'BIO' and b.manager = a.designation
    $sql = 'SELECT a.designation as designation 
            FROM emp_info a, emp_info b 
            WHERE a.level = 2 AND a.manager = \'' . $user . '\' and b.manager = a.designation';
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        array_push($feedbackList, $row['designation']);
      }
    }
  }

  // For Further levels, just take the feedback for the MANAGER
  if ($level > 1) {
    $sql = 'SELECT manager from emp_info where designation = \'' . $user . '\'';
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        array_push($feedbackList, $row['manager']);
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
    <h1 style="color:white;font-weight:bold;margin-bottom: 0px;padding-bottom: 0px;">L'ORÉAL</h1><br><h4 style="color:white;font-weight:bold;margin-top: 0px;margin-bottom: 20px;">India</h4>
    <a href="../review/choose_feedback.php"><button class="btn btn-sm">Home</button></a>
    <a href="../"><button class="btn btn-sm">Log Out</button></a>
	</header>
    
  <div class="container text-center">
        <?php
          foreach ($feedbackList as $designation) {
            echo '<a href="feedback_form.php?d=' . strtoupper($designation) . '"><button class="btn btn-lg" role="button">' . strtoupper($designation) . '</button></a>';
          }
        ?>
  </div> <!-- /container -->

</body>
</html>