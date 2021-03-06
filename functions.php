<?php

$write_pipe = "/var/www_tmp/iodwr";
$read_pipe = "/var/www_tmp/iodrw";


/**Возвращает строку выдачи системной команы $cmd. */
function GetSystemCmd($cmd)
{
  return shell_exec($cmd);
}


/** Парcит каждый элемент $user_arr и ищет ключевое слово $userUser. Возвращает строку файла shadow $6$соль$хэш, соответствующей найденному пользователю. */
function UserIdentity($user_arr, $userName)
{
  if (isset($user_arr) && $user_arr !== '') {
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

/** Читает содержимое $filename и ищет ключевое слово $userUser. Возвращает массив из строки файла. */
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

function RequestIodExch1($BlockName)
{
  global $write_pipe, $read_pipe;
  exec("echo " . $BlockName . " > " . $write_pipe);
  return 'OK'; //read_pipe($read_pipe);
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

/** возвращает тип ОС: aarch64 или i686 */
function archOS()
{
  return php_uname('m'); // тип архитектуры цпу
}

/** Возвращает true, если формат IP адреса корректен. vStr - IP адрес или маска? */
function IpAddressIsCorrect($vStr, $vFirstOctet)
{
  if (strpos($vStr, ",") > 0) return false; // запятые недопустимы
  $OctetsArray = explode(".", $vStr);
  // print_r(count($OctetsArray)); echo "<br />";
  if (count($OctetsArray) == 4) {
    for ($i = 0; $i <= 3; $i++) {
      // print_r($OctetsArray[$i]); echo "<br />";
      // print_r(true); echo "<br />";
      if (!is_numeric($OctetsArray[$i])) {
        return False;
      }
      // print_r($OctetsArray[0] >= $vFirstOctet && $OctetsArray[$i] >= 0 && $OctetsArray[$i] <= 255); echo "<br />";   
      if (!($OctetsArray[0] >= $vFirstOctet && $OctetsArray[$i] >= 0 && $OctetsArray[$i] <= 255)) {
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
  for ($i = 0; $i < count($vArr); $i++) {
    if ($vArr[$i]["id"] == $vID) {
      return $vArr[$i]["name"];
    }
  }
  return '';
}

/** Конвертирует массив от $confBlocks_obj->{"users"} в многомерный массив Array */
function ExplodeUsers($vUsers_arr)
{
  if (count($vUsers_arr) >= 1) {
    for ($i = 0; $i < count($vUsers_arr); $i++) {
      $user_prop[$i] = explode(":", $vUsers_arr[$i]);
    }
  }
  return $user_prop;
}

/** Конвертирует массив от $confBlocks_obj->{"groups"} в массив Array('name[]', 'id[]') */
function ExplodeGroups($vGroups_arr)
{
  $grp_count = count($vGroups_arr);
  if ($grp_count >= 1) {
    for ($i = 0; $i < $grp_count; $i++) {
      $groups_arr[$i] = array(
        "name" => explode(":", $vGroups_arr[$i])[0],
        "id" => explode(":", $vGroups_arr[$i])[2]
      );
    }
  }
  return $groups_arr;
}

/** Возвращает id группы из массива групп $vGroup_list_arr, которая задается ф-цией ExplodeGroups  */
function getMaxUserId($vUser_list_arr)
{
  $id = 0;
  for ($i = 0; $i < count($vUser_list_arr); $i++) {
    if ($vUser_list_arr[$i][2] > $id) { // $users_arr[$i][2] - это id пользователя
      $id = $vUser_list_arr[$i][2];
    }
  }
  return $id;
}

/** Возвращает true, если в массиве пользователей $vUsers_arr не найден пользователь с именем $vUser */
function NoDoubleUsers($vUser, $vUsers_arr) {
  foreach ($vUsers_arr as $elem) {
    if ($elem[0] == $vUser) {
      return false;
    }
  }
  return true;
}

/** Генерирует соль */
function SaltGenerator() {
  $Base64Characters = [".", "/", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"];
  $slt = '';
  for ($i=0; $i<=7; $i++) {
    $slt = $slt .  $Base64Characters[floor(rand(0,count($Base64Characters)))];
  }
  return $slt;
}

/** ф-ция возвращает true если на заданное имя хоста в vStr соответствует требованиям */ 
function HostNameIsCorrect($vStr) {
  $result  = False;
  If (strlen($vStr) > 0) {
    $vStr = strtolower($vStr);
    $charArray = str_split($vStr, 1);
      For ($i = 0; $i < count($charArray); $i++) {
        $asciiNum = ord($charArray[$i]);
          If ($asciiNum = 45) Continue; //  знак '-' допустим
          If ($asciiNum < 48 || ($asciiNum > 57 && $asciiNum < 97) || $asciiNum > 122) return $result;
      }
      $result = True;
  }
  return $result;
}


function contentoftab2_editUsers()
{
  return '<br><br>
  <form method="post" action="">
    <div id="frm_main">
      <div class="input_block">
        <a>User group: </a>
        <input type="text" name="user_group" id="user_group"> <br><br>
      </div>
      <div class="input_block">
        <a>User info: </a>
        <input type="text" name="user_info" id="user_info"> <br><br>
      </div>
      <input type="submit" id="user_add_submit" name="user_add_submit" value="Save" class="submit_btn"> <br><br>
      <hr>
    </div>
  </form>';
}

/** Возвращает текст javascript, перенаправляющий на таб, указанный в vUrl */
function GoToCurrentPage($vUrl)
{
  return ' <script type="text/javascript">
  window.location.href = "index.php' . $vUrl . '";
  </script>';
}

/** Возвращает массив данных из выдачи команды $shellCommand */
function GetArrayFromShell($shellCommand)
{
  $str = shell_exec($shellCommand);
  $lines_arr = explode("\n", $str);
  for ($i = 0; $i < count($lines_arr) - 1; $i++) {
    $single_space_str = preg_replace('!\s+!', ' ', $lines_arr[$i]);
    $arr[$i] = explode(" ", $single_space_str);
    // print_r($arr); echo "<br />";
  }
  return $arr;
}