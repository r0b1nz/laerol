<?php
  session_start();
  require "../db/connect.php";
  
  if (!authCheck($_SESSION['user'], $_SESSION['pass']) || !isset($_SESSION['isHR'])) {
    header('Location: ../');
    exit();
  }

  // Get the ReviewCount
  // Update: INSERT INTO `loreal_hr_feedback`.`review_cycle` (`date`, `review_count`) VALUES (CURRENT_DATE(), NULL);
  $reviewCountSQL = 'SELECT max(review_count) as rc FROM review_cycle';
  $reviewCount = $conn->query($reviewCountSQL)->fetch_assoc()['rc'];

  $getManagers = 'SELECT DISTINCT a.designation as designation FROM emp_info a, emp_info b WHERE a.designation = b.manager AND a.level <> 0';
  $result = $conn->query($getManagers);
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
      <tr>
        <td>1</td>
        <td class="do_center">HOD_HR</td>
        <td class="do_center">Yes</td>
      </tr>

      </tbody>
    </table>
  </div> <!-- /container -->

</body>
</html>

