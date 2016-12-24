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
        $st = mysqli_prepare($conn, 'SELECT * From locations');
		$result = mysqli_stmt_execute($st);

		$loactions = array();

		while($row = mysqli_fetch_assoc($result)){
		$locations[] = $row;
    echo "test";
		}
		mysqli_close($conn)


        // loop through the array
        // foreach ($dbikeinfo as $row) {
            // get the locations details
             // $row['number'] = $number;
             // $row['name'] = $name;
             // $row['position']['lat'] = $lat;
             // $row['position']['lng'] $lng = ;

            // echo '<pre>';
            // print_r($number);
            // print_r($name);
            // print_r($lat);
            // print_r($lng);
            // echo '</pre>';

            //execute insert query
            // mysqli_stmt_execute($st);
        // }
?>
