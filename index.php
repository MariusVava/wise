<?php
$starttime = microtime(true);
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags for bootstrap -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>WISE INFO</title>
    <!-- table sorting script -->
    <script type="text/javascript" src="scripts\sort-table.js"></script>
</head>
  <body>
<?php
#get USD price from google
$url_eth = "https://www.google.com/search?q=1+eth+in+usd&oq=1+eth+in+usd&aqs=chrome..69i57.3670j0j7&sourceid=chrome&ie=UTF-8";
$coindesk = file_get_contents($url_eth);
preg_match('!<div><div><div><div class="BNeawe iBp4i AP7Wnd"><div><div class="BNeawe iBp4i AP7Wnd">(.*?)dolar american</div>!', $coindesk, $match_eth_price);
$eth_price_kkt = $match_eth_price[1];
#load data from snapshots
$dir = "data";
chdir($dir);
#match snapshot date/time creation to get the newest snapshot
array_multisort(array_map('filemtime', ($files = glob("*.*"))), SORT_DESC, $files);
$newest_file = $files[0];
#define hours button standard color
$h24 = "btn-danger";
$h12 = "btn-danger";
$h6 = "btn-danger";
$h2 = "btn-danger";
$h1 = "btn-danger";
$button_custom_hours = '';
#read hours for which to generate the info
if (isset($_GET["hours"])){
    $timeframe = $_GET["hours"];
    if ($timeframe == 12){
        $previous_file = $files[239];
        $h12 = "btn-success";
    }
    elseif ($timeframe == 6){
            $previous_file = $files[119];
            $h6 = "btn-success";
        }
        else {
            if ($timeframe == 2){
                $previous_file = $files[39];
                $h2 = "btn-success";
            }
            else {
                if ($timeframe == 1){
                    $previous_file = $files[19];
                    $h1 = "btn-success";
                }
                else {
                  if ($timeframe == 24){
                    $h24 = "btn-success";
                  }
                  else{
                    $button_custom_hours = '<a class="btn btn-success" href="?hours='.$_GET["hours"].'"> '.$_GET["hours"].' hours</a>';
                  }
                    $hour_file = $_GET["hours"]*60/3;
                    $previous_file = $files[$hour_file]; 
                    $timeframe = $_GET["hours"];
                }
            }
        }
}
else {
    $previous_file = $files[479]; 
    $timeframe = 24;
    $h24 = "btn-success";
}
#edit filenames for info segment to display snapshot name new/old
$last_update = str_replace("WISE Token - The Smartest Way to Earn Crypto (","",$newest_file);
$last_update = str_replace(").html","",$last_update);
$last_update_previous = str_replace("WISE Token - The Smartest Way to Earn Crypto (","",$previous_file);
$last_update_previous = str_replace(").html","",$last_update_previous);
$folder = 'http://acidripp-home.ddns.net/wise/data/';
$newest_file = str_replace(" ","%20",$folder.$newest_file);
$previous_file = str_replace(" ","%20",$folder.$previous_file);
#read the 2 snapshots to compare data
$content = file_get_contents($newest_file);
$content_previous = file_get_contents($previous_file);
#get ethereum total for each day from both snapshots
preg_match_all('!<div class="mt-3 line-height-sm"><b class="font-size-xl pb-2">(.*?)</b><span class="text-black-50 d-block">total ether</span></div>!', $content, $match_eth);
$total_eth = $match_eth;
preg_match_all('!<div class="mt-3 line-height-sm"><b class="font-size-xl pb-2">(.*?)</b><span class="text-black-50 d-block">total ether</span></div>!', $content_previous, $match_eth_previous);
$total_eth_previous = $match_eth_previous;
#get day
preg_match_all('!<span class="font-size-md text-black font-weight-bold"><div>(.*?)</div>!', $content, $match_date);
$date = $match_date;
#get number of users that invested in that day
preg_match_all('!<div class="mt-3 line-height-sm"><b class=font-size-lg>(.*?)</b><span class="text-black-50 d-block">total users</span>!', $content, $match_users);
$investors = $match_users;
preg_match_all('!<div class="mt-3 line-height-sm"><b class=font-size-lg>(.*?)</b><span class="text-black-50 d-block">total users</span>!', $content_previous, $match_users_previous);
$investors_previous = $match_users_previous;
#calculate total user investments for all days - part of info
$total_users = 0;
for($i = 0; $i < count($total_eth[1]); $i++) {
    $total_users += $investors[1][$i];
}
#get total ethereum invested for info
preg_match('!current balance: (.*?) ETH!', $content, $eth_all_time);
$eth_all_time = $eth_all_time[1];
#calculate total wise available estimating 5M per day for info
$total_wise = 5000000*50;
#calculate estimate value of total wise by total ethereum for info
$wise_value_estimate = $total_wise/str_replace(",","",$eth_all_time);
$wise_value_estimate = round($wise_value_estimate, 4);
#get current time for info
$t=time();
$date_now = date("d-M-Y h:i",$t);
#calculate estimate of ethereum invested by user for info
$eth_user = str_replace(",","",$eth_all_time)/$total_users;
$eth_user = round($eth_user, 4);
#calculate how many days left to invest for progressbar
$days_left = (new DateTime('2020-12-30'))->diff(new DateTime())->days;
$time_invest = $days_left+1;
$days_left = $time_invest/50;
$days_left = $days_left*100;
$closed_day = $time_invest+2;
#calculate estimate of how many users invest dayly
$users_day = round($total_users/50, 0);
?>
&nbsp;
<!-- time left to invest -->
<div class="progress">
  <div class="progress-bar bg-info" style="width: <?php echo $days_left; ?>%" role="progressbar" aria-valuenow="<?php echo $days_left; ?>" aria-valuemin="0" aria-valuemax="50">DAYS LEFT: <?php echo $time_invest+1; ?></div>
