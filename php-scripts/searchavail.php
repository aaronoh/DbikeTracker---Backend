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
//create the conneciton
//        $conn = new mysqli($server, $username, $password, $db);
$conn = mysqli_connect($server, $username, $password, $db) or die('Error in Connecting: ' . mysqli_error($conn));

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
//information for the bikes api
$api_key = "ec447add626cfb0869dd4747a7e50e21d39d1850";
$contract_name = "Dublin";
//phpinfo();
$api_url = "https://api.jcdecaux.com/vls/v1/stations?contract=Dublin&apiKey=ec447add626cfb0869dd4747a7e50e21d39d1850";
$contents = file_get_contents($api_url);
//convert the json to a php assoc array for query
$dbikeinfo = json_decode($contents, true);

$time = $_POST['time'];
echo $time;
//$_POST['date']->format('Y-m-d'); // output = 2017-01-01
$dayofwk = $_POST['date'] = date('w');
echo $dayofwk;
$stat_id = $_POST['stat_id'];
echo $stat_id;
////sql query that will use the variables entered above and return the avail of bikes and slots of the selected statino
//$sql = mysqli_prepare($conn, 'SELECT avail_bikes, avail_slots, number
//    FROM availability_new
//    WHERE DAYOFWK = :dayofwk AND last_update = ":lastup" AND number = :statnum');
//$sql->bind_param('sss', $dayofwk, $startid, $last_up);
//$dayofw = $dayofwk;
//$startid = $startid;
//$lastup = $last_up;
//$sql->execute();
//printf("%d Row selected.\n", $stmt->affected_rows);
//
///* close statement and connection */
//$stmt->close();

$sql = "SELECT avail_bikes, avail_slots, number
//    FROM availability_new
//    WHERE DAYOFWK = 2 AND number = 22";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "number: " . $row["number"]. " - bikes: " . $row["avail_bikes"]. " - slots: " . $row["avail_slots"]. "<br>";
    }
} else {
    echo "0 results";
}

mysqli_close($conn);
?>


