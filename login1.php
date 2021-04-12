<br/> 
<form method="post">
	<div style="width: 250">
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
  $userUser = $_POST['user'];
  if(isset($userUser) && $userUser !== '')
    $pass = FindUserInFile($userUser, '/etc/passwd');
  if($pass != '') {
    $pass = FindUserInFile($userUser, '/etc/shadow');
    if($pass != '') {
      //echo $userUser." is OK!"."<\br>";
        $arrPwd = explode("$", $pass);
        $s1 = $arrPwd[1];
        $salt = $arrPwd[2];
        $hashed = crypt($_POST['pass'], substr($pass, 0, strlen($s1) + strlen($salt) + 3));
        //$pwdhash = $arrPwd[3];
        //if (password_verify($hash, $pwdhash))
        if ($pass == $hashed)
        {
           echo '<p>OK!</p>';
           $_SESSION['admin'] = $userUser;
           header("Location: index1.php");
           exit;
        }
        else
          echo '<p>Wrong password!</p>';
    }
    else
      echo '<p>Wrong login ('.$userUser.')!</p>';
  }
  else
    echo '<p>The login ('.$userUser.') is absent!</p>';
}
/*
if($_POST['submit']){
 if($users == $userUser && $pass == md5($_POST['pass']))
{
 $_SESSION['admin'] = $users;
 header("Location: index1.php");
 exit;
 }
else echo '<p>Wrong login or password!</p>';
}
*/
?>  