</div>
<!-- menu -->
<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-info btn-lg btn-block collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        [MORE INFO]
        </button>
      </h2>
    </div>
    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
        <div class="card-body">
        <div class="alert alert-danger" role="alert"><center>snapshot <strong><?php echo $last_update ?></strong> vs <strong><?php echo $last_update_previous ?></strong></center></div>
        <div class="alert alert-warning" role="alert">
  <!-- INFO -->
    <center>
  <?php
    echo "Total ETH invested: <strong>".str_replace(",","",$eth_all_time)." ETH</strong> by <strong>".$total_users." USERS</strong> | ~ETH/USER: <strong>".$eth_user." ETH</strong> | ~<strong>".$users_day."</strong> USERS/DAY";
  ?>
    </center>
</div>
<div class="alert alert-info" role="alert">
  <center>
  <?php
  echo "<strong>1 ETH</strong> valued at <strong>~".round($wise_value_estimate, 2)." WISE</strong> or <strong>".str_replace(",",".",$eth_price_kkt)." USD</strong>";
  ?>
  <center>
  <!-- end of INFO -->
</div>
        </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
<button class="btn btn-info btn-lg btn-block collapsed" onclick="myFunction()">[REFRESH DATA]</button>
<script>
function myFunction() {
    location.reload();
}
</script>
</h2>
</div>
</div>
<div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
      <a class="btn btn-info btn-lg btn-block collapsed" href="wallets.php" role="button">[WISE WAL(L)E(T)S]</a>
</div>
</div>
</div>
<!-- end of menu -->
&nbsp;
<div class="container">
<!-- hour display selector -->
<form action="/wise" method="get">
Displaying <span class="badge badge-dark">STATISTICAL INFORMATION</span> from the past:  
  <?php echo $button_custom_hours; ?>
  <a class="btn <?php echo $h24; ?>" href="?hours=24"> 24 hours
  </a>
  <a class="btn <?php echo $h12; ?>" href="?hours=12"> 12 hours
  </a>
  <a class="btn <?php echo $h6; ?>" href="?hours=6"> 6 hours
  </a>
  <a class="btn <?php echo $h2; ?>" href="?hours=2">2 hours
  </a>
  <a class="btn <?php echo $h1; ?>" href="?hours=1">1 hours
  </a>
  <input type="text" placeholder="custom" maxlength="4" size="4" style="width: 100px;" name="hours">&nbsp;<button class="btn btn-danger" type="submit">WIEW</button>
  </form>
