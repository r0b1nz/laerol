<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbName = 'loreal_hr_feedback';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "db Connected successfully";

function authCheck($user, $pass) {
	$sql = 'SELECT password from emp_info where designation = \'' . $user . '\'';
	$conn = $GLOBALS['conn'];
	$result = $conn->query($sql);

	if ($result->num_rows < 1) {
		return false;
	} else {
		$result = $result->fetch_assoc();
		if ($result['password'] == $pass) {
			return true;
		} else {
			return false;
		}		
	}
}

function invalid() {
	echo '<div class="alert alert-danger">
		  <strong>Wrong!</strong> username or password.
		</div>';
}
?>