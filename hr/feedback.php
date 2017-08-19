<?php
/*
  Feedback Viewer 
*/
  session_start();
  require "../db/connect.php";
  
  if (!authCheck($_SESSION['user'], $_SESSION['pass']) || !isset($_SESSION['isHR'])) {
    header('Location: ../');
    exit();
  }

  if (is_null($_GET['for']) || empty($_GET['for'])) {
    header('Location: ../hr');
  }

  $emp = securityPipe($_GET['for']);

  // Get the ReviewCount
  // Update: INSERT INTO `loreal_hr_feedback`.`review_cycle` (`date`, `review_count`) VALUES (CURRENT_DATE(), NULL);
  if (!is_null($_GET['rc']) && !is_nan($_GET['rc'])) {
    $reviewCount = securityPipe($_GET['rc']);
    echo 'Count;' . $reviewCount;
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
  // echo $level;

  $selfSQL = "SELECT competency, section, question, answer
              FROM feedbacks 
              WHERE review_count = {$reviewCount}
              AND designation = '{$emp}'
              AND reviewer = '{$emp}'
              GROUP BY competency, section, question
              ORDER BY competency, section, question";


  $teamSQL = "SELECT competency, section, question, answer
              FROM feedbacks
              WHERE review_count = {$reviewCount}
              AND designation = '{$emp}'
              AND reviewer in (SELECT designation FROM emp_info WHERE manager = '{$emp}')
              GROUP BY competency, section, question
              ORDER BY competency, section, question";

  $managerSQL = "SELECT competency, section, question, answer
              FROM feedbacks
              WHERE review_count = {$reviewCount}
              AND designation = '{$emp}'
              AND reviewer in (SELECT manager FROM emp_info WHERE designation = '{$emp}')
              GROUP BY competency, section, question
              ORDER BY competency, section, question";


  if ($level == 1) {
    $peerSQL = "SELECT competency, section, question, answer
              FROM feedbacks
              WHERE review_count = {$reviewCount}
              AND designation = '{$emp}'
              AND reviewer in (SELECT designation FROM emp_info WHERE level = $level AND designation <> '{$emp}')
              GROUP BY competency, section, question
              ORDER BY competency, section, question";

  // managerSQL => Manager + Peers (Level 0)
  $managerSQL = "SELECT competency, section, question, answer
              FROM feedbacks
              WHERE review_count = {$reviewCount}
              AND designation = '{$emp}'
              AND reviewer in (SELECT designation FROM emp_info WHERE level = 1 and designation <> '{$emp}'
                               UNION SELECT manager FROM emp_info WHERE designation = '{$emp}')
              GROUP BY competency, section, question
              ORDER BY competency, section, question";

  $onlyManagerSQL = "SELECT competency, section, question, answer
              FROM feedbacks
              WHERE review_count = {$reviewCount}
              AND designation = '{$emp}'
              AND reviewer in (SELECT manager FROM emp_info WHERE designation = '{$emp}')
              GROUP BY competency, section, question
              ORDER BY competency, section, question";

    $peerScoreDAO = $conn->query($peerSQL);
    $peerScore = array();
    if ($peerScoreDAO->num_rows > 0) {
      while ($row = $peerScoreDAO->fetch_assoc()) {
        array_push($peerScore, $row);
      }
    }
  }


  $selfScoresDAO = $conn->query($selfSQL);
  $teamScoresDAO = $conn->query($teamSQL);
  $managerScoreDAO = $conn->query($managerSQL);

  $selfScore = array();
  $teamScore = array();
  $managerScore = array();

  if ($selfScoresDAO->num_rows > 0) {
    while ($row = $selfScoresDAO->fetch_assoc()) {
      array_push($selfScore, $row);
    }
  }

  if ($teamScoresDAO->num_rows > 0) {
    while ($row = $teamScoresDAO->fetch_assoc()) {
      array_push($teamScore, $row);
    }
  }

  if ($managerScoreDAO->num_rows > 0) {
    while ($row = $managerScoreDAO->fetch_assoc()) {
      array_push($managerScore, $row);
    }
  }

  // print_r($selfScore);

  // exit();
  // Avergaes

  // Competency Avg
  $selfCAvg = array();
  $teamCAvg = array();
  $managerCAvg = array();
  $peerCAvg = array();

  // Section Avg
  $selfSAvg = array();
  $teamSAvg = array();
  $managerSAvg = array();

    // Fill Data
  $selfCsql = "SELECT competency, AVG(answer) as ans FROM feedbacks
                    WHERE review_count = {$reviewCount}
                    AND designation = '{$emp}'
                    AND reviewer = '{$emp}'
                    GROUP BY competency
                    ORDER BY competency";

  $teamCsql = "SELECT competency, AVG(answer) as ans FROM feedbacks
                    WHERE review_count = {$reviewCount}
                    AND designation = '{$emp}'
                    AND reviewer in (SELECT designation FROM emp_info WHERE manager = '{$emp}')
                    GROUP BY competency
                    ORDER BY competency";

  $peerCsql = "SELECT competency, AVG(answer) as ans FROM feedbacks
                    WHERE review_count = {$reviewCount}
                    AND designation = '{$emp}'
                    AND reviewer in (SELECT designation FROM emp_info WHERE level = $level AND designation <> '{$emp}')
                    GROUP BY competency
                    ORDER BY competency";


if ($level == 1) {
  $managerCsql = "SELECT competency, AVG(answer) as ans FROM feedbacks
                    WHERE review_count = {$reviewCount}
                    AND designation = '{$emp}'
                    AND reviewer in (SELECT designation FROM emp_info WHERE level = 1 and designation <> '{$emp}'
                               UNION SELECT manager FROM emp_info WHERE designation = '{$emp}')
                    GROUP BY competency
                    ORDER BY competency";  
} else {
  $managerCsql = "SELECT competency, AVG(answer) as ans FROM feedbacks
                    WHERE review_count = {$reviewCount}
                    AND designation = '{$emp}'
                    AND reviewer in (SELECT manager FROM emp_info WHERE designation = '{$emp}')
                    GROUP BY competency
                    ORDER BY competency";
}

  

  
  $selfDAO = $conn->query($selfCsql);
  $teamDAO = $conn->query($teamCsql);
  $managerDAO = $conn->query($managerCsql);
  $peerDAO = $conn->query($peerCsql);


  for ($c=0; $c < 5; $c++) { 
    $selfCAvg[$c] = $selfDAO->fetch_assoc()['ans'];
    $teamCAvg[$c] = $teamDAO->fetch_assoc()['ans'];
    $managerCAvg[$c] = $managerDAO->fetch_assoc()['ans'];
    if ($level == 1)
      $peerCAvg[$c] = $peerDAO->fetch_assoc()['ans'];
  }

      // Fill Data
  $selfSsql = "SELECT competency, section, AVG(answer) as ans FROM feedbacks
                    WHERE review_count = {$reviewCount}
                    AND designation = '{$emp}'
                    AND reviewer = '{$emp}'
                    GROUP BY competency, section
                    ORDER BY competency, section";
  // echo $selfSsql;

  $teamSsql = "SELECT competency, section, AVG(answer) as ans FROM feedbacks
                    WHERE review_count = {$reviewCount}
                    AND designation = '{$emp}'
                    AND reviewer in (SELECT designation FROM emp_info WHERE manager = '{$emp}')
                    GROUP BY competency, section
                    ORDER BY competency, section";

  if ($level == 1) {
      $managerSsql = "SELECT competency, section, AVG(answer) as ans FROM feedbacks
                    WHERE review_count = {$reviewCount}
                    AND designation = '{$emp}'
                    AND reviewer in (SELECT designation FROM emp_info WHERE level = 1 and designation <> '{$emp}'
                                      UNION SELECT manager FROM emp_info WHERE designation = '{$emp}')
                    GROUP BY competency, section
                    ORDER BY competency, section";
  } else {
    $managerSsql = "SELECT competency, section, AVG(answer) as ans FROM feedbacks
                    WHERE review_count = {$reviewCount}
                    AND designation = '{$emp}'
                    AND reviewer in (SELECT manager FROM emp_info WHERE designation = '{$emp}')
                    GROUP BY competency, section
                    ORDER BY competency, section";
  }

  

  $selfDAO = $conn->query($selfSsql);
  $teamDAO = $conn->query($teamSsql);
  $managerDAO = $conn->query($managerSsql);

  // print_r($teamSsql);

  for ($c=0; $c < 5; $c++) { 
    for ($s=0; $s < 4; $s++) { 
      $selfSAvg[$c][$s] = $selfDAO->fetch_assoc()['ans'];
      $teamSAvg[$c][$s] = $teamDAO->fetch_assoc()['ans'];
      $managerSAvg[$c][$s] = $managerDAO->fetch_assoc()['ans']; 
    }
  }


  //Average score: 50% Team + 30% Peer/Mgr + 20% Mgr
  $graphAvg = array();
  $graphMin = array();
  $graphMax = array();


  if ($level == 1) {

  for ($counter=0; $counter < 65 ; $counter++) { 
    if ($teamScore[$counter]['answer'] == 0 || $managerScore[$counter]['answer'] == 0 || $peerScore[$counter]['answer'] == 0) {
      $err = 'None of the ';
      if ($teamScore[$counter]['answer'] == 0)
        $err = $err . '(team member) ';
      if ($managerScore[$counter]['answer'] == 0)
        $err = $err . '(manager) ';
      if ($peerScore[$counter]['answer'] == 0)
        $err = $err . '(peer) ';

      $err = $err . 'has submitted the feedback for ' . $emp;

      echo '<script type="text/javascript">
        alertFunc();
        function alertFunc()
        {
          alert("' . $err . '");
          location.href = "../hr/view_feedback.php"
        }
        </script>';
        exit();
    }
  }

  $minScore = array();
  $maxScore = array();
  $avergaeScore = array(0,0,0,0,0);

  $minmaxsql = "SELECT competency, min(answer) as min, max(answer) as max FROM feedbacks 
              WHERE review_count = {$reviewCount}
              AND designation = '{$emp}'
              AND reviewer <> '{$emp}'
              GROUP BY competency
              ORDER BY competency";

  $minmaxDAO = $conn->query($minmaxsql);
  // print_r($minmaxsql);
  $counter = 0;
  if ($minmaxDAO->num_rows) {
    while ($row = $minmaxDAO->fetch_assoc()) {
        $minScore[$counter] = $row['min'];
        $maxScore[$counter++] = $row['max'];
    }
  }


  for ($c=0; $c < 5; $c++) { 
    $avergaeScore[$c] = $teamCAvg[$c] * 0.5 + $peerCAvg[$c]*0.3 + $managerCAvg[$c]*0.2;
  }



    
  } else {

  for ($c=0; $c < 65 ; $c++) { 
    if ($teamScore[$counter]['answer'] == 0 || $managerScore[$counter]['answer'] == 0 ) {
      $err = 'None of the ';
      if ($teamScore[$counter]['answer'] == 0)
        $err = $err . '(team member) ';
      if ($managerScore[$counter]['answer'] == 0)
        $err = $err . '(manager) ';

      $err = $err . 'has submitted the feedback for ' . $emp;

      echo '<script type="text/javascript">
        alertFunc();
        function alertFunc()
        {
          alert("' . $err . '");
          location.href = "../hr/view_feedback.php"
        }
        </script>';
    }
  }

  $minScore = array();
  $maxScore = array();
  $avergaeScore = array(0,0,0,0,0);

  $minmaxsql = "SELECT competency, min(answer) as min, max(answer) as max FROM feedbacks 
              WHERE review_count = {$reviewCount}
              AND designation = '{$emp}'
              AND reviewer <> '{$emp}'
              GROUP BY competency
              ORDER BY competency";

  $minmaxDAO = $conn->query($minmaxsql);
  // print_r($minmaxsql);
  $counter = 0;
  if ($minmaxDAO->num_rows) {
    while ($row = $minmaxDAO->fetch_assoc()) {
        $minScore[$counter] = $row['min'];
        $maxScore[$counter++] = $row['max'];
    }
  }


  for ($c=0; $c < 5; $c++) { 
    $avergaeScore[$c] = $teamCAvg[$c] * 0.5 + $managerCAvg[$c]*0.5;
  }



  }

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Loreal: Feedback form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <link rel="stylesheet" type="text/css" href="../css/feedback_style.css">
    <script src="../canvasjs.min.js"></script>
      <script type="text/javascript">
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer",
    {
      title:{
        text: "Feedback Statistics"
      },
      animationEnabled: true,
      legend: {
        cursor:"pointer",
        itemclick : function(e) {
          if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
              e.dataSeries.visible = false;
          }
          else {
              e.dataSeries.visible = true;
          }
          chart.render();
        }
      },
      axisY: {
        title: "Rating"
      },
      toolTip: {
        shared: true,  
        content: function(e){
          var str = '';
          var total = 0 ;
          var str3;
          var str2 ;
          for (var i = 0; i < e.entries.length; i++){
            var  str1 = "<span style= 'color:"+e.entries[i].dataSeries.color + "'> " + e.entries[i].dataSeries.name + "</span>: <strong>"+  e.entries[i].dataPoint.y + "</strong> <br/>" ; 
            total = e.entries[i].dataPoint.y + total;
            str = str.concat(str1);
          }
          str2 = "<span style = 'color:DodgerBlue; '><strong>"+e.entries[0].dataPoint.label + "</strong></span><br/>";
       
          return (str2.concat(str));
        }

      },
      data: [
      {        
        type: "bar",
        showInLegend: true,
        name: "Highest",
        color: "#A0EC37",
        dataPoints: [
        

        { y: <?php echo $maxScore[0] ?>, label: "PEOPLE DEVELOPER"},
        { y: <?php echo $maxScore[1] ?>, label: "ENTREPRENEUR"},
        { y: <?php echo $maxScore[2] ?>, label: "STRATEGIST"},        
        { y: <?php echo $maxScore[3] ?>, label: "INTEGRATOR"},        
        { y: <?php echo $maxScore[4] ?>, label: "INNOVATOR"}


        ]
      },
      {        
        type: "bar",
        showInLegend: true,
        name: "Average",
        color: "#37B3EC",          
        dataPoints: [
        { y: <?php echo round($avergaeScore[0], 2) ?>, label: "PEOPLE DEVELOPER"},
        { y: <?php echo round($avergaeScore[1], 2) ?>, label: "ENTREPRENEUR"},
        { y: <?php echo round($avergaeScore[2], 2) ?>, label: "STRATEGIST"},        
        { y: <?php echo round($avergaeScore[3], 2) ?>, label: "INTEGRATOR"},        
        { y: <?php echo round($avergaeScore[4], 2) ?>, label: "INNOVATOR"}


        ]
      },
      {        
        type: "bar",
        showInLegend: true,
        name: "Lowest",
        color: "#EC5637",
        dataPoints: [
        { y: <?php echo $minScore[0] ?>, label: "PEOPLE DEVELOPER"},
        { y: <?php echo $minScore[1] ?>, label: "ENTREPRENEUR"},
        { y: <?php echo $minScore[2] ?>, label: "STRATEGIST"},        
        { y: <?php echo $minScore[3] ?>, label: "INTEGRATOR"},        
        { y: <?php echo $minScore[4] ?>, label: "INNOVATOR"}

        ]
      }

      ]
    });

