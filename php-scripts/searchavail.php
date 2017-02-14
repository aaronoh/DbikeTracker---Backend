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

//for my percentile take in an array and a int as a percentile
function mypercentile($bikes,$percentile){ 
    //if the percentile is between 0 & 1 (0.1-0.99)
    if( 0 < $percentile && $percentile < 1 ) {
        //the p object is just equal to the percentile
        $p = $percentile; 
    }
    //else if the percentile is between 1 & 100
    else if( 1 < $percentile && $percentile <= 100 ) { 
        //convert the percentile
        $p = $percentile * .01; 
    }
    //else return nothing
    else { 
        return ""; 
    } 
    //count the size of the array input
    $count = count($bikes); 
    //get all the indexes and multiple by the percentile
    $allindex = ($count-1)*$p;
    //get the value of the index
    $intvalindex = intval($allindex); 
    //remove the outliers
    $floatval = $allindex - $intvalindex; 
    //sort the array 
    sort($bikes); 
    //if the value is not a float
    if(!is_float($floatval)){
        $percent_result = $bikes[$intvalindex]; 
    }else { 
        //if the size of the array is greater than the value index + 1 
        if($count > $intvalindex+1) {
            //the result is the float value multipled by the top value of the array, minus the current index and then add the bikes index
            $percent_result = $floatval*($bikes[$intvalindex+1] - $bikes[$intvalindex]) + $bikes[$intvalindex]; 
        }
        else {
            //the percent result is equal to a value of the bikes array
            $percent_result = $bikes[$intvalindex];
        }
    } 
    //return the percent result
    return $percent_result; 
} 
//echo results
echo "95 percentile " . mypercentile($bikes, 95) . " ";
echo " " . 
    "5 percentile " . mypercentile($bikes, 5) . " ";

$meanforgood;
// Function to calculate mean    
function mean($array) {
    //first count the size of the array
    $count = count($array);
    $mean = array_sum($array) / $count;
    
    $meanforgood = $mean;
    return $mean;    
}
//echo the mean to the html
echo " " . 
    " Mean is " . mean($bikes);
//function for standard deviation
    function stats_standard_deviation(array $a, $sample = false) {
        $n = count($a);
        if ($n === 0) {
            trigger_error("The array has zero elements", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("The array has only 1 element", E_USER_WARNING);
            return false;
        }
        $mean = $meanforgood;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = ((double) $val) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
           --$n;
        }
        return sqrt($carry / $n);
    }
//echo the standard deviation in the html
eecho " " . 
    " Standard deviation is " . stats_standard_deviation($bikes);


$conn->close();
?>