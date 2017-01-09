<?php

//connect to ClearDB
//cleardb url mysql://b4c04b4b0847ac:bdfbd3f7@us-cdbr-iron-east-04.cleardb.net/heroku_1aebd2cf6f33fe1?reconnect=true
//gets the variables from the url and parses them to the variables
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
//$db = "heroku_1aebd2cf6f33fe1";
$dsn = "mysql:host=" . $server . ";dbname=" . $db;
//create the conneciton
//        $conn = new mysqli($server, $username, $password, $db);
//$conn = mysqli_connect($server, $username, $password, $db) or die('Error in Connecting: ' . mysqli_error($conn));
// Connecting to mysql database
$conn = new PDO($dsn, $username, $password);
// Check for database connection error
if (!$conn) {
    die("Could not connect to DB");
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

//query for rounding times down
$round_query = "UPDATE AVAILABILITY SET LAST_UPDATE = SEC_TO_TIME((TIME_TO_SEC(LAST_UPDATE) DIV 600) * 600)";





$epoch = strtotime('now');
$dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
$tt = new DateTime("@$epoch"); //convert the epoch to UNIX time
echo $tt->format('H:i:s'); // output = 21:06:43
$time = date('H:i:s');
echo $tt->format('Y-m-d'); // output = 2017-01-01
$day = date('w');





// loop through the array
foreach ($dbikeinfo as $row) {

    $timestamp = $row['last_update'];
    // get the locations details
    $number = $row['number'];
//            $timeslot = ;
    $avail_bikes = $row['available_bikes'];
    $avail_slot = $row['available_bike_stands'];
    $status = $row['status'];
    $last_update = $time;
    $dayofwk = $day;
//
//    echo '<pre>';
//    print_r($number);
//    print_r($timeslot);
//    print_r($avail_bikes);
//    print_r($avail_slot);
//    print_r($status);
//    print_r($last_update);
//    print_r($day);
//    echo '</pre>';
    //execute insert query
//    mysqli_stmt_execute($st);
    $avail_insert = "INSERT INTO availability(number, timeslot, avail_bikes, avail_slots, status, last_update, dayofwk) VALUES (:number,:timeslot,:availb,:avails,:status,:time,:dayofwk)";
    $result = $conn->query($avail_insert);
//compare the time we just got to a time variable like NOW()/timeofdy;
//insert into availability table
    $statement = $conn->prepare($avail_insert);
    $params = array(
        'number' => $number,
        'timeslot' => $timeslot,
        'availb' => $avail_bikes,
        'avails' => $avail_slot,
        'status' => $status,
        'time' => $time,
        'dayofwk' => $dayofwk
    );
    $res = $statement->execute($params);
    echo "<br/> INSERT INTO availability(number, timeslot, avail_bikes, avail_slots, status, last_update, dayofwk) VALUES (:number, :timeslot, :availb, :avails,:status, :time, :dayofwk)";
    if (!$res) {
        echo "</br>Error---";
        print_r($statement->errorInfo());
        echo "</br>Error code: " . $statement->errorCode();
        //print_r($result->errorInfo());
        echo "</br>Column count: " . $statement->columnCount();
    } else {
        echo "<br/>>> Insert succesful!";
    }
}



//        mysqli_stmt_execute($gettime);
var_dump($timestamp);
//close connection
mysqli_close($conn);