chart.render();
}
</script>
</head>
<body>
  <header>
    <h1>L'ORÉAL: <?php echo $emp ?></h1>
    <h4>India</h4>
    <a href="../hr/choose_function.php"><button class="btn btn-sm">Home</button></a>
    <a href="../hr/view_feedback.php"><button class="btn btn-sm">Back</button></a>
  </header>
  <div class="row jumbotron" style="background-color: white;">
    <div class="col-lg-12">
      <div class="container">
      <button class="btn btn-sm" data-toggle="collapse" data-target=".collapse">Expand/Collapse</button>
        <table class="table table-responsive table-hover">
          <thead>
                <tr><th></th><th><h4>Competency</h4></th><th><h4>Self</h4></th><th><h4>Team</h4></th><th><h4>Others</h4></th></tr>
            </thead>
            <tbody>

            
                  <tr class="clickable" data-toggle="collapse" id="row1" data-target=".row1">
                  <td><i class="glyphicon glyphicon-plus"></i></td>
                  <td>PEOPLE DEVELOPER</td>
                  <td><?php echo round($selfCAvg[0], 2) ?></td>
                  <td><?php echo round($teamCAvg[0], 2) ?></td>
                  <td><?php echo round($managerCAvg[0], 2) ?></td>
                  </tr>

                      <tr class="clickable collapse row1" data-toggle="collapse" id="row11" data-target=".row11">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Treats all individuals in a respectful and consistent manner</td>
                      <td><?php echo round($selfSAvg[0][0], 2) ?></td>
                      <td><?php echo round($teamSAvg[0][0], 2) ?></td>  
                      <td><?php echo round($managerSAvg[0][0], 2) ?></td>
                      </tr>

                        <tr class="collapse row11">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Sets an example in terms of personal integrity</td>
                        <td><?php echo $selfScore[0]['answer'] ?></td>
                        <td><?php echo $teamScore[0]['answer'] ?></td>  
                        <td><?php echo $managerScore[0]['answer'] ?></td>
                        </tr><tr class="collapse row11">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Promotes a climate of mutual respect and transparency across all levels</td>
                        <td><?php echo $selfScore[1]['answer'] ?></td>
                        <td><?php echo $teamScore[1]['answer'] ?></td>  
                        <td><?php echo $managerScore[1]['answer'] ?></td>
                        </tr><tr class="collapse row11">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Listens and balances directness with empathy</td>
                        <td><?php echo $selfScore[2]['answer'] ?></td>
                        <td><?php echo $teamScore[2]['answer'] ?></td>  
                        <td><?php echo $managerScore[2]['answer'] ?></td>
                        </tr><tr class="collapse row11">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Delivers with integrity both sustainable and short term results</td>
                        <td><?php echo $selfScore[3]['answer'] ?></td>
                        <td><?php echo $teamScore[3]['answer'] ?></td>  
                        <td><?php echo $managerScore[3]['answer'] ?></td>
                        </tr><tr class="collapse row11">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Adjusts priorities to take into account the work load of his/her team</td>
                        <td><?php echo $selfScore[4]['answer'] ?></td>
                        <td><?php echo $teamScore[4]['answer'] ?></td>  
                        <td><?php echo $managerScore[4]['answer'] ?></td>
                        </tr>

                      <tr class="clickable collapse row1" data-toggle="collapse" id="row12" data-target=".row12">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Leverages diversity</td>
                      <td><?php echo round($selfSAvg[0][1], 2) ?></td>
                      <td><?php echo round($teamSAvg[0][1], 2) ?></td>  
                      <td><?php echo round($managerSAvg[0][1], 2) ?></td>
                      </tr>
                      
                        <tr class="collapse row12">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Seeks to understand others' motives, ambitions and emotions</td>
                        <td><?php echo $selfScore[5]['answer'] ?></td>
                        <td><?php echo $teamScore[5]['answer'] ?></td>  
                        <td><?php echo $managerScore[5]['answer'] ?></td>
                        </tr><tr class="collapse row12">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Encourages the team to work with diverse personalities, functions and cultures</td>
                        <td><?php echo $selfScore[6]['answer'] ?></td>
                        <td><?php echo $teamScore[6]['answer'] ?></td>  
                        <td><?php echo $managerScore[6]['answer'] ?></td>
                        </tr>

                      <tr class="clickable collapse row1" data-toggle="collapse" id="row13" data-target=".row13">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td> Stimulates learning</td>
                      <td><?php echo round($selfSAvg[0][2], 2) ?></td>
                      <td><?php echo round($teamSAvg[0][2], 2) ?></td>  
                      <td><?php echo round($managerSAvg[0][2], 2) ?></td>
                      </tr>

                        <tr class="collapse row13">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Analyzes personal mistakes and failures and learns from them</td>
                        <td><?php echo $selfScore[7]['answer'] ?></td>
                        <td><?php echo $teamScore[7]['answer'] ?></td>  
                        <td><?php echo $managerScore[7]['answer'] ?></td>
                        </tr><tr class="collapse row13">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Creates the work environment to stimulate learning and creativity</td>
                        <td><?php echo $selfScore[8]['answer'] ?></td>
                        <td><?php echo $teamScore[8]['answer'] ?></td>  
                        <td><?php echo $managerScore[8]['answer'] ?></td>
                        </tr><tr class="collapse row13">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Acquires and transmits beauty expertise gained through accumulated experience</td>
                        <td><?php echo $selfScore[9]['answer'] ?></td>
                        <td><?php echo $teamScore[9]['answer'] ?></td>  
                        <td><?php echo $managerScore[9]['answer'] ?></td>
                        </tr>
                      
                      <tr class="clickable collapse row1" data-toggle="collapse" id="row14" data-target=".row14">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td> Empowers and develops individuals to contribute their best</td>
                      <td><?php echo round($selfSAvg[0][3], 2) ?></td>
                      <td><?php echo round($teamSAvg[0][3], 2) ?></td>  
                      <td><?php echo round($managerSAvg[0][3], 2) ?></td>
                      </tr>
                
                        <tr class="collapse row14">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Clarifies performance expectations</td>
                        <td><?php echo $selfScore[10]['answer'] ?></td>
                        <td><?php echo $teamScore[10]['answer'] ?></td>  
                        <td><?php echo $managerScore[10]['answer'] ?></td>
                        </tr><tr class="collapse row14">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Clarifies development priorities</td>
                        <td><?php echo $selfScore[11]['answer'] ?></td>
                        <td><?php echo $teamScore[11]['answer'] ?></td>  
                        <td><?php echo $managerScore[11]['answer'] ?></td>
                        </tr><tr class="collapse row14">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Shows trust and confidence in people’s ability to succeed</td>
                        <td><?php echo $selfScore[12]['answer'] ?></td>
                        <td><?php echo $teamScore[12]['answer'] ?></td>  
                        <td><?php echo $managerScore[12]['answer'] ?></td>
                        </tr><tr class="collapse row14">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Uses a fact-based approach to appraise and gives feedback</td>
                        <td><?php echo $selfScore[13]['answer'] ?></td>
                        <td><?php echo $teamScore[13]['answer'] ?></td>  
                        <td><?php echo $managerScore[13]['answer'] ?></td>
                        </tr><tr class="collapse row14">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Spots talent accurately and mentors them for the Group</td>
                        <td><?php echo $selfScore[14]['answer'] ?></td>
                        <td><?php echo $teamScore[14]['answer'] ?></td>  
                        <td><?php echo $managerScore[14]['answer'] ?></td>
                        </tr>

                <tr class="clickable" data-toggle="collapse" id="row2" data-target=".row2">
                    <td><i class="glyphicon glyphicon-plus"></i></td>
                    <td>ENTREPRENEUR</td>
                    <td><?php echo round($selfCAvg[1], 2) ?></td>
                    <td><?php echo round($teamCAvg[1], 2) ?></td>
                    <td><?php echo round($managerCAvg[1], 2) ?></td>
                    </tr>

                    <tr class="clickable collapse row2" data-toggle="collapse" id="row21" data-target=".row21">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Takes accountability with courage</td>
                      <td><?php echo round($selfSAvg[1][0], 2) ?></td>
                      <td><?php echo round($teamSAvg[1][0], 2) ?></td>  
                      <td><?php echo round($managerSAvg[1][0], 2) ?></td>
                      </tr>

                        <tr class="collapse row21">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Stands by own decisions and takes responsibility for them</td>
                        <td><?php echo $selfScore[15]['answer'] ?></td>
                        <td><?php echo $teamScore[15]['answer'] ?></td>  
                        <td><?php echo $managerScore[15]['answer'] ?></td>
                        </tr><tr class="collapse row21">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Takes responsibility for setbacks and wins</td>
                        <td><?php echo $selfScore[16]['answer'] ?></td>
                        <td><?php echo $teamScore[16]['answer'] ?></td>  
                        <td><?php echo $managerScore[16]['answer'] ?></td>                        </tr><tr class="collapse row21">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Defends ideas with courage and tenacity especially with peers and superiors</td>
                        <td><?php echo $selfScore[17]['answer'] ?></td>
                        <td><?php echo $teamScore[17]['answer'] ?></td>  
                        <td><?php echo $managerScore[17]['answer'] ?></td>
                                                </tr><tr class="collapse row21">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Demonstrates in his decision-making adherence to the ethical charter and internal control norms</td>
                        <td><?php echo $selfScore[18]['answer'] ?></td>
                        <td><?php echo $teamScore[18]['answer'] ?></td>  
                        <td><?php echo $managerScore[18]['answer'] ?></td>
                        </tr>

                      <tr class="clickable collapse row2" data-toggle="collapse" id="row22" data-target=".row22">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Builds and manages a customer centric organization</td>
                      <td><?php echo round($selfSAvg[1][1], 2) ?></td>
                      <td><?php echo round($teamSAvg[1][1], 2) ?></td>  
                      <td><?php echo round($managerSAvg[1][1], 2) ?></td>
                      </tr>

                        <tr class="collapse row22">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Acts as a role model in connecting with consumers / customers through regular field or online visits</td>
                        <td><?php echo $selfScore[19]['answer'] ?></td>
                        <td><?php echo $teamScore[19]['answer'] ?></td>  
                        <td><?php echo $managerScore[19]['answer'] ?></td>
                        </tr><tr class="collapse row22">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Develops win/win partnerships with his/her business partners</td>
                        <td><?php echo $selfScore[20]['answer'] ?></td>
                        <td><?php echo $teamScore[20]['answer'] ?></td>  
                        <td><?php echo $managerScore[20]['answer'] ?></td>
                        </tr><tr class="collapse row22">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Builds an agile organization to fulfill customer needs</td>
                        <td><?php echo $selfScore[21]['answer'] ?></td>
                        <td><?php echo $teamScore[21]['answer'] ?></td>  
                        <td><?php echo $managerScore[21]['answer'] ?></td>
                        </tr>

                      <tr class="clickable collapse row2" data-toggle="collapse" id="row23" data-target=".row23">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Gives space for initiatives and enables teams to take risks</td>
                      <td><?php echo round($selfSAvg[1][2], 2) ?></td>
                      <td><?php echo round($teamSAvg[1][2], 2) ?></td>  
                      <td><?php echo round($managerSAvg[1][2], 2) ?></td>
                      </tr>

                        <tr class="collapse row23">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Allows and encourages testing and learning experiences</td>
                        <td><?php echo $selfScore[22]['answer'] ?></td>
                        <td><?php echo $teamScore[22]['answer'] ?></td>  
                        <td><?php echo $managerScore[22]['answer'] ?></td>
                        </tr><tr class="collapse row23">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Allows people to take bets and encourages bold approaches</td>
                        <td><?php echo $selfScore[23]['answer'] ?></td>
                        <td><?php echo $teamScore[23]['answer'] ?></td>  
                        <td><?php echo $managerScore[23]['answer'] ?></td>
                        </tr><tr class="collapse row23">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Recognizes the right to make mistakes</td>
                        <td><?php echo $selfScore[24]['answer'] ?></td>
                        <td><?php echo $teamScore[24]['answer'] ?></td>  
                        <td><?php echo $managerScore[24]['answer'] ?></td>
                        </tr>

                      <tr class="clickable collapse row2" data-toggle="collapse" id="row24" data-target=".row24">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Delivers with integrity both sustainable and short term results</td>
                      <td><?php echo round($selfSAvg[1][3], 2) ?></td>
                      <td><?php echo round($teamSAvg[1][3], 2) ?></td>  
                      <td><?php echo round($managerSAvg[1][3], 2) ?></td>
                      </tr>

                        <tr class="collapse row24">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Delivers short term results without jeopardizing long term priorities</td>
                        <td><?php echo $selfScore[25]['answer'] ?></td>
                        <td><?php echo $teamScore[25]['answer'] ?></td>  
                        <td><?php echo $managerScore[25]['answer'] ?></td>
                        </tr><tr class="collapse row24">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Achieves sustainable results by building robust business processes
