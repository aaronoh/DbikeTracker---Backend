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
echo "Connected successfully" . "<br>";
$time = $_POST['time'];
echo $time . " ";
//$_POST['date']->format('Y-m-d'); // output = 2017-01-01
$dayofwk = $_POST['date'];
$dayofwk = date('w');
echo $dayofwk . " ";
$stat_id = $_POST['statnum'];
echo $stat_id . " ";


$sql = "SELECT number, avail_bikes, avail_slots FROM availability_new WHERE DAYOFWK = '$dayofwk' AND LAST_UPDATE = '$time' AND NUMBER = '$stat_id'";
$result = $conn->query($sql);


echo $sql . "\n";
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo $row["avail_bikes"] . "\n";
        echo $row['avail_slots'] . "\n";
        
        echo join('<br>', $row['avail_bikes']);
        
        print("Unsorted array:<br/>");
        print_r($row["avail_bikes"]);
        arsort($row["avail_bikes"]);
        print("<br/>");
        print("Sorted array:<br/>");
        print_r($row);
        print("<br/>");

        $i = 0;
        $total = count($row["avail_bikes"]);
        $percentiles = array();
        $previousValue = -1;
        $previousPercentile = -1;
        foreach ($row["avail_bikes"] as $key => $value) {
            echo "\$row[$key] => $value";
            if ($previousValue == $value) {
                $percentile = $previousPercentile;
            } else {
                $percentile = 99 - $i * 100 / $total;
                $previousPercentile = $percentile;
            }
            $percentiles[$key] = $percentile;
            $previousValue = $value;
            $i++;
        }

        print("Percentiles:<br/>");
        print_r($percentiles);
        print("<br/>");
    }
} else {
    echo "0 results";
}



//function mypercentile($data, $percentile) {
//    if (0 < $percentile && $percentile < 1) {
//        $p = $percentile;
//    } else if (1 < $percentile && $percentile <= 100) {
//        $p = $percentile * .01;
//    } else {
//        return "";
//    }
//    $count = count($data);
//    $allindex = ($count - 1) * $p;
//    $intvalindex = intval($allindex);
//    $floatval = $allindex - $intvalindex;
//    sort($data);
//    if (!is_float($floatval)) {
//        $percentile_res = $data[$intvalindex];
//    } else {
//        if ($count > $intvalindex + 1){ 
//            $percentile_res = $floatval * ($data[$intvalindex + 1] - $data[$intvalindex]) + $data[$intvalindex];
//        }
//        else {
//            $percentile_res = $data[$intvalindex];
//        }
//    }
//    return $percentile_res;
//}
//mypercentile($data, $percentile);
$conn->close();
?>