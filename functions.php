<?php

$write_pipe = "/tmp/iodwr";
$read_pipe = "/tmp/iodrw";

function arr2ini(array $a, array $parent = array())
{
  $out = '';
  foreach ($a as $k => $v) {
    if (is_array($v)) {  //subsection case
      //merge all the sections into one array...
      $sec = array_merge((array) $parent, (array) $k);
      //add section information to the output
      $out .= '[' . join('.', $sec) . ']' . PHP_EOL;
      //recursively traverse deeper
      $out .= arr2ini($v, $sec);
    } else //plain key->value case
      $out .= "$k=$v" . PHP_EOL;
  }
  return $out;
}

function Get1stTabRow()
{
  $str = @file_get_contents('/etc/hostname');
  if (isset($_GET['timer'])) {
    $RefreshTime = $_GET['timer'];
    $conf = parse_ini_file("/var/www/html/settings", TRUE);
    $conf['RefreshTime']['RefreshTime'] = "" . $RefreshTime;
    $inisave = arr2ini($conf);
    //			print_r($inisave);
    flush(); // we need it because previous call of "parse_ini_file()" locks the file for some time
    @file_put_contents("/var/www/html/settings", $inisave);
  } else {
    $conf = parse_ini_file("/var/www/html/settings");
    $RefreshTime = $conf['RefreshTime'];
  }
  $ret = trim($str) . '<input onchange="OnTimerChange(this)" class="AutoRefresh" autofocus="autofocus" style="float: right; width: 3em" title="The page auto-refreshing time (sec.)" placeholder="' . $RefreshTime . '" max="99" min="0" type="number" name="timer" />';
  return $ret;
  //return gethostname();
}

function getdata()
{
  return shell_exec('date');
}

function getuptime()
{
  //return shell_exec('uptime -p');
  $str = strtok(exec('cat /proc/uptime'), ".");
  $days = sprintf("%2d", $str / (3600 * 24));
  $hours = sprintf("%2d", ($str % (3600 * 24)) / 3600);
  $mins = sprintf("%2d", (($str % (3600 * 24)) % 3600) / 60);
  $secs = sprintf("%2d", (($str % (3600 * 24)) % 3600) % 60);
  return "days " . $days . ": hours " . $hours . ": minutes " . $mins . ": seconds " . $secs;
}

function getmemusage()
{
  //echo shell_exec('free -m');
  //$str = @file_get_contents('/tmp/p.r');
  //						$outp = array();
  //						$ret =  0;
  //$str = system('free -m');
  //$str = exec('free -m', $outp, $ret);
  //						$str = exec('sh p.cmd', $outp, $ret);
  //$str = shell_exec('free -m >/tmp/p.r');
  //echo $str.len($outp);
  //passthru('free -m');
  //passthru('sh p.cmd');
  //						echo $str."  ".count($outp)." ret=".$ret;
  //exec('echo test123 | sudo -S "free -m" 2>&1', $outp);
  //exec('free -m 2>&1', $outp);
  //print_r($outp);
  //$str = @file_get_contents('/proc/meminfo');
  $str = shell_exec('cat /proc/meminfo | grep Mem');
  //return str_replace("\n", '<br>', $str);
  $arrStr = explode("\n", $str);
  $str = '<table class="tableinthecenter"><thead><tr>';
  $arrCell = [];
  for ($i = 0; $i < count($arrStr); $i++) {
    if (trim($arrStr[$i]) === '') continue;
    $arrSub = explode(":", $arrStr[$i]);
    $strTd = str_replace('Mem', '', $arrSub[0]) . ' (kB)';
    $arrCell[] = trim(str_replace('kB', '', $arrSub[1]));
    if ($strTd !== '')
      $str = $str . '<th>&nbsp;' . $strTd . '</th>';
  }
  $str = $str . '</tr></thead><tbody><tr>';
  for ($i = 0; $i < count($arrCell); $i++)
    $str = $str . '<td>' . $arrCell[$i] . '</td>';
  return $str . '</tr></tbody></table>';
}

