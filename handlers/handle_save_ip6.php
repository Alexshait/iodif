<?php
print_r($_GET); 
$ip_arr = array(
    "ip6" => json_encode($_GET ));
$json_str = "#" . json_encode($ip_arr);
print_r($json_str);

?>