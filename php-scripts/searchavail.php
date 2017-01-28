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

$sql = $conn->prepare("SELECT avail_bikes, avail_slots, number
//    FROM availability_new");
$result = $sql->execute();
echo "<br/> " . $result . " </br>";
if ($result > 0) {
    foreach ($result as $row) {
        $last = $row['avail_bikes'];
        $slots = $row['avail_slots'];
        $num = $dayMap[$row['number']];
        echo "<br/> " . $row . " </br>";
        
        
    }
        
    } 


//mysqli_close($conn);
?>


