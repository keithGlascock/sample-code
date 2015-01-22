<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Missing Pets in the Greater Peoria Area</title>
<link type="text/css" rel="stylesheet" href="missing_pets_style.css"/>
<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2.124&amp;key="></script>
<script type="text/javascript" src="pet_script_ver2.js"></script>
</head>
<body>
  <div id="map"></div>
  <div id="sidebar">
    <h1 align="center" title="Missing">Missing Pets</h1>
    <div id="lost"><h2>Lost</h2></div>
    <div id="found"><h2>Found</h2></div>
    <div id="sighted"><h2>Sighted</h2></div>

<?php
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

//Execute the MySQL query for all pet reports to create the content for the infowindow and the sidebar
    $SQLString = "SELECT * FROM pet_report";
    $QueryResult = @$DBConnect->query($SQLString)
      Or die("<p>Unable to execute the query.</p>"
      . "<p>Error code " . mysqli_errno($DBConnect)
      . ": " . mysqli_error($DBConnect)) . "</p>";
    $Row = $QueryResult->fetch_assoc();
    if (!$Row)
      echo "<p>No pets reported</p>";
    
    echo "<div id=\"infowindow_content\">";

    while ($Row){
      echo "<div id=\"{$Row['pet_id']}\" class='details_tab'>
      <h3>{$Row['description']}</h3>
      <dl>
        <dt>Date:</dt><dd>{$Row['report_date']}</dd>
        <dt>Source:</dt><dd>{$Row['source']}</dd>
        <dt>Contact:</dt><dd>{$Row['phone']}</dd>
      </dl>
      <p>{$Row['info']}</p>
      </div>";

    // Create an array of objects JSON for creating the map markers
    $json = $json.
      '{lat: '.$Row['latitude'].',
       long: '.$Row['longitude'].',
       id: '.$Row['pet_id'].',
       descrip: "'.$Row['description'].'",
       type: "'.$Row['pet_type'].'",
       date: "'.$Row['report_date'].'",
       status: "'.$Row['status'].'"},';

    $Row = $QueryResult->fetch_assoc();
    }

    $QueryResult->free_result();
?>

<script type="text/javascript">

function petJSON()
{
   // Output the JSON
    var x = <?php echo "{pets: [$json]}";?>;

   // Step throught the array for each pet location and add a marker to the map
    for (i = 0; i < x.pets.length; i++)
    {
      var pet_location = x.pets[i];
      addPetData(pet_location.lat, pet_location.long, pet_location.id, pet_location.descrip, pet_location.type, pet_location.date, pet_location.status);
    }
};

</script>

    </div>

<script type="text/javascript">
//GEvent.addDomListener(document.getElementById('test'), 'click', miss);
</script>

</body>
</html>
