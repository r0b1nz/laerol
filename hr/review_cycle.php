<?php
  session_start();
  require "../db/connect.php";
  
  if (!authCheck($_SESSION['user'], $_SESSION['pass']) || !isset($_SESSION['isHR'])) {
    header('Location: ../');
    exit();
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updateReviewCount = 'INSERT INTO review_cycle VALUES (CURRENT_DATE(), NULL)';
    if ($conn->query($updateReviewCount) === FALSE) {
      echo 'Error in updating review count';
    }
  }

  // Get the ReviewCount
  // Update: INSERT INTO `loreal_hr_feedback`.`review_cycle` (`date`, `review_count`) VALUES (CURRENT_DATE(), NULL);
  $reviewCountSQL = 'SELECT max(review_count) as rc FROM review_cycle';
  $reviewCount = $conn->query($reviewCountSQL)->fetch_assoc()['rc'];
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
  </header>
    
  <div class="container text-center">
    <h3 class="heading2">Review Count is <?php echo $reviewCount ?></h3>
    <form method="POST">
      <button name="Submit" id="submit" class="btn btn-lg" type="submit">Start New Review Cycle</button>
    </form>
  </div> <!-- /container -->

</body>
</html>

