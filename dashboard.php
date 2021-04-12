<div class="vtabs">
  <div id="content-1">
    <table border class="stretched">
      <tr>	<td>Name </td>
          <td><?php echo Get1stTabRow(); ?></td>
      </tr>
      <tr>	<td>System Info</td>
          <td>
          <?php echo shell_exec('uname -a'); //php_uname('a');  ?>
          </td>
      </tr>
      <tr>	<td>Date/Time </td>
          <td id="datetime">
              <?php echo getdata(); ?>
          </td>
      </tr>
      <tr>	<td>Network connection</td>
          <td>
              <?php
              exec('ifconfig | grep BROADCAST', $arr);
              foreach($arr as $id => $st) {
                  $st = trim($st);
                  $str = "";
                  if($st[0] == 'e')
                      $str = "Ethernet";
                  elseif($st[0] == 'i' || $st[0] == 'w')
                      $str = "Wi-Fi";
                  if(strlen($str) > 0) {
                      $p = strpos($st, "RUNNING");
                      echo $str." - O".(($p > 0) ? "N" : "FF")."<br>";
                  }
              }
              ?>
          </td>
      </tr>
      <tr>	<td>Network settings</td>
          <td>
              <?php
              //$str = shell_exec('ip address | grep inet | grep global');
              //strtok($str, " ");
              //$tmp = strtok(" ");
              //$str = strtok($tmp, "/");
              $str = shell_exec('ifconfig | grep inet | grep broadcast');
              //$pos = strpos($str, "inet ");
              //$str = substr($str, $pos+5);
              $pos = strpos($str, "broadcast");
              $str = substr($str, 0, $pos);
              //str_replace("inet", "", $str);
              echo $str;
              ?>
          </td>
      </tr>
      <tr>	<td>Uptime </td>
          <td id="uptime">
              <?php echo getuptime(); ?>
          </td>
      </tr>
      <tr>	<td>CPU Usage </td>
          <td>
      <div style="padding-left: 50px; text-align: left; height: 100px;overflow:scroll;overflow-x: hidden;" id="procinfo">
      <?php echo getprocinfo(); ?>
      </div>
          </td>
      </tr>
      <tr>	<td>Temperature </td>
          <td>
              <?php
              echo ((float)(@file_get_contents('/sys/class/thermal/thermal_zone0/temp')) / 1000.0).'&deg;C';
              /*
              $str = shell_exec('dpkg -l | grep lm-sensors');
              if(strlen($str)  > 5) {
						      //echo 'Package "lm-sensors" installed.';
					      }
				      else
					      echo 'Install "lm-sensors" package to see processor temperature.';
                */
              ?>
          </td>
      </tr>
      <tr>	<td>Memory usage </td>
          <td id="memusage">
              <?php echo getmemusage(); ?>
          </td>
      </tr>
      <tr>	<td>MMC usage </td>
          <td>
              <?php
              //$str = exec('cat /proc/meminfo');
              //$str = @file_get_contents('/proc/meminfo');
              //$str = shell_exec('dpkg -l | grep mmc-utils');
              //if(strlen($str)  > 5) {
              //  exec('lspci | grep -i MMC', $arr);
              //  $str =  ($arr.len == 0) ? "No MMC" : $arr[0];
              //  echo $str;
              //  //}
              //else
              //	echo 'Install "mmc-utils" package to see MMC parameters';
              $str = 'No MMC';
              $devlist = strtolower(@file_get_contents('/proc/devices'));
              $pos = strpos($devlist , 'block');
              if($pos !== false) {
                $pos = strpos($devlist, 'devices', $pos);
                if($pos !== false) {
                  $pos = strpos($devlist, 'mmc', $pos);
                  if($pos !== false) {
                    //$str =  'MMC presents';
                    $str = shell_exec('df -l | grep /dev/mmc');
                    sscanf($str, "%s %s %s %s %s", $Nam, $Tot, $Used, $Avail, $Perc);
                    $str = '<table class="tableinthecenter">'."<thead><th>&nbsp;Total (kB)&nbsp;</th><th>&nbsp;Used (kB)&nbsp;</th><th>&nbsp;Available (kB)&nbsp;</th><th>&nbsp;Use%&nbsp;</th></thead>".
                           "<tbody><td>".$Tot."</td><td>".$Used."</td><td>".$Avail."</td><td>".$Perc."</td></tbody></table>";
                  }
                }
              }
              echo $str;
              ?>
          </td>
      </tr>
<!--
      <tr>	<td>Test </td>
          <td>
-->      
              <?php
//      $conf = parse_ini_file("/var/www/html/settings");
//      $inisave = arr2ini($conf);
//      print_r($inisave);
//      //print_r($_GET);
      ?>
<!--
          </td>
      </tr>
-->      
    </table>
		</div>
  <div id="content-2">
    <div style="margins: auto; padding-left: 50px; text-align: left; position: absolute; top: 0; height: 100%; width: fit-content; overflow:scroll;overflow-x: hidden;" id="procinfo">
            <?php echo getprocinfo(); ?>
          </div>
  </div>
  <div class="vtabs__links" sela="0">
    <a href="#content-1">Dashboard</a>
    <a href="#content-2">Processes</a>
  </div>
</div>
