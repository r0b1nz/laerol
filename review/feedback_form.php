<?php
  session_start();
  require "../db/connect.php";
  $feedbackQuestions = 5;
  $user = $_SESSION['user'];

  if (is_null($_GET['d']) or empty($_GET['d'])) {
    header('Location: choose_feedback.php'); 
  }

  //For testing
  $_SESSION['feedbackFor'] = $_GET['d'];

  if (!authCheck($_SESSION['user'], $_SESSION['pass']) || !isset($_SESSION['feedbackFor']) || $_SESSION['isHR']) {
    header('Location: ../');
    exit();
  }

  //Check if $_SESSION['feedbackFor'] is a manager
  $isManagerSQL = 'SELECT 1 from emp_info where manager = \'' . $_SESSION['feedbackFor'] . '\'';
  $isManagerResult = $conn->query($isManagerSQL);
  if ($isManagerResult->num_rows < 1) {
    // Not a manager, Cannot take reivew
    echo $_SESSION['feedbackFor'] . 'Not a manager';
    exit();
  }

  $feedbackFor = $_SESSION['feedbackFor']; // TODO: Add SQL Injection validation

  // Get the ReviewCount
  // Update: INSERT INTO `loreal_hr_feedback`.`review_cycle` (`date`, `review_count`) VALUES (CURRENT_DATE(), NULL);
  $reviewCountSQL = 'SELECT max(review_count) as rc FROM review_cycle';
  $reviewCount = $conn->query($reviewCountSQL)->fetch_assoc()['rc'];

  // if the feedback is already done.
  $repeatSQL = 'SELECT 1 FROM emp_feedback WHERE review_count = ' . $reviewCount . '
                AND designation = \'' . $_SESSION["feedbackFor"] . '\' 
                AND reviewer = \'' . $user . '\'';
  if ($conn->query($repeatSQL)->num_rows > 0) {
    echo 'Feedback already submitted. Please go back and give feedback for another employee';
    exit();
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prefix = 'competency';
    $scores = array();
    for ($c = 1; $c <= $feedbackQuestions; $c++) { 
      $sum = 0.0;
      $totalQuestions = 0;
      for ($q=1; $q < 6; $q++) { 
        $fieldName = $prefix . $c . '_q' . $q;
        $answer = 0;
        
        if (isset($_POST[$fieldName])) {
          $answer = $_POST[$fieldName];
          $totalQuestions++;
          $sum = $sum + $answer;
        }        
        // echo $fieldName . ':' . $answer . '<br>'; // No values from the view
      }
      $avg = $sum / $totalQuestions;
      $scores[$c - 1] = round($avg, 2);
    }

    $avgScore = array_sum($scores) / $feedbackQuestions;
    $insertSQL = 'INSERT INTO emp_feedback VALUES(' . $reviewCount . ', 
                    \'' . $feedbackFor . '\', \'' . $user . '\', 
                    ' . $scores[0] . ', '. $scores[1] . ', 
                    ' . $scores[2] . ', '. $scores[3] . ', 
                    ' . $scores[4] . ', ' . $avgScore . ')';
    echo $insertSQL;
    if ($conn->query($insertSQL) === FALSE) {
      echo '<script>alert("Error in saving feedback")</script>';
    } else {
      echo '<script>alert("Thank you for the feedback")</script>';
    }
  }

