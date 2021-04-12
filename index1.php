<?php
session_start();
if($_GET['do'] == 'logout'){
 unset($_SESSION['admin']);
 session_destroy();
} 
require_once dirname(__FILE__).'/functions.php';
$pass = FindUserInFile($_SESSION['admin'], '/etc/passwd');
if($pass == '') {
  header("Location: login1.php");
  exit; 
}
// Authorized !
?>

<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<!DOCTYPE html"
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<!-- <html xmlns="http://www.w3.org/1999/xhtml"> -->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <!-- <meta http-equiv="Refresh" content="0; URL=http://192.168.0.105/#content-1"> -->
        <title>InOutDev solution test page</title>
        <link rel="stylesheet" type="text/css" href="..\css\base.css"/>
        <link rel="stylesheet" type="text/css" href="..\css\tabs.css"/>
        <style type="text/css" media="screen">
        </style>
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> -->
        <script src="../js/JQuery/jquery.min.js"></script>
        <script language="JavaScript" src="../js/base.js" charset="utf-8" ></script>
    </head>
    <body>
        <!-- <div class="main_page"> -->
            <div class="main_page tabs">
                <div class="tab">
                    <input type="radio" id="tab-1" name="tab-group-1"
                        <?php
						$bTabSet = isset($_GET['tab']);
                        if(!$bTabSet || ($bTabSet && $_GET['tab'] == "tab1"))
                            echo  "checked";
                        ?>>
                    <label for="tab-1">Status</label>
                    <div id="tab1" class="content">
                        <?php
							include(dirname(__FILE__).'/dashboard.php');
                        ?>
                    </div>
                </div>
                <div class="tab">
                    <input type="radio" id="tab-2" name="tab-group-1"
                        <?php
                        if(isset($_GET['tab']) && $_GET['tab'] == "tab2")
                            echo  "checked";
                        ?>>
                    <label for="tab-2">System Settings</label>
                    <div id="tab2" class="content">
                        <?php
							include(dirname(__FILE__).'/systemsets.php');
                        ?>
	                </div>
                </div>
                <div class="tab">
                    <input type="radio" id="tab-3" name="tab-group-1"
                        <?php
                        if(isset($_GET['tab']) && $_GET['tab'] == "tab3")
                            echo  "checked";
                        ?>>
                    <label for="tab-3">Monitoring Systems</label>
                    <div id="tab3" class="content">
                        <?php
							include(dirname(__FILE__).'/monitorsyst.php');
                        ?>
                    </div>
                </div>
                <div class="tab">
                    <input type="radio" id="tab-4" name="tab-group-1"
                        <?php
                        if(isset($_GET['tab']) && $_GET['tab'] == "tab4")
                            echo  "checked";
                        ?>>
                    <label for="tab-4">Control Systems</label>
                    <div id="tab4" class="content">
                        <?php
							include(dirname(__FILE__).'/controlsyst.php');
                        ?>
                    </div>
                </div>
				<div class="tab">
                    <input type="radio" id="tab-5" name="tab-group-1"
                        <?php
                        if(isset($_GET['tab']) && $_GET['tab'] == "tab5")
                            echo  "checked";
                        ?>>
                    <label for="tab-5">TTY</label>
                    <div id="tab5" class="content">
					<?php $host='https://'.$_SERVER['SERVER_NAME'].':4200'; ?>
					<!--
						<object>
						<embed src="<?php echo ' '.$host; ?>" height="100%" style="height: -webkit-fill-available; width: -webkit-fill-available;"  align="left"></embed>
						</object> -->
						  <iframe src="<?php echo $host; ?>" height="100%" style="height: -webkit-fill-available; width: 100%;"  align="left">You're browser does not support IFRAME</iframe>
                    </div>
				</div>
                <div class="tab">
                    <input type="radio" id="tab-6" name="tab-group-1"
                        <?php
                        if(isset($_GET['tab']) && $_GET['tab'] == "tab6")
                            echo  "checked";
                        ?>>
                    <label for="tab-6">About the project</label>
                    <div id="tab6" class="content">
                        <p>Hardware-software solution</p><br>
                        <p>"InOutDev" </p>
                    </div>
                </div>
                <div class="tab" style="float: right; margin-top: 8px; margin-right: 20px">
					<a href="index1.php?do=logout">Logout</a> 
                </div>
            </div>
        <!-- </div> -->
    </body>
</html>
