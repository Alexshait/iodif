<?php

?>
<br><br>
<form method="post" action="">
  <div id="frm_main">
    <div class="input_block">
      <a>User name: </a>
      <input type="text" name="user_name" id="user_name" value=" <?php echo '11111111111' ?> "> <br><br>
    </div>
    <div class="input_block">
      <a>User group: </a>
      <input type="text" name="user_group" id="user_group"> <br><br>
    </div>
    <div class="input_block">
      <a>User info: </a>
      <input type="text" name="user_info" id="user_info"> <br><br>
    </div>
    <input type="submit" id="user_add_submit" name="user_add_submit" value="Save" class="submit_btn" onclick="return confirm('Do you want to save changes?')"> <br><br>
    <hr>
  </div>
</form>

