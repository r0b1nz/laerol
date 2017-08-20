<?php
  session_start();
  require "../db/connect.php";
  
  if (!authCheck($_SESSION['user'], $_SESSION['pass']) || !isset($_SESSION['isHR'])) {
    header('Location: ../');
    exit();
  }

  $emp = securityPipe($_GET['for']);

  // Get the ReviewCount
  // Update: INSERT INTO `loreal_hr_feedback`.`review_cycle` (`date`, `review_count`) VALUES (CURRENT_DATE(), NULL);
  if (!is_null($_GET['rc']) && !is_nan($_GET['rc'])) {
    $reviewCount = securityPipe($_GET['rc']);
    // echo 'Count;' . $reviewCount;
  } else {
    $reviewCountSQL = 'SELECT max(review_count) as rc FROM review_cycle';
    $reviewCount = $conn->query($reviewCountSQL)->fetch_assoc()['rc'];
  }


  // Get emp level
  $level = 2;
  $levelSQL = "SELECT level FROM emp_info WHERE designation = '{$emp}'";
  $result = $conn->query($levelSQL);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $level = $row['level'];
  }

  $reviewers = array();
  if ($level == 1) {
    // get peers + team
    $sql = "SELECT DISTINCT designation
            FROM emp_info
            WHERE level = 1
            UNION (SELECT DISTINCT designation FROM emp_info WHERE manager = '{$emp}')
            UNION (SELECT manager FROM emp_info WHERE designation = '{$emp}')";
    $audience = $conn->query($sql);
    if ($audience->num_rows > 0) {
      while ($row = $audience->fetch_assoc()) {
        array_push($reviewers, $row['designation']);
      }
    }
  } else {
    // Get only the team
    $sql = "SELECT DISTINCT designation
        FROM emp_info
        WHERE manager = '{$emp}'";
    $audience = $conn->query($sql);
    if ($audience->num_rows > 0) {
      while ($row = $audience->fetch_assoc()) {
        array_push($reviewers, $row['designation']);
      }
    }

    $isManagerSQL = "SELECT 1 
                      FROM emp_info
                      WHERE manager = '{$emp}'";
    $result = $conn->query($isManagerSQL);
    if ($result->num_rows > 0) {
      $getManager = "SELECT manager FROM emp_info where designation = '{$emp}'";
      $result = $conn->query($getManager);
      array_push($reviewers, $result->fetch_assoc()['manager']);
    }

  }

  function hasSubmitted($user, $emp, $reviewCount, $conn) {
    $sql = "SELECT 1 
            FROM feedbacks 
            WHERE review_count = '{$reviewCount}'
            AND designation = '{$emp}'
            AND reviewer = '{$user}'";
    // echo $sql;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      return 'Yes';
    } else {
      return 'No';
    }
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
    <h1 style="color:white;font-weight:bold;margin-bottom: 0px;padding-bottom: 0px;">L'OREAL</h1><br><h4 style="color:white;font-weight:bold;margin-top: 0px;margin-bottom: 20px;">India</h4>
    <a href="../hr/choose_function.php"><button class="btn btn-sm">Home</button></a>
    <a href="../hr/view_feedback.php"><button class="btn btn-sm">Back</button></a><br><br>
  </header>
    
  <div class="container center_div">
    <table class="table">
      <thead class="thead-inverse">
        <tr>
          <th>#</th>
          <th class="do_center">Designation</th>
          <th class="do_center">Submitted</th>
        </tr>
      </thead>
      <tbody>
      <?php
        foreach ($reviewers as $key => $value) {
      echo '<tr>';
      echo '  <td>' . ($key+1) . '</td>';
      echo '  <td class="do_center">' . $reviewers[$key] . '</td>';
      echo '<td class="do_center">' . hasSubmitted($reviewers[$key], $emp, $reviewCount, $conn) . '</td>';
      echo ' </tr>';
        }
      ?>

      </tbody>
    </table>
  </div> <!-- /container -->

</body>
</html>

