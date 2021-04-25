<?php
print_r($_GET); 
$ip_arr = array(
    "ip" => json_encode($_GET ));
$json_str = "#" . json_encode($ip_arr);
print_r($json_str);

?>