<!-- end of hours -->
</div>
&nbsp;
<div class="container-fluid">
<!-- main information table -->
<table class="table table-striped table-dark table-hover js-sort-table" id="wise_table">
  <thead>
    <tr>
      <th scope="col">STATUS</th>
      <th scope="col">DAYS</th>
      <th class="js-sort-number" scope="col">NO. of USERS</th>
      <th class="js-sort-number" scope="col">NEW USERS</th>
      <th class="js-sort-number" scope="col">TOTAL ETH</th>
      <th class="js-sort-number" scope="col">ETH ADDED</th>
      <th class="js-sort-number" scope="col">SIMULATE</th>
      <th class="js-sort-number" scope="col">1 ETH VALUE</th>
      <th class="js-sort-number" scope="col">1 ETH CLOSED</th>
      <th class="js-sort-number" scope="col">1 WISE VALUE</th>
      <th class="js-sort-number" scope="col">ROI</th>
    </tr>
  </thead>
  <tbody>
<?php
#define green rows for investing (ROI)
$value_multiplyer = 1.3;
#calculate ROI based on average of all wise divided by all ethereum
$wise_multiplyer = $value_multiplyer*$wise_value_estimate;
$wise_worth_buying = round($wise_multiplyer, 4);
$eth_value_all = 0;
$chart_wise_value = "remove";
#start to generate data for each day
for($i = 0; $i < count($total_eth[1]); $i++) {
    #calculate daily eth value
    $eth_value = 5000000/$total_eth[1][$i];
    $eth_value = round($eth_value, 4);   
    #calculate ROI and color table row 
    if ($eth_value < $wise_value_estimate){
        $tr = '<tr class="table-danger">';
    }
    else {
        if ($eth_value > $wise_worth_buying){
            $tr = '<tr class="table-success">';
        }
        else{
        $tr = "<tr>";
    }
    }
    #calculate if day is closed
    $close = 50-$closed_day;
    $badge = '<td style="text-align:center;vertical-align:middle"><span class="badge badge-success">OPEN</span></td>';
    if ($i <= $close){
        $tr = '<tr class="bg-danger">';
        $badge = '<td style="text-align:center;vertical-align:middle"><span class="badge badge-warning">CLOSED</span></td>';
        $simulate_view = '<td style="text-align:left;vertical-align:middle">&nbsp;</td>';
    }
    else {
        $tr = $tr;
        $simulate_view = '<td style="text-align:left;vertical-align:middle">
        <div class="input-group-sm mb-3">
            <input name="day'.$i.'" type="text" class="form-control" placeholder="+ETH" size="4">
        </div>
        </td>';
    }
    #display table data
    #display row color
    echo $tr;
    #display if open or closed
    echo $badge;
    #display the day number and date
    echo  '<th style="vertical-align:middle" scope="row">'.str_replace(" - Day #"," (",str_replace("/",".",$date[1][$i])).')</th>';
    #calculate how many users have invested since last snapshot compared to
    $user_diff = $investors[1][$i] - $investors_previous[1][$i];
    if ($user_diff > 0){
        $user_diff = '<span class="badge badge-pill badge-success">+ '.$user_diff.'</span>';
        #$user_diff = $user_diff;
    }
    else {
        $user_diff = "";
    }
    #display total investors for that day
    echo  '<td style="text-align:right;vertical-align:middle">'.$investors[1][$i].'</td>';
    #display new investors from snapshot comparrison
    echo '<td style="text-align:left;vertical-align:middle">'.$user_diff.'</td>';
    #calculate the difference of ETH invested compared to snapshot
    $eth_diff = round($total_eth[1][$i] - $total_eth_previous[1][$i], 2);
    if ($eth_diff > 0){
        $eth_diff = '<span class="badge badge-pill badge-success">+ '.$eth_diff.'</span>';
    }
    else {
        $eth_diff = "";
    }
    #display ETH invested in that day
    echo  '<td style="text-align:right;vertical-align:middle">'.$total_eth[1][$i].' <span class="badge badge-light">ETH</span></td>';
    #display ETH invested since snapshot used to compare
    echo '<td style="text-align:left;vertical-align:middle">'.$eth_diff.'</td>';
    #simulate ETH investment - NOT WORKING
    echo $simulate_view;
    #display how much WISE is 1 ETH worth for that day
    echo  '<td style="vertical-align:middle">'.$eth_value.' <span class="badge badge-light">WISE</span></td>';
    #calculate ETH value based on days closed before this day (divide all ETH by sum of WISE for all days prior)
    $eth_value_all += $total_eth[1][$i];
    $wise_value = $total_eth[1][$i]/5000000;
    $wise_value = number_format($wise_value, 9);
    $eth_value_closed = round(($i+1)*5000000/$eth_value_all, 4);
    #display closed ETH value
    echo  '<td style="vertical-align:middle">'.$eth_value_closed.' <span class="badge badge-light">WISE</span></td>';  
    #display how much ETH is a WISE worth
    echo  '<td style="vertical-align:middle">'.$wise_value.' <span class="badge badge-light">ETH</span></td>';
    #calculate ROI
    $roi = $eth_value/$wise_value_estimate;
    $roi = round($roi, 1);
    #display ROI
    echo  '<td style="vertical-align:middle">'.$roi.' X</td>';
    #end of day data
    echo "</tr>";
    #create chart data for WISE
    $wise_value_chart = $wise_value*5000000;
    $chart_wise_value = $chart_wise_value.", ".$wise_value_chart;
}
#cleanup chart data for wise
$chart_wise_value = str_replace("remove, ","",$chart_wise_value);
?>
</tbody>
</table>
<!-- end of table -->
</div>
&nbsp;
<!-- charts -->
<div class="container-fluid">
    <div class="row my-3">
        <div class="col">
            <center><h4><span class="badge badge-primary"><strong>EOS/HEX</strong></span> trending hystorical data vs <span class="badge badge-success"><strong>WISE</strong></span> live data</h4></center>
            <!-- chart legend/information -->
            <br /><h6><span class="badge badge-primary">+</span> <strong>EOS</strong> volume traded <strong>in USD</strong> divided by 30.000 for scale, 1 point = 7 days</h6>
            <br /><h6><span class="badge badge-danger">+</span> <strong>ETH</strong> value <strong>in HEX</strong> divided by 200 for scale, 1 point = 7 days</h6>
            <br /><h6><span class="badge badge-success">+</span> <strong>WISE</strong> value <strong>in ETH</strong> multiplied by 5.000.000 for scale</h6>
        </div>
    </div>
    <div class="row my-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                <canvas id="chLine" style="display: block"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CHART SCRIPT -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.13.0/umd/popper.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>
