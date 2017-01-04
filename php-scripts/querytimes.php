<?php

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
echo "Connected successfully \n";

//information for the bikes api
$api_key = "ec447add626cfb0869dd4747a7e50e21d39d1850";
$contract_name = "Dublin";
//phpinfo();
$api_url = "https://api.jcdecaux.com/vls/v1/stations?contract=Dublin&apiKey=ec447add626cfb0869dd4747a7e50e21d39d1850";
$contents = file_get_contents($api_url);
//convert the json to a php assoc array for query
$dbikeinfo = json_decode($contents, true);

//        //insert into availability table
//        $st = mysqli_prepare($conn, 'INSERT INTO availability(number, timeslot, avail_bikes, avail_slots, status) VALUES (?, ?, ?, ?, ?)');
//        //bind the varibales
//        mysqli_stmt_bind_param($st, 'isiis', $number, $timeslot, $avail_bikes, $avail_slot, $status);
//
//        // loop through the array
//        foreach ($dbikeinfo as $row) {
//            // get the locations details
//            $number = $row['number'];
//            $timeslot = strftime("%Y-%m-%d, %H:%M:%S", time());
//            $avail_bikes = $row['available_bikes'];
//            $avail_slot = $row['available_bike_stands'];
//            $status = $row['status'];
//            $timestamp = $row['last_update'];
//
////            echo '<pre>';
////            print_r($number);
////            print_r($timeslot);
////            print_r($avail_bikes);
////            print_r($avail_slot);
////            print_r($status);
////            echo '</pre>';
//            // execute insert query
//            mysqli_stmt_execute($st);
//        }
//close connection
//        mysqli_close($conn);
//insert new time stamp every 10 minutes






//
//$epoch = strtotime('now'); //current time
//$dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
//$tt = new DateTime("@$epoch"); //convert the epoch to UNIX time
//echo $tt->format('H:i:s'); // output = 21:06:43
//$time = date('H:i:s');
//echo $tt->format('Y-m-d'); // output = 2017-01-01
//$day = date('w');
//
//
//echo " day as int is " . $day . " ";

$data = array();
//$q = mysqli_query($conn, "SELECT * FROM TIMES");
$timesquery = "SELECT * FROM TIMES";
$times_result = $conn->query($timesquery);
while ($row = $times_result->fetch_assoc()) {
//    $last_update = $row2['LAST_UPDATE'];
//    $dayofwk = $row2['DAYOFWK'];
//echo "<br> timesid: ". $row["TIMES_ID"]. " - timeofdy: ". $row["TIMEOFDY"] . " - dayofwk: ". $row["DAYOFWK"] . "<br>";
     $timeofdy = $row['TIMEOFDY'];
     $tdayofwk = $row['DAYOFWK'];
//     echo $timeofdy . $tdayofwk;
}
echo $timeofdy . $tdayofwk;


$data_check = array();

$query = "SELECT LAST_UPDATE, DAYOFWK FROM AVAILABILITY";
$result = $conn->query($query);
//$qs = mysqli_query($conn, "SELECT LAST_UPDATE, DAYOFWK FROM AVAILABILITY");
while ($row2 = $result->fetch_assoc()) {
//    $last_update = $row2['LAST_UPDATE'];
//    $dayofwk = $row2['DAYOFWK'];
        $last_update = $row2["LAST_UPDATE"];
        $dayofwk = $row2["DAYOFWK"];
//echo "<br> lastupdate: ". $row2["LAST_UPDATE"]. " - dayofwk: ". $row2["DAYOFWK"] . "<br>";
    
}






//insert into availability table
//        $st = mysqli_prepare($conn, 'INSERT INTO availability(number, timeslot, avail_bikes, avail_slots, status) VALUES (?, (SELECT TIMES_ID FROM TIMES WHERE , ?, ?, ?)');
//        //bind the varibales
//        mysqli_stmt_bind_param($st, 'isiis', $number, $timeslot, $avail_bikes, $avail_slot, $status);
//
//        // loop through the array
//        foreach ($dbikeinfo as $row) {
//            // get the locations details
//            $number = $row['number'];
//            $timeslot = strftime("%Y-%m-%d, %H:%M:%S", time());
//            $avail_bikes = $row['available_bikes'];
//            $avail_slot = $row['available_bike_stands'];
//            $status = $row['status'];
//            $timestamp = $row['last_update'];
//
//if (mysqli_query($conn, $st)) {
//    echo "New record created successfully";
//} else {
//    echo "Error: " . $st . "<br>" . mysqli_error($conn);
//}
?>