function getprocinfo()
{
  $proc = shell_exec('top -b -n 1 | tail -n +7');
  //$proc = str_replace("\n", '<br>', shell_exec('ps -ef'));
  //return (strlen($proc) < 5) ? "No processes info" : str_replace("\n", '<br>', $proc);
  $retstr = "No processes info\n";
  if (strlen($proc) > 5) {
    $retstr = "<table class=\"proctable tableinthecenter\">\n<thead>";
    $delim = "\n\r\f";
    $ln = strtok($proc, $delim);
    $tagCell = 'th';
    while ($ln !== false) {
      $retstr = $retstr . "\t<tr>\n\t\t";
      $cells = explode(' ', $ln);
      foreach ($cells as $cell) {
        $cell = trim($cell);
        if ($cell == '')
          continue;
        $retstr = $retstr . '<' . $tagCell . '> &nbsp;' . $cell . ' </' . $tagCell . ">\n";
      }
      $retstr = $retstr . "\t</tr>\n";
      if ($tagCell == 'th')
        $retstr = $retstr . "</thead><tbody>\n";
      $tagCell = 'td';
      $ln = strtok($delim);
    }
    $retstr = $retstr . "</tbody></table>\n";
  }
  return $retstr;
}

/** Читает содержимое $filename и ищет ключевое слово $userUser. Возвращает строку файла shadow без имени пользователя.
 */
function UserIdentity($user_arr, $userName)
{
  if(isset($user_arr) && $user_arr !== '') {
    foreach ($user_arr as &$elem) {
      $arrS2 = explode(":", $elem);
      if (count($arrS2) > 0) {
        if ($arrS2[0] == $userName)
          return $arrS2[1];
      }
    }
    return null;
  }
}

/** Читает содержимое $filename и ищет ключевое слово $userUser. Возвращает массив из строки файла.
 */
function FindUserInFile($userUser, $filename)
{
  $accounts = @file_get_contents($filename);
  if (strlen($accounts) == 0) {
    $accounts = shell_exec("sudo cat " . $filename);
  }
  $arrStr = explode("\n", $accounts);
  $ret = '';
  for ($i = 0; $i < count($arrStr) && ($ret == ''); $i++) {
    $s1 = trim($arrStr[$i]);
    $arrS2 = explode(":", $s1);
    if (count($arrS2) > 0) {
      if ($arrS2[0] == $userUser)
        $ret = $arrS2[1];
    }
  }
  return $ret;
}

/** Отправляет команду на iodc.d на выдачу блока информации $BlockName. Возвращает сроковое содержимое ответа в json.
 */
function RequestIodExch($BlockName)
{
  global $write_pipe, $read_pipe;
  exec("echo " . $BlockName . " > " . $write_pipe);
  return read_pipe($read_pipe);
}

/** Читает содержимое pipe-файла $filename и возвращает содержимое json-выражение  в массив */
function read_pipe($filename)
{
  $handle = fopen($filename, 'r');
  if (!$handle) {
    print_r('Error functions.read_pipe(): filename=' . $filename);
    return null;
    // return "error in handle";
  }
  $read = fread($handle, 8192);
  fclose($handle);
  return $read;
}

/** Читает содержимое pipe-файла $filename и возвращает содержимое json-выражение  в массив */
function archOS()
{
  $cpu_str = php_uname('m'); // тип архитектуры цпу
  if ($cpu_str == "aarch64") {
    return " ";
  }
  elseif ($cpu_str == "i686") {
    return ":";
  }
  else {
    return " ";
  }
}

/** Возвращает true, если формат IP адреса корректен. vStr - IP адрес или маска? */
function IpAddressIsCorrect($vStr, $vFirstOctet)
{
  If (strpos($vStr, ",") > 0) return false; // запятые недопустимы
  $OctetsArray = explode(".", $vStr);
  // print_r(count($OctetsArray)); echo "<br />";
  If (count($OctetsArray) == 4) {
    For ($i = 0; $i<= 3; $i++) {
      // print_r($OctetsArray[$i]); echo "<br />";
      // print_r(true); echo "<br />";
      If (!is_numeric($OctetsArray[$i])) {
        return False;
      }     
      // print_r($OctetsArray[0] >= $vFirstOctet && $OctetsArray[$i] >= 0 && $OctetsArray[$i] <= 255); echo "<br />";   
      If (!($OctetsArray[0] >= $vFirstOctet && $OctetsArray[$i] >= 0 && $OctetsArray[$i] <= 255)) {
        return false;
      } 
    }
    return true;
  } else {
    return False;
  }         
}

/** Возвращает имя группы пользователей по её id - vID. vArr - соответствует 2-мерному массиву $groups_arr в systemsets.php*/
function GroupName_by_ID($vArr, $vID)
{
  for ($i=0; $i<count($vArr); $i++) {
    if ($vArr[$i]["id"] == $vID) {
      return $vArr[$i]["name"];
    }
  }
  return '';
}
?>

