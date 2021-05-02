<?php
// require 'functions.php';
/******************************** NETWORK ***************************************/
$confBlocks_str = RequestConfBlock("all");
$confBlocks_obj = json_decode(substr($confBlocks_str, 0, strlen($confBlocks_str)-1));
//print_r(explode(" ", $confBlocks_obj->{"ip"})); echo "<br />";
//print_r($confBlocks_obj); echo "<br />";
$ip_dhcp = $confBlocks_obj->{"ip"}->{"iface eth0 inet"};
$ip_addr = preg_split("/[\s:]+/", $confBlocks_obj->{"commands"}->{"ifconfig eth0 | grep 'inet '"});
$ip_gw = $confBlocks_obj->{"ip"}->{"gateway"};
$dns1 = ''; $dns2 = ''; $dns3 = '';
if (count($confBlocks_obj->{"dns"}) >= 1) $dns1 = explode(" ", $confBlocks_obj->{"dns"}[0]);
if (count($confBlocks_obj->{"dns"}) >= 2) $dns2 = explode(" ", $confBlocks_obj->{"dns"}[1]);
if (count($confBlocks_obj->{"dns"}) >= 3) $dns3 = explode(" ", $confBlocks_obj->{"dns"}[2]);
$ip6_dhcp = $confBlocks_obj->{"ip6"}->{"iface eth0 inet6"};
$ip6_addr = preg_split("/ /", $confBlocks_obj->{"commands"}->{"ifconfig eth0 | grep 'inet6 '"});
// print_r($ip6_addr); echo "<br />";
$ip6_mask = $confBlocks_obj->{"ip6"}->{"netmask"};
$ip6_gw = $confBlocks_obj->{"ip6"}->{"gateway"};
/**************** SUBMIT NETWORK *********************************/
if(isset($_POST['submit_ip'])) {
  require_once dirname(__FILE__).'/functions.php';
print_r(IpAddressIsCorrect($_GET['address'],1)); echo "<br />";
  if (!IpAddressIsCorrect($_POST['address'],1)) {
      $message = "IP address is not correct!";
      echo "<script type='text/javascript'>alert('$message');  </script>"; //window.history.go(-1);
  } elseif (!IpAddressIsCorrect($_POST['netmask'],1)) {
      $message = "Netmask is not correct!";
      echo "<script type='text/javascript'>alert('$message');  </script>";
  } elseif (!IpAddressIsCorrect($_POST['gateway'],1)) {
      $message = "Gateway address is not correct!";
      echo "<script type='text/javascript'>alert('$message');  </script>";
  } else {
      $ip_arr = array(
        "ip" => json_encode($_POST));
      $json_str = "#" . json_encode($ip_arr);
      print_r($json_str);

  }
}

if(isset($_POST['submit_ip6'])) {
  $ip6_arr = array(
    "ip6" => json_encode($_POST));
  $json_str = "#" . json_encode($ip6_arr);
  }
/****************************************** TIME *************************************************/ 
$region_timezone_arr = explode('/', $confBlocks_obj->{"timezone"}[0]);
$zone = $region_timezone_arr[0];
$timezone = $region_timezone_arr[1];
//$timezone = "Europe";
$ntpserv =  $confBlocks_obj->{"ntpserv"}->{"NTP="};

/************************ SUBMIT TIME ***************************** */

