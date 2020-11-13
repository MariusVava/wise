<?php
$starttime = microtime(true);
?>

<?php
#get ETH value in USD
$url_eth = "https://www.google.com/search?q=1+eth+in+usd&oq=1+eth+in+usd&aqs=chrome..69i57.3670j0j7&sourceid=chrome&ie=UTF-8";
$coindesk = file_get_contents($url_eth);
preg_match('!<div><div><div><div class="BNeawe iBp4i AP7Wnd"><div><div class="BNeawe iBp4i AP7Wnd">(.*?)dolar american</div>!', $coindesk, $match_eth_price);
$eth_price_kkt = $match_eth_price[1];
$eth_usd = str_replace(",",".",$eth_price_kkt);
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>WISE WAL(L)E(T)S</title>

    <script type="text/javascript" src="scripts\sort-table.js"></script>

</head>
  <body>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

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
?>

<div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
      <a class="btn btn-info btn-lg btn-block collapsed" href="../wise/" role="button">[WISE STATISTICS]</a>
</div>
</div>
&nbsp;
<div class="container">
<table class="table table-striped table-dark table-hover js-sort-table" id="wise_table">
  <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">WALLET</th>
      <th class="js-sort-number" scope="col">TRANS.</th>
      <th class="js-sort-number" scope="col">TOTAL ETH</th>
      <th class="js-sort-number" scope="col">ETH in USD</th>
    </tr>
  </thead>
  <tbody>
        <?php
        $result = $db->query("SELECT * FROM wallets");
            while($val = $result->fetch_assoc()){  ?>
            <tr>
                <td><?php echo $val['id']; ?></td>
                <td><a href="https://etherscan.io/address/<?php echo $val['wallet']; ?>" target="_blank"><span class="badge badge-warning">&#x1F50D;</span></a> <?php echo $val['wallet']; ?></td>
                <td style="text-align:right"><?php echo $val['transactions']; ?></td>
                <td><?php echo $val['total']; ?> <span class="badge badge-pill badge-light">ETH</span></td>
                <td><?php echo $val['total']*$eth_usd; ?> <span class="badge badge-pill badge-success">$</span></td>
            </tr>
            <?php
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