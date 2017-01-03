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

    echo '<pre>';
    print_r($number);
    print_r($avail_bikes);
    print_r($avail_slots);
    echo '</pre>';
    //execute update query
    mysqli_stmt_execute($st);
}


$data = array();
$q = mysqli_query($conn, "select * from `locations`");
while ($row = mysqli_fetch_object($q)) {
    $data[] = $row;
}
echo json_encode($data);
//close connection
mysqli_close($conn);
?>