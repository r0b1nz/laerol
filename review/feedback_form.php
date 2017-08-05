<?php
  session_start();
  require "../db/connect.php";
  $user = $_SESSION['user'];

  //For testing
  $_SESSION['feedbackFor'] = 'BIO';

  if (!authCheck($_SESSION['user'], $_SESSION['pass']) || !isset($_SESSION['feedbackFor'])) {
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
    for ($c = 1; $c < 6; $c++) { 
      for ($q=1; $q < 6; $q++) { 
        $fieldName = $prefix . $c . '_q' . $q;
        $answer = "None";
        if (isset($_POST[$fieldName])) {
          $answer = $_POST[$fieldName];
        }
        echo $fieldName . ':' . $answer . '<br>'; // No values from the view
      }
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
  </header>
    <div class="container center_div">

    <h1>Feedback form: <strong><?php echo $_SESSION['feedbackFor'] ?></strong></h1>
    <form method="post">
    <h3>Competency 1</h3>
      <div class="form-group">
        <label for="competency1_q1">Question 1</label><br>
        <label class="radio-inline"><input type="radio" name="competency1_q1" value="1">1</label>
        <label class="radio-inline"><input type="radio" name="competency1_q1">2</label>
        <label class="radio-inline"><input type="radio" name="competency1_q1">3</label>
        <label class="radio-inline"><input type="radio" name="competency1_q1">4</label>
        <label class="radio-inline"><input type="radio" name="competency1_q1">5</label>
      </div>
      <div class="form-group">
        <label for="competency1_q2">Question 2</label><br>
        <label class="radio-inline"><input type="radio" name="competency1_q2">1</label>
        <label class="radio-inline"><input type="radio" name="competency1_q2">2</label>
        <label class="radio-inline"><input type="radio" name="competency1_q2">3</label>
        <label class="radio-inline"><input type="radio" name="competency1_q2">4</label>
        <label class="radio-inline"><input type="radio" name="competency1_q2">5</label>
      </div>
      <div class="form-group">
        <label for="competency1_q3">Question 3</label><br>
        <label class="radio-inline"><input type="radio" name="competency1_q3">1</label>
        <label class="radio-inline"><input type="radio" name="competency1_q3">2</label>
        <label class="radio-inline"><input type="radio" name="competency1_q3">3</label>
        <label class="radio-inline"><input type="radio" name="competency1_q3">4</label>
        <label class="radio-inline"><input type="radio" name="competency1_q3">5</label>
      </div>
      <div class="form-group">
        <label for="competency1_q4">Question 4</label><br>
        <label class="radio-inline"><input type="radio" name="competency1_q4">1</label>
        <label class="radio-inline"><input type="radio" name="competency1_q4">2</label>
        <label class="radio-inline"><input type="radio" name="competency1_q4">3</label>
        <label class="radio-inline"><input type="radio" name="competency1_q4">4</label>
        <label class="radio-inline"><input type="radio" name="competency1_q4">5</label>
      </div>
      <div class="form-group">
        <label for="competency1_q5">Question 5</label><br>
        <label class="radio-inline"><input type="radio" name="competency1_q5">1</label>
        <label class="radio-inline"><input type="radio" name="competency1_q5">2</label>
        <label class="radio-inline"><input type="radio" name="competency1_q5">3</label>
        <label class="radio-inline"><input type="radio" name="competency1_q5">4</label>
        <label class="radio-inline"><input type="radio" name="competency1_q5">5</label>
      </div>

      <h3>Competency 2</h3>
      <div class="form-group">
        <label for="competency2_q1">Question 1</label><br>
        <label class="radio-inline"><input type="radio" name="competency2_q1">1</label>
        <label class="radio-inline"><input type="radio" name="competency2_q1">2</label>
        <label class="radio-inline"><input type="radio" name="competency2_q1">3</label>
        <label class="radio-inline"><input type="radio" name="competency2_q1">4</label>
        <label class="radio-inline"><input type="radio" name="competency2_q1">5</label>
      </div>
      <div class="form-group">
        <label for="competency2_q2">Question 2</label><br>
        <label class="radio-inline"><input type="radio" name="competency2_q2">1</label>
        <label class="radio-inline"><input type="radio" name="competency2_q2">2</label>
        <label class="radio-inline"><input type="radio" name="competency2_q2">3</label>
        <label class="radio-inline"><input type="radio" name="competency2_q2">4</label>
        <label class="radio-inline"><input type="radio" name="competency2_q2">5</label>
      </div>
      <div class="form-group">
        <label for="competency2_q3">Question 3</label><br>
        <label class="radio-inline"><input type="radio" name="competency2_q3">1</label>
        <label class="radio-inline"><input type="radio" name="competency2_q3">2</label>
        <label class="radio-inline"><input type="radio" name="competency2_q3">3</label>
        <label class="radio-inline"><input type="radio" name="competency2_q3">4</label>
        <label class="radio-inline"><input type="radio" name="competency2_q3">5</label>
      </div>
      <div class="form-group">
        <label for="competency2_q4">Question 4</label><br>
        <label class="radio-inline"><input type="radio" name="competency2_q4">1</label>
        <label class="radio-inline"><input type="radio" name="competency2_q4">2</label>
        <label class="radio-inline"><input type="radio" name="competency2_q4">3</label>
        <label class="radio-inline"><input type="radio" name="competency2_q4">4</label>
        <label class="radio-inline"><input type="radio" name="competency2_q4">5</label>
      </div>
      <div class="form-group">
        <label for="competency2_q5">Question 5</label><br>
        <label class="radio-inline"><input type="radio" name="competency2_q5">1</label>
        <label class="radio-inline"><input type="radio" name="competency2_q5">2</label>
        <label class="radio-inline"><input type="radio" name="competency2_q5">3</label>
        <label class="radio-inline"><input type="radio" name="competency2_q5">4</label>
        <label class="radio-inline"><input type="radio" name="competency2_q5">5</label>
      </div>


      <h3>Competency 3</h3>
      <div class="form-group">
        <label for="competency3_q1">Question 1</label><br>
        <label class="radio-inline"><input type="radio" name="competency3_q1">1</label>
        <label class="radio-inline"><input type="radio" name="competency3_q1">2</label>
        <label class="radio-inline"><input type="radio" name="competency3_q1">3</label>
        <label class="radio-inline"><input type="radio" name="competency3_q1">4</label>
        <label class="radio-inline"><input type="radio" name="competency3_q1">5</label>
      </div>
      <div class="form-group">
        <label for="competency3_q2">Question 2</label><br>
        <label class="radio-inline"><input type="radio" name="competency3_q2">1</label>
        <label class="radio-inline"><input type="radio" name="competency3_q2">2</label>
        <label class="radio-inline"><input type="radio" name="competency3_q2">3</label>
        <label class="radio-inline"><input type="radio" name="competency3_q2">4</label>
        <label class="radio-inline"><input type="radio" name="competency3_q2">5</label>
      </div>
      <div class="form-group">
        <label for="competency3_q3">Question 3</label><br>
        <label class="radio-inline"><input type="radio" name="competency3_q3">1</label>
        <label class="radio-inline"><input type="radio" name="competency3_q3">2</label>
        <label class="radio-inline"><input type="radio" name="competency3_q3">3</label>
        <label class="radio-inline"><input type="radio" name="competency3_q3">4</label>
        <label class="radio-inline"><input type="radio" name="competency3_q3">5</label>
      </div>
      <div class="form-group">
        <label for="competency3_q4">Question 4</label><br>
        <label class="radio-inline"><input type="radio" name="competency3_q4">1</label>
        <label class="radio-inline"><input type="radio" name="competency3_q4">2</label>
        <label class="radio-inline"><input type="radio" name="competency3_q4">3</label>
        <label class="radio-inline"><input type="radio" name="competency3_q4">4</label>
        <label class="radio-inline"><input type="radio" name="competency3_q4">5</label>
      </div>
      <div class="form-group">
        <label for="competency3_q5">Question 5</label><br>
        <label class="radio-inline"><input type="radio" name="competency3_q5">1</label>
        <label class="radio-inline"><input type="radio" name="competency3_q5">2</label>
        <label class="radio-inline"><input type="radio" name="competency3_q5">3</label>
        <label class="radio-inline"><input type="radio" name="competency3_q5">4</label>
        <label class="radio-inline"><input type="radio" name="competency3_q5">5</label>
      </div>

      <h3>Competency 4</h3>
      <div class="form-group">
        <label for="competency4_q1">Question 1</label><br>
        <label class="radio-inline"><input type="radio" name="competency4_q1">1</label>
        <label class="radio-inline"><input type="radio" name="competency4_q1">2</label>
        <label class="radio-inline"><input type="radio" name="competency4_q1">3</label>
        <label class="radio-inline"><input type="radio" name="competency4_q1">4</label>
        <label class="radio-inline"><input type="radio" name="competency4_q1">5</label>
      </div>
      <div class="form-group">
        <label for="competency4_q2">Question 2</label><br>
        <label class="radio-inline"><input type="radio" name="competency4_q2">1</label>
        <label class="radio-inline"><input type="radio" name="competency4_q2">2</label>
        <label class="radio-inline"><input type="radio" name="competency4_q2">3</label>
        <label class="radio-inline"><input type="radio" name="competency4_q2">4</label>
        <label class="radio-inline"><input type="radio" name="competency4_q2">5</label>
      </div>
      <div class="form-group">
        <label for="competency4_q3">Question 3</label><br>
        <label class="radio-inline"><input type="radio" name="competency4_q3">1</label>
        <label class="radio-inline"><input type="radio" name="competency4_q3">2</label>
        <label class="radio-inline"><input type="radio" name="competency4_q3">3</label>
        <label class="radio-inline"><input type="radio" name="competency4_q3">4</label>
        <label class="radio-inline"><input type="radio" name="competency4_q3">5</label>
      </div>
      <div class="form-group">
        <label for="competency4_q4">Question 4</label><br>
        <label class="radio-inline"><input type="radio" name="competency4_q4">1</label>
        <label class="radio-inline"><input type="radio" name="competency4_q4">2</label>
        <label class="radio-inline"><input type="radio" name="competency4_q4">3</label>
        <label class="radio-inline"><input type="radio" name="competency4_q4">4</label>
        <label class="radio-inline"><input type="radio" name="competency4_q4">5</label>
      </div>
      <div class="form-group">
        <label for="competency4_q5">Question 5</label><br>
        <label class="radio-inline"><input type="radio" name="competency4_q5">1</label>
        <label class="radio-inline"><input type="radio" name="competency4_q5">2</label>
        <label class="radio-inline"><input type="radio" name="competency4_q5">3</label>
        <label class="radio-inline"><input type="radio" name="competency4_q5">4</label>
        <label class="radio-inline"><input type="radio" name="competency4_q5">5</label>
      </div>

      <h3>Competency 5</h3>
      <div class="form-group">
        <label for="competency5_q1">Question 1</label><br>
        <label class="radio-inline"><input type="radio" name="competency5_q1">1</label>
        <label class="radio-inline"><input type="radio" name="competency5_q1">2</label>
        <label class="radio-inline"><input type="radio" name="competency5_q1">3</label>
        <label class="radio-inline"><input type="radio" name="competency5_q1">4</label>
        <label class="radio-inline"><input type="radio" name="competency5_q1">5</label>
      </div>
      <div class="form-group">
        <label for="competency5_q2">Question 2</label><br>
        <label class="radio-inline"><input type="radio" name="competency5_q2">1</label>
        <label class="radio-inline"><input type="radio" name="competency5_q2">2</label>
        <label class="radio-inline"><input type="radio" name="competency5_q2">3</label>
        <label class="radio-inline"><input type="radio" name="competency5_q2">4</label>
        <label class="radio-inline"><input type="radio" name="competency5_q2">5</label>
      </div>
      <div class="form-group">
        <label for="competency5_q3">Question 3</label><br>
        <label class="radio-inline"><input type="radio" name="competency5_q3">1</label>
        <label class="radio-inline"><input type="radio" name="competency5_q3">2</label>
        <label class="radio-inline"><input type="radio" name="competency5_q3">3</label>
        <label class="radio-inline"><input type="radio" name="competency5_q3">4</label>
        <label class="radio-inline"><input type="radio" name="competency5_q3">5</label>
      </div>
      <div class="form-group">
        <label for="competency5_q4">Question 4</label><br>
        <label class="radio-inline"><input type="radio" name="competency5_q4">1</label>
        <label class="radio-inline"><input type="radio" name="competency5_q4">2</label>
        <label class="radio-inline"><input type="radio" name="competency5_q4">3</label>
        <label class="radio-inline"><input type="radio" name="competency5_q4">4</label>
        <label class="radio-inline"><input type="radio" name="competency5_q4">5</label>
      </div>
      <div class="form-group">
        <label for="competency5_q5">Question 5</label><br>
        <label class="radio-inline"><input type="radio" name="competency5_q5">1</label>
        <label class="radio-inline"><input type="radio" name="competency5_q5">2</label>
        <label class="radio-inline"><input type="radio" name="competency5_q5">3</label>
        <label class="radio-inline"><input type="radio" name="competency5_q5">4</label>
        <label class="radio-inline"><input type="radio" name="competency5_q5">5</label>
      </div>

      <button type="submit" class="btn btn-default submit">Submit</button>
    </form>

    </div> <!-- /container -->
  </body>
</html>