and capabilities
</td>
                        <td><?php echo $selfScore[26]['answer'] ?></td>
                        <td><?php echo $teamScore[26]['answer'] ?></td>  
                        <td><?php echo $managerScore[26]['answer'] ?></td>
                        </tr>

                    <tr class="clickable" data-toggle="collapse" id="row3" data-target=".row3">
                    <td><i class="glyphicon glyphicon-plus"></i></td>
                    <td>STRATEGIST</td>
                    <td><?php echo round($selfCAvg[2], 2) ?></td>
                    <td><?php echo round($teamCAvg[2], 2) ?></td>
                    <td><?php echo round($managerCAvg[2], 2) ?></td>
                    </tr>

                      <tr class="clickable collapse row3" data-toggle="collapse" id="row31" data-target=".row31">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Builds an inspiring and shared vision</td>
                      <td><?php echo round($selfSAvg[2][0], 2) ?></td>
                      <td><?php echo round($teamSAvg[2][0], 2) ?></td>  
                      <td><?php echo round($managerSAvg[2][0], 2) ?></td>
                      </tr>

                        <tr class="collapse row31">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Inspires others through his/her clear vision of the future</td>
                        <td><?php echo $selfScore[27]['answer'] ?></td>
                        <td><?php echo $teamScore[27]['answer'] ?></td>  
                        <td><?php echo $managerScore[27]['answer'] ?></td>
                        </tr><tr class="collapse row31">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Builds a shared vision co-owned by the team</td>
                        <td><?php echo $selfScore[28]['answer'] ?></td>
                        <td><?php echo $teamScore[28]['answer'] ?></td>  
                        <td><?php echo $managerScore[28]['answer'] ?></td>
                        </tr><tr class="collapse row31">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Maintains a constant focus on priorities</td>
                        <td><?php echo $selfScore[29]['answer'] ?></td>
                        <td><?php echo $teamScore[29]['answer'] ?></td>  
                        <td><?php echo $managerScore[29]['answer'] ?></td>
                        </tr>

                      <tr class="clickable collapse row3" data-toggle="collapse" id="row32" data-target=".row32">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Creates strategic scenarios for growth</td>
                      <td><?php echo round($selfSAvg[2][1], 2) ?></td>
                      <td><?php echo round($teamSAvg[2][1], 2) ?></td>  
                      <td><?php echo round($managerSAvg[2][1], 2) ?></td>
                      </tr>

                        <tr class="collapse row32">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Analyzes the environment from various perspectives and looks for
