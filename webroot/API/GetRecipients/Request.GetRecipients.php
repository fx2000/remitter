<?php
/**
 * Get Recipients
 *
 * Plataforma de Administración de Remesas API
 *
 * @copyright     Copyright (c) Fundación Duque de La Gomera, S.A. (http://www.duquedelagomera.com)
 * @link          http://par.hispanoremesas.com HispanoRemesas(tm) Project
 * @package       API
 * @since         PAR(tm) v 1.5.0
 */

include "../Dbconn.php";

class RequestGetRecipientsAPI extends Dbconn {

    /*
     * Get Recipients
     */
    function getRecipients($data) {

        $recipients = array();

        // Get User ID
        $user = $this->getUser($data['TaxID']);

        // Get data from users table
        $selRecipients =
            "SELECT *
                FROM cpr_recipients
                WHERE client_id = " . $user['id'] . " AND delete_status = 0";
        $resRecipients = $this->fireQuery($selRecipients);  
        $numRecipients = $this->rowCount($resRecipients);
        
        // If there are recipients on the list, return them all
        if ($numRecipients > 0) {
            $i = 0;
            
            while ($arrRecipients = $this->fetchAssoc($resRecipients)) {

                // Get Bank Name
                $bankName = $this->getBankName($arrRecipients['bank_id']);

                // Get Bank Account Type
                $bankAccountType = $this->getBankAccountType($arrRecipients['bank_account_type']);

                $recipients[$i]['ID'] = $arrRecipients['id'];
                $recipients[$i]['Name'] = $arrRecipients['fname1'] . ' ' . $arrRecipients['fname2'] . ' ' . $arrRecipients['lname1'] . ' ' . $arrRecipients['lname2'];
                $recipients[$i]['TaxID'] = $arrRecipients['tax_id'];
                $recipients[$i]['Bank'] = $bankName['name'];
                $recipients[$i]['AccountType'] = $bankAccountType['name'];
                $recipients[$i]['AccountNumber'] = $arrRecipients['bank_account_number'];
                $i++;
            }

            $results = array(
                'ID'         => $user['id'],
                'Name'       => $user['fname1'] . ' ' .  $user['fname2'] . ' ' .  $user['lname1'] . ' ' .  $user['lname2'],
                'Recipients' => $recipients
            );

            return $results;
        } else {
            return $numRecipients;
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
                WHERE id = " . "\"" . $bankAccountType . "\"";
        $result = $this->fireQuery($query);
        $value = $this->fetchAssoc($result);
        return $value;
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
