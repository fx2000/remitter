<?php
/**
 * Get Settings
 *
 * Plataforma de Administración de Remesas API
 *
 * @copyright     Copyright (c) Fundación Duque de La Gomera, S.A. (http://www.duquedelagomera.com)
 * @link          http://par.hispanoremesas.com HispanoRemesas(tm) Project
 * @package       API
 * @since         PAR(tm) v 1.5.0
 */

include "Request.GetSettings.php";
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
        $logger->info("Received GetSettings request:", $CLIENT_DATA_ARY);

        $returnArray = array();
        $responseArray = array();
        $client_key_array = array();
        $check_data_array = array(
            '0' => 'APIkey',
            '1' => 'DeviceID'
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
            $logger->error("GetSettings request failed: " . $this->generateJSONError('101'));
            return $this->generateJSONError('101');
        } else {
            
            // Check API Key
            if (in_array("APIkey", $client_key_array)) {
                $apiKey = $CLIENT_DATA_ARY['APIkey'];
                
                if (strlen($apiKey) == 0) {
                    $logger->error("GetSettings request failed: " . $this->generateJSONError('103'));
                    return $this->generateJSONError('103');
                }
            } else {
                $logger->error("GetSettings request failed: " . $this->generateJSONError('102'));
                return $this->generateJSONError('102');
            }

            // Check Device ID
            if (in_array("DeviceID", $client_key_array)) {
                $deviceId = $CLIENT_DATA_ARY['DeviceID'];
                
                if (strlen($deviceId) == 0) {
                    $logger->error("GetSettings request failed: " . $this->generateJSONError('107'));
                    return $this->generateJSONError('107');
                }
            } else {
                $logger->error("GetSettings request failed: " . $this->generateJSONError('106'));
                return $this->generateJSONError('106');
            }

            // If all fields are validated correctly, call the Get Settings API
            $REQ_SUCCESS = new RequestGetSettingsAPI();

            // Check that API Key is valid
            $apiKeyValid = $REQ_SUCCESS->checkAPIkey($apiKey);

            if ($apiKeyValid == 0) {
                $logger->error("GetSettings request failed: " . $this->generateJSONError('104'));
                return $this->generateJSONError('104');
            }

            // Request Settings
            $settings = $REQ_SUCCESS->GetSettings($CLIENT_DATA_ARY);
        
    
            if ($settings > 0) {
                $status = '100';
                $obj_server_RespCode_code = new ServerStatusCode();
                $output = $obj_server_RespCode_code->getStatusCodeMessage($status);
                $arr['Status'] = '1';
                $arr['Code'] = $status;
                $arr['Message'] = $output;
                $arr['Data'] = $settings;
                $result['Response'] = $arr;
                $logger->info("GetSettings request successful: " . json_encode($result));
                return json_encode($result);
            } else {
                $logger->error("GetSettings request failed: " . $this->generateJSONError('105'));
                return $this->generateJSONError('105');
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
