<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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

// use prepare statement for insert query
//        $st = mysqli_prepare($conn, 'SELECT * FROM locations');
//$result = mysqli_stmt_execute($st) or die ("Query error: " . mysqli_error());
//		$loactions = array();
//		while($row = mysqli_fetch_assoc($result)){
//		$locations[] = $row;
//		}
//                mysqli_close($conn);
//                echo $_GET['jsoncallback'] . '(' . json_encode($result) . ');';




$st = mysqli_prepare($conn, 'SELECT NUMBER,NAME FROM locations');
$result = mysqli_stmt_execute($st) or die("Query error: " . mysqli_error());
if (!$result) {
    echo "Cannot do query" . "<br/>";
    exit;
}
$row = mysqli_fetch_row($result);
$count = $row[0];

if ($count > 0) {
    echo "Query works";
} else {
    echo "Query doesn't work" . "<br/>";
}

$num_results = mysqli_num_rows($result);

for ($i = 0; $i < $num_results; $i++) {
    $row = mysqli_fetch_assoc($result);
    print_r($row);
    //echo '<img src="' . $row['Image'] . '>';
    //echo "<br/>" . "Price: " . stripslashes($row['Price']);
}


//        $result = mysqli_fetch_row($st);
//        echo json_encode($result);
?>
