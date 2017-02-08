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
$date = date_create($_POST['date']);
$intofwk = date_format($date, "w");
echo $intofwk . " ";
$stat_id = $_POST['statnum'];
echo $stat_id . " ";


$sql = "SELECT number, avail_bikes, avail_slots FROM availability_new WHERE DAYOFWK = '$intofwk' AND LAST_UPDATE = '$time' AND NUMBER = '$stat_id'";
$result = $conn->query($sql);


echo $sql . "\n";
if ($result->num_rows > 0) {
    $bikes = array();
    // output data of each row
    while ($row = $result->fetch_assoc()) {
//        echo $row["avail_bikes"] . "\n";
//        echo $row['avail_slots'] . "\n";


        array_push($bikes, $row['avail_bikes']);
    }
} else {
    echo "0 results";
}
print_r($bikes);
//function for working out the percentile
function get_percentile($percentile, $bikes) {
    sort($bikes);
    $index = ($percentile/100) * count($bikes);
    if (floor($index) == $index) {
         $result = ($bikes[$index-1] + $bikes[$index])/2;
    }
    else {
        $result = $bikes[floor($index)];
    }
    return $result;
}



echo "70th %tile" . get_percentile(70, $bikes) . " ";
echo "95th %tile" . get_percentile(95, $bikes) . " " . "<br>";
function mypercentile($bikes,$percentile){ 
    if( 0 < $percentile && $percentile < 1 ) { 
        $p = $percentile; 
    }else if( 1 < $percentile && $percentile <= 100 ) { 
        $p = $percentile * .01; 
    }else { 
        return ""; 
    } 
    $count = count($bikes); 
    $allindex = ($count-1)*$p; 
    $intvalindex = intval($allindex); 
    $floatval = $allindex - $intvalindex; 
    sort($bikes); 
    if(!is_float($floatval)){ 
        $percent_result = $data[$intvalindex]; 
    }else { 
        if($count > $intvalindex+1) 
            $percent_result = $floatval*($bikes[$intvalindex+1] - $bikes[$intvalindex]) + $bikes[$intvalindex]; 
        else 
            $percent_result = $bikes[$intvalindex]; 
    } 
    return $percent_result; 
} 

echo "95 percentile " . mypercentile($bikes, 97.5) . " ";
echo "2.5 percentile " . mypercentile($bikes, 2.5) . " ";



$conn->close();
?>