<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//connect to ClearDB
//cleardb url mysql://b4c04b4b0847ac:bdfbd3f7@us-cdbr-iron-east-04.cleardb.net/heroku_1aebd2cf6f33fe1?reconnect=true
//gets the variables from the url and parses them to the variables
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
//create the conneciton
//        $conn = new mysqli($server, $username, $password, $db);
$conn = mysqli_connect($server, $username, $password, $db) or die('Error in Connecting: ' . mysqli_error($conn));

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully" . "<br>";
$time = $_POST['time'];
echo $time . " ";
//$_POST['date']->format('Y-m-d'); // output = 2017-01-01
$date = date_create($_POST['date']);
$intofwk = date_format($date, "w");
echo $intofwk . " ";
$stat_id = $_POST['statnum'];
echo $stat_id . " ";


$sql = "SELECT number, avail_bikes, avail_slots FROM availability_new WHERE DAYOFWK = '$intofwk' AND LAST_UPDATE = '$time' AND NUMBER = '$stat_id'";
$result = $conn->query($sql);


echo $sql . "\n";
if ($result->num_rows > 0) {
    $bikes = array();
    // output data of each row
    while ($row = $result->fetch_assoc()) {
//        echo $row["avail_bikes"] . "\n";
//        echo $row['avail_slots'] . "\n";


        array_push($bikes, $row['avail_bikes']);
    }
} else {
    echo "0 results";
}
print_r($bikes);
//function for working out the percentile
function get_percentile($percentile, $array) {
    sort($array);
    $index = ($percentile/100) * count($array);
    if (floor($index) == $index) {
         $result = ($array[$index-1] + $array[$index])/2;
    }
    else {
        $result = $array[floor($index)];
    }
    return $result;
}



echo get_percentile(75, $bikes);
echo get_percentile(90, $bikes);


$conn->close();
?>