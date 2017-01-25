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
//sql query that will use the variables entered above and return the avail of bikes and slots of the selected statino
$searchData = $conn->prepare($conn, 'SELECT NUMBER, AVAIL_BIKES, AVAIL_SLOTS FROM availability_new WHERE LAST_UPDATE = ?  AND DAYOFWK = ? AND NUMBER = ?');
$searchData->bind_param("sis", $time, $dayofwk, $stat_id);
$result = $searchData->execute();
if ($result > 0) {
    foreach ($result as $row) {
        echo "<br/> " . $row . " </br>";
    }
} else {
    echo 'no joy gringo';
}
?>


