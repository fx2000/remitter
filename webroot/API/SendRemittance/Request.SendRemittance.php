<?php
/**
 * Send Remittance
 *
 * Remitter API
 *
 * @package       API
 * @since         remitter(tm) v 1.5.0
 */

include "../Dbconn.php";

class RequestSendRemittanceAPI extends Dbconn {

    /*
     * Get Settings
     */
    function SendRemittance($data) {

        $remittance = array();

        // Get User
        $user = $this->getUser($data['TaxID']);

        // Get Recipient
        $recipient = $this->getRecipient($data['RecipientID']);

        // Get Settings
        $settings = $this->getSettings();

        // Get Fees
        $tax = $settings['fee2'] - round(($settings['fee2'] / ((100 + $settings['tax']) / 100)), 2, PHP_ROUND_HALF_UP);
        $fee = $settings['fee2'] - $tax;

        // Get Paid Amount
        $amountPaid = $data['Amount'] + $settings['fee2'];

        // Get delivered amount
        $delivered = ($data['Amount'] * $settings['purchase_rate']);

        // Get current datetime
        $date = date('Y-m-d H:i:s', time());

        // Insert remittance information into remittances table
        $selSendRemittance =
            "INSERT INTO remittances (
                trans_dt,
                client_id,
                recipient_id,
                amount,
                tax,
                fee,
                payment_type,
                purchase_rate,
                amount_payed,
                amount_delivered,
                status,
                operator_id
            ) VALUES (" .
                "\"" . $date . "\"" . "," .
                $user['id'] . "," .
                $data['RecipientID'] . "," .
                $data['Amount'] . "," .
                $tax . "," .
                $fee . "," .
                PAYMENT_PP . "," .
                $settings['purchase_rate'] . "," .
                $amountPaid . "," .
                $delivered . "," .
                AVAILABLE . "," .
                OPERATOR .
            ")";
        $resSendRemittance = $this->fireQuery($selSendRemittance);
        $remittanceId = mysqli_insert_id($this->_conn);

        if ($resSendRemittance) {

            // Get Bank Details
            $bankName = $this->getBankName($recipient['bank_id']);
            $bankAccountType = $this->getBankAccountType($recipient['bank_account_type']);

            // Populate successful remittance array
            $remittance = array(
                'Company'         => COMPANY,
                'RUC'             => RUC,
                'Address'         => ADDRESS,
                'ID'              => str_pad($remittanceId, 6, "0", STR_PAD_LEFT),
                'DateTime'        => date('Y-m-d H:i A', time()),
                'Amount'          => "$" . number_format($data['Amount'], 2, ",", "."),
                'Fee'             => "$" . number_format($settings['fee2'], 2, ",", "."),
                'AmountPaid'      => "$" . number_format($amountPaid, 2, ",", "."),
                'PaymentType'     => 'Efectivo',
                'Rate'            => "BsS." . number_format($settings['purchase_rate'], 2, ",", "."),
                'AmountDelivered' => "BsS." . number_format(round(($data['Amount'] * $settings['purchase_rate']), 2, PHP_ROUND_HALF_UP), 2, ",", "."),
                'Operator'        => 'Autoservicio',
                'Device'          => 'Terminal Punto Pago #' . $data['DeviceID'],
                'SenderName'      => $user['fname1'] . ' ' . $user['fname2'] . ' ' . $user['lname1'] . ' ' . $user['lname2'],
                'SenderID'        => $data['TaxID'],
                'Origin'          => 'Panamá',
                'RecipientName'   => $recipient['fname1'] . ' ' . $recipient['fname2'] . ' ' . $recipient['lname1'] . ' ' . $recipient['lname2'],
                'RecipientID'     => $recipient['tax_id'],
                'Destination'     => 'Venezuela',
                'AccountNumber'   => $recipient['bank_account_number'],
                'AccountType'     => $bankAccountType['name'],
                'Bank'            => $bankName['name'],
                'Notes'           => NOTES
            );

            // Save invoice information to database
            $selInvoice =
            "INSERT INTO invoices (
                id_remesa,
                nombre,
                cedula,
                direccion,
                total_pagos,
                total_final,
                otro_pago,
                codigo,
                nombre_articulo,
                unidad,
                precio_neto,
                alicuota
            ) VALUES (" .
                $remittanceId . "," .
                "\"" . $remittance['SenderName'] . "\"" . "," .
                "\"" . $remittance['SenderID'] . "\"" . "," .
                "\"" . $user['address'] . "\"" . "," .
                $settings['fee2'] . "," .
                $settings['fee2'] . "," .
                $settings['fee2'] . "," .
                "\"" . CODE . "\"" . "," .
                "\"" . ARTICLE . " " . $remittance['ID'] . "\"" . "," .
                "\"" . UNIT . "\"" . "," .
                $fee . "," .
                $settings['tax'] .
            ")";
            $resInvoice = $this->fireQuery($selInvoice);

            if ($resInvoice) {

                // Generate TXT file for fiscal printer
                $this->invoice($remittanceId);

                // Send Pushover notification
                $this->pushover($remittance['SenderName'], $remittanceId, $data['Amount'], $bankName['name']);

                return $remittance;
            } else {
                return 0;
            }

        } else {
            return 0;
        }
    }

