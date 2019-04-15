<?php
/**
 * Server Status Codes
 *
 * Plataforma de Administración de Remesas API
 *
 * @copyright     Copyright (c) Fundación Duque de La Gomera, S.A. (http://www.duquedelagomera.com)
 * @link          http://par.hispanoremesas.com HispanoRemesas(tm) Project
 * @package       API
 * @since         PAR(tm) v 1.5.0
 */

include('config.php');

class ServerStatusCode {
    
    public static function getStatusCodeMessage($status) {
        $codes = Array(
            100 => 'Success',
            101 => 'Missing parameters',
            102 => 'Missing API Key parameter',
            103 => 'API Key parameter is empty',
            104 => 'Invalid API Key',
            105 => 'Unable to obtain Settings from server',
            106 => 'Missing Device ID parameter',
            107 => 'Device ID parameter is empty',
            108 => 'Missing Tax ID parameter',
            109 => 'Tax ID parameter is empty',
            110 => 'Missing PIN parameter',
            111 => 'PIN parameter is empty',
            112 => 'The Tax ID and PIN combination doesn\'t match any known user',
            113 => 'Unable to obtain Recipients from server',
            114 => 'Missing Recipient ID parameter',
            115 => 'Recipient ID parameter is empty',
            116 => 'Missing Amount parameter',
            117 => 'Amount parameter is empty',
            118 => 'The specified Recipient ID is not valid for that User',
            119 => 'The remittance amount must be an integer higher than 0',
            120 => 'Unable to send Remittance',
            121 => 'Service Disabled'
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }
}
