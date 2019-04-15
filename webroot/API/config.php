<?php
/**
 * API Configuration
 *
 * Plataforma de Administración de Remesas API
 *
 * @copyright     Copyright (c) Fundación Duque de La Gomera, S.A. (http://www.duquedelagomera.com)
 * @link          http://par.hispanoremesas.com HispanoRemesas(tm) Project
 * @package       API
 * @since         PAR(tm) v 1.5.0
 */

// Server URL
define('DOMAINURL','https://par.hispanoremesas.com/');

// Directories
define('LOG_DIR', '/var/www/logs');
define('TEMPLATE_DIR', '/var/www/src/Template/Email/html');
define('ROOT', '/var/www');

// Set email parameters
define('EMAIL_SERVER', 'ssl://premium39.web-hosting.com');
define('EMAIL_FROM', 'noreply@hispanoremesas.com');
define('EMAIL_USER', 'noreply@hispanoremesas.com');
define('EMAIL_PASSWORD', ')WSRHHEwQ9{=');
define('EMAIL_SENDER_NAME', 'HispanoRemesas');
define('EMAIL_STAFF', 'admin@hispanoremesas.com');

// Set Payment Methods
define('PAYMENT_CASH', 1);          // Cash
define('PAYMENT_ACH', 2);           // Wire Transfer
define('PAYMENT_PP', 3);            // Punto Pago POS
define('PAYMENT_OTHER', 4);         // Other Payment Method

// Set Transaction Status
define('AVAILABLE', 1);             // Available
define('RESERVED', 2);              // Reserved
define('VERIFICATION', 3);          // In Verification
define('COMPLETE', 4);              // Complete
define('CANCELLED', 5);             // Cancelled

// Invoice values
define('CODE', 'REM');                          // Available
define('UNIT', 'TRX');                          // Available
define('ARTICLE', 'Remesa Panamá->Venezuela');  // Available

// Other Constants
define('OPERATOR', '9999');                                                             // Punto Pago Operator ID
define('COMPANY', 'HipanoRemesas');                                                     // Punto Pago Operator ID
define('RUC', 'RUC 155663383-2-2018 DV76');                                             // Punto Pago Operator ID
define('ADDRESS', 'Ave. Federico Boyd, PH DoubleTree Hilton, Local C11, El Carmen');    // Punto Pago Operator ID
define('NOTES', 'Todas las remesas son efectuadas entre 24 y 48 horas hábiles luego de la fecha y hora especificada en este comprobante. HispanoRemesas no se hace responsable por demoras ocasionadas por el sistema financiero del país destino. Si tienes alguna duda o reclamo, llámanos al +507 385-0011, escríbenos por Whatsapp al +507 6218-1809 o por email a clientes@hispanoremesas.com, no olvides mencionar el número de operación en la parte superior de este documento. HispanoRemesas es el nombre comercial de DuFer Holdings Group Inc, una Casa de Remesas debidamente autorizada por el Ministerio de Comercio e Industrias mediante la resolución número 220 del 31 de julio de 2018.');           // Punto Pago Operator ID

// Security
define('SALT', 'e88aa5da787a1f01d6c7f8b6f83af847b04d92388deab83b49f478f799076df1'); // SHA1 salt

// Pusover details
define('TOKEN', 'anx1ivsh6289s1dn8t8cboids6mxoh');  // Cash
define('USER', 'uq6jg1j33et4s6w2paxjzhre31ug2w');   // Wire Transfer
define('SOUND', 'cashregister');                    // Punto Pago POS
