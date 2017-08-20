<?php
  session_start();
  require "../db/connect.php";
  
  if (!authCheck($_SESSION['user'], $_SESSION['pass']) || !isset($_SESSION['isHR'])) {
    header('Location: ../');
    exit();
  }

  // Get the ReviewCount
  // Update: INSERT INTO `loreal_hr_feedback`.`review_cycle` (`date`, `review_count`) VALUES (CURRENT_DATE(), NULL);
  // $reviewCountSQL = 'SELECT max(review_count) as rc FROM review_cycle';
  $reviewCountSQL = 'SELECT * FROM REVIEW_CYCLE ORDER BY review_count desc LIMIT 1';

  // $reviewCount = $conn->query($reviewCountSQL)->fetch_assoc()['review_count'];

  $result = $conn->query($reviewCountSQL);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $reviewCount = $row['review_count'];
    $date = $row['date'];
  }

  $getManagers = 'SELECT DISTINCT a.designation as designation FROM emp_info a, emp_info b WHERE a.designation = b.manager AND a.level <> 0';
  $result = $conn->query($getManagers);

  $rc = $reviewCount;
  if (!is_null($_GET['ReviewCycleNumber'])) {
    if (!is_nan($_GET['ReviewCycleNumber'])) {
      $rc = securityPipe($_GET['ReviewCycleNumber']);
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
    <a href="../hr/choose_function.php"><button class="btn btn-sm">Home</button></a><br><br>
    <form action="../hr/view_feedback.php">
    <p style="color: white">Review Cycle Number: <strong><?php echo $rc . ' </strong><br>Started on: <strong>' . $date; $rc = '&rc=' . $rc; ?></strong></p>
    <select name="ReviewCycleNumber">
      <?php for ($i=1; $i <= $reviewCount; $i++) { 
        echo '<option value="' . $i . '">' . $i . '</option>';
      } ?>
    </select>&nbsp;&nbsp;&nbsp;
    <input class="btn btn-sm" type="submit">
  </form>
  </header>
    
  <div class="container center_div">
    <table class="table">
      <thead class="thead-inverse">
        <tr>
          <th>#</th>
          <th class="do_center">Designation</th>
          <th class="do_center">Click to View Feedback</th>
          <th class="do_center">Click to Feedback Details</th>
        </tr>
      </thead>
      <tbody>

    <?php
      if ($result->num_rows > 0) {
      $managerCounter = 1;
      while ($manager = $result->fetch_assoc()) {
        echo '<tr>';
        echo '  <th scope="row">' . $managerCounter . '</th>';
        echo '  <td class="do_center">' . $manager['designation'] . '</td>';
        echo '  <td class="do_center"><a href="feedback.php?for=' . $manager['designation'] . $rc . '"><button type="button" class="btn btn-danger" value="designationOfEmployee">View</button></td>';
        echo '  <td class="do_center"><a href="view_submission_details.php?for=' . $manager['designation']  . $rc .  '"><button type="button" class="btn btn-danger" value="designationOfEmployee">View</button></td>';
        echo '</tr>';
        $managerCounter++;
      }
    }
 ?>



      </tbody>
    </table>
  </div> <!-- /container -->

</body>
</html>

