<?php
$starttime = microtime(true);
#get ETH value in USD from google
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>WISE WAL(L)E(T)S</title>
    <!-- javascript for sorting tables -->
    <script type="text/javascript" src="scripts\sort-table.js"></script>
</head>
  <body>
<?php
#database connection
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
<!-- MENU -->
<div class="card" id="top">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
      <a class="btn btn-info btn-lg btn-block collapsed" href="../wise/" role="button">[WISE STATISTICS]</a>
</div>
<div class="card-header" id="headingOne">
      <h2 class="mb-0">
      <a class="btn btn-info btn-lg btn-block collapsed" href="#sum" role="button">[TOTAL NO. TRANSACTIONS]</a>
</div>
</div>
<!-- end of MENU -->
&nbsp;
<!-- TABLE containing wallet data -->
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
        $transaction_sum = 0;
        $no_wallets = 0;
        $eth_transacted = 0;
            while($val = $result->fetch_assoc()){
              $transaction_sum += $val['transactions'];  
              $no_wallets++;
              $eth_transacted += $val['total'];
              #display data for each wallet:
              ?>
            <tr>
                <!-- wallet DB id -->
                <td><?php echo $val['id']; ?></td>
                <td>
                <!-- wallet etherscan info -->
                <a href="https://etherscan.io/address/<?php echo $val['wallet']; ?>" target="_blank"><span class="badge badge-warning">&#x1F50D;</span></a> 
                <!-- WALLET TRANSACTIONS INFO -->
                <a href="#" role="button" data-toggle="modal" data-target="#wallet<?php echo $no_wallets; ?>"><span class="badge badge-info">TRANSACTIONS</span></a> 
                <?php echo $val['wallet']; ?>
<!-- TRANSACTION DATA -->
<div class="modal fade" id="wallet<?php echo $no_wallets; ?>" tabindex="-1" role="dialog" aria-labelledby="wallet<?php echo $no_wallets; ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="wallet<?php echo $no_wallets; ?>" style="color: black">
        <a href="https://etherscan.io/address/<?php echo $val['wallet']; ?>" target="_blank"><span class="badge badge-warning">&#x1F50D;</span></a> <?php echo $val['wallet']; ?>
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="color: black">
        <!--TABLE FOR TRANSACTIONS-->
        <table class="table table-striped table-dark table-hover js-sort-table" id="wise_table" style="color: black">
          <thead>
            <tr>
              <th scope="col">DATE</th>
              <th class="js-sort-number" scope="col">ETH</th>
            </tr>
          </thead>
          <tbody>
        <?php
        $wallet_from = $val['wallet'];
        $result1 = $db->query("SELECT date_time, eth_in FROM etherscan WHERE wallet_from='$wallet_from'");
            while($val1 = $result1->fetch_assoc()){
              ?>
            <tr>
                <td><?php echo $val1['date_time']; ?></td>
                <td><?php echo $val1['eth_in']; ?></td>
            </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
        <!-- END OF TRANSACTIONS TABLE -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
                </td>
                <!-- number of transactions for each wallet -->
                <td style="text-align:right"><?php echo $val['transactions']; ?></td>
                <!-- total ETH invested by each wallet -->
                <td><?php echo $val['total']; ?> <span class="badge badge-pill badge-light">ETH</span></td>
                <!-- ETH value in USD at the moment -->
                <td><?php echo $val['total']*$eth_usd; ?> <span class="badge badge-pill badge-success">$</span></td>
            </tr>
            <?php
            }
            #close SQL connection
            $db->close();
            ?>
<!-- END OF WALLET TABLE -->
</tbody>
</table>
<!-- DISPLAY all info from WALLETS -->
<center><h4 id="sum">Total transactions made <strong><?php echo $transaction_sum; ?></strong> from <strong><?php echo $no_wallets; ?></strong> addresses, totalling <strong><?php echo round($eth_transacted, 2); ?> ETH</strong></h4></center>
</div>
<!-- bottom MENU -->
<div class="card">
<div class="card-header" id="headingOne">
      <h2 class="mb-0">
      <a class="btn btn-info btn-lg btn-block collapsed" href="#top" role="button">[BACK TO TOP]</a>
</div>
</div>
<!-- calculate page load times -->
<?php
$endtime = microtime(true);
?>
<div class="alert alert-light" role="alert">
  <center>
    <?php printf("Page loaded in %f seconds", $endtime - $starttime ); ?>
  </center>
</div>
<!-- javascript -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>