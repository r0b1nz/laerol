<?php
  session_start();
  require "../db/connect.php";
  
  if (!authCheck($_SESSION['user'], $_SESSION['pass']) || !isset($_SESSION['isHR'])) {
    header('Location: ../');
    exit();
  }

  $getAllUsers = 'SELECT * FROM emp_info';
  $result = $conn->query($getAllUsers);
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
  </header>
    
  <div class="container center_div">
    <table class="table">
      <thead class="thead-inverse">
        <tr>
          <th>#</th>
          <th>Designation</th>
          <th>Username</th>
          <th>Password</th>
        </tr>
      </thead>
      <tbody>

        <?php
          if ($result->num_rows > 0) {
            $counter = 1;
            while($user = $result->fetch_assoc()) {
              echo '<tr>';
              echo '<th scope="row">' . $counter . '</th>';
              echo '<td>' . $user['designation'] . '</td>';
              echo '<td>' . $user['designation'] . '</td>';
              echo '<td>' . $user['password'] . '</td>';
              echo '</tr>';
              $counter++;
            }
          }
        ?>

      </tbody>
    </table>
  </div> <!-- /container -->

</body>
</html>