<script>
// chart colors
var colors = ['#007bff','#28a745','#333333','#c3e6cb','#dc3545','#6c757d', '#ff33c7'];
/* large line chart */
var chLine = document.getElementById("chLine");
var chartData = {
  labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50'],
  /* EOS DATA */
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
              #due to the fact that wise goes for 50 days and EOS went for 350, we do value for 7 days
              if($i % 7 == 0){
                $sum += $val['volume'];
                if ($i == 7){
                  $eos_values=$eos_values.$sum/7/30000;
                }
                else {
                  $eos_values=$eos_values.", ".$sum/7/30000;
                }
                $sum = 0;
              }
              else{
                $sum += $val['volume'];
              }
            $i++;
            }
  $db->close();
  ?>
  datasets: [{
    data: [<?php echo $eos_values; ?>],
    backgroundColor: 'transparent',
    borderColor: colors[0],
    borderWidth: 4,
    pointBackgroundColor: colors[0]
  },
  /* WISE DATA */
  {
    data: [<?php echo $chart_wise_value; ?>],
    backgroundColor: 'transparent',
    borderColor: colors[1],
    borderWidth: 4,
    pointBackgroundColor: colors[1]
  },
  /* HEX DATA */  
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
  $result = $db->query("SELECT * FROM hex_launch2");
            $i = 1;
            $sum = 0;
            $hex_values="";
            while($val = $result->fetch_assoc()){
              #do the same as for EOS and get value of every 7 days
              if($i % 7 == 0){
                $sum += $val['usd_in_hex'];
                if ($i == 7){
                  $hex_values=$hex_values.$sum/7*10000000;
                }
                else {
                  $hex_values=$hex_values.", ".$sum/7*10000000;
                }
                $sum = 0;
              }
              else{
                $sum += $val['eth_in_hex'];
              }
            $i++;
            }
  $db->close();
  ?>
  {
  data: [<?php echo $hex_values; ?>],
    backgroundColor: 'transparent',
    borderColor: colors[6],
    borderWidth: 4,
    pointBackgroundColor: colors[6]
  }]
};
if (chLine) {
  new Chart(chLine, {
  type: 'line',
  data: chartData,
  options: {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: false
        }
      }]
    },
    legend: {
      display: false
    }
  }
  });
}
</script>
<!-- end of charts -->
<!-- javascript -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<?php
#calculate page load times
$endtime = microtime(true);
?>
<div class="alert alert-light" role="alert">
  <center>
    <?php printf("Page loaded in %f seconds", $endtime - $starttime ); ?>
  </center>
</div>
</body>
</html>