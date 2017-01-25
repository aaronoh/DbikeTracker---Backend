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

$time = $_POST['time'];
echo $time;
//$_POST['date']->format('Y-m-d'); // output = 2017-01-01
$dayofwk = $_POST['date'] = date('w');
echo $dayofwk;
$stat_id = $_POST['stat_id'];
echo $stat_id;
//sql query that will use the variables entered above and return the avail of bikes and slots of the selected statino
$sql = 'SELECT NUMBER, AVAIL_BIKES, AVAIL_SLOTS FROM availability_new WHERE LAST_UPDATE = ?  AND DAYOFWK = ? AND NUMBER = ?';
echo "<br/> SELECT NUMBER, AVAIL_BIKES, AVAIL_SLOTS FROM availability_new WHERE LAST_UPDATE = " . $time ." AND DAYOFWK = " . $dayofwk . " AND NUMBER = " . $stat_id . "  </br>";
//$sql->bind_param('sii', $time, $dayofwk, $stat_id);
//$res = $conn->query($sql);
gettype($time);
gettype($dayofwk);
gettype($stat_id);


if ($res->num_rows > 0) {
    // output data of each row
    while($row = $res->fetch_assoc()) {
        echo "number: " . $row["number"]. " - avail_bikes: " . $row["avail_bikes"]. " " . $row["avail_slots"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();

?>


