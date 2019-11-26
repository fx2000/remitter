<?php
/**
 * Get Settings
 *
 * Remitter API
 *
 * @package       API
 * @since         remitter(tm) v 1.5.0
 */

include "../Dbconn.php";

class RequestGetSettingsAPI extends Dbconn {

    /*
     * Get Settings
     */
    function getSettings($data) {

        // Get data from settings table
        $selSettings =
            "SELECT *
                FROM cpr_settings ";
        $resSettings = $this->fireQuery($selSettings);  
        $numSettings = $this->rowCount($resSettings);
        
        // If data is present, return settings
        if ($numSettings > 0) {
            $arrSettings = $this->fetchAssoc($resSettings);
            $response['ServiceStatus'] = $arrSettings['service_status'];
            $response['Fee'] = number_format($arrSettings['fee'], 2);
            $response['Rate'] = "BsS." . number_format($arrSettings['purchase_rate'], 2, ",", ".");
            return $response;
        
        // Otherwise, return 0
        } else {
            return $numSettings;
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
}

