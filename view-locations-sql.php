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

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/* return name of current default database */
if ($result = mysqli_query($conn, "SELECT DATABASE()")) {
    $row = mysqli_fetch_row($result);
    printf("Default database is %s.\n", $row[0]);
    mysqli_free_result($result);
}
$st = mysqli_prepare($conn, 'SELECT * FROM locations');
$result = mysqli_query($st);
        //mysqli_stmt_execute($st) or die ("Query error: " . mysqli_error());
var_dump($results);

mysqli_close($conn);


// use prepare statement for insert query
//        $st = mysqli_prepare($conn, 'SELECT * FROM locations');
//$result = mysqli_stmt_execute($st) or die ("Query error: " . mysqli_error());
//		$loactions = array();
//		while($row = mysqli_fetch_assoc($result)){
//		$locations[] = $row;
//		}
//                mysqli_close($conn);
//                echo $_GET['jsoncallback'] . '(' . json_encode($result) . ');'; 
//        $result = mysqli_fetch_row($st);
//        echo json_encode($result);
?>