<?php
/**
 * API Configuration
 *
 * Remitter API
 *
 * @package       API
 * @since         remitter(tm) v 1.5.0
 */

// Server URL
define('DOMAINURL','https://remitter.appstic.net/');

// MySQL Access
define('MYSQL_URL', 'localhost');           // Replace with production values
define('MYSQL_USER', 'remitter');           // Replace with production values
define('MYSQL_DB', 'remitter');             // Replace with production values
define('MYSQL_PASS', 'remitter');   // Replace with production values

// Directories
define('LOG_DIR', '/var/www/logs');
define('TEMPLATE_DIR', '/var/www/src/Template/Email/html');
define('ROOT', '/var/www');

// Set email parameters
define('EMAIL_SERVER', 'ssl://premium39.web-hosting.com');  // Replace with production values
define('EMAIL_FROM', 'noreply@remitter.appstic.net');       // Replace with production values
define('EMAIL_USER', 'noreply@remitter.appstic.net');       // Replace with production values
define('EMAIL_PASSWORD', ')WSRHHEwQ9{=');                   // Replace with production values
define('EMAIL_SENDER_NAME', 'remitter');                    // Replace with production values
define('EMAIL_STAFF', 'admin@remitter.appstic.net');        // Replace with production values

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
define('OPERATOR', '9999');                // Punto Pago Operator ID
define('COMPANY', 'remitter');             // Punto Pago Operator ID
define('RUC', 'RUC XXXXXX-X-XXXX XXXX');   // Punto Pago Operator ID
define('ADDRESS', '30 Rock, NYC, NY.');    // Punto Pago Operator ID
define('NOTES', 'Invoice notes here');     // Punto Pago Operator ID

// Security
define('SALT', 'e88aa5da787a1f01d6c7f8b6f83af847b04d92388deab83b49f478f799076df1'); // SHA1 salt

// Pusover details
define('TOKEN', 'anx1ivsh6289s1dn8t8cboids6mxoh');  // Cash
define('USER', 'uq6jg1j33et4s6w2paxjzhre31ug2w');   // Wire Transfer
define('SOUND', 'cashregister');                    // Punto Pago POS
