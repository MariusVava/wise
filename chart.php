<?php
  error_reporting(0);
  define('DB_NAME', 'wise'); //Your DB Name
  define('DB_USER', 'wise'); //Your DB User Name
  define('DB_PASSWORD', 'wise'); //Your DB Password
  define('DB_HOST', 'localhost'); //Your Host Name
    
  // Create connection
  $db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  // Check connection
  if ($db->connect_error) {
      die("Connection failed: " . $db->connect_error);
  }

  $result = $db->query("SELECT * FROM eos_chart");
            $i = 1;
            $sum = 0;
            $eos_values="";
            while($val = $result->fetch_assoc()){
                #print_r($val);
              if($i % 7 == 0){
                $sum += $val['volume'];
                #echo "<br />$i  is divisible by 7";
                if ($i == 7){
                  $eos_values=$eos_values.$sum;
                  #echo $eos_values;
                }
                else {
                  $eos_values=$eos_values.", ".$sum;
                  #echo $eos_values;
                }
                $sum = 0;
              }
              else{
                $sum += $val['volume'];
                #echo "<br />$i <strong>is NOT divisible by 7</strong>";
                #echo $eos_values;
              }
            $i++;
            }
  $db->close();
  ?>