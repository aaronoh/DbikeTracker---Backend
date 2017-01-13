<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$st = mysqli_prepare($conn, 'INSERT INTO stations_copy(name, address, number, lat, lng) VALUES (?, ?, ?, ?, ?)');
//bind the varibales
mysqli_stmt_bind_param($st, 'ssiss', $name, $address, $number, $lat, $lng);

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

