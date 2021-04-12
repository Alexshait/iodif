<?php
  $curyear = date("F");
  $cmn = date("m");
  $months = [];
  $sls = [];
  for ($i=1; $i<13; $i++) {
    $months[] = date("F", mktime(0, 0, 0, $i, 1, 2000));
    $sls[] = ($i == (int)$cmn) ? ' selected="selected"' : '';
  }
  $nmbdays = cal_days_in_month(1, (int)$cmn, (int)$curyear); // 1- CAL_GREGORIAN
  //echo '$nmbdays = '.$nmbdays;
?>
<div class="vtabs">
		<div id="content0-1">
		Contents 1...<br>
		Contents 1...<br>
		</div>
		<div id="content0-2">
      <table class="tableinthecenter">
      <tbody>
        <tr>
          <td rowspan="3">
            Date:&nbsp;</td>
          <td>
            Year
          </td>
          <td>
            <input onchange="OnYearChange(this)" class="YearChange" autofocus="autofocus" style="width: 4em" title="Current year" placeholder="2021" max="3000" min="1000" type="number" name="year" value="<?php echo $curyear;?>">
          </td>
        </tr>
        <tr>
          <td>
            Month
          </td>
          <td>
            <select id="month" onchange="OnMonthChange(this)" class="MonthChange" style="width: 4em" title="Current month" name="month" value="<?php echo $months[$cmn-1];?>">
            <?php
            for ($i=1; $i<13; $i++)
              echo "<option value='$i'".$sls[$i-1].">".$months[$i-1]."</option>\n";
            ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            Day
          </td>
          <td>
            <input onchange="OnDayChange(this)" class="DayChange" style="width: 4em" title="Current day" placeholder="1" max="<?php echo $nmbdays;?>" min="1" type="number" name="day" value="<?php echo date("j");?>">
          </td>
        </tr>

        <tr>
          <td rowspan="3">
            Time:&nbsp;
          </td>
          <td>
            Hours
          </td>
          <td>
            <input onchange="OnHourChange(this)" class="HourChange" style="width: 4em" title="Current hours" placeholder="14" max="24" min="0" type="number" name="hour" value="14">
          </td>
        </tr>
        <tr>
          <td>
            Minutes
          </td>
          <td>
            <input onchange="OnMinuteChange(this)" class="MonthChange" style="width: 4em" title="Current minutes" placeholder="1" max="60" min="0" type="number" name="minutes" value="1">
          </td>
        </tr>
        <tr>
          <td>
            Seconds
          </td>
          <td>
            <input onchange="OnSecondChange(this)" class="SecondChange" style="width: 4em" title="Current seconds" placeholder="0" max="60" min="0" type="number" name="day" value="0">
          </td>
        </tr>
      </tbody>
      </table>
    </div>
		<div id="content0-3">
		Contents 3...
		</div>
		<div id="content0-4">
		Contents 4...
		</div>
		<div id="content0-5">
		Contents 5...
		</div>
		<div class="vtabs__links">
		<a href="#content0-1">Network</a>
		<a href="#content0-2">Date/Time</a>
		<a href="#content0-3">MRTG</a>
		<a href="#content0-4">Cellular(3G/4G modem)</a>
		<a href="#content0-5">COM Settings</a>
		</div>
</div>
