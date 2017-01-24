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
$sql = $conn->prepare("SELECT * FROM availability_new WHERE DAYOFWK = 0");
$result = $sql->execute();
echo "<br/> " . $result . " </br>";
if ($result > 0) {
    foreach ($result as $row) {
        $last_update = $row['LAST_UPDATE'];
        $timeofdy = $row['TIMEOFDY'];
        $dayofwk = $dayMap[$row['DAYOFWKAV']];
        $arrayofdays = $row['DAYOFWK'];
        echo $dayofwk;
        echo $last_update;

        if (($dayofwk = 0) && ($last_update >= "00:00:00")) {
            echo "Updating! >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>";

            $timeslot_query = "UPDATE availability_new SET TIMESLOT = :timeslotVal + 1 WHERE DAYOFWK = :dayOfWeek AND LAST_UPDATE = :lastUpdate";
            echo "<br/> UPDATE availability_new SET TIMESLOT = TIMESLOT + 1 " . " WHERE DAYOFWK = " . $dayofwk . " AND LAST_UPDATE = " . $last_update . "  </br>";
        }
        else {
        throw new Exception($conn->error);
    }
    } 
//        // for each item in result as $row
//        foreach ($result as $row) {
//            $timesid = $row['TIMES_ID'];
//            $last_update = $row['LAST_UPDATE'];
//            $timeofdy = $row['TIMEOFDY'];
//            $dayofwk = $dayMap[$row['DAYOFWKAV']];
//            $arrayofdays = $row['DAYOFWK'];
//            echo $dayofwk;
//            echo $last_update;
//
//            if (($dayofwk = 0) && ($last_update >= "00:00:00") ){
//                echo "Updating! >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>";
//                
//                $timeslot_query = "UPDATE availability_new SET TIMESLOT = :timeslotVal + 1 WHERE DAYOFWK = :dayOfWeek AND LAST_UPDATE = :lastUpdate";
//                echo "<br/> UPDATE availability_new SET TIMESLOT = TIMESLOT + 1 " . " WHERE DAYOFWK = " . $dayofwk . " AND LAST_UPDATE = ". $last_update . "  </br>";
////                $statement = $conn->prepare($timeslot_query);
////                $params = array('timeslotVal' => $timesid, 'dayOfWeek' => $arrayofdays, 'lastUpdate' => $timeofdy);
////                $res = $statement->execute($params);
////                
//               
//            }
//        }
    }
//close the while loop
//mysqli_close($conn);
    echo json_encode($data);
?>