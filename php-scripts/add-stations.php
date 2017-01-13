<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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

$st = mysqli_prepare($conn, 'INSERT INTO stations_copy(name, address, number, lat, lng) VALUES (?, ?, ?, ?, ?)');
//bind the varibales
mysqli_stmt_bind_param($st, 'ssidd', $name, $address, $number, $lat, $lng);

// loop through the array
foreach ($dbikeinfo as $row) {
    // get the locations details
    $name = $row['name'];
    $address = $row['address'];
    $number = $row['number'];
    $lat = $row['position']['lat'];
    $lng = $row['position']['lng'];

//            echo '<pre>';
//            print_r($number);
//            print_r($name);
//            print_r($address);
//            echo '</pre>';
    // execute insert query
    mysqli_stmt_execute($st);
}




//close connection
mysqli_close($conn);

