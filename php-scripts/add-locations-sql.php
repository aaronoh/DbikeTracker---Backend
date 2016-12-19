<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




        // use prepare statement for insert query
        $st = mysqli_prepare($conn, 'INSERT INTO locations(number, name, lat, lng) VALUES (?, ?, ?, ?)');
        //bind the varibales
        mysqli_stmt_bind_param($st, 'isdd', $number, $name, $lat, $lng);

        // loop through the array
        foreach ($dbikeinfo as $row) {
            // get the locations details
            $number = $row['number'];
            $name = $row['name'];
            $lat = $row['position']['lat'];
            $lng = $row['position']['lng'];
            
//            echo '<pre>';
//            print_r($number);
//            print_r($name);
//            print_r($lat);
//            print_r($lng);
//            echo '</pre>';
            
            // execute insert query
            mysqli_stmt_execute($st);
        }