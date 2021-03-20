<form method="post"  id="smsformtbl">
	@csrf
    <table class="form_table">
    <tr><td>TO*</td><td><input type="hidden" name="to" id="to" value="<?= $mobile1 ?>" />
    <?php
    
        ?>
    <input type="text" name="to1" id="to1" value="<?= $mobile1 ?>" />
    </td></tr>
    <tr><td>Body*</td><td><textarea name="body" cols="60" rows="3" id="bodydata" ></textarea></td></tr>
    <tr><td></td><td><input type="submit" name="send_sms" id="send_sms" value="Submit" /></td></tr>
    </table>
    </form>
 