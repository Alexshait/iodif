var inCharSet = ' abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
var strConsInput = '';
var sPrompt = '>';
$(document).ready(function () {

	// A.O. cut off all URL parameter
//	window.location.hash = '';
//	window.location.search = '';
//	var strloc = window.location.toString();
//	history.pushState(null, null, strloc); // this is a method to changhe URL without the page reloading

    $('body').on('keypress', "textarea#sysconsole", function (e) {
        if (13 == e.which) { // keyCode.ENTER
            $.ajax({
                type: "post",
                url: "consoleenter.php?inp=" + strConsInput // &#063;
            }).done(function (msg) {
                if (msg.length > 0) {
                    e.target.value = e.target.value + '\n' + msg + '\n' + sPrompt;
                }
            });
            strConsInput = '';
        }
        //return /\w\d/.test(e.which);
        bCharVis = (inCharSet.indexOf(String.fromCharCode(e.which)) >= 0);
        if (bCharVis)
            strConsInput = strConsInput + String.fromCharCode(e.which);
        return bCharVis;
    });
    $('div.vtabs__links a').click(
        function () {
            pardiv = $(this).closest('div.vtabs__links');
            if (pardiv.length) {
                pardiv.attr('sela', $(this).index());
            }
        }
    );
    $('div.tab label').click(
        function (e) {
            dv = $(this).parent().find('div.vtabs__links');
            if (dv.length) {
                as = dv.find('a');
                if (as.length) {
                    ind = 0;
                    atrsela = dv.attr('sela');
                    if (atrsela != undefined)
                        ind = parseInt(atrsela, 10);
                    window.location.hash = $(as[ind]).attr('href');
                    strloc = window.location.origin;
                    history.pushState(null, null, strloc); // this is a method to changhe URL without the page reloading
                }
            }
        }
    );
    tm = "0";
    TmInp = $('input.AutoRefresh');
    if (TmInp.length) {
        tm = TmInp.attr("placeholder");
        TmInp.attr("value", tm);
    }
    tm = parseInt(tm, 10) * 1000; // sec --> msec.
    if (tm > 0)
        setTimeout(
			function tick() {
			    setTimeout(tick, tm);
			    if ($('div#tab1').css('z-index') != "1")
			        return;
			    $.ajax({
			        type: "post",
			        url: "tickhandler.php"
			    }).done(function (msg) {
			        if (msg.length > 0) {
			            arr = msg.split('\v');
			            if (arr.length > 0)
			                $('td#datetime').html(arr[0]);
			            if (arr.length > 1)
			                $('td#uptime').html(arr[1]);
			            if (arr.length > 2)
			                $('td#memusage').html(arr[2]);
			            if (arr.length > 3) {
			                $('div#procinfo').html(arr[3]);
			                $('div#procinfo1').html(arr[3]);
			            }
			        }
			    });
			},
			tm
		);

    if (window.location.hash == '')
        window.location.hash = '#content-1';
    //history.pushState(null, null, strloc); // this is a method to changhe URL without the page reloading
});

function OnTimerChange(obj) {
	location.assign(location.origin + "?timer=" + obj.value);
}
function OnYearChange(obj) {
    var val = obj.value();
}
function OnMonthChange(obj) {
    var val = obj.value();
}
function OnDayChange(obj) {
    var val = obj.value();
}
function OnHourChange(obj) {
    var val = obj.value();
}
function OnMinuteChange(obj) {
    var val = obj.value();
}
function OnSecondChange(obj) {
    var val = obj.value();
}
