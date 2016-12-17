</html>

<!DOCTYPE HTML>
<html>
    <title>dbiketracker</title>
    <head>
        <?php
        //connect to ClearDB
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
//        try {
//            $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
//            // set the PDO error mode to exception
//            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//            echo "Connected successfully";
//        } catch (PDOException $e) {
//            echo "Connection failed: " . $e->getMessage();
//        }
        ?>
    </head>
</html>
