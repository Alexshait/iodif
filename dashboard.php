<div class="container-fluid">
  <div class="DashboardTitle">
    <img src="img/version.png" alt="Version"> Version
  </div>
  <div class="DashboardText" id="Text0">
    <?php $device_info_arr = GetArrayFromShell('uname -a'); ?>
    <p>Host name: <b><?php echo $device_info_arr[0][1]; ?></b></p>
    <p>Processor: <b><?php echo $device_info_arr[0][11]; ?></b></p>
    <p>Kernel version: <b><?php echo $device_info_arr[0][0] . ' ' . $device_info_arr[0][2]; ?></b></p>
    <p>Firmware: <b><?php echo $device_info_arr[0][3]; ?></b></p>
  </div>
  <br>
  <hr> <br>

  <div class="DashboardTitle">
    <img src="img/uptime.png" alt="Uptime"> Uptime
  </div>
  <!-- <form method="post"> -->
  <div class="DashboardText">
    <?php $uptime_str = GetSystemCmd('uptime --pretty');
    $date_str = GetSystemCmd('date'); ?>
    <p>Duration: <b><?php echo $uptime_str; ?> </b></p>
    <p>System date: <b><?php echo $date_str; ?> </b></p>

  </div>
  <input type="submit" value="Refresh" onClick="window.location.reload();">
  <!-- </form> -->

  <br>
  <hr> <br>

  <div class="DashboardTitle">
    <img src="img/cpu.png" alt="CPU"> CPU
  </div>
  <div class="DashboardText">
    <?php $response_str = RequestIodExch("cpu");
    $single_space_str = preg_replace('!\s+!', ' ', $response_str);
    $arrCpu = explode(" ", $single_space_str);
    ?>
    <p>Load: <b><?php echo $arrCpu[4]; ?></b></p>
    <p>Frequency: <b><?php echo $arrCpu[2]; ?></b></p>
    <p>Tempreture: <b><?php echo $arrCpu[10]; ?></b></p>
  </div>
  <br>
  <hr> <br>

  <div class="DashboardTitle">
    <img src="img/memory.png" alt="Memory"> Memory
  </div>
  <div class="DashboardText">
    <?php $mem_arr = GetArrayFromShell('free -h');
    $mmc_arr = GetArrayFromShell('df -h | grep /dev/mmc'); 
    // print_r(count($mem_arr)); echo "<br />";?> 
    <p>Memory Used / Total: <b><?php echo $mem_arr[1][2] . ' / ' . $mem_arr[1][1]; ?></b></p>
    <p>Swap Used / Total: <b><?php echo $mem_arr[2][2] . ' / ' . $mem_arr[2][1]; ?></b></p>
    <p>Disk Used / Total: <b><?php echo $mmc_arr[0][2] . ' / ' . $mmc_arr[0][1]; ?></b></p>
  </div>
  <br>
  <hr> <br>

  <?php
    $response_str = RequestIodExch("usb");
    $lines_arr = explode("\n", $response_str);
    for ($i = 0; $i < count($lines_arr) - 1; $i++) {
      $single_space_str = preg_replace('!\s+!', ' ', $lines_arr[$i]);
      $usbDisk_arr[$i] = explode(" ", $single_space_str);  // массив подключенных usb-дисков
    }
    $usb_arr = GetArrayFromShell('ls -la /dev/serial/by-id | grep usb'); // массив usb адаптеров
    // print_r($usbDisk_arr); echo "<br />";
    // print_r($usb_arr); echo "<br />";
    if (count($usbDisk_arr) > 0 || count($usb_arr) > 0) {
      $line_usbdisk = '';
      $line_usb = '';
      for ($i = 0; $i < count($usbDisk_arr); $i++) {
        if (count($usbDisk_arr) > 0) $line = '<p><b>Usb disk ' . $usbDisk_arr[$i][1] . ' ' . $usbDisk_arr[$i][2] . ' ' .  str_replace(',', '', $usbDisk_arr[$i][3]) . '</b></p>';
        $line_usbdisk = $line_usbdisk . $line;
      }
      for ($i = 0; $i < count($usb_arr); $i++) {
        if (count($usb_arr) > 0) $line = '<p><b>Device: ' . $usb_arr[$i][8] . '</b></p>';
        $line_usb = $line_usb . $line;
      }
      $block = '<div class="DashboardTitle">
      <img src="img/usb_hdd.png" alt="USB"> Usb devices
      </div>
      <div class="DashboardText">
      ' . $line_usbdisk . $line_usb . '
      </div>';
      echo $block;
    }
  ?>
</div>