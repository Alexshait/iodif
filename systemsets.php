<?php
// require 'functions.php';
$confBlocks_str = RequestConfBlock("all");
$confBlocks_obj = json_decode(substr($confBlocks_str, 0, strlen($confBlocks_str)-1));
//print_r(explode(" ", $confBlocks_obj->{"dns"}[0])); echo "<br />";
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
/*********************************/ 
$curyear = date("F");
$cmn = date("m");
$months = [];
$sls = [];
for ($i = 1; $i < 13; $i++) {
  $months[] = date("F", mktime(0, 0, 0, $i, 1, 2000));
  $sls[] = ($i == (int)$cmn) ? ' selected="selected"' : '';
}
$nmbdays = cal_days_in_month(1, (int)$cmn, (int)$curyear); // 1- CAL_GREGORIAN
//echo '$nmbdays = '.$nmbdays;
/**************************************************************************************** */
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
      // print_r($json_str);

  }
}

if(isset($_POST['submit_ip6'])) {
  $ip6_arr = array(
    "ip6" => json_encode($_POST));
  $json_str = "#" . json_encode($ip6_arr);
}
/***************************************************************************************** */
?>

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
        <div class="ip">
          <!-- <label class="lbl_radio" for="address">IP address: </label> -->
          <a>    IP address: </a>
          <input type="text" name="address" id="address" value=<?php echo $ip_addr[2]; ?>><br><br>
        </div>
        <div class="ip">
          <a    >Mask: </a>
          <input type="text" name="netmask" id="netmask" value=<?php  echo $ip_addr[6]; ?>><br><br>
        </div>
        <div class="ip">
          <a>    Gateway: </a>
          <input type="text" name="gateway" id="gateway" value=<?php echo  $ip_gw; ?>><br><br>
        </div>
        <div class="ip">
          <a>    DNS: </a>
          <input type="text" name="dns1" id="dns1" value=<?php  echo $dns1[1]; ?>><br><br>
        </div>
        <div class="ip">
          <a>    DNS: </a>
          <input type="text" name="dns2" id="dns2" value=<?php  echo $dns2[1]; ?>><br><br>
        </div>
        <input type="submit" id="btn_save" values="Save" name="submit_ip" onclick="return confirm('Do you want to save changes?')"> <br><br>
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

        <div class="ip">
          <a>IP address IPv6: </a>
          <input type="text" name="address" id="address" value=<?php echo $ip6_addr[2]; ?>><br><br>
        </div>
        <div class="ip">
          <a>Mask IPv6: </a>
          <input type="text" name="netmask" id="netmask" value=<?php echo $ip6_mask; ?>><br><br>
        </div>
        <div class="ip">
          <a>Gateway IPv6: </a>
          <input type="text" name="gateway" id="gateway" value=<?php echo $ip6_gw; ?>><br><br>
        </div>
        <!-- <div class="ip">
          <a>DNS IPv6: </a>
          <input type="text" name="dns1" id="dns1" /><br><br>
        </div>
        <div class="ip">
          <a>DNS IPv6: </a>
          <input type="text" name="dns2" id="dns2" /><br><br>
        </div> -->
        <input type="submit" id="btn_save_6" values="Save" name="submit_ip6" onclick="return confirm('Do you want to save changes?')"><br><br>
        <hr>
      </div>
    </form>
  </div>
  <!-- Меню TIME ******************************************** -->
  <div id="content0-2">
    <form method="post" action="">
      <div id="frm_main">
        <div class="ip">
          <a>    NTP servers separated by comma: </a>
          <input type="text" name="ntp_server" id="ntp_server" value=<?php echo $ntp_server[0]; ?>><br><br>
        </div>
        <div class="ip">
          <a>    Region: </a>
          <input type="text" name="ntp_server" id="ntp_server" value=<?php echo $ntp_server[0]; ?>><br><br>
        </div>
        <dev class="ip">
          <select class="names" name="text_hname">
            <option value="Archer">Archer</option>
            <option value="Barbarian">Barbarian</option>
            <option value="Balloon">Balloon</option>
            <option value="Witch">Witch</option>
            <option value="Spirit">Spirit</option>
            <option value="Hog_Rider">Hog Rider</option>
            <option value="Minion">Minion</option>
          </select>
        </dev>
      </div>
    </form>
  </div>
  <div id="content0-3">
    Contents 3...
  </div>
  <div id="content0-4">
    Contents 4...
  </div>
  <div id="content0-5">
    Contents 5...
  </div>
  <div class="vtabs__links">
    <a href="#content0-1">Network</a>
    <a href="#content0-2">Date/Time</a>
    <a href="#content0-3">MRTG</a>
    <a href="#content0-4">Cellular(3G/4G modem)</a>
    <a href="#content0-5">COM Settings</a>
  </div>
</div>