the how and why of events
</td>
                        <td><?php echo $selfScore[30]['answer'] ?></td>
                        <td><?php echo $teamScore[30]['answer'] ?></td>  
                        <td><?php echo $managerScore[30]['answer'] ?></td>
                        </tr><tr class="collapse row32">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Explores different business opportunities and strategizes growth </td>
                        <td><?php echo $selfScore[31]['answer'] ?></td>
                        <td><?php echo $teamScore[31]['answer'] ?></td>  
                        <td><?php echo $managerScore[31]['answer'] ?></td>
                        </tr><tr class="collapse row32">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Thinks big</td>
                        <td><?php echo $selfScore[32]['answer'] ?></td>
                        <td><?php echo $teamScore[32]['answer'] ?></td>  
                        <td><?php echo $managerScore[32]['answer'] ?></td>
                        </tr><tr class="collapse row32">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Thinks ahead</td>
                        <td><?php echo $selfScore[33]['answer'] ?></td>
                        <td><?php echo $teamScore[33]['answer'] ?></td>  
                        <td><?php echo $managerScore[33]['answer'] ?></td>
                        </tr>

                      <tr class="clickable collapse row3" data-toggle="collapse" id="row33" data-target=".row33">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Leads transformation by aligning organization and human capabilities</td>
                      <td><?php echo round($selfSAvg[2][2], 2) ?></td>
                      <td><?php echo round($teamSAvg[2][2], 2) ?></td>  
                      <td><?php echo round($managerSAvg[2][2], 2) ?></td>
                      </tr>

                        <tr class="collapse row33">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Designs a transformation strategy and translates it into a concrete action plan</td>
                        <td><?php echo $selfScore[34]['answer'] ?></td>
                        <td><?php echo $teamScore[34]['answer'] ?></td>  
                        <td><?php echo $managerScore[34]['answer'] ?></td>
                        </tr><tr class="collapse row33">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Drives transformation initiatives and mobilizes stakeholders</td>
                        <td><?php echo $selfScore[35]['answer'] ?></td>
                        <td><?php echo $teamScore[35]['answer'] ?></td>  
                        <td><?php echo $managerScore[35]['answer'] ?></td>
                        </tr><tr class="collapse row33">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Supports teams to achieve sustainable transformation</td>
                        <td><?php echo $selfScore[36]['answer'] ?></td>
                        <td><?php echo $teamScore[36]['answer'] ?></td>  
                        <td><?php echo $managerScore[36]['answer'] ?></td>
                        </tr><tr class="collapse row33">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Demonstrates resilience and bounces back after difficult situations</td>
                        <td><?php echo $selfScore[37]['answer'] ?></td>
                        <td><?php echo $teamScore[37]['answer'] ?></td>  
                        <td><?php echo $managerScore[37]['answer'] ?></td>
                        </tr>

                      <tr class="clickable collapse row3" data-toggle="collapse" id="row34" data-target=".row34">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Demonstrates sound judgment in decision making</td>
                      <td><?php echo round($selfSAvg[2][3], 2) ?></td>
                      <td><?php echo round($teamSAvg[2][3], 2) ?></td>  
                      <td><?php echo round($managerSAvg[2][3], 2) ?></td>
                      </tr>

                        <tr class="collapse row34">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Addresses situations in a holistic perspective</td>
                        <td><?php echo $selfScore[38]['answer'] ?></td>
                        <td><?php echo $teamScore[38]['answer'] ?></td>  
                        <td><?php echo $managerScore[38]['answer'] ?></td>
                        </tr><tr class="collapse row34">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Combines experience, intuition and fact-based reasoning</td>
                        <td><?php echo $selfScore[39]['answer'] ?></td>
                        <td><?php echo $teamScore[39]['answer'] ?></td>  
                        <td><?php echo $managerScore[39]['answer'] ?></td>
                        </tr><tr class="collapse row34">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Reads new and complex situations quickly and decides accordingly</td>
                        <td><?php echo $selfScore[40]['answer'] ?></td>
                        <td><?php echo $teamScore[40]['answer'] ?></td>  
                        <td><?php echo $managerScore[40]['answer'] ?></td>
                        </tr><tr class="collapse row34">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Presents a complex situation in a simple manner</td>
                        <td><?php echo $selfScore[41]['answer'] ?></td>
                        <td><?php echo $teamScore[41]['answer'] ?></td>  
                        <td><?php echo $managerScore[41]['answer'] ?></td>
                        </tr>

                    <tr class="clickable" data-toggle="collapse" id="row4" data-target=".row4">
                    <td><i class="glyphicon glyphicon-plus"></i></td>
                    <td>INTEGRATOR</td>
                    <td><?php echo round($selfCAvg[3], 2) ?></td>
                    <td><?php echo round($teamCAvg[3], 2) ?></td>
                    <td><?php echo round($managerCAvg[3], 2) ?></td>
                    </tr>

                      <tr class="clickable collapse row4" data-toggle="collapse" id="row41" data-target=".row41">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Fosters a climate of trust and constructive confrontation</td>
                      <td><?php echo round($selfSAvg[3][0], 2) ?></td>
                      <td><?php echo round($teamSAvg[3][0], 2) ?></td>  
                      <td><?php echo round($managerSAvg[3][0], 2) ?></td>
                      </tr>

                        <tr class="collapse row41">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Allows people to express their point of view</td>
                        <td><?php echo $selfScore[42]['answer'] ?></td>
                        <td><?php echo $teamScore[42]['answer'] ?></td>  
                        <td><?php echo $managerScore[42]['answer'] ?></td>
                        </tr><tr class="collapse row41">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Stimulates dialogue between functions</td>
                        <td><?php echo $selfScore[43]['answer'] ?></td>
                        <td><?php echo $teamScore[43]['answer'] ?></td>  
                        <td><?php echo $managerScore[43]['answer'] ?></td>
                        </tr><tr class="collapse row41">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Focuses on ideas, facts and does not make it personal</td>
                        <td><?php echo $selfScore[44]['answer'] ?></td>
                        <td><?php echo $teamScore[44]['answer'] ?></td>  
                        <td><?php echo $managerScore[44]['answer'] ?></td>
                        </tr>

                      <tr class="clickable collapse row4" data-toggle="collapse" id="row42" data-target=".row42">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Develops collective performance of the team</td>
                      <td><?php echo round($selfSAvg[3][1], 2) ?></td>
                      <td><?php echo round($teamSAvg[3][1], 2) ?></td>  
                      <td><?php echo round($managerSAvg[3][1], 2) ?></td>
                      </tr>

                        <tr class="collapse row42">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Empowers team members with clear delegation</td>
                        <td><?php echo $selfScore[45]['answer'] ?></td>
                        <td><?php echo $teamScore[45]['answer'] ?></td>  
                        <td><?php echo $managerScore[45]['answer'] ?></td>
                        </tr><tr class="collapse row42">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Ensures team members work together as a team in a supportive climate</td>
                        <td><?php echo $selfScore[46]['answer'] ?></td>
                        <td><?php echo $teamScore[46]['answer'] ?></td>  
                        <td><?php echo $managerScore[46]['answer'] ?></td>
                        </tr><tr class="collapse row42">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Celebrates success and gives energy and enthusiasm to the team</td>
                        <td><?php echo $selfScore[47]['answer'] ?></td>
                        <td><?php echo $teamScore[47]['answer'] ?></td>  
                        <td><?php echo $managerScore[47]['answer'] ?></td>
                        </tr><tr class="collapse row42">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Shares experience, information and best practices with generosity</td>
                        <td><?php echo $selfScore[48]['answer'] ?></td>
                        <td><?php echo $teamScore[48]['answer'] ?></td>  
                        <td><?php echo $managerScore[48]['answer'] ?></td>
                        </tr>

                      <tr class="clickable collapse row4" data-toggle="collapse" id="row43" data-target=".row43">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Enhances transversal cooperation</td>
                      <td><?php echo round($selfSAvg[3][2], 2) ?></td>
                      <td><?php echo round($teamSAvg[3][2], 2) ?></td>  
                      <td><?php echo round($managerSAvg[3][2], 2) ?></td>
                      </tr>

                        <tr class="collapse row43">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Designs working methods and performance criteria that foster cooperation</td>
                        <td><?php echo $selfScore[49]['answer'] ?></td>
                        <td><?php echo $teamScore[49]['answer'] ?></td>  
                        <td><?php echo $managerScore[49]['answer'] ?></td>
                        </tr><tr class="collapse row43">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Designs working methods and performance criteria that foster cooperation</td>
                        <td><?php echo $selfScore[50]['answer'] ?></td>
                        <td><?php echo $teamScore[50]['answer'] ?></td>  
                        <td><?php echo $managerScore[50]['answer'] ?></td>
                        </tr><tr class="collapse row43">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Recognizes and rewards collective achievements</td>
                        <td><?php echo $selfScore[51]['answer'] ?></td>
                        <td><?php echo $teamScore[51]['answer'] ?></td>  
                        <td><?php echo $managerScore[51]['answer'] ?></td>
                        </tr>

                      <tr class="clickable collapse row4" data-toggle="collapse" id="row44" data-target=".row44">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Mobilizes stakeholders through active networking</td>
                      <td><?php echo round($selfSAvg[3][3], 2) ?></td>
                      <td><?php echo round($teamSAvg[3][3], 2) ?></td>  
                      <td><?php echo round($managerSAvg[3][3], 2) ?></td>
                      </tr>

                        <tr class="collapse row44">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Builds his/her network within and outside L’Oréal to achieve business objectives</td>
                        <td><?php echo $selfScore[52]['answer'] ?></td>
                        <td><?php echo $teamScore[52]['answer'] ?></td>  
                        <td><?php echo $managerScore[52]['answer'] ?></td>
                        </tr><tr class="collapse row44">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Encourages and assists others to develop people network</td>
                        <td><?php echo $selfScore[53]['answer'] ?></td>
                        <td><?php echo $teamScore[53]['answer'] ?></td>  
                        <td><?php echo $managerScore[53]['answer'] ?></td>
                        </tr>

                    <tr class="clickable" data-toggle="collapse" id="row5" data-target=".row5">
                    <td><i class="glyphicon glyphicon-plus"></i></td>
                    <td>INNOVATOR</td>
                    <td><?php echo round($selfCAvg[4], 2) ?></td>
                    <td><?php echo round($teamCAvg[4], 2) ?></td>
                    <td><?php echo round($managerCAvg[4], 2) ?></td>
                    </tr>

                      <tr class="clickable collapse row5" data-toggle="collapse" id="row51" data-target=".row51">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Puts the consumer as the central focus</td>
                      <td><?php echo round($selfSAvg[4][0], 2) ?></td>
                      <td><?php echo round($teamSAvg[4][0], 2) ?></td>  
                      <td><?php echo round($managerSAvg[4][0], 2) ?></td>
                      </tr>

                        <tr class="collapse row51">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Ensures and enables teams to stay connected with evolving consumer needs</td>
                        <td><?php echo $selfScore[54]['answer'] ?></td>
                        <td><?php echo $teamScore[54]['answer'] ?></td>  
                        <td><?php echo $managerScore[54]['answer'] ?></td>
                        </tr><tr class="collapse row51">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Inspires teams through his/her keen focus on consumers</td>
                        <td><?php echo $selfScore[55]['answer'] ?></td>
                        <td><?php echo $teamScore[55]['answer'] ?></td>  
                        <td><?php echo $managerScore[55]['answer'] ?></td>
                        </tr><tr class="collapse row51">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Shows interest in the culture and rituals of beauty and uses it toachieve local relevance</td>
                        <td><?php echo $selfScore[56]['answer'] ?></td>
                        <td><?php echo $teamScore[56]['answer'] ?></td>  
                        <td><?php echo $managerScore[56]['answer'] ?></td>
                        </tr>

                      <tr class="clickable collapse row5" data-toggle="collapse" id="row52" data-target=".row52">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Challenges the status quo and strives for excellence</td>
                      <td><?php echo round($selfSAvg[4][1], 2) ?></td>
                      <td><?php echo round($teamSAvg[4][1], 2) ?></td>  
                      <td><?php echo round($managerSAvg[4][1], 2) ?></td>
                      </tr>

                        <tr class="collapse row52">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Encourage teams to reflect on the ways of working and to continuously improve </td>
                        <td><?php echo $selfScore[57]['answer'] ?></td>
                        <td><?php echo $teamScore[57]['answer'] ?></td>  
                        <td><?php echo $managerScore[57]['answer'] ?></td>
                        </tr><tr class="collapse row52">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Thinks out of the box and generates innovative strategies</td>
                        <td><?php echo $selfScore[58]['answer'] ?></td>
                        <td><?php echo $teamScore[58]['answer'] ?></td>  
                        <td><?php echo $managerScore[58]['answer'] ?></td>
                        </tr>

                      <tr class="clickable collapse row5" data-toggle="collapse" id="row53" data-target=".row53">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Innovates beyond the product</td>
                      <td><?php echo round($selfSAvg[4][2], 2) ?></td>
                      <td><?php echo round($teamSAvg[4][2], 2) ?></td>  
                      <td><?php echo round($managerSAvg[4][2], 2) ?></td>
                      </tr>

                        <tr class="collapse row53">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Drives innovation with focus on consumer insights</td>
                        <td><?php echo $selfScore[59]['answer'] ?></td>
                        <td><?php echo $teamScore[59]['answer'] ?></td>  
                        <td><?php echo $managerScore[59]['answer'] ?></td>
                        </tr><tr class="collapse row53">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Makes sure his/her team integrates new technologies to enhance the consumer beauty journey</td>
                        <td><?php echo $selfScore[60]['answer'] ?></td>
                        <td><?php echo $teamScore[60]['answer'] ?></td>  
                        <td><?php echo $managerScore[60]['answer'] ?></td>
                        </tr><tr class="collapse row53">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Encourages a comprehensive approach of innovation, including all key components from start</td>
                        <td><?php echo $selfScore[61]['answer'] ?></td>
                        <td><?php echo $teamScore[61]['answer'] ?></td>  
                        <td><?php echo $managerScore[61]['answer'] ?></td>
                        </tr>

                      <tr class="clickable collapse row5" data-toggle="collapse" id="row54" data-target=".row54">
                      <td><i class="glyphicon glyphicon-plus"></i></td>
                      <td>Seizes what is just starting and opens new ventures</td>
                      <td><?php echo round($selfSAvg[4][3], 2) ?></td>
                      <td><?php echo round($teamSAvg[4][3], 2) ?></td>  
                      <td><?php echo round($managerSAvg[4][3], 2) ?></td>
                      </tr>            

                        <tr class="collapse row54">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Identifies beauty trends as they emerge and champions promising ideas</td>
                        <td><?php echo $selfScore[62]['answer'] ?></td>
                        <td><?php echo $teamScore[62]['answer'] ?></td>  
                        <td><?php echo $managerScore[62]['answer'] ?></td>
                        </tr><tr class="collapse row54">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Prefers to take risks rather than missing opportunities</td>
                        <td><?php echo $selfScore[63]['answer'] ?></td>
                        <td><?php echo $teamScore[63]['answer'] ?></td>  
                        <td><?php echo $managerScore[63]['answer'] ?></td>
                        </tr><tr class="collapse row54">
                        <td><i class="glyphicon glyphicon-minus"></i></td>
                        <td>Stimulates curiosity and external focus</td>
                        <td><?php echo $selfScore[64]['answer'] ?></td>
                        <td><?php echo $teamScore[64]['answer'] ?></td>  
                        <td><?php echo $managerScore[64]['answer'] ?></td>
                        </tr>

                  </tbody>
        </table>
      </div> <!-- /container -->
    </div>

        <div class="col-lg-12">
      <div class="container">
        <table class="table">
        <thead class="thead-inverse">
          <tr>
            <th><h4 style="font-weight: bold;">Aggregate</h4></th>
            <th><h4 style="font-weight: bold;">Value</h4></th>
          </tr>
        </thead>
        <tbody>
          <tr class="aggregate">
            <td>Self</td>
                        <td><?php
                        $sum = 0;
                        $count = 0;
                         foreach ($selfScore as $key => $value) {
                          $count++;
                          $sum = $sum + $selfScore[$key]['answer'];                    
                        }
                        echo round($sum / $count, 2);
                        ?>
                          
                        </td>
          </tr>
          <tr class="aggregate">
            <td>Team</td>
            <td><?php
                        $sum = 0;
                        $count = 0;
                         foreach ($teamScore as $key => $value) {
                          $count++;
                          $sum = $sum + $teamScore[$key]['answer'];                    
                        }
                        echo round($sum / $count, 2);
                        ?></td>
          </tr>
          <tr class="aggregate">
            <td>Others</td>
            <td><?php
                        $sum = 0;
                        $count = 0;
                         foreach ($managerScore as $key => $value) {
                          $count++;
                          $sum = $sum + $managerScore[$key]['answer'];                    
                        }
                        echo round($sum / $count, 2);
                        ?></td>
          </tr>  
        </tbody>
      </table>
        <div id="chartContainer" style="height: 300px; width: 100%;"></div>
        <br><br>
        <?php
          $getCommentSQL = "SELECT q1,q2,q3 
                            FROM feedbacks_subjective
                            WHERE review_count = {$reviewCount}
                            AND designation = '{$emp}'";
          $comments = $conn->query($getCommentSQL);
          $ques1 = array();
          $ques2 = array();
          $ques3 = array();
          if ($comments->num_rows > 0) {
            while ($row = $comments->fetch_assoc()) {
              if (!empty($row['q1']))
                array_push($ques1, $row['q1']);
              if (!empty($row['q2']))
                array_push($ques2, $row['q2']);
              if (!empty($row['q3']))
                array_push($ques3, $row['q3']);
            }
          }
        ?>
        <div class="freeText">
          <p>Question 1 - </p><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#ques1">View Responses</button>
          <div id="ques1" class="collapse">
            <?php
              echo join('; ', $ques1);
            ?>
          </div>
          <p>Question 2 - </p><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#ques2">View Responses</button>
          <div id="ques2" class="collapse">
            <?php
              echo join('; ', $ques2);
            ?>
          </div>
          <p>Question 3 - </p><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#ques3">View Responses</button>
          <div id="ques3" class="collapse">
            <?php
              echo join('; ', $ques3);
            ?>
          </div>                    
        </div>
      </div> <!-- /container -->
    </div>

  </div>  
  <script src="../js//jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
</body>
</html>
