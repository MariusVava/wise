<?php
$starttime = microtime(true);
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>WISE INFO</title>

    <script type="text/javascript" src="scripts\sort-table.js"></script>

</head>
  <body>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>


<?php
$url_eth = "https://www.google.com/search?q=1+eth+in+usd&oq=1+eth+in+usd&aqs=chrome..69i57.3670j0j7&sourceid=chrome&ie=UTF-8";
$coindesk = file_get_contents($url_eth);
preg_match('!<div><div><div><div class="BNeawe iBp4i AP7Wnd"><div><div class="BNeawe iBp4i AP7Wnd">(.*?)dolar american</div>!', $coindesk, $match_eth_price);
$eth_price_kkt = $match_eth_price[1];

$files = scandir('data', SCANDIR_SORT_DESCENDING);
$newest_file = $files[0];

$h24 = "btn-danger";
$h12 = "btn-danger";
$h6 = "btn-danger";
$h2 = "btn-danger";
$h1 = "btn-danger";
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
                    $previous_file = $files[479]; 
                    $timeframe = 24;
                    $h24 = "btn-success";
                }
            }
        }
}
else {
    $previous_file = $files[479]; 
    $timeframe = 24;
    $h24 = "btn-success";
}
$last_update = str_replace("WISE Token - The Smartest Way to Earn Crypto (","",$newest_file);
$last_update = str_replace(").html","",$last_update);
$last_update_previous = str_replace("WISE Token - The Smartest Way to Earn Crypto (","",$previous_file);
$last_update_previous = str_replace(").html","",$last_update_previous);
$folder = 'data\\';
$newest_file = $folder.$newest_file;
$previous_file = $folder.$previous_file;

$content = file_get_contents($newest_file);
$content_previous = file_get_contents($previous_file);

preg_match_all('!<div class="mt-3 line-height-sm"><b class="font-size-xl pb-2">(.*?)</b><span class="text-black-50 d-block">total ether</span></div>!', $content, $match_eth);
$total_eth = $match_eth;
preg_match_all('!<div class="mt-3 line-height-sm"><b class="font-size-xl pb-2">(.*?)</b><span class="text-black-50 d-block">total ether</span></div>!', $content_previous, $match_eth_previous);
$total_eth_previous = $match_eth_previous;

preg_match_all('!<span class="font-size-md text-black font-weight-bold"><div>(.*?)</div>!', $content, $match_date);
$date = $match_date;

preg_match_all('!<div class="mt-3 line-height-sm"><b class=font-size-lg>(.*?)</b><span class="text-black-50 d-block">total users</span>!', $content, $match_users);
$investors = $match_users;
preg_match_all('!<div class="mt-3 line-height-sm"><b class=font-size-lg>(.*?)</b><span class="text-black-50 d-block">total users</span>!', $content_previous, $match_users_previous);
$investors_previous = $match_users_previous;

$total_users = 0;
for($i = 0; $i < count($total_eth[1]); $i++) {
    $total_users += $investors[1][$i];
}
preg_match('!current balance: (.*?) ETH!', $content, $eth_all_time);
$eth_all_time = $eth_all_time[1];
$referrals = 543;
$total_wise = 5000000*50+$referrals;
$wise_value_estimate = $total_wise/str_replace(",","",$eth_all_time);
$wise_value_estimate = round($wise_value_estimate, 4);
$t=time();
$date_now = date("d-M-Y h:i",$t);

$eth_user = str_replace(",","",$eth_all_time)/$total_users;
$eth_user = round($eth_user, 4);
?>

<?php
$days_left = (new DateTime('2020-12-30'))->diff(new DateTime())->days;
$time_invest = $days_left+1;
$days_left = $time_invest/50;
$days_left = $days_left*100;
$closed_day = $time_invest+2;
$users_day = round($total_users/50, 0);
?>
&nbsp;
<div class="progress">
  <div class="progress-bar bg-info" style="width: <?php echo $days_left; ?>%" role="progressbar" aria-valuenow="<?php echo $days_left; ?>" aria-valuemin="0" aria-valuemax="50">DAYS LEFT: <?php echo $time_invest+1; ?></div>
</div>
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
&nbsp;
<div class="container">
Displaying <span class="badge badge-dark">STATISTICAL INFORMATION</span> from the past:  
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
</div>
&nbsp;
<div class="container-fluid">
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
$value_multiplyer = 1.3;
$wise_multiplyer = $value_multiplyer*$wise_value_estimate;
$wise_worth_buying = round($wise_multiplyer, 4);
$eth_value_all = 0;
for($i = 0; $i < count($total_eth[1]); $i++) {

    $eth_value = 5000000/$total_eth[1][$i];
    $eth_value = round($eth_value, 4);
    
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
    echo $tr;
    echo $badge;
    echo  '<th style="vertical-align:middle" scope="row">'.str_replace(" - Day #"," (",str_replace("/",".",$date[1][$i])).')</th>';

    $user_diff = $investors[1][$i] - $investors_previous[1][$i];
    if ($user_diff > 0){
        $user_diff = '<span class="badge badge-pill badge-success">+ '.$user_diff.'</span>';
        #$user_diff = $user_diff;
    }
    else {
        $user_diff = "";
    }

    echo  '<td style="text-align:right;vertical-align:middle">'.$investors[1][$i].'</td>';
    echo '<td style="text-align:left;vertical-align:middle">'.$user_diff.'</td>';

    $eth_diff = round($total_eth[1][$i] - $total_eth_previous[1][$i], 2);
    if ($eth_diff > 0){
        $eth_diff = '<span class="badge badge-pill badge-success">+ '.$eth_diff.'</span>';
        #$eth_diff = $eth_diff;
    }
    else {
        $eth_diff = "";
    }
    echo  '<td style="text-align:right;vertical-align:middle">'.$total_eth[1][$i].' <span class="badge badge-light">ETH</span></td>';
    echo '<td style="text-align:left;vertical-align:middle">'.$eth_diff.'</td>';
    echo $simulate_view;
    echo  '<td style="vertical-align:middle">'.$eth_value.' <span class="badge badge-light">WISE</span></td>';
    $eth_value_all += $total_eth[1][$i];
    $wise_value = $total_eth[1][$i]/5000000;
    $wise_value = number_format($wise_value, 9);
    $eth_value_closed = round(($i+1)*5000000/$eth_value_all, 4);
    echo  '<td style="vertical-align:middle">'.$eth_value_closed.' <span class="badge badge-light">WISE</span></td>';  
    echo  '<td style="vertical-align:middle">'.$wise_value.' <span class="badge badge-light">ETH</span></td>';
    $roi = $eth_value/$wise_value_estimate;
    $roi = round($roi, 1);
    echo  '<td style="vertical-align:middle">'.$roi.' X</td>';
    echo "</tr>";
}

?>

</tbody>
</table>
</div>
<?php
$endtime = microtime(true);
?>
<div class="alert alert-light" role="alert">
  <center>
    <?php printf("Page loaded in %f seconds", $endtime - $starttime ); ?>
  </center>
</div>
</body>
</html>
