<?php
  session_start();
  require "../db/connect.php";
  
  if (!authCheck($_SESSION['user'], $_SESSION['pass']) || !isset($_SESSION['isHR'])) {
    header('Location: ../');
    exit();
  }

  if (is_null($_GET['for']) || empty($_GET['for'])) {
    header('Location: ../hr');
  }

  $emp = $_GET['for'];

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

  // $reviewCount = 2;

  $withoutSelf = 'SELECT avg(competency1) as c1, avg(competency2) as c2, avg(competency3) as c3, 
                  avg(competency4) as c4, avg(competency5) as c5, avg(competency_agg) as cavg
                  FROM emp_feedback 
                  WHERE designation = \'' . $emp . '\' and reviewer <> \'' . $emp . '\' and review_count = ' . $reviewCount;

  $selfScores = 'SELECT sum(competency1) as c1, sum(competency2) as c2, sum(competency3) as c3, 
                  sum(competency4) as c4, sum(competency5) as c5, sum(competency_agg) as cavg
                  FROM emp_feedback 
                  WHERE designation = \'' . $emp . '\' and reviewer = \'' . $emp . '\' and review_count = ' . $reviewCount;

  $scoresByOthers = $conn->query($withoutSelf);
  $scoresBySelf = $conn->query($selfScores);

  $finalScores = array();
  $finalSelfScores = array();

  if ($scoresByOthers->num_rows > 0) {
    $score = $scoresByOthers->fetch_assoc();
    array_push($finalScores, $score['c1']);
    array_push($finalScores, $score['c2']);
    array_push($finalScores, $score['c3']);
    array_push($finalScores, $score['c4']);
    array_push($finalScores, $score['c5']);
    array_push($finalScores, $score['cavg']);
  }

  if ($scoresBySelf->num_rows > 0) {
    $score = $scoresBySelf->fetch_assoc();
    array_push($finalSelfScores, $score['c1']);
    array_push($finalSelfScores, $score['c2']);
    array_push($finalSelfScores, $score['c3']);
    array_push($finalSelfScores, $score['c4']);
    array_push($finalSelfScores, $score['c5']);
    array_push($finalSelfScores, $score['cavg']);
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
    <h1 style="color:white;font-weight:bold;margin-bottom: 0px;padding-bottom: 5px;">L'ORÉAL: <?php echo $emp; ?></h1><h4 style="color:white;font-weight:bold;margin-top: 0px;margin-bottom: 20px;">India</h4>
    <a href="../hr/choose_function.php"><button class="btn btn-sm">Home</button></a>
  </header>
  
  <div class="row jumbotron">
    <div class="col-lg-12">
      <div class="container">
        <table class="table">
        <thead class="thead-inverse">
          <tr>
            <th>#</th>
            <th>Competency</th>
            <th>Self</th>
            <th>Others</th>
          </tr>
        </thead>
        <tbody>
<!--           <tr>
            <th scope="row">1</th>
            <td>Competency 1</td>
            <td>4.5</td>
            <td>4</td>
          </tr> -->
          <?php
            for ($i=0; $i < 5; $i++) { 
              echo '<tr>';
              echo  '<th scope="row">' . $i . '</th>';
              echo  '<td>Competency ' . $i . '</td>';
              echo  '<td>' . round($finalSelfScores[$i], 2) . '</td>';
              echo  '<td>' . round($finalScores[$i], 2) . '</td>';
              echo'</tr>';
            }
          ?>
        </tbody>
      </table>
      </div> <!-- /container -->
    </div>

        <div class="col-lg-12">
      <div class="container">
        <table class="table">
        <thead class="thead-inverse">
          <tr>
            <th>Aggregate</th>
            <th>Value</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Self</td>
            <td><?php echo round($finalSelfScores[5], 2); ?></td>
          </tr>
          <tr>
            <td>Others</td>
            <td><?php echo round($finalScores[5]); ?></td>
          </tr>
        </tbody>
      </table>
      </div> <!-- /container -->
    </div>

  </div>  
</body>
</html>

