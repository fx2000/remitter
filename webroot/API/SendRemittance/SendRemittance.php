<?php
/**
 * Send Remittance
 *
 * Remitter API
 *
 * @package       API
 * @since         remitter(tm) v 1.5.0
 */
?>

<!DOCTYPE html>
<html>
<body>
    <form name="API.SendRemittance" id="API.SendRemittance" enctype="multipart/form-data" method="post" action="JSON.SendRemittance.php" accept-charset="utf-8">  
    <table>
        <TR><TD>API Key</TD><TD><input name="APIkey" type="text"></TD></TR>
        <TR><TD>Device ID</TD><TD><input name="DeviceID" type="text"></TD></TR>
        <TR><TD>Cédula</TD><TD><input name="TaxID" type="text"></TD></TR>
        <TR><TD>PIN</TD><TD><input name="PIN" type="number"></TD></TR>
        <TR><TD>Beneficiario</TD><TD><input name="RecipientID" type="number"></TD></TR>
        <TR><TD>Monto</TD><TD><input name="Amount" type="number"></TD></TR>
    </table>
    <div class="submit"><input type="submit" value="Submit"></div>
</form> 
</body>
</html>
