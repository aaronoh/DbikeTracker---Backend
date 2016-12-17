</html>

<!DOCTYPE HTML>
<html>
    <title>dbiketracker</title>
    <head>
        <?php
        //connect to ClearDB
        //gets the variables from the url and parses them to the variables
        $url = parse_url(getenv("mysql://b4c04b4b0847ac:bdfbd3f7@us-cdbr-iron-east-04.cleardb.net/heroku_1aebd2cf6f33fe1?reconnect=true"));
        
        $server = $url["us-cdbr-iron-east-04.cleardb.net"];
        $username = $url["b4c04b4b0847ac"];
        $password = $url["bdfbd3f7"];
        $db = substr($url["b4c04b4b0847ac"], 1);
        //create the conneciton
        $conn = new mysqli($server, $username, $password, $db);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        echo "Connected successfully";
        ?>
    </head>
</html>
