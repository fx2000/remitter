<?php
/**
 * Send Remittance
 *
 * Plataforma de Administración de Remesas API
 *
 * @copyright     Copyright (c) Fundación Duque de La Gomera, S.A. (http://www.duquedelagomera.com)
 * @link          http://par.hispanoremesas.com HispanoRemesas(tm) Project
 * @package       API
 * @since         PAR(tm) v 1.5.0
 */

include "Request.SendRemittance.php";
include "../ServerStatusCodes.php";

class RestResponse {

    /**
     * Generate JSON response
     */
    function generateResponse($CLIENT_DATA_ARY) {

        // Initializing logger
        $logger = new Katzgrau\KLogger\Logger(LOG_DIR);

        // Logging Forgot Password
        $logger->info("============================================================");
        $logger->info("Received SendRemittance request:", $CLIENT_DATA_ARY);

        $returnArray = array();
        $responseArray = array();
        $client_key_array = array();
        $check_data_array = array(
            '0' => 'APIkey',
            '1' => 'DeviceID',
            '2' => 'TaxID',
            '3' => 'PIN',
            '4' => 'RecipientID',
            '5' => 'Amount'
        );

        // Check if the correct parameters are being sent and mark as (S)uccess or (F)ailed
        foreach ($CLIENT_DATA_ARY as $key => $val) {
            array_push($client_key_array, $key);
        }
        
        for ($i = 0; $i < count($client_key_array); $i++) {
            
            if (in_array($client_key_array[$i], $check_data_array)) {
                array_push($returnArray, 'S');
            } else {
                array_push($returnArray, 'F');
            }
        }

        // If parameter check fails, send an error message
        if (in_array("F", $returnArray)) {
            $logger->error("SendRemittance request failed: " . $this->generateJSONError('101'));
            return $this->generateJSONError('101');
        } else {
            
            // Check API Key
            if (in_array("APIkey", $client_key_array)) {
                $apiKey = $CLIENT_DATA_ARY['APIkey'];
                
                if (strlen($apiKey) == 0) {
                    $logger->error("SendRemittance request failed: " . $this->generateJSONError('103'));
                    return $this->generateJSONError('103');
                }
            } else {
                $logger->error("SendRemittance request failed: " . $this->generateJSONError('102'));
                return $this->generateJSONError('102');
            }

            // Check Device ID
            if (in_array("DeviceID", $client_key_array)) {
                $deviceId = $CLIENT_DATA_ARY['DeviceID'];
                
                if (strlen($deviceId) == 0) {
                    $logger->error("SendRemittance request failed: " . $this->generateJSONError('107'));
                    return $this->generateJSONError('107');
                }
            } else {
                $logger->error("SendRemittance request failed: " . $this->generateJSONError('106'));
                return $this->generateJSONError('106');
            }

            // Check Tax ID
            if (in_array("TaxID", $client_key_array)) {
                $taxId = $CLIENT_DATA_ARY['TaxID'];
                
                if (strlen($taxId) == 0) {
                    $logger->error("SendRemittance request failed: " . $this->generateJSONError('109'));
                    return $this->generateJSONError('109');
                }
            } else {
                $logger->error("SendRemittance request failed: " . $this->generateJSONError('108'));
                return $this->generateJSONError('108');
            }

            // Check PIN
            if (in_array("PIN", $client_key_array)) {
                $pin = $CLIENT_DATA_ARY['PIN'];
                
                if (strlen($pin) == 0) {
                    $logger->error("SendRemittance request failed: " . $this->generateJSONError('111'));
                    return $this->generateJSONError('111');
                }
            } else {
                $logger->error("SendRemittance request failed: " . $this->generateJSONError('110'));
                return $this->generateJSONError('110');
            }

            // Check Recipient ID
            if (in_array("RecipientID", $client_key_array)) {
                $recipientId = $CLIENT_DATA_ARY['RecipientID'];
                
                if (strlen($recipientId) == 0) {
                    $logger->error("SendRemittance request failed: " . $this->generateJSONError('115'));
                    return $this->generateJSONError('115');
                }
            } else {
                $logger->error("SendRemittance request failed: " . $this->generateJSONError('114'));
                return $this->generateJSONError('114');
            }

            // Check Amount
            if (in_array("Amount", $client_key_array)) {
                $amount = $CLIENT_DATA_ARY['Amount'];
                
                if (strlen($amount) == 0) {
                    $logger->error("SendRemittance request failed: " . $this->generateJSONError('116'));
                    return $this->generateJSONError('116');
                }
            } else {
                $logger->error("SendRemittance request failed: " . $this->generateJSONError('117'));
                return $this->generateJSONError('117');
            }

            // If all fields are validated correctly, call the SendRemittance API
            $REQ_SUCCESS = new RequestSendRemittanceAPI();

            // Check Service Status
            $serviceStatusValid = $REQ_SUCCESS->getServiceStatus();

            if ($serviceStatusValid == 0) {
                $logger->error("GetRecipients request failed: " . $this->generateJSONError('121'));
                return $this->generateJSONError('121');
            }

            // Check that API Key is valid
            $apiKeyValid = $REQ_SUCCESS->checkAPIkey($apiKey);

            if ($apiKeyValid == 0) {
                $logger->error("SendRemittance request failed: " . $this->generateJSONError('104'));
                return $this->generateJSONError('104');
            }

            // Check that Tax ID and PIN are valid
            $userValid = $REQ_SUCCESS->checkUser($taxId, $pin);

            if ($userValid == 0) {
                $logger->error("SendRemittance request failed: " . $this->generateJSONError('112'));
                return $this->generateJSONError('112');
            }

            // Check that Recipient ID is valid
            $recipientValid = $REQ_SUCCESS->checkRecipient($taxId, $recipientId);

            if ($recipientValid == 0) {
                $logger->error("SendRemittance request failed: " . $this->generateJSONError('118'));
                return $this->generateJSONError('118');
            }

            // Check that Amount is valid
            $amountValid = $REQ_SUCCESS->checkAmount($amount);

            if ($amountValid == 0) {
                $logger->error("SendRemittance request failed: " . $this->generateJSONError('119'));
                return $this->generateJSONError('119');
            }

            // Send Remittance
            $remittance = $REQ_SUCCESS->SendRemittance($CLIENT_DATA_ARY);

            if ($remittance > 0) {
                $status = '100';
                $obj_server_RespCode_code = new ServerStatusCode();
                $output = $obj_server_RespCode_code->getStatusCodeMessage($status);
                $arr['Status'] = '1';
                $arr['Code'] = $status;
                $arr['Message'] = $output;
                $arr['Data'] = $remittance;
                $result['Response'] = $arr;
                $logger->info("SendRemittance request successful: " . json_encode($result));
                return json_encode($result);
            } else {
                $logger->error("SendRemittance request failed: " . $this->generateJSONError('120'));
                return $this->generateJSONError('120');
            }
        }
    }

    /**
     * Generate JSON error
     */
    function generateJSONError($status) { 
        $obj_server_RespCode_code = new ServerStatusCode();
        $output = $obj_server_RespCode_code->getStatusCodeMessage($status);
        $arr['Status'] = '0';
        $arr['Code'] = $status;
        $arr['Message'] = $output;
        $result['Response'] = $arr;
        return json_encode($result);
    }
}

// Send response back to user
$POSTDATA = $_POST;
$obj = new RestResponse();
echo $obj->generateResponse($POSTDATA);
