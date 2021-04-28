<?php
require '../functions.php';
$write_pipe = "/tmp/iodwr";
$read_pipe = "/tmp/iodrw";

print_r($_GET); 
echo "<br />";
$ip_arr = array(
    "ip" => json_encode($_GET ));
$json_str = "#" . json_encode($ip_arr);
print_r($json_str);
exec("echo all > " . $write_pipe);
echo "<br />";

$json_str = read_pipe($read_pipe);
print_r($json_str);
echo "<br />";
$json_arr = json_decode(substr($json_str, 0, strlen($json_str)-1));
print_r($json_arr);


?>