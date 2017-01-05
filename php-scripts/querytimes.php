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
$epoch = strtotime('now'); //current time
$dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
$tt = new DateTime("@$epoch"); //convert the epoch to UNIX time
echo $tt->format('H:i:s'); // output = 21:06:43
$time = date('H:i:s');
echo $tt->format('Y-m-d'); // output = 2017-01-01
$day = date('w');
//
//
//echo " day as int is " . $day . " ";

$data = array();
//$q = mysqli_query($conn, "SELECT * FROM TIMES");
$timesquery = "SELECT times.* , availability.`LAST_UPDATE`, availability.`DAYOFWK` as DAYOFWKAV 
FROM TIMES JOIN availability
ON   times.TIMEOFDY = availability.LAST_UPDATE";


$result = $conn->query($timesquery);
if (mysqli_num_rows($result) > 0) {
    foreach ($result as $row) {
        $timesid = $row['TIMES_ID'];
        $last_update = $row['LAST_UPDATE'];
        $timeofdy = $row['TIMEOFDY'];
        $dayofwk = $row['DAYOFWKAV'];
        $arrayofdays = $row['DAYOFWK'];


//        echo "<br> ARRAY OF DAYS " . $arrayofdays . "</br>";
//        echo "<br> DAY OF WEEK " . $dayofwk . "</br>";
//        echo "<br> LAST UPDATE  " . $last_update . "</br>";
//        echo "<br> TIME OF DAY " . $timeofdy . "</br>";

        if ($last_update[$i] == $timeofdy[$i] && $dayofwk[$i] == $arrayofdays[$i]) {
            echo "<br> dayofwk: " . $dayofwk . " - arrayofdays: " . $arrayofdays . "</br>";
            echo "<br> lastupdate: " . $last_update . " - timeofdy: " . $timeofdy . "</br>";
//////            echo "<br> timesid: ". $row["TIMES_ID"]. " - dayofwk1: ". $row['DAYOFWKAV'] . " - dayofwk2: ". $row["DAYOFWK"] .  " - lastupdate: ". $row["LAST_UPDATE"] ." - timeofdy: ". $row["TIMEOFDY"] . "<br>";
//            $timeslot_query = mysqli_prepare($conn, "UPDATE availability SET TIMESLOT = ? WHERE DAYOFWK = $dayofwk");
////         mysqli_prepare($conn, 'INSERT INTO timeslotjunc(TIMES_ID) VALUES(?)');
/////        bind the varibales
//            mysqli_stmt_bind_param($timeslot_query, 'i', $row['TIMES_ID']);
//            echo "<br> timeody: " . $timeofdy . " - lastupdate: " . $last_update . "</br>";
//            //         execute insert query
//            mysqli_stmt_execute($timeslot_query);
            $i++;
        }

    }
}
//close the while loop
mysqli_close($conn);
echo json_encode($data);
?>