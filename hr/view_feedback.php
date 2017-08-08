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

  $getManagers = 'SELECT a.designation as designation FROM emp_info a, emp_info b WHERE a.designation = b.manager';
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
    <h1 class="heading choose">L'OREAL</h1>
    <a href="../hr/choose_function.php"><button class="btn btn-sm">Home</button></a>
  </header>
    
  <div class="container center_div">
    <table class="table">
      <thead class="thead-inverse">
        <tr>
          <th>#</th>
          <th class="do_center">Designation</th>
          <th class="do_center">Click to View Feedback</th>
        </tr>
      </thead>
      <tbody>
<!--    TEMPLATE for reference. 
TODO: Add hyperlink to the button
        <tr>
          <th scope="row">1</th>
          <td class="do_center">Plant Director</td>
          <td class="do_center"><button type="button" class="btn btn-danger" value="designationOfEmployee">View</button></td>
        </tr>
 -->
        <?php
          if ($result->num_rows > 0) {
            $managerCounter = 1;
            while ($manager = $result->fetch_assoc()) {
              echo '<tr>';
              echo '<th scope="row">' . $managerCounter . '</th>';
              echo '<td class="do_center">' . $manager['designation'] . '</td>';
              echo '<td class="do_center"><a href="feedback.php?for=' . $manager['designation'] . '"><button type="button" class="btn btn-danger" value="designationOfEmployee">View</button></a></td>';
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

