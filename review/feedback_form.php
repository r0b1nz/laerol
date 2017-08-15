<?php
  session_start();
  require "../db/connect.php";
  $feedbackQuestions = 5;
  $user = $_SESSION['user'];

  if (is_null($_GET['d']) or empty($_GET['d'])) {
    header('Location: choose_feedback.php'); 
  }

  //For testing
  $_SESSION['feedbackFor'] = securityPipe($_GET['d']);

  if (!authCheck($_SESSION['user'], $_SESSION['pass']) || !isset($_SESSION['feedbackFor']) || $_SESSION['isHR']) {
    header('Location: ../');
    exit();
  }

  //Check if $_SESSION['feedbackFor'] is a manager
  $isManagerSQL = 'SELECT 1 from emp_info where manager = \'' . $_SESSION['feedbackFor'] . '\'';
  $isManagerResult = $conn->query($isManagerSQL);
  if ($isManagerResult->num_rows < 1) {
    // Not a manager, Cannot take reivew
    echo 'Oops, ' . $_SESSION['feedbackFor'] . ' is not a manager.';
    exit();
  }

  $feedbackFor = $_SESSION['feedbackFor']; // TODO: Add SQL Injection validation

  // Get the ReviewCount
  // Update: INSERT INTO `loreal_hr_feedback`.`review_cycle` (`date`, `review_count`) VALUES (CURRENT_DATE(), NULL);
  $reviewCountSQL = 'SELECT max(review_count) as rc FROM review_cycle';
  $reviewCount = $conn->query($reviewCountSQL)->fetch_assoc()['rc'];

  // if the feedback is already done.
  $repeatSQL = 'SELECT 1 FROM feedback WHERE review_count = ' . $reviewCount . '
                AND designation = \'' . $_SESSION["feedbackFor"] . '\' 
                AND reviewer = \'' . $user . '\'';
  if ($conn->query($repeatSQL)->num_rows > 0) {
    echo 'Feedback already submitted. Please go back and give feedback for another employee';
    exit();
  }

  /*if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prefix = 'competency';
    $scores = array();
    for ($c = 1; $c <= $feedbackQuestions; $c++) { 
      $sum = 0.0;
      $totalQuestions = 0;
      for ($q=1; $q < 6; $q++) { 
        $fieldName = $prefix . $c . '_q' . $q;
        $answer = 0;
        
        if (isset($_POST[$fieldName])) {
          $answer = securityPipe($_POST[$fieldName]);
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
    // echo $insertSQL;
    if ($conn->query($insertSQL) === FALSE) {
      echo '<script>alert("Error in saving feedback")</script>';
    } else {
      // echo '<script>alert("Thank you for the feedback")</script>';
      // header('Location: choose_feedback.php');
      echo '<script type="text/javascript">
        alertFunc();
        function alertFunc()
        {
          alert("Successfully Submitted Review");
          location.href = "../review/choose_feedback.php"
        }
        </script>';
    }
  }*/


  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // vars: competency1_s1_q1
    $competencyCount = 5;
    $sections = 4;
    $maxQPerSection = 5;

    for ($c=1; $c <= $competencyCount = 5; $c++) { 
      $section = array();
      $totalSectionScore = 0;
      $minSection = 10000;
      $maxSection = -1;
      // loop sections
      for ($s=1; $s <= $sections ; $s++) { 
        // $min = 10000;
        $min = $minSection;
        // $max = -1;
        $max = $maxSection;
        $totalScore = 0;
        $totalQuestions = 0;

        for ($q=1; $q <= $maxQPerSection ; $q++) { 
          $radioName = 'competency' . $c . '_s' . $s . '_q' . $q;
          if (isset($_POST[$radioName])) {
            if ($_POST[$radioName] > $max)
              $max = $_POST[$radioName];
            if ($_POST[$radioName] < $min)
              $min = $_POST[$radioName];
            $totalQuestions++;
            $totalScore = $totalScore + $_POST[$radioName];
          }
        }

        if ($totalQuestions == 0)
          continue;
        $sectionScore = round($totalScore / $totalQuestions, 2);
        $totalSectionScore = $totalSectionScore + $sectionScore;
        if ($sectionScore < $minSection)
          $minSection = $sectionScore;
        if ($sectionScore > $maxSection)
          $maxSection = $sectionScore;


        // array_push($section, $sectionScore . ';' . $min . ';' . $max);
        array_push($section, $sectionScore);
        $minSection = $min;
        $maxSection = $max;
      }
      //make SQL and push ($section[], $totalSectionScore/$sections, $minSection, $maxSection) 
      $competencyScore = round($totalSectionScore / $sections, 2);
      $insertSQL = "INSERT INTO feedback VALUES ({$reviewCount}, '{$feedbackFor}', '{$user}', 
                                                  {$c}, '{$section[0]}', '{$section[1]}',
                                                  '{$section[2]}', '{$section[3]}',
                                                  '{$competencyScore}', {$minSection}, {$maxSection})";
      // echo $insertSQL;
      if ($conn->query($insertSQL) === FALSE) {
        echo '<script>alert("Error in saving feedback")</script>';
      } else {
        // echo '<script>alert("Thank you for the feedback")</script>';
        // header('Location: choose_feedback.php');
        echo '<script type="text/javascript">
          alertFunc();
          function alertFunc()
          {
            alert("Successfully Submitted Review");
            location.href = "../review/choose_feedback.php"
          }
          </script>';
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
    <link rel="stylesheet" type="text/css" href="../css/form_style.css">
  </head>

  <body>
  <header>
    <h1>L'ORÉAL</h1><br>
    <h4>India</h4>
    <a href="../review/choose_feedback.php"><button class="btn btn-sm">Home</button></a>
  </header>
  <div class="container">
  <h1 align="center" class="feedback_center_div">Feedback Form</h1>
    <form method="POST" class="form_div">
      <h3 align="center">PEOPLE DEVELOPER - "Grow people to grow the business"</h3>
      <div class="questions">
      <h4 align="center">Treats all individuals in a respectful and consistent manner</h4>
        <div class="form-group">
          <label for="competency1_s1_q1">1. Sets an example in terms of personal integrity </label><br>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency1_s1_q2">2. Promotes a climate of mutual respect and transparency across all levels</label><br>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency1_s1_q3">3. Listens and balances directness with empathy</label><br>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q3" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency1_s1_q4">4. Makes people decisions based on performance and merit</label><br>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q4" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q4" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q4" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q4" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q4" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency1_s1_q5">5. Adjusts priorities to take into account the work load of his/her team</label><br>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q5" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q5" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q5" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q5" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s1_q5" value="5">5</label>
        </div>

        <h4 align="center">Leverages diversity </h4>
        <div class="form-group">
          <label for="competency1_s2_q1">1. Seeks to understand others' motives, ambitions and emotions</label><br>
          <label class="radio-inline"><input type="radio" name="competency1_s2_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s2_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s2_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s2_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s2_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency1_s2_q2">2. Encourages the team to work with diverse personalities, functions and cultures</label><br
          <label class="radio-inline"><input type="radio" name="competency1_s2_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s2_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s2_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s2_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s2_q2" value="5">5</label>
        </div>

        <h4 align="center">Stimulates learning</h4>
        <div class="form-group">
          <label for="competency1_s3_q1">1. Analyzes personal mistakes and failures and learns from them </label><br>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency1_s3_q2">2. Creates the work environment to stimulate learning and creativity </label><br>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency1_s3_q3">3. Acquires and transmits beauty expertise gained through accumulated experience</label><br>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s3_q3" value="5">5</label>
        </div>

        <h4 align="center">Empowers and develops individuals to contribute their best</h4>
        <div class="form-group">
          <label for="competency1_s4_q1">1. Clarifies performance expectations</label><br>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency1_s4_q2">2. Clarifies development priorities</label><br>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency1_s4_q3">3. Shows trust and confidence in people’s ability to succeed</label><br>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q3" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency1_s4_q4">4. Uses a fact-based approach to appraise and gives feedback</label><br>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q4" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q4" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q4" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q4" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q4" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency1_s4_q5">5. Spots talent accurately and mentors them for the Group</label><br>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q5" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q5" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q5" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q5" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency1_s4_q5" value="5">5</label>
        </div>
      </div>

      <h3 align="center">ENTREPRENEUR - "RUN IT AS YOUR OWN BUSINESS"</h3>
      <div class="questions">
      <h4 align="center">Takes accountability with courage</h4>
        <div class="form-group">
          <label for="competency2_s1_q1">1. Stands by own decisions and takes responsibility for them</label><br>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency2_s1_q2">2. Takes responsibility for setbacks and wins</label><br>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency2_s1_q3">3. Defends ideas with courage and tenacity especially with peers and superiors</label><br>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q3" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency2_s1_q4">4. Demonstrates in his decision-making adherence to the ethical charter and internal control norms</label><br>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q4" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q4" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q4" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q4" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency2_s1_q4" value="5">5</label>
        </div>

        <h4 align="center">Builds and manages a customer centric organization</h4>
        <div class="form-group">
          <label for="competency2_s2_q1">1. Acts as a role model in connecting with consumers / customers through regular field or online visits</label><br>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency2_s2_q2">2. Develops win/win partnerships with his/her business partners</label><br>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency2_s2_q3">3. Builds an agile organization to fulfill customer needs</label><br>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency2_s2_q3" value="5">5</label>
        </div>
      </div>
        
      <h4 align="center">Gives space for initiatives and enables teams to take risks</h4>
        <div class="form-group">
          <label for="competency2_s3_q1">1. Allows and encourages testing and learning experiences</label><br>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency2_s3_q2">2. Allows people to take bets and encourages bold approaches</label><br>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency2_s3_q3">3. Recognizes the right to make mistakes</label><br>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency2_s3_q3" value="5">5</label>
        </div>

        <h4 align="center">Delivers with integrity both sustainable and short term results</h4>
        <div class="form-group">
          <label for="competency2_s4_q1">1. Delivers short term results without jeopardizing long term priorities</label><br>
          <label class="radio-inline"><input type="radio" name="competency2_s4_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency2_s4_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency2_s4_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency2_s4_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency2_s4_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency2_s4_q2">2. Achieves sustainable results by building robust business processes
and capabilities</label><br>
          <label class="radio-inline"><input type="radio" name="competency2_s4_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency2_s4_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency2_s4_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency2_s4_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency2_s4_q2" value="5">5</label>
        </div>

      <h3 align="center">STRATEGIST - "SHAPE THE FUTURE AND MAKE THE WAY"</h3>
      <div class="questions">
      <h4 align="center">Builds an inspiring and shared vision</h4>
        <div class="form-group">
          <label for="competency3_s1_q1">1. Inspires others through his/her clear vision of the future</label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency3_s1_q2">2. Builds a shared vision co-owned by the team</label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency3_s1_q3">3. Maintains a constant focus on priorities</label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s1_q3" value="5">5</label>
        </div>

        <h4 align="center">Creates strategic scenarios for growth</h4>
        <div class="form-group">
          <label for="competency3_s2_q1">1. Analyzes the environment from various perspectives and looks for
the how and why of events</label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency3_s2_q2">2. Explores different business opportunities and strategizes growth </label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency3_s2_q3">3. Thinks big</label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q3" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency3_s2_q4">4. Thinks ahead</label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q4" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q4" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q4" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q4" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s2_q4" value="5">5</label>
        </div>

        <h4 align="center">Leads transformation by aligning organization and human capabilities</h4>
        <div class="form-group">
          <label for="competency3_s3_q1">1. Designs a transformation strategy and translates it into a concrete action plan</label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency3_s3_q2">2. Drives transformation initiatives and mobilizes stakeholders</label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency3_s3_q3">3. Supports teams to achieve sustainable transformation</label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q3" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency3_s3_q4">4. Demonstrates resilience and bounces back after difficult situations</label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q4" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q4" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q4" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q4" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s3_q4" value="5">5</label>
        </div>

        <h4 align="center">Demonstrates sound judgment in decision making</h4>
        <div class="form-group">
          <label for="competency3_s4_q1">1. Addresses situations in a holistic perspective </label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency3_s4_q2">2. Combines experience, intuition and fact-based reasoning </label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency3_s4_q3">3. Reads new and complex situations quickly and decides accordingly</label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q3" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency3_s4_q4">4. Presents a complex situation in a simple manner</label><br>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q4" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q4" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q4" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q4" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency3_s4_q4" value="5">5</label>
        </div>
      </div>

      <h3 align="center">INTEGRATOR - "FOSTER COOPERATION FOR AGILITY"</h3>
      <div class="questions">
      <h4 align="center">Fosters a climate of trust and constructive confrontation</h4>
        <div class="form-group">
          <label for="competency4_s1_q1">1. Allows people to express their point of view</label><br>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency4_s1_q2">2. Stimulates dialogue between functions</label><br>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency4_s1_q3">3. Focuses on ideas, facts and does not make it personal</label><br>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency4_s1_q3" value="5">5</label>
        </div>

        <h4 align="center">Develops collective performance of the team</h4>
        <div class="form-group">
          <label for="competency4_s2_q1">1. Empowers team members with clear delegation</label><br>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency4_s2_q2">2. Ensures team members work together as a team in a supportive climate</label><br>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency4_s2_q3">3. Celebrates success and gives energy and enthusiasm to the team</label><br>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q3" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency4_s2_q4">4. Shares experience, information and best practices with generosity</label><br>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q4" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q4" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q4" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q4" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency4_s2_q4" value="5">5</label>
        </div>

        <h4 align="center">Enhances transversal cooperation</h4>
        <div class="form-group">
          <label for="competency4_s3_q1">1. Designs working methods and performance criteria that foster cooperation</label><br>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency4_s3_q2">2. Encourages use of project management Encourages use of new collaborative tools</label><br>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency4_s3_q3">3. Recognizes and rewards collective achievements</label><br>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency4_s3_q3" value="5">5</label>
        </div>

        <h4 align="center">Mobilizes stakeholders through active networking</h4>
        <div class="form-group">
          <label for="competency4_s4_q1">1. Builds his/her network within and outside L’Oréal to achieve business objectives</label><br>
          <label class="radio-inline"><input type="radio" name="competency4_s4_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency4_s4_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency4_s4_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency4_s4_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency4_s4_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency4_s4_q2">2. Encourages and assists others to develop people network</label><br>
          <label class="radio-inline"><input type="radio" name="competency4_s4_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency4_s4_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency4_s4_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency4_s4_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency4_s4_q2" value="5">5</label>
        </div>
      </div>

      <h3 align="center">INNOVATOR - "INVENTS LIFE-CHANGING BEAUTY EXPERIENCES"</h3>
      <div class="questions">
      <h4 align="center">Puts the consumer as the central focus</h4>
        <div class="form-group">
          <label for="competency5_s1_q1">1. Ensures and enables teams to stay connected with evolving consumer needs</label><br>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency5_s1_q2">2. Inspires teams through his/her keen focus on consumers</label><br>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency5_s1_q3">3. Shows interest in the culture and rituals of beauty and uses it toachieve local relevance</label><br>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency5_s1_q3" value="5">5</label>
        </div>

        <h4 align="center">Challenges the status quo and strives for excellence</h4>
        <div class="form-group">
          <label for="competency5_s2_q1">1. Encourage teams to reflect on the ways of working and to continuously improve</label><br>
          <label class="radio-inline"><input type="radio" name="competency5_s2_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency5_s2_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency5_s2_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency5_s2_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency5_s2_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency5_s2_q2">2. Thinks out of the box and generates innovative strategies</label><br>
          <label class="radio-inline"><input type="radio" name="competency5_s2_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency5_s2_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency5_s2_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency5_s2_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency5_s2_q2" value="5">5</label>
        </div>

        <h4 align="center">Innovates beyond the product</h4>
        <div class="form-group">
          <label for="competency5_s3_q1">1. Drives innovation with focus on consumer insights</label><br>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency5_s3_q2">2. Makes sure his/her team integrates new technologies to enhance the consumer beauty journey</label><br>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency5_s3_q3">3. Encourages a comprehensive approach of innovation, including all key components from start</label><br>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency5_s3_q3" value="5">5</label>
        </div>

        <h4 align="center">Seizes what is just starting and opens new ventures</h4>
        <div class="form-group">
          <label for="competency5_s4_q1">1. Identifies beauty trends as they emerge and champions promising ideas</label><br>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q1" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q1" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q1" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q1" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q1" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency5_s4_q2">2. Prefers to take risks rather than missing opportunities</label><br>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q2" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q2" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q2" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q2" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q2" value="5">5</label>
        </div>
        <div class="form-group">
          <label for="competency5_s4_q3">3. Stimulates curiosity and external focus</label><br>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q3" value="1" required>1</label>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q3" value="2">2</label>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q3" value="3">3</label>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q3" value="4">4</label>
          <label class="radio-inline"><input type="radio" name="competency5_s4_q3" value="5">5</label>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-block" style="margin-bottom: 20px;">Submit</button>
    </form>
  </div>
 
  </body>
</html>