if(isset($_POST['btn_save_zone'])) { // задаем timezone на основе выбора региона
  $timezone = $_POST['select_zone'];
}
if(isset($_POST['time_submit'])) {
  $zone = $_POST['select_zone'];
  $timezone = $_POST['select_timezone'];
  $time_str = "{\"timezone\":{[\" $zone / $timezone \"]}";
  $ntpserv = $_POST['NTP='];
  $ntp_str = "{\"ntpserv\":{\"NTP=\":\" $ntpserv \"}";
  $json_str = "#" . $ntp_str . "," . $time_str;
  // print_r($json_str); echo "<br />";
}
?>
<!----------------------------------------- HTML ------------------------------------------------ -->
<div class="vtabs">
  <div id="content0-1">
    <form method="post" action="">
      <div id="frm_main">
        <div id="dhcp">
          <!-- <label class="lbl_radio" for="iface eth0 inet:">DHCP:</label> -->
          <a>DHCP: </a>
          <input type="radio" name="iface eth0 inet" id="DHCP" value="dhcp" <?php echo ($ip_dhcp == 'DHCP') ?  "checked" : "" ;?>> 
          Static: <input type="radio" name="iface eth0 inet" id="DHCP" value="static" <?php echo ($ip_dhcp == 'static') ?  "checked" : "" ;?>> <Br> <br>
        </div>
        <div class="input_block">
          <!-- <label class="lbl_radio" for="address">IP address: </label> -->
          <a>    IP address: </a>
          <input type="text" name="address" id="address" value=<?php echo $ip_addr[2]; ?>><br><br>
        </div>
        <div class="input_block">
          <a    >Mask: </a>
          <input type="text" name="netmask" id="netmask" value=<?php  echo $ip_addr[6]; ?>><br><br>
        </div>
        <div class="input_block">
          <a>    Gateway: </a>
          <input type="text" name="gateway" id="gateway" value=<?php echo  $ip_gw; ?>><br><br>
        </div>
        <div class="input_block">
          <a>    DNS: </a>
          <input type="text" name="dns1" id="dns1" value=<?php  echo $dns1[1]; ?>><br><br>
        </div>
        <div class="input_block">
          <a>    DNS: </a>
          <input type="text" name="dns2" id="dns2" value=<?php  echo $dns2[1]; ?>><br><br>
        </div>
        <input type="submit" id="btn_save" value="Save" name="submit_ip" onclick="return confirm('Do you want to save changes?')"> <br><br>
        <hr>
      </div>
    </form>
    <!--Раздел настроек TCP/IP v6 ----------------------- -->
    <form method="post" action="">
      <div id="frm_main">
        <div id="dhcp">
          <a>DHCP IPv6: </a>
          <input type="radio" name="iface eth0 inet6" id="DHCP" value="dhcp" <?php echo ($ip6_dhcp == 'dhcp') ?  "checked" : "" ;?>> 
          Static: <input type="radio" name="iface eth0 inet6" id="DHCP" value="static" <?php echo ($ip6_dhcp == 'static') ?  "checked" : "" ;?>/> <Br> <br>
        </div>

        <div class="input_block">
          <a>IP address IPv6: </a>
          <input type="text" name="address" id="address" value=<?php echo $ip6_addr[2]; ?>><br><br>
        </div>
        <div class="input_block">
          <a>Mask IPv6: </a>
          <input type="text" name="netmask" id="netmask" value=<?php echo $ip6_mask; ?>><br><br>
        </div>
        <div class="input_block">
          <a>Gateway IPv6: </a>
          <input type="text" name="gateway" id="gateway" value=<?php echo $ip6_gw; ?>><br><br>
        </div>
        <!-- <div class="input_block">
          <a>DNS IPv6: </a>
          <input type="text" name="dns1" id="dns1" /><br><br>
        </div>
        <div class="input_block">
          <a>DNS IPv6: </a>
          <input type="text" name="dns2" id="dns2" /><br><br>
        </div> -->
        <input type="submit" id="btn_save_6" value="Save" name="submit_ip6" onclick="return confirm('Do you want to save changes?')"><br><br>
        <hr>
      </div>
    </form>
  </div>
  <div id="content0-2">
    Contents 2...
  </div>
  <!------------------- Меню TIME ----------------------- -->
  <div id="content0-3">
    <form method="post" action="">
      <div id="frm_main">
        <div class="input_block">
          <a>NTP servers separated by comma: </a>
          <input type="text" name="NTP=" id="NTP=" value=<?php echo $ntpserv; ?>><br><br>
        </div>
        <dev class="input_block">

          <form method="post" action="">   <!--  FORM ZONE -->
            <a>Region: </a>
            <?php
              $filename = "./zones/zones";
              $eachlines = file($filename, FILE_IGNORE_NEW_LINES);
              echo '<select name="select_zone" id="select_zone">'; // SELECT ZONE
              foreach($eachlines as $lines){
                $selected = '';
                if ($lines == $zone) $selected = "selected";
                echo "<option $selected> {$lines} </option>";
              }
              echo '</select>';
            ?>
            <input type="submit" id="btn_save_zone" value="Set zone" name="btn_save_zone"><br><br>
          </form>

        </dev>
        <dev class="input_block">
          <a>Time zone: </a>
            <?php
              $filename = "./zones/" . $zone;
              $eachlines = file($filename, FILE_IGNORE_NEW_LINES);
              echo '<select name="select_timezone" id="select_timezone">'; // SELECT TIMEZONE
              foreach($eachlines as $lines){
                $selected = '';
                if ($lines == $timezone) $selected = "selected";
                echo "<option $selected> {$lines}</option>";
              }
              echo '</select>';
            ?> <br><br>
        </dev>
        
        <input type="submit" id="time_submit" name="time_submit" value="Save" onclick="return confirm('Do you want to save changes?')"> <br><br>
        <hr>
      </div>
    </form>
  </div>
  <div id="content0-4">
    Contents 4...
  </div>
  <div id="content0-5">
    Contents 5...
  </div>
  <div id="content0-6">
    Contents 6...
  </div>
  <div id="content0-7">
    Contents 7...
  </div>
  <div class="vtabs__links">
    <a href="#content0-1">Network</a>
    <a href="#content0-2">WLAN</a>
    <a href="#content0-3">Date/Time</a>
    <a href="#content0-4">Users</a>
    <a href="#content0-5">Routes</a>
    <a href="#content0-6">System</a>
    <a href="#content0-7">Cellular(3G/4G modem)</a>
  </div>
</div>


