<?php
$cpu = array("ip" => 2, "msk" => 6);
if (archOS() == "aarch64") {
  $cpu = array(
    "ip" => 1,
    "msk" => 3
  );
}
// require 'functions.php';
/******************************** NETWORK ***************************************/
$confBlocks_str = RequestIodExch("all");
$confBlocks_obj = json_decode(substr($confBlocks_str, 0, strlen($confBlocks_str) - 1));
//print_r(explode(" ", $confBlocks_obj->{"ip"})); echo "<br />";
//print_r($confBlocks_obj); echo "<br />";
$ip_dhcp = $confBlocks_obj->{"ip"}->{"iface eth0 inet"};
$ip_addr = preg_split("/[\s:]+/", $confBlocks_obj->{"commands"}->{"ifconfig eth0 | grep 'inet '"});
$ip_gw = $confBlocks_obj->{"ip"}->{"gateway"};
$dns1 = '';
$dns2 = '';
$dns3 = '';
if (count($confBlocks_obj->{"dns"}) >= 1) $dns1 = explode(" ", $confBlocks_obj->{"dns"}[0]);
if (count($confBlocks_obj->{"dns"}) >= 2) $dns2 = explode(" ", $confBlocks_obj->{"dns"}[1]);
if (count($confBlocks_obj->{"dns"}) >= 3) $dns3 = explode(" ", $confBlocks_obj->{"dns"}[2]);
$ip6_dhcp = $confBlocks_obj->{"ip6"}->{"iface eth0 inet6"};
$ip6_addr = preg_split("/ /", $confBlocks_obj->{"commands"}->{"ifconfig eth0 | grep 'inet6 '"});
// print_r($ip6_addr); echo "<br />";
$ip6_mask = $confBlocks_obj->{"ip6"}->{"netmask"};
$ip6_gw = $confBlocks_obj->{"ip6"}->{"gateway"};
/**************** SUBMIT NETWORK *********************************/
if (isset($_POST['submit_ip'])) {
  require_once dirname(__FILE__) . '/functions.php';
  print_r(IpAddressIsCorrect($_GET['address'], 1));
  echo "<br />";
  if (!IpAddressIsCorrect($_POST['address'], 1)) {
    $message = "IP address is not correct!";
    echo "<script type='text/javascript'>alert('$message');  </script>"; //window.history.go(-1);
  } elseif (!IpAddressIsCorrect($_POST['netmask'], 1)) {
    $message = "Netmask is not correct!";
    echo "<script type='text/javascript'>alert('$message');  </script>";
  } elseif (!IpAddressIsCorrect($_POST['gateway'], 1)) {
    $message = "Gateway address is not correct!";
    echo "<script type='text/javascript'>alert('$message');  </script>";
  } else {
    unset($_POST['submit_ip']); // удаляем из массива не нужный элемент формы submit_ip от кнопки
    unset($_POST['dns1']);
    unset($_POST['dns2']);
    // $ip_arr = array(
    //   "ip" => json_encode($_POST, JSON_UNESCAPED_UNICODE)
    // );
    $ip_str = json_encode($_POST, JSON_UNESCAPED_UNICODE);
    $ip_str = str_replace("_", " ", $ip_str); // $_POST заменяет пробелы на "_", поэтому требуется заменить на пробел
    print_r($ip_str); echo "<br />";
    $json_str = "'#{\"ip\":" . $ip_str . "}'";  // ,\"syscmd\":[\"reboot\"]
    print_r($json_str); echo "<br />";
    $respone = RequestIodExch($json_str);
    print_r($respone); echo "<br />";
  }
}

if (isset($_POST['submit_ip6'])) {
  unset($_POST['submit_ip6']); // удаляем из массива не нужный элемент формы submit_ip от кнопки
  $ip6_arr = array(
    "ip6" => json_encode($_POST)
  );
  $json_str = "#" . json_encode($ip6_arr);

}
/****************************************** TIME *************************************************/
$region_timezone_arr = explode('/', $confBlocks_obj->{"timezone"}[0]);
$zone = $region_timezone_arr[0];
$timezone = $region_timezone_arr[1];
//$timezone = "Europe";
$ntpserv =  $confBlocks_obj->{"ntpserv"}->{"NTP="};

/************************ SUBMIT TIME ***************************** */

