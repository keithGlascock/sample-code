<?php


$latitude = $_GET["latitude"];
$longitude = $_GET['longitude'];
$pet_type = $_GET['pet_type'];
$pet_info = $_GET['info'];
$pet_status = $_GET['status'];
$pet_description = $_GET["description"];
$pet_phone = $_GET['phone'];
$pet_source = $_GET['source'];
$report_date = date('Y-m-d');

echo "<p>$report_date</p>";

echo $latitude.$longitude.$pet_type.$pet_info.$pet_status.$pet_description.$pet_phone.$pet_source;

// Connect to the database
$DBConnect = @new mysqli("localhost", "root", "blacklab");
if (mysqli_connect_errno())
	die("<p>The database server is not available.</p>"
  	. "<p>Error code " . mysqli_connect_errno()
 	. ": " . mysqli_connect_error()) . "</p>";
$DBName = "pets";
@$DBConnect->select_db($DBName)
  Or die("<p>The datbase is not available.</p>"
  . "<p>Error code " . mysqli_errno($DBConnect)
  . ": " . mysqli_error($DBConnect)) . "</p>";

//Execute the MySQL query to insert a new pet report to the database
$SQLString = "INSERT INTO pet_report VALUES($latitude, '$longitude', '$pet_type', 
	'$pet_info', '$pet_status', '$report_date', '$pet_description', '$pet_phone',
	'$pet_source', NULL)";
$DBConnect->query($SQLString)
  Or die("<p>Unable to execute the query.</p>"
  . "<p>Error code " . mysqli_errno($DBConnect)
  . ": " . mysqli_error($DBConnect)) . "</p>";

$DBConnect->close();

?>
