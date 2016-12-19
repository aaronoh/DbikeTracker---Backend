<?php

//array for the locations and name of the stations
        $location_name = array();
        $position = array();
        $uri = "mongodb://heroku_2g7zhsrw:fptu2g7faerobjk513p7frl9sq@ds013222.mlab.com:13222/heroku_2g7zhsrw";
        $conn = new Mongo($uri);
        $db = $conn->heroku_2g7zhsrw;
        echo (" **Connection to database successful** ");
        echo($conn);
        $collection = $db->locations;
        //count the size of the array
        $size = count($dbikeinfo);

        for ($i = 0; $i < count($dbikeinfo); $i++) {
            $position = array($dbikeinfo[$i]['position']);
            $location_name = array($dbikeinfo[$i]['name']);
//            print_r($position);
//            print_r($location_name);
            $merged = array_merge($location_name, $position);
            print_r($merged);

            
            
            $collection->insert($merged);
            var_dump($ref);
        }

        echo " **Station database selected**  <br><br>";




