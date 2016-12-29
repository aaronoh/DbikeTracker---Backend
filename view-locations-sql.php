<?php
//cleardb url mysql://b4c04b4b0847ac:bdfbd3f7@us-cdbr-iron-east-04.cleardb.net/heroku_1aebd2cf6f33fe1?reconnect=true
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
$conn = mysqli_connect($server, $username, $password, $db) or die('Error in Connecting: ' . mysqli_error($conn));
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

if ($result = $conn->query("SELECT * FROM locations")) {
    $data_array = array();
    while($data = mysqli_fetch_assoc($result)){ 
//        $data_array[] = $data;
          echo $_GET['jsoncallback'] . '(' . json_encode($data) . ');';
    
    }    
    

  
    $data->close();
    }
?>
