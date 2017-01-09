<?php

//connect to ClearDB
//cleardb url mysql://b4c04b4b0847ac:bdfbd3f7@us-cdbr-iron-east-04.cleardb.net/heroku_1aebd2cf6f33fe1?reconnect=true
//gets the variables from the url and parses them to the variables
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$dayMap = array(
    'Sunday',
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday'
);
//$server = "us-cdbr-iron-east-04.cleardb.net";
//$username = "b4c04b4b0847ac";
//$password = "bdfbd3f7";
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
if ($result) {
// if the number of rows in the result is greater than 0
    if ($result->rowCount() > 0) {
        // for each item in result as $row
        foreach ($result as $row) {
            $timesid = $row['TIMES_ID'];
            $last_update = $row['LAST_UPDATE'];
            $timeofdy = $row['TIMEOFDY'];
            $dayofwk = $dayMap[$row['DAYOFWKAV']];
            $arrayofdays = $row['DAYOFWK'];
//            echo "<br> ARRAY OF DAYS " . $arrayofdays . "</br>";
//            echo "<br> DAY OF WEEK " . $dayofwk . "</br>";
//            echo "<br> DAY OF WEEK INTEGER " . $row['DAYOFWKAV'] . "</br>";
//            echo "<br> LAST UPDATE  " . $last_update . "</br>";
//            echo "<br> TIME OF DAY " . $timeofdy . "</br>";
            if (($last_update == $timeofdy) && ($row['DAYOFWKAV'] == $arrayofdays)) {
                echo "Updating! >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>";
//            echo "<br> dayofwk: " . $dayofwk . " - arrayofdays: " . $arrayofdays . "</br>";
//            echo "<br> lastupdate: " . $last_update . " - timeofdy: " . $timeofdy . "</br>";
////            echo "<br> timesid: ". $row["TIMES_ID"]. " - dayofwk1: ". $row['DAYOFWKAV'] . " - dayofwk2: ". $row["DAYOFWK"] .  " - lastupdate: ". $row["LAST_UPDATE"] ." - timeofdy: ". $row["TIMEOFDY"] . "<br>";
                //$timeslot_query = mysqli_prepare($conn, "UPDATE availability SET TIMESLOT = ? WHERE DAYOFWK = $arrayofdays");
//         mysqli_prepare($conn, 'INSERT INTO timeslotjunc(TIMES_ID) VALUES(?)');
///        bind the varibales
                // mysqli_stmt_bind_param($timeslot_query, 'i', $row['TIMES_ID']);
                //         execute insert query
                //  mysqli_stmt_execute($timeslot_query);
                $timeslot_query = "UPDATE availability SET TIMESLOT = :timeslotVal WHERE DAYOFWK = :dayOfWeek";
                $statement = $conn->prepare($timeslot_query);
                $params = array('timeslotVal' => $timesid, 'dayOfWeek' => $arrayofdays);
                $res = $statement->execute($params);

                echo "<br/> UPDATE availability SET TIMESLOT = " . $timesid . " WHERE LAST_UPDATE = " . $timeofdy . " AND DAYOFWK = " . $arrayofdays;
                if (!$res) {
                    echo "</br>Error---";
                    print_r($statement->errorInfo());
                    echo "</br>Error code: " . $statement->errorCode();
                    //print_r($result->errorInfo());
                    echo "</br>Column count: " . $statement->columnCount();
                } else {
                    echo "<br/>>> Update succesful!";
                }
            }
            $count = $res->rowCount();
            print("Updated $count rows.\n");
        }
    }
//close the while loop
}
//mysqli_close($conn);
echo json_encode($data);
?>