<style>
    td{
        padding: 10px;
    }
</style>
<script>
function printPage()
{
    document.getElementById('print').style.display = 'none';
    window.print();
    document.getElementById('print').style.display = 'block';
}
</script>
<div style="float: left;width: 80%;">
<table>
    <tr>
        <td>
            Operator : <?php echo $result['operator_id']?>
        </td>
    </tr>
    <tr>
        <td>
            Phone No : <?php echo $result['phone_no']?>
        </td>
         </tr>
    <tr>
        <td>
            Topup Amount : $<?php echo $result['amount']?>
        </td>
         </tr>
    <tr>
        <td>
            Transaction Id : <?php echo $result['transaction_id']?>
        </td>
         </tr>
         <?php
         if($result['recharge_status'] != 0)
            {
        ?>
         
    <tr>
        <td>
            Error Code : <?php echo $result['recharge_status']?>
        </td>
    </tr>
    <?php 
            }
    ?>
    <tr>
        <td>
            
        </td>
    </tr>
</table>
</div>
<div style="text-align: right;float: left;width: 20%;">
    <input type="button" id="print" value="Print" onclick="printPage()">
</div>