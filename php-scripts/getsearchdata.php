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

$searchData = array();
$query = mysqli_query($conn, "SELECT locations.NUMBER, locations.NAME, locations.LAT, locations.LNG, live_data.AVAIL_BIKES, live_data.AVAIL_SLOTS
  FROM locations INNER JOIN live_data
    ON locations.NUMBER = live_data.NUMBER ORDER BY live_data.NUMBER");
while ($row = mysqli_fetch_object($query)) {
    $searchData[] = $row;
}
echo json_encode($searchData);
?>