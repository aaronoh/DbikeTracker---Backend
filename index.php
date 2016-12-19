</html>

<!DOCTYPE HTML>
<html>
    <title>dbiketracker</title>
    <head>
        <?php
        //connect to ClearDB
        //cleardb url mysql://b4c04b4b0847ac:bdfbd3f7@us-cdbr-iron-east-04.cleardb.net/heroku_1aebd2cf6f33fe1?reconnect=true
        //gets the variables from the url and parses them to the variables
        $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

        $server = $url["host"];
        $username = $url["user"];
        $password = $url["pass"];
        $db = substr($url["path"], 1);
        //create the conneciton
        $conn = new mysqli($server, $username, $password, $db);

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
//        print_r($dbikeinfo);
        //add each json object field to a php variable
        $number = $dbikeinfo['number'];
        $name = $dbikeinfo['name'];
        $address = $dbikeinfo['address'];
        $lat = $dbikeinfo['position']['lat'];
        $lng = $dbikeinfo['position']['lng'];
        $banking = $dbikeinfo['banking'];
        $status = $dbikeinfo['status'];
        $bikestands = $dbikeinfo['bike_stands'];
        $avail_bikestands = $dbikeinfo['available_bike_stands'];
        $avil_bikes = $dbikeinfo['available_bikes'];
        $timestamp = $dbikeinfo['last_update'];



        // use prepare statement for insert query
        $st = mysqli_prepare($conn, 'INSERT INTO locations(number, name, lat, lng) VALUES (?, ?, ?, ?)');
        //bind the varibales
        mysqli_stmt_bind_param($st, 'isdd', $number, $name, $lat, $lng);

        // loop through the array
        foreach ($dbikeinfo as $row) {
            // get the locations details
            $number = $row['number'];
            $name = $row['name'];
            $lat = $row['lat'];
            $lng = $row['lng'];

            // execute insert query
            mysqli_stmt_execute($st);
        }

        //close connection
        mysqli_close($con);
        ?>
    </head>
</html>
