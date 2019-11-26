<?php
/**
 * remitter
 *
 * @link      https://github.com/fx2000/remitter
 * @since     0.1
 */

namespace App\Mailer;

use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{
    public function welcome($user, $password, $pin)
    {
        // Extract User information
        $name = $user->fname1;
        $fullname = $user->fname1 . ' ' . $user->lname1;
        $email = $user->email;

        // Select template
        switch ($user->user_type) {
            case 1:
                $template = 'welcome_staff';
                break;
            case 2:
                $template = 'welcome_staff';
                break;
            case 3:
                $template = 'welcome_staff';
                break;
            case 4:
                $template = 'welcome_investor';
                break;
            case 5:
                $template = 'welcome_client';
                break;
            default:
                $template = 'welcome_client';
                break;
        }

        // Send email
        $this
            ->to($email, $fullname)
            ->from('noreply@remitter.appstic.net', 'remitter')
            ->subject(sprintf('Bienvenido a remitter %s', $name))
            ->template($template)
            ->emailFormat('html')
            ->viewVars([
                'nombre'   => $name,
                'email'    => $email,
                'password' => $password,
                'pin'      => $pin
            ]);
    }

    public function remittance_received($remittance, $recipient, $client) {

        // Extract information
        $fullname = $client->fname1 . ' ' . $client->fname2 . ' ' . $client->lname1 . ' ' . $client->lname2;
        $email = $client->email;

        // Get bank name
        $bank_name = array(
          '16' => 'Banco General',
          '17' => 'Banistmo',
          '44' => 'Banco Nacional de Panamá',
          '45' => 'Multibank',
          '46' => 'BNP Paribas',
          '47' => 'BAC International Bank',
          '48' => 'Global Bank',
          '49' => 'Caja de Ahorros',
          '50' => 'Banesco Panamá',
          '51' => 'Socotiabank Transformandose',
          '52' => 'Banco Aliado',
          '53' => 'BLADEX',
          '54' => 'Banvivienda',
          '55' => 'Credicorp Bank',
          '56' => 'Banco Azteca',
          '57' => 'Canal Bank',
          '58' => 'St Georges Bank',
          '59' => 'Primer Banco del Istmo',
          '60' => 'Towerbank',
          '61' => 'Banco de Occidente',
          '62' => 'Banco Pichincha',
          '63' => 'Banco Davivienda',
          '64' => 'MMG Bank',
          '65' => 'Mega International Commercial Bank',
          '66' => 'Banco Transatlántico',
          '67' => 'Metrobank',
          '68' => 'Banco Santander',
          '69' => 'Mercantil Bank',
          '70' => 'Banco Lafise',
          '71' => 'Banco Delta',
          '72' => 'Banco Panamá',
          '73' => 'Capital Bank',
          '74' => 'AllBank',
          '75' => 'Banco Nacional de Panamá',
          '76' => 'Multibank',
          '77' => 'BNP Paribas',
          '78' => 'BAC International Bank',
          '79' => 'Global Bank',
          '80' => 'Caja de Ahorros',
          '81' => 'Banesco Panamá',
          '82' => 'Socotiabank Transformandose',
          '83' => 'Banco Aliado',
          '84' => 'BLADEX',
          '85' => 'Banvivienda',
          '86' => 'Credicorp Bank',
          '87' => 'Banco Azteca',
          '88' => 'Canal Bank',
          '89' => 'St Georges Bank',
          '90' => 'Primer Banco del Istmo',
          '91' => 'Towerbank',
          '92' => 'Banco de Occidente',
          '93' => 'Banco Pichincha',
          '94' => 'Banco Davivienda',
          '95' => 'MMG Bank',
          '96' => 'Mega International Commercial Bank',
          '97' => 'Banco Transatlántico',
          '98' => 'Metrobank',
          '99' => 'Banco Santander',
          '100' => 'Mercantil Bank',
          '101' => 'Banco Lafise',
          '102' => 'Banco Delta',
          '103' => 'Banco Panamá',
          '104' => 'Capital Bank',
          '105' => 'AllBank',
          '106' => 'Bangente',
          '4' => 'Banesco',
          '5' => 'Mercantil',
          '6' => 'Banco de Venezuela',
          '7' => 'Banplus',
          '8' => 'Bancaribe',
          '9' => 'Banco del Tesoro',
          '10' => 'Bicentenario Banco Universal',
          '11' => 'BBVA Provincial',
          '12' => 'Banco Fondo Común',
          '13' => 'Banco Occidental de Descuento',
          '14' => 'Banco Plaza',
          '15' => 'Banco Exterior',
          '18' => '100% Banco',
          '19' => 'Banco Agrícola de Venezuela',
          '20' => 'Banco Activo',
          '21' => 'Banfanb',
          '22' => 'Banmujer',
          '23' => 'Banco Caroní',
          '24' => 'Casa Propia Entidad de Ahorro y Préstamo',
          '25' => 'Citibank Venezuela',
          '26' => 'DELSUR Banco Universal',
          '27' => 'Mi Casa Entidad de Ahorro y Préstamo',
          '28' => 'Banco Nacional de Crédito',
          '29' => 'Banco Sofitasa',
          '30' => 'Venezolano de Crédito',
          '31' => '100% Banco',
          '32' => 'Banco Agrícola de Venezuela',
          '33' => 'Banco Activo',
          '34' => 'Banfanb',
          '35' => 'Banmujer',
          '36' => 'Banco Caroní',
          '37' => 'Casa Propia Entidad de Ahorro y Préstamo',
          '38' => 'Citibank Venezuela',
          '39' => 'DELSUR Banco Universal',
          '40' => 'Mi Casa Entidad de Ahorro y Préstamo',
          '41' => 'Banco Nacional de Crédito',
          '42' => 'Banco Sofitasa',
          '43' => 'Venezolano de Crédito'
        );
        $bank = $bank_name[$recipient->bank_id];

        // Get account type
        switch ($recipient->bank_account_type) {
            case 1:
                $account_type = "Ahorros";
                break;
            case 2:
                $account_type = "Corriente";
                break;
            default:
                $account_type = "Otro";
                break;
        }

        // Get payment type
        switch ($remittance->payment_type) {
            case 1:
                $payment_type = "Efectivo";
                break;
            case 2:
                $payment_type = "ACH";
                break;
            case 3:
                $payment_type = "Terminal de Punto Pago";
                break;
            default:
                $payment_type = "Otro";
                break;
        }

        // Send email
        $this
            ->to($email, $fullname)
            ->from('noreply@remitter.appstic.net', 'remitter')
            ->subject(sprintf('%s, hemos recibido tu remesa correctamente', $client->fname1))
            ->template('remittance_received')
            ->emailFormat('html')
            ->viewVars([
                'name'            => $client->fname1,
                'datetime'        => $remittance->trans_dt,
                'recipient'       => $recipient->fname1 . ' ' . $recipient->fname2 . ' ' . $recipient->lname1 . ' ' . $recipient->lname2,
                'recipient_taxid' => $recipient->tax_id,
                'amount_received' => number_format(($remittance->amount * $remittance->purchase_rate), 2),
                'exchange_rate'   => number_format($remittance->purchase_rate, 2),
                'amount_sent'     => number_format($remittance->amount, 2),
                'amount_paid'     => number_format($remittance->amount_payed, 2),
                'amount_fee'      => number_format($remittance->fee + $remittance->tax, 2),
                'payment_type'    => $payment_type,
                'bank'            => $bank,
                'account_number'  => $recipient->bank_account_number,
                'account_type'    => $account_type,
                'id'              => str_pad($remittance->id,6,"0",STR_PAD_LEFT)
            ]);
    }

    public function remittance_sent($remittance, $recipient, $client) {

        // Extract information
        $fullname = $client->fname1 . ' ' . $client->lname1;
        $email = $client->email;

        // Get bank name
        $bank_name = array(
          '16' => 'Banco General',
          '17' => 'Banistmo',
          '44' => 'Banco Nacional de Panamá',
          '45' => 'Multibank',
          '46' => 'BNP Paribas',
          '47' => 'BAC International Bank',
          '48' => 'Global Bank',
          '49' => 'Caja de Ahorros',
          '50' => 'Banesco Panamá',
          '51' => 'Socotiabank Transformandose',
          '52' => 'Banco Aliado',
          '53' => 'BLADEX',
          '54' => 'Banvivienda',
          '55' => 'Credicorp Bank',
          '56' => 'Banco Azteca',
          '57' => 'Canal Bank',
          '58' => 'St Georges Bank',
          '59' => 'Primer Banco del Istmo',
          '60' => 'Towerbank',
          '61' => 'Banco de Occidente',
          '62' => 'Banco Pichincha',
          '63' => 'Banco Davivienda',
          '64' => 'MMG Bank',
          '65' => 'Mega International Commercial Bank',
          '66' => 'Banco Transatlántico',
          '67' => 'Metrobank',
          '68' => 'Banco Santander',
          '69' => 'Mercantil Bank',
          '70' => 'Banco Lafise',
          '71' => 'Banco Delta',
          '72' => 'Banco Panamá',
          '73' => 'Capital Bank',
          '74' => 'AllBank',
          '75' => 'Banco Nacional de Panamá',
          '76' => 'Multibank',
          '77' => 'BNP Paribas',
          '78' => 'BAC International Bank',
          '79' => 'Global Bank',
          '80' => 'Caja de Ahorros',
          '81' => 'Banesco Panamá',
          '82' => 'Socotiabank Transformandose',
          '83' => 'Banco Aliado',
          '84' => 'BLADEX',
          '85' => 'Banvivienda',
          '86' => 'Credicorp Bank',
          '87' => 'Banco Azteca',
          '88' => 'Canal Bank',
          '89' => 'St Georges Bank',
          '90' => 'Primer Banco del Istmo',
          '91' => 'Towerbank',
          '92' => 'Banco de Occidente',
          '93' => 'Banco Pichincha',
          '94' => 'Banco Davivienda',
          '95' => 'MMG Bank',
          '96' => 'Mega International Commercial Bank',
          '97' => 'Banco Transatlántico',
          '98' => 'Metrobank',
          '99' => 'Banco Santander',
          '100' => 'Mercantil Bank',
          '101' => 'Banco Lafise',
          '102' => 'Banco Delta',
          '103' => 'Banco Panamá',
          '104' => 'Capital Bank',
          '105' => 'AllBank',
          '106' => 'Bangente',
          '4' => 'Banesco',
          '5' => 'Mercantil',
          '6' => 'Banco de Venezuela',
          '7' => 'Banplus',
          '8' => 'Bancaribe',
          '9' => 'Banco del Tesoro',
          '10' => 'Bicentenario Banco Universal',
          '11' => 'BBVA Provincial',
          '12' => 'Banco Fondo Común',
          '13' => 'Banco Occidental de Descuento',
          '14' => 'Banco Plaza',
          '15' => 'Banco Exterior',
          '18' => '100% Banco',
          '19' => 'Banco Agrícola de Venezuela',
          '20' => 'Banco Activo',
          '21' => 'Banfanb',
          '22' => 'Banmujer',
          '23' => 'Banco Caroní',
          '24' => 'Casa Propia Entidad de Ahorro y Préstamo',
          '25' => 'Citibank Venezuela',
          '26' => 'DELSUR Banco Universal',
          '27' => 'Mi Casa Entidad de Ahorro y Préstamo',
          '28' => 'Banco Nacional de Crédito',
          '29' => 'Banco Sofitasa',
          '30' => 'Venezolano de Crédito',
          '31' => '100% Banco',
          '32' => 'Banco Agrícola de Venezuela',
          '33' => 'Banco Activo',
          '34' => 'Banfanb',
          '35' => 'Banmujer',
          '36' => 'Banco Caroní',
          '37' => 'Casa Propia Entidad de Ahorro y Préstamo',
          '38' => 'Citibank Venezuela',
          '39' => 'DELSUR Banco Universal',
          '40' => 'Mi Casa Entidad de Ahorro y Préstamo',
          '41' => 'Banco Nacional de Crédito',
          '42' => 'Banco Sofitasa',
          '43' => 'Venezolano de Crédito'
        );
        $bank = $bank_name[$recipient->bank_id];

        // Get account type
        switch ($recipient->bank_account_type) {
            case 1:
                $account_type = "Ahorros";
                break;
            case 2:
                $account_type = "Corriente";
                break;
            default:
                $account_type = "Otro";
                break;
        }

        // Send email
        $this
            ->to($email, $fullname)
            ->from('noreply@remitter.appstic.net', 'remitter')
            ->subject(sprintf('%s, tu remesa ha sido enviada con éxito', $client->fname1))
            ->template('remittance_sent')
            ->emailFormat('html')
            ->viewVars([
                'name'            => $client->fname1,
                'datetime'        => $remittance->delivered_dt,
                'recipient'       => $recipient->fname1 . ' ' . $recipient->lname1,
                'amount_received' => number_format(($remittance->amount * $remittance->purchase_rate), 2),
                'exchange_rate'   => number_format($remittance->purchase_rate, 2),
                'amount_sent'     => number_format($remittance->amount, 2),
                'bank'            => $bank,
                'account_number'  => $recipient->bank_account_number,
                'account_type'    => $account_type,
                'id'              => str_pad($remittance->id,6,"0",STR_PAD_LEFT)
            ]);
    }

    public function payment_received($payment, $investor, $account) {

        // Extract information
        $fullname = $investor->fname1 . ' ' . $investor->lname1;
        $email = $investor->email;

        // Get bank name
        $bank_name = array(
          '16' => 'Banco General',
          '17' => 'Banistmo',
          '44' => 'Banco Nacional de Panamá',
          '45' => 'Multibank',
          '46' => 'BNP Paribas',
          '47' => 'BAC International Bank',
          '48' => 'Global Bank',
          '49' => 'Caja de Ahorros',
          '50' => 'Banesco Panamá',
          '51' => 'Socotiabank Transformandose',
          '52' => 'Banco Aliado',
          '53' => 'BLADEX',
          '54' => 'Banvivienda',
          '55' => 'Credicorp Bank',
          '56' => 'Banco Azteca',
          '57' => 'Canal Bank',
          '58' => 'St Georges Bank',
          '59' => 'Primer Banco del Istmo',
          '60' => 'Towerbank',
          '61' => 'Banco de Occidente',
          '62' => 'Banco Pichincha',
          '63' => 'Banco Davivienda',
          '64' => 'MMG Bank',
          '65' => 'Mega International Commercial Bank',
          '66' => 'Banco Transatlántico',
          '67' => 'Metrobank',
          '68' => 'Banco Santander',
          '69' => 'Mercantil Bank',
          '70' => 'Banco Lafise',
          '71' => 'Banco Delta',
          '72' => 'Banco Panamá',
          '73' => 'Capital Bank',
          '74' => 'AllBank',
          '75' => 'Banco Nacional de Panamá',
          '76' => 'Multibank',
          '77' => 'BNP Paribas',
          '78' => 'BAC International Bank',
          '79' => 'Global Bank',
          '80' => 'Caja de Ahorros',
          '81' => 'Banesco Panamá',
          '82' => 'Socotiabank Transformandose',
          '83' => 'Banco Aliado',
          '84' => 'BLADEX',
          '85' => 'Banvivienda',
          '86' => 'Credicorp Bank',
          '87' => 'Banco Azteca',
          '88' => 'Canal Bank',
          '89' => 'St Georges Bank',
          '90' => 'Primer Banco del Istmo',
          '91' => 'Towerbank',
          '92' => 'Banco de Occidente',
          '93' => 'Banco Pichincha',
          '94' => 'Banco Davivienda',
          '95' => 'MMG Bank',
          '96' => 'Mega International Commercial Bank',
          '97' => 'Banco Transatlántico',
          '98' => 'Metrobank',
          '99' => 'Banco Santander',
          '100' => 'Mercantil Bank',
          '101' => 'Banco Lafise',
          '102' => 'Banco Delta',
          '103' => 'Banco Panamá',
          '104' => 'Capital Bank',
          '105' => 'AllBank',
          '106' => 'Bangente',
          '4' => 'Banesco',
          '5' => 'Mercantil',
          '6' => 'Banco de Venezuela',
          '7' => 'Banplus',
          '8' => 'Bancaribe',
          '9' => 'Banco del Tesoro',
          '10' => 'Bicentenario Banco Universal',
          '11' => 'BBVA Provincial',
          '12' => 'Banco Fondo Común',
          '13' => 'Banco Occidental de Descuento',
          '14' => 'Banco Plaza',
          '15' => 'Banco Exterior',
          '18' => '100% Banco',
          '19' => 'Banco Agrícola de Venezuela',
          '20' => 'Banco Activo',
          '21' => 'Banfanb',
          '22' => 'Banmujer',
          '23' => 'Banco Caroní',
          '24' => 'Casa Propia Entidad de Ahorro y Préstamo',
          '25' => 'Citibank Venezuela',
          '26' => 'DELSUR Banco Universal',
          '27' => 'Mi Casa Entidad de Ahorro y Préstamo',
          '28' => 'Banco Nacional de Crédito',
          '29' => 'Banco Sofitasa',
          '30' => 'Venezolano de Crédito',
          '31' => '100% Banco',
          '32' => 'Banco Agrícola de Venezuela',
          '33' => 'Banco Activo',
          '34' => 'Banfanb',
          '35' => 'Banmujer',
          '36' => 'Banco Caroní',
          '37' => 'Casa Propia Entidad de Ahorro y Préstamo',
          '38' => 'Citibank Venezuela',
          '39' => 'DELSUR Banco Universal',
          '40' => 'Mi Casa Entidad de Ahorro y Préstamo',
          '41' => 'Banco Nacional de Crédito',
          '42' => 'Banco Sofitasa',
          '43' => 'Venezolano de Crédito'
        );
        $bank = $bank_name[$payment->bank_id];

        // Get account type
        switch ($payment->bank_account_type) {
            case 1:
                $account_type = "Ahorros";
                break;
            case 2:
                $account_type = "Corriente";
                break;
            default:
                $account_type = "Otro";
                break;
        }

        // Send email
        $this
            ->to($email, $fullname)
            ->from('noreply@remitter.appstic.net', 'remitter')
            ->subject(sprintf('%s, hemos recibido tu solicitud de pago', $investor->fname1))
            ->template('payment_received')
            ->emailFormat('html')
            ->viewVars([
                'name'            => $investor->fname1,
                'datetime'        => $payment->trans_dt,
                'balance'         => number_format($account->balance, 2),
                'amount'          => number_format($payment->amount, 2),
                'bank'            => $bank,
                'account_name'    => $investor->fname1 . ' ' . $investor->lname1,
                'account_number'  => $payment->bank_account_number,
                'account_type'    => $account_type,
                'id'              => str_pad($payment->id,6,"0",STR_PAD_LEFT)
            ]);
    }

    public function remittance_completed($remittance, $recipient, $investor, $account) {

        // Extract information
        $fullname = $investor->fname1 . ' ' . $investor->lname1;
        $email = $investor->email;

        // Get bank name
        $bank_name = array(
          '16' => 'Banco General',
          '17' => 'Banistmo',
          '44' => 'Banco Nacional de Panamá',
          '45' => 'Multibank',
          '46' => 'BNP Paribas',
          '47' => 'BAC International Bank',
          '48' => 'Global Bank',
          '49' => 'Caja de Ahorros',
          '50' => 'Banesco Panamá',
          '51' => 'Socotiabank Transformandose',
          '52' => 'Banco Aliado',
          '53' => 'BLADEX',
          '54' => 'Banvivienda',
          '55' => 'Credicorp Bank',
          '56' => 'Banco Azteca',
          '57' => 'Canal Bank',
          '58' => 'St Georges Bank',
          '59' => 'Primer Banco del Istmo',
          '60' => 'Towerbank',
          '61' => 'Banco de Occidente',
          '62' => 'Banco Pichincha',
          '63' => 'Banco Davivienda',
          '64' => 'MMG Bank',
          '65' => 'Mega International Commercial Bank',
          '66' => 'Banco Transatlántico',
          '67' => 'Metrobank',
          '68' => 'Banco Santander',
          '69' => 'Mercantil Bank',
          '70' => 'Banco Lafise',
          '71' => 'Banco Delta',
          '72' => 'Banco Panamá',
          '73' => 'Capital Bank',
          '74' => 'AllBank',
          '75' => 'Banco Nacional de Panamá',
          '76' => 'Multibank',
          '77' => 'BNP Paribas',
          '78' => 'BAC International Bank',
          '79' => 'Global Bank',
          '80' => 'Caja de Ahorros',
          '81' => 'Banesco Panamá',
          '82' => 'Socotiabank Transformandose',
          '83' => 'Banco Aliado',
          '84' => 'BLADEX',
          '85' => 'Banvivienda',
          '86' => 'Credicorp Bank',
          '87' => 'Banco Azteca',
          '88' => 'Canal Bank',
          '89' => 'St Georges Bank',
          '90' => 'Primer Banco del Istmo',
          '91' => 'Towerbank',
          '92' => 'Banco de Occidente',
          '93' => 'Banco Pichincha',
          '94' => 'Banco Davivienda',
          '95' => 'MMG Bank',
          '96' => 'Mega International Commercial Bank',
          '97' => 'Banco Transatlántico',
          '98' => 'Metrobank',
          '99' => 'Banco Santander',
          '100' => 'Mercantil Bank',
          '101' => 'Banco Lafise',
          '102' => 'Banco Delta',
          '103' => 'Banco Panamá',
          '104' => 'Capital Bank',
          '105' => 'AllBank',
          '106' => 'Bangente',
          '4' => 'Banesco',
          '5' => 'Mercantil',
          '6' => 'Banco de Venezuela',
          '7' => 'Banplus',
          '8' => 'Bancaribe',
          '9' => 'Banco del Tesoro',
          '10' => 'Bicentenario Banco Universal',
          '11' => 'BBVA Provincial',
          '12' => 'Banco Fondo Común',
          '13' => 'Banco Occidental de Descuento',
          '14' => 'Banco Plaza',
          '15' => 'Banco Exterior',
          '18' => '100% Banco',
          '19' => 'Banco Agrícola de Venezuela',
          '20' => 'Banco Activo',
          '21' => 'Banfanb',
          '22' => 'Banmujer',
          '23' => 'Banco Caroní',
          '24' => 'Casa Propia Entidad de Ahorro y Préstamo',
          '25' => 'Citibank Venezuela',
          '26' => 'DELSUR Banco Universal',
          '27' => 'Mi Casa Entidad de Ahorro y Préstamo',
          '28' => 'Banco Nacional de Crédito',
          '29' => 'Banco Sofitasa',
          '30' => 'Venezolano de Crédito',
          '31' => '100% Banco',
          '32' => 'Banco Agrícola de Venezuela',
          '33' => 'Banco Activo',
          '34' => 'Banfanb',
          '35' => 'Banmujer',
          '36' => 'Banco Caroní',
          '37' => 'Casa Propia Entidad de Ahorro y Préstamo',
          '38' => 'Citibank Venezuela',
          '39' => 'DELSUR Banco Universal',
          '40' => 'Mi Casa Entidad de Ahorro y Préstamo',
          '41' => 'Banco Nacional de Crédito',
          '42' => 'Banco Sofitasa',
          '43' => 'Venezolano de Crédito'
        );
        $bank = $bank_name[$recipient->bank_id];

        // Get account type
        switch ($recipient->bank_account_type) {
            case 1:
                $account_type = "Ahorros";
                break;
            case 2:
                $account_type = "Corriente";
                break;
            default:
                $account_type = "Otro";
                break;
        }

        // Send email
        $this
            ->to($email, $fullname)
            ->from('inversionistas@remitter.appstic.net', 'remitter')
            ->subject(sprintf('Gracias %s, haz completado una remesa con éxito', $investor->fname1))
            ->template('remittance_completed')
            ->emailFormat('html')
            ->viewVars([
                'name'            => $investor->fname1,
                'datetime'        => $remittance->delivered_dt,
                'recipient'       => $recipient->fname1 . ' ' . $recipient->lname1,
                'amount_received' => number_format(($remittance->amount_sold), 2),
                'exchange_rate'   => number_format($remittance->amount_delivered / $remittance->amount_sold, 2),
                'amount_sent'     => number_format($remittance->amount_delivered, 2),
                'bank'            => $bank,
                'account_number'  => $recipient->bank_account_number,
                'account_type'    => $account_type,
                'id'              => str_pad($remittance->id,6,"0",STR_PAD_LEFT),
                'balance'         => $account->balance
            ]);
    }

    public function remittance_verification($remittance, $recipient, $investor) {

        // Extract information
        $fullname = $investor->fname1 . ' ' . $investor->lname1;
        $email = $investor->email;

        // Get bank name
        $bank_name = array(
          '16' => 'Banco General',
          '17' => 'Banistmo',
          '44' => 'Banco Nacional de Panamá',
          '45' => 'Multibank',
          '46' => 'BNP Paribas',
          '47' => 'BAC International Bank',
          '48' => 'Global Bank',
          '49' => 'Caja de Ahorros',
          '50' => 'Banesco Panamá',
          '51' => 'Socotiabank Transformandose',
          '52' => 'Banco Aliado',
          '53' => 'BLADEX',
          '54' => 'Banvivienda',
          '55' => 'Credicorp Bank',
          '56' => 'Banco Azteca',
          '57' => 'Canal Bank',
          '58' => 'St Georges Bank',
          '59' => 'Primer Banco del Istmo',
          '60' => 'Towerbank',
          '61' => 'Banco de Occidente',
          '62' => 'Banco Pichincha',
          '63' => 'Banco Davivienda',
          '64' => 'MMG Bank',
          '65' => 'Mega International Commercial Bank',
          '66' => 'Banco Transatlántico',
          '67' => 'Metrobank',
          '68' => 'Banco Santander',
          '69' => 'Mercantil Bank',
          '70' => 'Banco Lafise',
          '71' => 'Banco Delta',
          '72' => 'Banco Panamá',
          '73' => 'Capital Bank',
          '74' => 'AllBank',
          '75' => 'Banco Nacional de Panamá',
          '76' => 'Multibank',
          '77' => 'BNP Paribas',
          '78' => 'BAC International Bank',
          '79' => 'Global Bank',
          '80' => 'Caja de Ahorros',
          '81' => 'Banesco Panamá',
          '82' => 'Socotiabank Transformandose',
          '83' => 'Banco Aliado',
          '84' => 'BLADEX',
          '85' => 'Banvivienda',
          '86' => 'Credicorp Bank',
          '87' => 'Banco Azteca',
          '88' => 'Canal Bank',
          '89' => 'St Georges Bank',
          '90' => 'Primer Banco del Istmo',
          '91' => 'Towerbank',
          '92' => 'Banco de Occidente',
          '93' => 'Banco Pichincha',
          '94' => 'Banco Davivienda',
          '95' => 'MMG Bank',
          '96' => 'Mega International Commercial Bank',
          '97' => 'Banco Transatlántico',
          '98' => 'Metrobank',
          '99' => 'Banco Santander',
          '100' => 'Mercantil Bank',
          '101' => 'Banco Lafise',
          '102' => 'Banco Delta',
          '103' => 'Banco Panamá',
          '104' => 'Capital Bank',
          '105' => 'AllBank',
          '106' => 'Bangente',
          '4' => 'Banesco',
          '5' => 'Mercantil',
          '6' => 'Banco de Venezuela',
          '7' => 'Banplus',
          '8' => 'Bancaribe',
          '9' => 'Banco del Tesoro',
          '10' => 'Bicentenario Banco Universal',
          '11' => 'BBVA Provincial',
          '12' => 'Banco Fondo Común',
          '13' => 'Banco Occidental de Descuento',
          '14' => 'Banco Plaza',
          '15' => 'Banco Exterior',
          '18' => '100% Banco',
          '19' => 'Banco Agrícola de Venezuela',
          '20' => 'Banco Activo',
          '21' => 'Banfanb',
          '22' => 'Banmujer',
          '23' => 'Banco Caroní',
          '24' => 'Casa Propia Entidad de Ahorro y Préstamo',
          '25' => 'Citibank Venezuela',
          '26' => 'DELSUR Banco Universal',
          '27' => 'Mi Casa Entidad de Ahorro y Préstamo',
          '28' => 'Banco Nacional de Crédito',
          '29' => 'Banco Sofitasa',
          '30' => 'Venezolano de Crédito',
          '31' => '100% Banco',
          '32' => 'Banco Agrícola de Venezuela',
          '33' => 'Banco Activo',
          '34' => 'Banfanb',
          '35' => 'Banmujer',
          '36' => 'Banco Caroní',
          '37' => 'Casa Propia Entidad de Ahorro y Préstamo',
          '38' => 'Citibank Venezuela',
          '39' => 'DELSUR Banco Universal',
          '40' => 'Mi Casa Entidad de Ahorro y Préstamo',
          '41' => 'Banco Nacional de Crédito',
          '42' => 'Banco Sofitasa',
          '43' => 'Venezolano de Crédito'
        );
        $bank = $bank_name[$recipient->bank_id];

        // Get account type
        switch ($recipient->bank_account_type) {
            case 1:
                $account_type = "Ahorros";
                break;
            case 2:
                $account_type = "Corriente";
                break;
            default:
                $account_type = "Otro";
                break;
        }

        // Send email
        $this
            ->to('inversionistas@remitter.appstic.net','remitter')
            ->from('noreply@remitter.appstic.net', 'remitter')
            ->subject('Hay una nueva remesa pendiente por verificación')
            ->template('remittance_verification')
            ->emailFormat('html')
            ->viewVars([
                'name'            => $fullname,
                'datetime'        => $remittance->delivered_dt,
                'recipient'       => $recipient->fname1 . ' ' . $recipient->lname1,
                'amount'          => number_format(($remittance->amount_sold), 2),
                'exchange_rate'   => number_format($remittance->purchase_rate, 2),
                'amount_sent'     => number_format($remittance->amount_delivered, 2),
                'bank'            => $bank,
                'account_number'  => $recipient->bank_account_number,
                'account_type'    => $account_type,
                'id'              => str_pad($remittance->id,6,"0",STR_PAD_LEFT)
            ]);
    }
}