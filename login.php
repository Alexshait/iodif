<br/> 
<form method="post">
	<div style="width: 400">
		Username: <input style="float: right" type="text" name="user" /> <br /> 
<br/> 
		Password: <input style="float: right" type="password" name="pass" /> <br />
<br/> 
		<input type="submit" name="submit" value="Login" />
	</div>
</form>

<?php
session_start();

require_once dirname(__FILE__).'/functions.php';

if($_POST['submit']){
  $pass = '';
  $userName = $_POST['user'];
  if($userName !== '') {
    $json_blocks_str = RequestIodExch("shadow");
    $json_obj = json_decode(substr($json_blocks_str, 0, strlen($json_blocks_str)-1));
    $pass = UserIdentity($json_obj->{'shadow'}, $userName);
    print_r($pass);echo "<br />";
    if($pass != null) {
      $arrPwd = explode("$", $pass);
      $s1 = $arrPwd[1];
      $salt = $arrPwd[2];
      $hashed = crypt($_POST['pass'], substr($pass, 0, strlen($s1) + strlen($salt) + 3));
      if ($pass == $hashed)
      {
         echo '<p>OK!</p>';
         $_SESSION['admin'] = $userName;
         header("Location: index.php");
         exit;
      }
      else
        echo '<p>Wrong password!</p>';
    }
  }
  else
    echo '<p>The login or password is not correct!</p>';
}
/*
if($_POST['submit']){
 if($users == $userName && $pass == md5($_POST['pass']))
{
 $_SESSION['admin'] = $users;
 header("Location: index.php");
 exit;
 }
else echo '<p>Wrong login or password!</p>';
}
*/
?>  
