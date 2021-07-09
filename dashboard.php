<div class="container-fluid">
  <div class="DashboardTitle">
    <img src="img/version.png" alt="Version"> Version
  </div>
  <div class="DashboardText" id="Text0">
    <?php $device_info_arr = GetArrayFromShell('uname -a') ?>
    <p>Host name: <b><?php echo $device_info_arr[1]; ?></b></p>
    <p>Processor: <b><?php echo $device_info_arr[11]; ?></b></p>
    <p>Kernel version: <b><?php echo $device_info_arr[0] . ' ' . $device_info_arr[2]; ?></b></p>
    <p>Firmware: <b><?php echo $device_info_arr[3]; ?></b></p>
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
    <p>Load: <b><?php echo $arrCpu[3]; ?></b></p>
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
    $mmc_arr = GetArrayFromShell('df -h | grep /dev/mmc'); ?>
    <p>Memory Used / Total: <b><?php echo $mem_arr[9] . ' / ' . $mem_arr[8]; ?></b></p>
    <p>Swap Used / Total: <b><?php echo $mem_arr[16] . ' / ' . $mem_arr[15]; ?></b></p>
    <p>Disk Used / Total: <b><?php echo $mmc_arr[2] . ' / ' . $mmc_arr[1]; ?></b></p>
  </div>
  <br>
  <hr> <br>

  <?php
    $response_str = RequestIodExch("usb");
    $single_space_str = preg_replace('!\s+!', ' ', $response_str);
    $usbDisk_arr = explode(" ", $single_space_str);                      // vfccbd подключенных usb-дисков
    $usb_arr = GetArrayFromShell('ls -la /dev/serial/by-id | grep usb'); // массив usb адаптеров
    // print_r($usb_arr);
    if (count($usbDisk_arr) > 1 || count($usb_arr) > 1) {
      $line1 = '';
      $line2 = '';
      if (count($usbDisk_arr) > 1) $line1 = '<p><b>Usb disk ' . $usbDisk_arr[1] . ' ' . $usbDisk_arr[2] . ' ' .  str_replace(',', '', $usbDisk_arr[3]) . '</b></p>';
      if (count($usb_arr) > 1) $line2 = '<p><b>Device: ' . $usb_arr[8] . '</b></p>';
      $block = '<div class="DashboardTitle">
      <img src="img/usb_hdd.png" alt="USB"> Usb devices
      </div>
      <div class="DashboardText">
      ' . $line1 . $line2 . '
      </div>';
      echo $block;
    }
  ?>
</div>