    /**
     * Check API Key
     */
    function checkAPIkey($apiKey) {
        $query =
            "SELECT id
                FROM api
                WHERE api_key = " . "\"" . $apiKey . "\"";
        $result = $this->fireQuery($query);
        $value = $this->rowCount($result);
        return $value;
    }

    /**
     * Check User
     */
    function checkUser($taxId, $pin) {
        $query =
            "SELECT id 
                FROM users
                WHERE (tax_id = " . "\"" . $taxId . "\"" . " OR passport = " . "\"" . $taxId . "\"" . ") AND pin = " . $pin . " AND status = 1 AND delete_status = 0";
        $result = $this->fireQuery($query);
        $value = $this->rowCount($result);
        return $value;
    }

    /**
     * Check Recipient
     */
    function checkRecipient($taxId, $recipientId) {

        // Get User ID
        $userId = $this->getUser($taxId);

        $query =
            "SELECT id 
                FROM cpr_recipients
                WHERE client_id = " . $userId['id'] . " AND status = 1 AND delete_status = 0";
        $result = $this->fireQuery($query);
        $value = $this->rowCount($result);
        return $value;
    }

    /**
     * Check Amount
     */
    function checkAmount($amount) {
        if (!is_numeric($amount) || $amount < 1 || $amount != round($amount)) {
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * Get User
     */
    function getUser($taxId) {
        $query =
            "SELECT * 
                FROM users
                WHERE tax_id = " . "\"" . $taxId . "\"" . " OR passport = " . "\"" . $taxId . "\"";
        $result = $this->fireQuery($query);
        $value = $this->fetchAssoc($result);
        return $value;
    }

    /**
     * Get Recipient
     */
    function getRecipient($recipientId) {
        $query =
            "SELECT * 
                FROM cpr_recipients
                WHERE id = " . $recipientId;
        $result = $this->fireQuery($query);
        $value = $this->fetchAssoc($result);
        return $value;
    }

    /**
     * Get Settings
     */
    function getSettings() {
        $query =
            "SELECT * 
                FROM cpr_settings";
        $result = $this->fireQuery($query);
        $value = $this->fetchAssoc($result);
        return $value;
    }

    /**
     * Get Bank Name
     */
    function getBankName($bankId) {
        $query =
            "SELECT name 
                FROM cpr_banks
                WHERE id = " . "\"" . $bankId . "\"";
        $result = $this->fireQuery($query);
        $value = $this->fetchAssoc($result);
        return $value;
    }

    /**
     * Get Bank Account Type
     */
    function getBankAccountType($bankAccountType) {
        $query =
            "SELECT name 
                FROM cpr_bank_account_types
                WHERE id = " . $bankAccountType;
        $result = $this->fireQuery($query);
        $value = $this->fetchAssoc($result);
        return $value;
    }

    /**
     * Get Remittance
     */
    function getRemittance($remittanceId) {
        $query =
            "SELECT * 
                FROM remittances
                WHERE id = " . $remittanceId;
        $result = $this->fireQuery($query);
        $value = $this->fetchAssoc($result);
        return $value;
    }

    /**
     * Get Invoice
     */
    function getInvoice($id) {
        $query =
            "SELECT * 
                FROM invoices
                WHERE id_remesa = " . $id;
        $result = $this->fireQuery($query);
        $value = $this->fetchAssoc($result);
        return $value;
    }

    /*
     * Generate push notification
     */
    function pushover($name, $id, $amount, $bank) {
        curl_setopt_array($ch = curl_init(), array(
          CURLOPT_URL => "https://api.pushover.net/1/messages.json",
          CURLOPT_POSTFIELDS => array(
            "token"   => "anx1ivsh6289s1dn8t8cboids6mxoh",
            "user"    => "uq6jg1j33et4s6w2paxjzhre31ug2w",
            'sound'   => 'cashregister',
            "message" => 'El cliente '. $name . ' ha enviado la remesa ' . $id . ' por $' . number_format($amount, 2) . ' a ' . $bank
          ),
          CURLOPT_SAFE_UPLOAD    => true,
          CURLOPT_RETURNTRANSFER => true,
        ));
        curl_exec($ch);
        curl_close($ch);
    }

    /*
     * Generate invoice
     */
    function invoice($id)
    {
        $this->autoRender = false;

        // Initialize variablEs
        $contentTi = '';
        $contentMv = '';

        // Get data from invoices table
        $data = $this->getInvoice($id);

        // If there is data to write to the invoice files
        if (!empty($data)) {

            // Generate document number
            $documento = str_pad($data['id_remesa'], 7, "0", STR_PAD_LEFT);

            // Generate file contents
            $contentTi =
                'FACTI' . $documento . "\t" .
                $data['nombre'] . "\t" .
                $data['cedula'] . "\t" .
                $data['direccion'] . "\t" .
                number_format($data['descuento'], 2, '.', '') . "\t" .
                number_format($data['total_pagos'], 2, '.', '') . "\t" .
                number_format($data['total_final'], 2, '.', '') . "\t" .
                number_format($data['recargos'], 2, '.', '') . "\t" .
                number_format($data['porcentaje_recargo'], 2, '.', '') . "\t" .
                number_format($data['efectivo'], 2, '.', '') . "\t" .
                number_format($data['cheque'], 2, '.', '') . "\t" .
                number_format($data['tarjeta_credito'], 2, '.', '') . "\t" .
                number_format($data['tarjeta_debito'], 2, '.', '') . "\t" .
                number_format($data['nota_credito'], 2, '.', '') . "\t" .
                number_format($data['otro_pago'], 2, '.', '');
            $contentMv =
                'FACTI' . $documento . "\t" .
                $data['codigo'] . "\t" .
                $data['nombre_articulo'] . "\t" .
                $data['unidad'] . "\t" .
                $data['cantidad'] . "\t" .
                number_format($data['precio_neto'], 2, '.', '') . "\t" .
                number_format($data['alicuota'], 2, '.', '') . "\t" .
                $data['agrupado'] . "\t" .
                number_format($data['isc'], 2, '.', '');
        }
        // Generate invoice header file
        $path = ROOT . '/invoices/IN/';
        $fileNameTi = 'FACTI' . $documento . '.TXT';
        $newFile = $path . $fileNameTi;
        file_put_contents($newFile, $contentTi);
        header('Content-type: text/plain');

        // Generate invoice movement file
        $path = ROOT . '/invoices/IN/';
        $fileNameMv = 'FACMV' . $documento . '.TXT';
        $newFile = $path . $fileNameMv;
        file_put_contents($newFile, $contentMv);
        header('Content-type: text/plain');

        return;
    }

    /**
      * Check service status
      */
    function getServiceStatus() {
        $query =
            "SELECT service_status 
                FROM cpr_settings
                WHERE id = 1";
        $result = $this->fireQuery($query);
        $value = $this->fetchAssoc($result);
        return $value['service_status'];
    }
}
