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
$timesquery = "SELECT times.* , availability_new_copy.`LAST_UPDATE`, availability_new_copy.`DAYOFWK` as DAYOFWKAV 
FROM TIMES JOIN availability_new_copy
ON   times.TIMEOFDY = availability_new_copy.LAST_UPDATE";
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

            while($dayofwk = 0) {
            echo "Updating! >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>";

            $timeslot_query = "UPDATE availability_new_copy SET TIMESLOT = TIMESLOT + 1 WHERE DAYOFWK = :dayOfWeek AND LAST_UPDATE = :lastUpdate";
            $statement = $conn->prepare($timeslot_query);
            $params = array('dayOfWeek' => $arrayofdays, 'lastUpdate' => $timeofdy);
            $res = $statement->execute($params);
            }
//close the while loop
        }
    }
}

//mysqli_close($conn);
        echo json_encode($data);
?>