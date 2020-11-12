<?php
$servername = "localhost";
$username = "wise";
$password = "wise";
$dbname = "wise";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT wallet FROM wallets";
$result = $conn->query($sql);
while($row = mysqli_fetch_array($result)) {
    $row_wallet = $row["wallet"];
    $sqlw = "SELECT * FROM etherscan WHERE wallet_from='$row_wallet'";
    $resultw = $conn->query($sqlw);
    $value_total = 0;
        while($roww = mysqli_fetch_array($resultw)) {
            echo $resultw["eth_in"];
            $value_total += $resultw["eth_in"];
        }
    $wallets_wallet = $row["eth_in"];
    $sqli = "INSERT INTO wallets (total)
    VALUES ($value_total)
    WHERE wallet='$wallets_wallet'";
}
$conn->close();
?>