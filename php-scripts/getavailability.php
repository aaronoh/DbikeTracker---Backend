<?php
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
?>
<html>
    <head>
        <title>Get Availability</title>

    </head>
    <body>

        <form method="post" action="submit.php">
            Select a time:
            <select name="time"></input>
                <option name=""now><?php strtotime('now') ?></option>
                <option name="1">07:00:00</option>
                <option name="2">10:00:00</option>
                <option name="3">13:00:00</option>
                <option name="4">17:00:00</option>
            </select>
            <br>
            Select a date:
            <select name="date"></input>
                <option name="day1">2017-02-01</option>
                <option name="day2">2017-02-14</option>
                <option name="day3">2017-02-28</option>
                <option name="day4">2017-03-01</option>
        </form>



    </body>


</html>

