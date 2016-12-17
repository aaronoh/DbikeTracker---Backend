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
        $db = substr($url["heroku_1aebd2cf6f33fe1"], 1);
        //create the conneciton
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        ?>
    </head>
</html>