if (isset($_POST['btn_save_zone'])) { // задаем timezone на основе выбора региона
  $timezone = $_POST['select_zone'];
}
if (isset($_POST['time_submit'])) {
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
  <div id="menu_network">
    <p>Current settings: <br>
      DHCP v4: <?php echo ($ip_dhcp == 'DHCP') ?  "Enabled" : "Disabled"; ?> <br>
      IP address v4: <?php echo $ip_addr[$cpu["ip"]]; ?> <br>
      Mask v4: <?php echo $ip_addr[$cpu["msk"]]; ?><br>
      Gateway v4: <?php echo $ip_gw; ?><br>
      DNS: <?php echo $dns1[1] . "; " . $dns2[1] . "; " . $dns3[1]; ?><br>
    </p>
    <hr>
    <p>
      DHCP v6: <?php echo ($ip6_dhcp == 'DHCP') ?  "Enabled" : "Disabled"; ?> <br>
      IP address v6: <?php echo $ip6_addr[$cpu["ip"]]; ?> <br>
      Mask v6: <?php echo $ip6_mask; ?><br>
      Gateway v6: <?php echo $ip6_gw; ?><br>
    </p>
    <hr>
    <br><br>
    <form method="post" action="">
      <div id="frm_main">
        <div id="radio_div">
          <!-- <label class="lbl_radio" for="iface eth0 inet:">DHCP:</label> -->
          <a>DHCP: </a>
          <input type="radio" name="iface eth0 inet" id="radio_div" value="dhcp" <?php echo ($ip_dhcp == 'DHCP') ?  "checked" : ""; ?>>
          Static: <input type="radio" name="iface eth0 inet" id="radio_div" value="static" <?php echo ($ip_dhcp == 'static') ?  "checked" : ""; ?>> <Br> <br>
        </div>
        <div class="input_block">
          <!-- <label class="lbl_radio" for="address">IP address: </label> -->
          <a> IP address: </a>
          <input type="text" name="address" id="address" value=<?php echo $ip_addr[$cpu["ip"]]; ?>><br><br>
        </div>
        <div class="input_block">
          <a>Mask: </a>
          <input type="text" name="netmask" id="netmask" value=<?php echo $ip_addr[$cpu["msk"]]; ?>><br><br>
        </div>
        <div class="input_block">
          <a> Gateway: </a>
          <input type="text" name="gateway" id="gateway" value=<?php echo  $ip_gw; ?>><br><br>
        </div>
        <div class="input_block">
          <a> DNS: </a>
          <input type="text" name="dns1" id="dns1" value=<?php echo $dns1[1]; ?>><br><br>
        </div>
        <div class="input_block">
          <a> DNS: </a>
          <input type="text" name="dns2" id="dns2" value=<?php echo $dns2[1]; ?>><br><br>
        </div>
        <input type="submit" id="btn_save" value="Save" name="submit_ip" class="submit_btn" onclick="return confirm('Do you want to save changes?')"> <br><br>
        <hr>
        <br><br>
      </div>
    </form>
    <!--Раздел настроек TCP/IP v6 ----------------------- -->
    <form method="post" action="">
      <div id="frm_main">
        <div id="radio_div">
          <a>DHCP IPv6: </a>
          <input type="radio" name="iface eth0 inet6" id="radio_div" value="dhcp" <?php echo ($ip6_dhcp == 'dhcp') ?  "checked" : ""; ?>>
          Static: <input type="radio" name="iface eth0 inet6" id="radio_div" value="static" <?php echo ($ip6_dhcp == 'static') ?  "checked" : ""; ?> /> <Br> <br>
        </div>

        <div class="input_block">
          <a>IP address IPv6: </a>
          <input type="text" name="address" id="address" value=<?php echo $ip6_addr[$cpu["ip"]]; ?>><br><br>
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
        <input type="submit" id="btn_save_6" value="Save" name="submit_ip6" class="submit_btn" onclick="return confirm('Do you want to save changes?')"><br><br>
        <hr>
      </div>
    </form>
  </div>
  <div id="menu_wlan">
    Contents 2...
  </div>
  <div id="menu_gsm">
    Contents 7...
  </div>
  <!------------------- Меню TIME ----------------------- -->
  <div id="menu_time">
    <p>Current settings: <br>
      NTP servers: <?php echo $ntpserv; ?> <br>
      Region: <?php echo $zone; ?><br>
      Time zone: <?php echo $timezone; ?><br><br>
    </p>
    <hr>
    <form method="post" action="">
      <div id="frm_main">
        <br><br>
        <div class="input_block">
          <a>NTP servers separated by comma: </a>
          <input type="text" name="NTP=" id="NTP=" value=<?php echo $ntpserv; ?>><br><br>
        </div>
        <dev class="input_block">

          <form method="post" action="">
            <!--  FORM ZONE -->
            <a>Region: </a>
            <?php
            $filename = "./zones/zones";
            $eachlines = file($filename, FILE_IGNORE_NEW_LINES);
            echo '<select name="select_zone" id="select_zone">'; // SELECT ZONE
            foreach ($eachlines as $lines) {
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
          foreach ($eachlines as $lines) {
            $selected = '';
            if ($lines == $timezone) $selected = "selected";
            echo "<option $selected> {$lines}</option>";
          }
          echo '</select>';
          ?> <br><br>
        </dev>

        <input type="submit" id="time_submit" name="time_submit" class="submit_btn" value="Save" onclick="return confirm('Do you want to save changes?')"> <br><br>
        <hr>
      </div>
    </form>
  </div>
  <!-- ---------------- Меню USERS ---------------------- -->
  <div id="menu_users">
    <br><br>
    <form method="post" action="">
      <?php
      $users_arr = $confBlocks_obj->{"users"};
      if (count($users_arr) >= 1) {
        for ($i = 0; $i <= count($users_arr); $i++) {
          $user_prop[$i] = explode(":", $users_arr[$i]);
        }
      }
      $groups_arr = '';
      $grp_arr = $confBlocks_obj->{"groups"};
      $grp_count = count($grp_arr);
      if ($grp_count >= 1) {
        for ($i = 0; $i < $grp_count; $i++) {
          $groups_arr[$i] = array(
            "name" => explode(":", $grp_arr[$i])[0],
            "id" => explode(":", $grp_arr[$i])[2]
          );
        }
      }
      // print_r($groups_arr[3]["name"]);  echo "<br />";
      $colName = array(
        0 => 'User name',
        1 => 'User group',
        2 => 'User info',
        3 => 'Select'
      );
      // print_r($colName);  echo "<br />";
      $table = '<table class="table_in_center" border="2" style="width:80%">';
      for ($tr = -1; $tr <= count($users_arr) - 1; $tr++) {
        $table .= '<tr>';
        for ($td = 0; $td <= 3; $td++) {
          if ($tr == -1) {
            $table .= '<td class="table_header">' . $colName[$td] . '</td>';
          } else {
            if ($td == 3) { // последнее поле select
              $table .= '<td class="table_row"> <input value="' . $tr . '" id="radio_div" name="type_radio" type="radio" /> </td>';
            } elseif ($td == 1) { // поле группы
              $table .= '<td class="table_row">' . GroupName_by_ID($groups_arr, $user_prop[$tr][user_column_seq($td)]) . '</td>';
            } else {
              $table .= '<td class="table_row">' . $user_prop[$tr][user_column_seq($td)] . '</td>';
            }
          }
        }
        $table .= '</tr>';
      }
      $table .= '</table>';
      echo $table;

      function user_column_seq($vIndex)
      {
        if ($vIndex == 0) {
          return 0;
        } elseif ($vIndex == 1) {
          return 3;
        } elseif ($vIndex == 2) {
          return 4;
        } else {
          print_r("user_column_seq(): argument is not correct!");
        }
      }

      ?>

      <br><br>
      <input type="submit" id="user_edit_submit" name="user_edit_submit" class="submit_btn" value="Edit user"> <a> </a>
      <input type="submit" id="user_delete_submit" name="user_delete_submit" class="submit_btn" value="Delete user">
      <br><br>
      <hr>
      <br><br>
      <div id="frm_main">
        <div class="input_block">
          <a>User name: </a>
          <input type="text" name="user_name" id="user_name"> <br><br>
        </div>
        <div class="input_block">
          <a>User group: </a>
          <?php
          echo '<select name="select_group" id="select_group">'; // SELECT GROUP
          for ($i = 0; $i < $grp_count; $i++) {
            $group_name = $groups_arr[$i]["name"];
            echo "<option> {$group_name} </option>";
          }
          echo '</select>';
          ?>
          <br><br>
        </div>
        <div class="input_block">
          <a>User info: </a>
          <input type="text" name="user_info" id="user_info"> <br><br>
        </div>
        <input type="submit" id="user_add_submit" name="user_add_submit" value="Add user" class="submit_btn" onclick="return confirm('Do you want to save changes?')"> <br><br>
        <hr>
      </div>

    </form>
    <?php
    if (isset($_POST['user_add_submit'])) {
      // print_r("user_add_submit"); echo '<br />';
      // include(dirname(__FILE__) . '/users_update.php');
    }
    if (isset($_POST['user_edit_submit'])) {
      // print_r("user_add_submit"); echo '<br />';
      // include(dirname(__FILE__) . '/users_update.php');
    }
    if (isset($_POST['user_delete_submit'])) {
      // print_r("user_add_submit"); echo '<br />';
      // include(dirname(__FILE__) . '/users_update.php');
    }
    ?>

  </div>
  <!-- ---------------- Меню ROUTES ---------------------- -->
  <div id="menu_routes">
    Contents 5...
  </div>
  <div id="menu_system">
    Contents 6...
  </div>

  <div class="vtabs__links">
    <a href="#menu_network">Network</a>
    <a href="#menu_wlan">WLAN</a>
    <a href="#menu_gsm">GSM (3G/4G modem)</a>
    <a href="#menu_time">Date/Time</a>
    <a href="#menu_users">Users</a>
    <a href="#menu_routes">Routes</a>
    <a href="#menu_system">System</a>

  </div>
</div>