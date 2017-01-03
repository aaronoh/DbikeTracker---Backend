<?php

//cleardb url mysql://b4c04b4b0847ac:bdfbd3f7@us-cdbr-iron-east-04.cleardb.net/heroku_1aebd2cf6f33fe1?reconnect=true
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
$conn = mysqli_connect($server, $username, $password, $db) or die('Error in Connecting: ' . mysqli_error($conn));
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$api_url = "https://api.jcdecaux.com/vls/v1/stations?contract=Dublin&apiKey=ec447add626cfb0869dd4747a7e50e21d39d1850";
$contents = file_get_contents($api_url);
$dbikeinfo = json_decode($contents, true);
$st = mysqli_prepare($conn, 'UPDATE live_data SET avail_bikes = ?, avail_slots = ? WHERE number = ?');
//bind the varibales
mysqli_stmt_bind_param($st, 'iii', $avail_bikes, $avail_slots, $number);

// loop through the array
foreach ($dbikeinfo as $row) {
    // get the locations details
    $number = $row['number'];
    $avail_bikes = $row['available_bikes'];
    $avail_slots = $row['available_bike_stands'];
    //execute update query
    mysqli_stmt_execute($st);
}


$data = array();
$q = mysqli_query($conn, "SELECT locations.NAME, locations.LAT, locations.LNG, live_data.AVAIL_BIKES, live_data.AVAIL_SLOTS
  FROM locations INNER JOIN live_data
    ON locations.NUMBER = live_data.NUMBER");
while ($row = mysqli_fetch_object($q)) {
    $data[] = $row;
}
echo json_encode($data);

//Join 
//$joinQ = mysqli_query($conn, "SELECT locations.NAME, locations.LAT, locations.LNG, live_data.AVAIL_BIKES, live_data.AVAIL_SLOTS
//  FROM locations INNER JOIN live_data
//    ON locations.NUMBER = live_data.NUMBER");


//close connection
mysqli_close($conn);
?>