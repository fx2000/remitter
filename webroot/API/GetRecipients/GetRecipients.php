<?php
/**
 * Get Recipients
 *
 * remitter API
 *
 * @package       API
 * @since         remitter(tm) v 1.5.0
 */
?>

<!DOCTYPE html>
<html>
<body>
    <form name="API.GetRecipients" id="API.GetRecipients" enctype="multipart/form-data" method="post" action="JSON.GetRecipients.php" accept-charset="utf-8">  
    <table>
        <TR><TD>API Key</TD><TD><input name="APIkey" type="text"></TD></TR>
        <TR><TD>Device ID</TD><TD><input name="DeviceID" type="text"></TD></TR>
        <TR><TD>Cédula</TD><TD><input name="TaxID" type="text"></TD></TR>
        <TR><TD>PIN</TD><TD><input name="PIN" type="text"></TD></TR>
    </table>
    <div class="submit"><input type="submit" value="Submit"></div>
</form> 
</body>
</html>
