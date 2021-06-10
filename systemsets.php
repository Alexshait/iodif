<?php
// require 'functions.php';
$confBlocks_str = RequestIodExch("all");
$confBlocks_obj = json_decode(substr($confBlocks_str, 0, strlen($confBlocks_str) - 1));
?>
<div class="vtabs">
  <!--********************************************* MENU NETWORK ********************************************* -->
  <div id="menu_network">
    <?php
    if (isset($_POST['submit_ip'])) {
      $current_tab = "?tab=tab2#menu_network"; // используется для возврата на исходный таб
      $user_message_menu_temp = 'Successully changed';
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
        print_r($ip_str);
        echo "<br />";
        $json_str = "'#{\"ip\":" . $ip_str . "}'";  // ,\"syscmd\":[\"reboot\"]
        print_r($json_str);
        echo "<br />";
        $confBlocks_str = RequestIodExch($json_str);
        print_r($confBlocks_str);
        echo "<br />";
      }
    }

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

    $ip6_mask = $confBlocks_obj->{"ip6"}->{"netmask"};
    $ip6_gw = $confBlocks_obj->{"ip6"}->{"gateway"};
    $cpu = array("ip" => 2, "msk" => 6);
    if (archOS() == "aarch64") {
      $cpu = array(
        "ip" => 1,
        "msk" => 3
      );
    }
    if (isset($_POST['submit_ip6'])) {
      $current_tab = "?tab=tab2#menu_network"; // используется для возврата на исходный таб
      $user_message_menu_temp = 'Successully changed';
      unset($_POST['submit_ip6']); // удаляем из массива не нужный элемент формы submit_ip от кнопки
      $ip6_arr = array(
        "ip6" => json_encode($_POST)
      );
      $json_str = "#" . json_encode($ip6_arr);
    }

    ?>

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
    <form method="post" action="?tab=tab2#menu_temp">
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
    <form method="post" action="?tab=tab2#menu_temp">
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
  <!-- /****************************************** MENU TIME ************************************************* -->
  <div id="menu_time">
    <?php
    /*---------------------- SUBMIT TIME --------------------- */

    if (isset($_POST['btn_save_zone'])) { // задаем timezone на основе выбора региона
      $zone = $_POST['select_zone'];
      echo GoToCurrentPage('?tab=tab2#menu_time');
    }
    if (isset($_POST['time_submit'])) {
      $current_tab = "?tab=tab2#menu_time"; // используется для возврата на исходный таб
      $user_message_menu_temp = 'Successully changed';
      $zone = $_POST['select_zone'];
      $timezone = $_POST['select_timezone'];
      $ntpserv = $_POST['NTP='];
      $json_str = "'#{\"ntpserv\":{\"NTP=\":\"$ntpserv\"},\"timezone\":[\"$zone/$timezone\"]}'";
      $reply = RequestIodExch($json_str);
      print_r($reply);
    }

    $region_timezone_arr = explode('/', $confBlocks_obj->{"timezone"}[0]);
    $current_zone = $region_timezone_arr[0];
    $zone = $current_zone;
    $current_timezone = $region_timezone_arr[1];
    //$timezone = "Europe";
    $current_ntpserv =  $confBlocks_obj->{"ntpserv"}->{"NTP="};

    ?>
    <p>Current settings: <br>
      NTP servers: <?php echo $current_ntpserv; ?> <br>
      Region: <?php echo $current_zone; ?><br>
      Time zone: <?php echo $current_timezone; ?><br><br>
    </p>
    <hr>
    <div id="frm_main">
      <form method="post" action="">

        <div class="input_block">
          <a>Region: </a>
          <?php
          $filename = "./zones/zones";
          $eachlines = file($filename, FILE_IGNORE_NEW_LINES);
          echo '<select name="select_zone" id="select_zone">'; // SELECT ZONE
          if (!isset($_POST['btn_save_zone'])) echo "<option selected> </option>";
          foreach ($eachlines as $lines) {
            $selected = '';
            if ($_POST['select_zone'] == $lines) $selected = "selected";
            echo "<option $selected> {$lines} </option>";
          }
          echo '</select>';
          ?>
          <input type="submit" id="btn_save_zone" value="Set zone" name="btn_save_zone">
          <br>
        </div>

      </form>

      <form method="post" action="?tab=tab2#menu_temp">

        <dev class="input_block">
          <a>Time zone: </a>
          <?php
          if (isset($_POST['btn_save_zone'])) {
            $filename = "./zones/" . $_POST['select_zone'];
          } else {
            $filename = "./zones/" . $zone;
          }
          // print_r($_POST['select_zone']); echo '<br />';
          $eachlines = file($filename, FILE_IGNORE_NEW_LINES);
          echo '<select name="select_timezone" id="select_timezone">'; // SELECT TIMEZONE
          echo "<option selected> </option>";
          foreach ($eachlines as $lines) {
            $selected = '';
            if ($_POST['select_timezone'] == $lines) $selected = "selected";
            echo "<option $selected> {$lines}</option>";
          }
          echo '</select>';
          ?> <br><br>
        </dev>

        <div class="input_block">
          <a>NTP servers separated by comma: </a>
          <input type="text" name="NTP=" id="NTP=" value=<?php echo $current_ntpserv; ?>><br><br>
        </div>

        <input type="submit" id="time_submit" name="time_submit" class="submit_btn" value="Save" onclick="return confirm('Do you want to save changes?')"> <br><br>
        <hr>

      </form>
    </div>
  </div>
  <!-- **************************************** Меню USERS ********************************** -->
  <div id="menu_users">
    <br><br>
    <form method="post" action="?tab=tab2#menu_temp">
      <?php
      $users_arr = ExplodeUsers($confBlocks_obj->{"users"});
      $groups_arr = ExplodeGroups($confBlocks_obj->{"groups"});
      // ------------------------ ТАБЛИЦА -------------------
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
              $table .= '<td class="table_row">' . GroupName_by_ID($groups_arr, $users_arr[$tr][user_column_seq($td)]) . '</td>';
            } else {
              $table .= '<td class="table_row">' . $users_arr[$tr][user_column_seq($td)] . '</td>';
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
      <input type="submit" id="user_delete_submit" name="user_delete_submit" class="submit_btn" value="Delete user" onclick="return confirm('Do you want to delete selected user?')">
      <br><br>
    </form>

    <form method="post" action="?tab=tab2#menu_temp">
      <hr>
      <br><br>
      <div id="frm_main">
        <div class="input_block">
          <a>User name: </a>
          <input type="text" name="user_name" id="user_name"> <br><br>
        </div>
        <div class="input_block">
          <a>User password: </a>
          <input type="text" name="password" id="password"> <br><br>
          <a>Confirm password: </a>
          <input type="text" name="confirm_pwd" id="confirm_pwd"> <br><br>
        </div>
        <div class="input_block">
          <a>User group: </a>
          <?php
          echo '<select name="select_group" id="select_group">'; // SELECT GROUP
          for ($i = 0; $i < count($groups_arr); $i++) {
            $group_name = $groups_arr[$i]["name"];
            echo "<option value={$groups_arr[$i]["id"]}> {$group_name} </option>";
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
      $current_tab = "?tab=tab2#menu_users"; // используется для возврата на исходный таб
      if ($_POST['password'] == $_POST['confirm_pwd'] && strlen($_POST['password']) >= 6 && HostNameIsCorrect($_POST['user_name']) && NoDoubleUsers($_POST['user_name'], $users_arr)) {
        $usr_id = 1 + getMaxUserId($users_arr);
        $user_line = $_POST['user_name'] . ':x:' . $usr_id . ':' . $_POST['select_group'] . ':' . $_POST['user_info'] . ':/home/' . $_POST['user_name'] .  ':/bin/bash';
        $pass = $_POST['password'];
        $salt = SaltGenerator();
        $hashed = crypt($_POST['password'], '$6$' . $salt . '$'); //substr($pass, 0, strlen($s1) + strlen($salt) + 3)
        $shadow_line = $_POST['user_name'] . ':' . $hashed . ':18349:0:99999:7:::';
        $json_str = "'#{\"users\":[\"" . $user_line . "\"],\"shadow\":[\"" . $shadow_line . "\"]}'";
        $reply = RequestIodExch($json_str);
        // print_r('json' . $json_str);
        $user_message_menu_temp = 'Successully changed';
      } else {
        $user_message_menu_temp = 'The new user was not created. A password must be at least 6 characters long and confirmed, user name must be alphanumeric and unique.';
      }
    }
    if (isset($_POST['user_delete_submit'])) {
      $current_tab = "?tab=tab2#menu_users"; // используется для возврата на исходный таб
      if (isset($_POST['type_radio'])) {
        $user_message_menu_temp = 'Successully changed';
        $json_str = "'#{\"users\":[\"" . $confBlocks_obj->{"users"}[$_POST['type_radio']] . "*\"],\"shadow\":[\"" . $confBlocks_obj->{"shadow"}[$_POST['type_radio']] . "*\"]}'";
        $reply = RequestIodExch($json_str);
        // print_r($reply);
      } else {
        $user_message_menu_temp = 'A user was not selected to delete.';
      }
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
  <div id="menu_temp">
    <div class="input_block">
      <p><?php echo $user_message_menu_temp; ?></p>
      <br><br>
      <form method="post" action=<?php echo $current_tab; ?>>
        <input type="submit" name="btn_go_back" class="submit_btn" value="Return">
      </form>
    </div>

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
<p>Date: <?php echo $confBlocks_obj->{"commands"}->{"date"}; ?> <br> </p>