?>
<!DOCTYPE html>
<html lang="en">
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
    <h1 class="heading">L'OREAL</h1>
    <button class="btn btn-sm"><a href="/laerol/review/choose_feedback.php">Home</a></button>
  </header>
    <div class="container center_div">

    <h1>Feedback form: <strong><?php echo $_SESSION['feedbackFor'] ?></strong></h1>
    <form method="post">
    <h3>Competency 1</h3>
      <div class="form-group">
        <label for="competency1_q1">Question 1</label><br>
        <label class="radio-inline"><input type="radio" name="competency1_q1" value="1" required>1</label>
        <label class="radio-inline"><input type="radio" name="competency1_q1" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency1_q1" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency1_q1" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency1_q1" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency1_q2">Question 2</label><br>
        <label class="radio-inline"><input type="radio" name="competency1_q2" value="1" required>1</label>
        <label class="radio-inline"><input type="radio" name="competency1_q2" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency1_q2" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency1_q2" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency1_q2" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency1_q3">Question 3</label><br>
        <label class="radio-inline"><input type="radio" name="competency1_q3" value="1" required>1</label>
        <label class="radio-inline"><input type="radio" name="competency1_q3" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency1_q3" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency1_q3" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency1_q3" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency1_q4">Question 4</label><br>
        <label class="radio-inline"><input type="radio" name="competency1_q4" value="1" required>1</label>
        <label class="radio-inline"><input type="radio" name="competency1_q4" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency1_q4" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency1_q4" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency1_q4" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency1_q5">Question 5</label><br>
        <label class="radio-inline"><input type="radio" name="competency1_q5" value="1" required>1</label>
        <label class="radio-inline"><input type="radio" name="competency1_q5" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency1_q5" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency1_q5" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency1_q5" value="5">5</label>
      </div>

      <h3>Competency 2</h3>
      <div class="form-group">
        <label for="competency2_q1">Question 1</label><br>
        <label class="radio-inline"><input type="radio" name="competency2_q1" value="1" required>1</label>
        <label class="radio-inline"><input type="radio" name="competency2_q1" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency2_q1" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency2_q1" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency2_q1" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency2_q2">Question 2</label><br>
        <label class="radio-inline"><input type="radio" name="competency2_q2" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency2_q2" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency2_q2" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency2_q2" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency2_q2" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency2_q3">Question 3</label><br>
        <label class="radio-inline"><input type="radio" name="competency2_q3" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency2_q3" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency2_q3" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency2_q3" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency2_q3" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency2_q4">Question 4</label><br>
        <label class="radio-inline"><input type="radio" name="competency2_q4" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency2_q4" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency2_q4" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency2_q4" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency2_q4" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency2_q5">Question 5</label><br>
        <label class="radio-inline"><input type="radio" name="competency2_q5" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency2_q5" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency2_q5" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency2_q5" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency2_q5" value="5">5</label>
      </div>


      <h3>Competency 3</h3>
      <div class="form-group">
        <label for="competency3_q1">Question 1</label><br>
        <label class="radio-inline"><input type="radio" name="competency3_q1" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency3_q1" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency3_q1" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency3_q1" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency3_q1" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency3_q2">Question 2</label><br>
        <label class="radio-inline"><input type="radio" name="competency3_q2" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency3_q2" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency3_q2" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency3_q2" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency3_q2" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency3_q3">Question 3</label><br>
        <label class="radio-inline"><input type="radio" name="competency3_q3" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency3_q3" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency3_q3" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency3_q3" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency3_q3" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency3_q4">Question 4</label><br>
        <label class="radio-inline"><input type="radio" name="competency3_q4" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency3_q4" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency3_q4" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency3_q4" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency3_q4" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency3_q5">Question 5</label><br>
        <label class="radio-inline"><input type="radio" name="competency3_q5" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency3_q5" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency3_q5" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency3_q5" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency3_q5" value="5">5</label>
      </div>

      <h3>Competency 4</h3>
      <div class="form-group">
        <label for="competency4_q1">Question 1</label><br>
        <label class="radio-inline"><input type="radio" name="competency4_q1" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency4_q1" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency4_q1" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency4_q1" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency4_q1" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency4_q2">Question 2</label><br>
        <label class="radio-inline"><input type="radio" name="competency4_q2" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency4_q2" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency4_q2" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency4_q2" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency4_q2" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency4_q3">Question 3</label><br>
        <label class="radio-inline"><input type="radio" name="competency4_q3" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency4_q3" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency4_q3" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency4_q3" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency4_q3" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency4_q4">Question 4</label><br>
        <label class="radio-inline"><input type="radio" name="competency4_q4" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency4_q4" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency4_q4" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency4_q4" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency4_q4" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency4_q5">Question 5</label><br>
        <label class="radio-inline"><input type="radio" name="competency4_q5" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency4_q5" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency4_q5" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency4_q5" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency4_q5" value="5">5</label>
      </div>

      <h3>Competency 5</h3>
      <div class="form-group">
        <label for="competency5_q1">Question 1</label><br>
        <label class="radio-inline"><input type="radio" name="competency5_q1" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency5_q1" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency5_q1" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency5_q1" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency5_q1" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency5_q2">Question 2</label><br>
        <label class="radio-inline"><input type="radio" name="competency5_q2" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency5_q2" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency5_q2" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency5_q2" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency5_q2" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency5_q3">Question 3</label><br>
        <label class="radio-inline"><input type="radio" name="competency5_q3" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency5_q3" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency5_q3" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency5_q3" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency5_q3" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency5_q4">Question 4</label><br>
        <label class="radio-inline"><input type="radio" name="competency5_q4" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency5_q4" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency5_q4" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency5_q4" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency5_q4" value="5">5</label>
      </div>
      <div class="form-group">
        <label for="competency5_q5">Question 5</label><br>
        <label class="radio-inline"><input type="radio" name="competency5_q5" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency5_q5" value="2">2</label>
        <label class="radio-inline"><input type="radio" name="competency5_q5" value="3">3</label>
        <label class="radio-inline"><input type="radio" name="competency5_q5" value="4">4</label>
        <label class="radio-inline"><input type="radio" name="competency5_q5" value="5">5</label>
      </div>

      <button type="submit" class="btn btn-default btn-block">Submit</button>

    </form>

    </div> <!-- /container -->
  </body>
</html>