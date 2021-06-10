<?php $output = '>'; ?>

<div class="vtabs">
			<div id="content2-1">
			Contents 1...<br>
			Contents 1...<br>
			</div>
			<div id="content2-2">
			Contents 2...<br>
			Contents 2...<br>
			</div>
			<div id="content2-3">
			Contents 3...
			</div>
			<div id="content2-4">
			<div id="frm_main" style="height:600px; width: 80%;">
                <?php $host = 'https://' . $_SERVER['SERVER_NAME'] . ':4200'; ?>

                <iframe src="<?php echo $host; ?>" style="height: -webkit-fill-available; width: 100%;" align="left">You're browser does not support IFRAME</iframe>
            </div>
        
        <!-- <iframe src="https://192.168.8.128:4200" style="height: -webkit-fill-available; width: -webkit-fill-available;"  align="left">You're browser does not support IFRAME</iframe> -->
       
        <!--
        <object data="https://192.168.0.105:4200" class="stretched"  align="left">You're browser does not support IFRAME</object>
        -->
        
        <!-- <object>
          <iframe src="https://192.168.8.128:4200" height="100%" style="height: -webkit-fill-available; width: -webkit-fill-available;"  align="left">You're browser does not support IFRAME</iframe>
        </object> -->
       
        <!--
        <object>
          <embed src="https://192.168.0.105:4200" height="100%" style="height: -webkit-fill-available; width: -webkit-fill-available;"  align="left"></embed>
        </object>
        -->
      </div>
			<div class="vtabs__links">
			<a href="#content2-1">RS232/RS485</a>
			<a href="#content2-2">Modbus RTU/TCP</a>
			<a href="#content2-3">DarkStat</a>
			<a href="#content2-4">Telnet/ssh</a>
			</div>
	</div>
