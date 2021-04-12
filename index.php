<?php
session_start();
if($_GET['do'] == 'logout'){
 unset($_SESSION['admin']);
 session_destroy();
} 
require_once dirname(__FILE__).'/functions.php';
$pass = FindUserInFile($_SESSION['admin'], '/etc/passwd');
if($pass == '') {
  header("Location: login.php");
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
		<!--
        <link rel="stylesheet" href="..\css\base.css">
        <link rel="stylesheet" href="..\css\tabs.css">
		-->
        <style type="text/css">
  * {
    margin: 0px 0px 0px 0px;
    padding: 0px 0px 0px 0px;
  }

  body, html {
    padding: 3px 3px 3px 3px;

    background-color: #D8DBE2;

    font-family: Verdana, sans-serif;
    font-size: 11pt;
    text-align: center;
  }

  div.main_page {
    position: absolute;
    display: table;
    top: 0;
    left: 1px;
    width: 99.7%;
    height: 99.8%;
    /*margin-bottom: 3px;
    margin-left: auto;
    margin-right: auto;*/
    padding: 0px 0px 0px 0px;

    border-width: 2px;
    border-color: #212738;
    border-style: solid;

    background-color: #FFFFFF;

    text-align: center;
  }

  div.page_header {
    height: 99px;
    width: 100%;

    background-color: #F5F6F7;
  }

  div.page_header span {
    margin: 15px 0px 0px 50px;

    font-size: 180%;
    font-weight: bold;
  }

  div.page_header img {
    margin: 3px 0px 0px 40px;

    border: 0px 0px 0px;
  }

  div.table_of_contents {
    clear: left;

    min-width: 200px;

    margin: 3px 3px 3px 3px;

    background-color: #FFFFFF;

    text-align: left;
  }

  div.table_of_contents_item {
    clear: left;

    width: 100%;

    margin: 4px 0px 0px 0px;

    background-color: #FFFFFF;

    color: #000000;
    text-align: left;
  }

  div.table_of_contents_item a {
    margin: 6px 0px 0px 6px;
  }

  div.content_section {
    margin: 3px 3px 3px 3px;

    background-color: #FFFFFF;

    text-align: left;
  }

  div.content_section_text {
    padding: 4px 8px 4px 8px;

    color: #000000;
    font-size: 100%;
  }

  div.content_section_text pre {
    margin: 8px 0px 8px 0px;
    padding: 8px 8px 8px 8px;

    border-width: 1px;
    border-style: dotted;
    border-color: #000000;

    background-color: #F5F6F7;

    font-style: italic;
  }

  div.content_section_text p {
    margin-bottom: 6px;
  }

  div.content_section_text ul, div.content_section_text li {
    padding: 4px 8px 4px 16px;
  }

  div.section_header {
    padding: 3px 6px 3px 6px;

    background-color: #8E9CB2;

    color: #FFFFFF;
    font-weight: bold;
    font-size: 112%;
    text-align: center;
  }

  div.section_header_red {
    background-color: #CD214F;
  }

  div.section_header_grey {
    background-color: #9F9386;
  }

  .floating_element {
    position: relative;
    float: left;
  }

  div.table_of_contents_item a,
  div.content_section_text a {
    text-decoration: none;
    font-weight: bold;
  }

  div.table_of_contents_item a:link,
  div.table_of_contents_item a:visited,
  div.table_of_contents_item a:active {
    color: #000000;
  }

  div.table_of_contents_item a:hover {
    background-color: #000000;

    color: #FFFFFF;
  }

  div.content_section_text a:link,
  div.content_section_text a:visited,
   div.content_section_text a:active {
    background-color: #DCDFE6;

    color: #000000;
  }

  div.content_section_text a:hover {
    background-color: #000000;

    color: #DCDFE6;
  }

  div.validator {
  }

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button
{
	opacity: 1;
}

.stretched {
    /*width: 100%;*/
    width: -moz-available;          /* WebKit-based browsers will ignore this. */
    width: -webkit-fill-available;  /* Mozilla-based browsers will ignore this. */
    width: stretch;
    width: fill-available;
    height: -moz-available;          /* WebKit-based browsers will ignore this. */
    height: -webkit-fill-available;  /* Mozilla-based browsers will ignore this. */
    height: stretch;
    height: fill-available;
}

.proctable td
{
    padding: 5px;
}

table .tableinthecenter
{
    /*width: 350px;*/
    margin: auto;
}
.tabs {
  position: relative;
  min-height: -webkit-fill-available; 
  clear: both;
  /*margin: 25px 0;*/
}
.tab {
  float: left;
}
.tab label {
  background: #eee;
  padding: 5px 10px 5px 10px;
  border: 1px solid #ccc;
  /*margin-left: -1px;*/
  position: relative;
  /*left: 1px;*/
  top: 6px;
}
.tab [type=radio] {
  display: none;
}
.content {
  position: absolute;
  top: 28px;
  left: 0;
  background: white;
  right: 0;
  bottom: 0;
  padding: 3px;
  border: 1px solid #ccc;
  margin-top: 6px;
}
[type=radio]:checked ~ label {
  background: white;
  border-bottom: 1px solid white;
  z-index: 2;
}
[type=radio]:checked ~ label ~ .content {
  z-index: 1;
}


.vtabs {
  display: flex;
  height: fit-content;
  flex-direction: column;
}

.vtabs > div {
  height: 100%;
}

.vtabs__links {
  display: flex;
  flex-direction: row;
  order: 0;
  white-space: nowrap;
  margin-bottom: 15px;
  background-color: #fff;
  border: 1px solid #e3f2fd;
  box-shadow: 0 2px 4px 0 #e3f2fd;
  height: fit-content;
}

.vtabs__links>a {
  display: inline-block;
  text-decoration: none;
  color: #1976d2;
  padding: 6px 10px;
  text-align: center;
}

.vtabs__links>a:hover {
  background-color: rgba(227, 242, 253, 0.3);
}

.vtabs>#content-1:target~.vtabs__links>a[href="#content-1"],
.vtabs>#content-2:target~.vtabs__links>a[href="#content-2"],
.vtabs>#content0-1:target~.vtabs__links>a[href="#content0-1"],
.vtabs>#content0-2:target~.vtabs__links>a[href="#content0-2"],
.vtabs>#content0-3:target~.vtabs__links>a[href="#content0-3"],
.vtabs>#content0-4:target~.vtabs__links>a[href="#content0-4"],
.vtabs>#content0-5:target~.vtabs__links>a[href="#content0-5"],
.vtabs>#content1-1:target~.vtabs__links>a[href="#content1-1"],
.vtabs>#content1-2:target~.vtabs__links>a[href="#content1-2"],
.vtabs>#content1-3:target~.vtabs__links>a[href="#content1-3"],
.vtabs>#content1-4:target~.vtabs__links>a[href="#content1-4"],
.vtabs>#content2-1:target~.vtabs__links>a[href="#content2-1"],
.vtabs>#content2-2:target~.vtabs__links>a[href="#content2-2"],
.vtabs>#content2-3:target~.vtabs__links>a[href="#content2-3"],
.vtabs>#content2-4:target~.vtabs__links>a[href="#content2-4"]
{
  background-color: #bbdefb;
  cursor: default;
}

.vtabs>div:not(.vtabs__links) {
  display: none;
  order: 1;
  flex-grow: 1;
}

@media (min-width: 576px) {
  .vtabs {
    flex-direction: row;
    height: fit-content;
  }

  .vtabs__links {
    flex-direction: column;
    border: none;
    box-shadow: none;
    height: fit-content;
  }

  .vtabs__links>a {
    border: 1px solid #e3f2fd;
    box-shadow: 0 2px 4px 0 #e3f2fd;
    margin-bottom: 8px;
  }

  .vtabs__links>a:last-child {
    margin-bottom: 0;
  }

  .vtabs>div:not(.vtabs__links) {
    padding-left: 15px;
  }
}

.vtabs>div:target {
  display: block;
}

        </style>
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
					<a href="index.php?do=logout">Logout</a> 
                </div>
            </div>
        <!-- </div> -->
    </body>
</html>
