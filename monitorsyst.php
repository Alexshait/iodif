						<div class="vtabs">
							  <div id="loganalyzer_tab" style="height:600px; width: 100%;">
							  	<?php $host = 'http://' . $_SERVER['SERVER_NAME'] . '/loganalyzer'; ?>
								<iframe src="<?php echo $host; ?>" style="height: -webkit-fill-available; width: 100%;" align="left">You're browser does not support IFRAME</iframe>
							  </div>
							  <div id="mrtg_tab" style="height:600px; width: 100%;">
							  	<?php $host = 'http://' . $_SERVER['SERVER_NAME'] . '/mrtg'; ?>
								<iframe src="<?php echo $host; ?>" style="height: -webkit-fill-available; width: 100%;" align="left">You're browser does not support IFRAME</iframe>
							  </div>
							  <div id="content1-3">
								Contents 3...
							  </div>
							  <div id="content1-4">
								Contents 4...
							  </div>
							  <div class="vtabs__links">
								<a href="#loganalyzer_tab">LogAnalyzer</a>
								<a href="#mrtg_tab">MRTG</a>
								<a href="#content1-3">DarkStat</a>
								<a href="#content1-4">Modbus RTU/ASCII/TCP</a>
							  </div>
						</div>
