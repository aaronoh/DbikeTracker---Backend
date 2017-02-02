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
$dayofwk = $_POST['date'];
$dayofwk = date('w');
echo $dayofwk . " ";
$stat_id = $_POST['statnum'];
echo $stat_id . " ";


$sql = "SELECT number, avail_bikes, avail_slots FROM availability_new WHERE DAYOFWK = '$dayofwk' AND LAST_UPDATE = '$time' AND NUMBER = '$stat_id'";
$result = $conn->query($sql);

echo $sql;
if ($result->num_rows > 0) {
//    echo "<table><tr><th>num</th><th>avail_bikes</th><th>avail_slots</th></tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
//        echo "<tr><td>".$row["number"]."</td><td>".$row["avail_bikes"]."</td><td>".$row["avail_slots"]."</td></tr>";
        echo $row["avail_bikes"] . "\n";
        echo $row['avail_slots'] . "\n";
    }
//    echo "</table>";
} else {
    echo "0 results";
}
//$data = array();
//$sql = "SELECT number, avail_bikes, avail_slots FROM availability_new WHERE DAYOFWK = '$intofwk' AND LAST_UPDATE = '$time' AND NUMBER = '$stat_id'";
//$query = mysqli_query($conn, $sql);
//while($row= mysqli_fetch_object($query)){
//    $data[]=$row;
//}
//echo json_encode($data);
$conn->close();
?>