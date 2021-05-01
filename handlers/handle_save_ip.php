<?php
require '../functions.php';
// print_r(IpAddressIsCorrect($_GET['address'],1)); echo "<br />";
if (!IpAddressIsCorrect($_GET['address'],1)) {
    $message = "IP address is not correct!";
    echo "<script type='text/javascript'>alert('$message');  </script>"; //window.history.go(-1);
} elseif (!IpAddressIsCorrect($_GET['netmask'],1)) {
    $message = "Netmask is not correct!";
    echo "<script type='text/javascript'>alert('$message');  </script>";
} elseif (!IpAddressIsCorrect($_GET['gateway'],1)) {
    $message = "Gateway address is not correct!";
    echo "<script type='text/javascript'>alert('$message');  </script>";
} else {
    $ip_arr = array(
        "ip" => json_encode($_GET));
    $json_str = "#" . json_encode($ip_arr);
}
die;
//header("Location: {$_SERVER["HTTP_REFERER"]}");
?>