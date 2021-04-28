<?php
// $in_json = shell_exec("/usr/local/sbin/iod -r /usr/local/sbin/iod.conf all");
// print_r($in_json);
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
?>
<div class="vtabs">
  <div id="content0-1">
    <form method="GET" action="./handlers/handle_save_ip.php">
      <div id="frm_main">
        <div id="dhcp">
          <!-- <label class="lbl_radio" for="iface eth0 inet:">DHCP:</label> -->
          <a>DHCP: </a>
          <input type="radio" name="iface eth0 inet" id="DHCP" value="dhcp" checked> Static: <input type="radio" name="iface eth0 inet" id="DHCP" value="static" /> <Br> <br>
        </div>
        <div class="ip">
          <!-- <label class="lbl_radio" for="address">IP address: </label> -->
          <a>    IP address: </a>
          <input type="text" name="address" id="address" value="10.0.0.0"><br><br>
        </div>
        <div class="ip">
          <a    >Mask: </a>
          <input type="text" name="netmask" id="netmask" /><br><br>
        </div>
        <div class="ip">
          <a>    Gateway: </a>
          <input type="text" name="gateway" id="gateway" /><br><br>
        </div>
        <div class="ip">
          <a>    DNS: </a>
          <input type="text" name="dns1" id="dns1" /><br><br>
        </div>
        <div class="ip">
          <a>    DNS: </a>
          <input type="text" name="dns2" id="dns2" /><br><br>
        </div>
        <input type="submit" id="btn_save" values="Save"> <br><br>
        <hr>
      </div>
    </form>
    <!--Раздел настроек TCP/IP v6 ----------------------- -->
    <form method="GET" action="./handlers/handle_save_ip6.php">
      <div id="frm_main">
        <div id="dhcp">
          <a>DHCP IPv6: </a>
          <input type="radio" name="iface eth0 inet6" id="DHCP" value="dhcp" checked> Static: <input type="radio" name="iface eth0 inet6" id="DHCP" value="static" /> <Br> <br>
        </div>

        <div class="ip">
          <a>IP address IPv6: </a>
          <input type="text" name="address" id="address" /><br><br>
        </div>
        <div class="ip">
          <a>Mask IPv6: </a>
          <input type="text" name="netmask" id="netmask" /><br><br>
        </div>
        <div class="ip">
          <a>Gateway IPv6: </a>
          <input type="text" name="gateway" id="gateway" /><br><br>
        </div>
        <div class="ip">
          <a>DNS IPv6: </a>
          <input type="text" name="dns1" id="dns1" /><br><br>
        </div>
        <div class="ip">
          <a>DNS IPv6: </a>
          <input type="text" name="dns2" id="dns2" /><br><br>
        </div>
        <input type="submit" id="btn_save_6" values="Save"><br><br>
        <hr>
      </div>
    </form>
  </div>
  <!-- Меню TIME ******************************************** -->
  <div id="content0-2">
    <table class="tableinthecenter">
      <tbody>
        <tr>
          <td rowspan="3">
            Date:&nbsp;</td>
          <td>
            Year
          </td>
          <td>
            <input onchange="OnYearChange(this)" class="YearChange" autofocus="autofocus" style="width: 4em" title="Current year" placeholder="2021" max="3000" min="1000" type="number" name="year" value="<?php echo $curyear; ?>">
          </td>
        </tr>
        <tr>
          <td>
            Month
          </td>
          <td>
            <select id="month" onchange="OnMonthChange(this)" class="MonthChange" style="width: 4em" title="Current month" name="month" value="<?php echo $months[$cmn - 1]; ?>">
              <?php
              for ($i = 1; $i < 13; $i++)
                echo "<option value='$i'" . $sls[$i - 1] . ">" . $months[$i - 1] . "</option>\n";
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            Day
          </td>
          <td>
            <input onchange="OnDayChange(this)" class="DayChange" style="width: 4em" title="Current day" placeholder="1" max="<?php echo $nmbdays; ?>" min="1" type="number" name="day" value="<?php echo date("j"); ?>">
          </td>
        </tr>

        <tr>
          <td rowspan="3">
            Time:&nbsp;
          </td>
          <td>
            Hours
          </td>
          <td>
            <input onchange="OnHourChange(this)" class="HourChange" style="width: 4em" title="Current hours" placeholder="14" max="24" min="0" type="number" name="hour" value="14">
          </td>
        </tr>
        <tr>
          <td>
            Minutes
          </td>
          <td>
            <input onchange="OnMinuteChange(this)" class="MonthChange" style="width: 4em" title="Current minutes" placeholder="1" max="60" min="0" type="number" name="minutes" value="1">
          </td>
        </tr>
        <tr>
          <td>
            Seconds
          </td>
          <td>
            <input onchange="OnSecondChange(this)" class="SecondChange" style="width: 4em" title="Current seconds" placeholder="0" max="60" min="0" type="number" name="day" value="0">
          </td>
        </tr>
      </tbody>
    </table>
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