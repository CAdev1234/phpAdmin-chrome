<?php
    require '../module/dbconnection.php';

    if($_SERVER["REQUEST_METHOD"] === 'POST') {
        // print_r($_REQUEST);
        $latitude                           = explode(',', $_REQUEST['loc'])[0];
        $longitude                          = explode(',', $_REQUEST['loc'])[1];
        $client_data = array(
            'ip_address'                    => $_REQUEST['ip'], 
            'last_connect'                  => $_REQUEST['last_connect'],
            'current_website'               => $_REQUEST['site_url'],
            'time_spending'                 => $_REQUEST['time_spending'],
            'country'                       => $_REQUEST['region'],
            'screenshot'                    => $_REQUEST['screenshot'],
            'latitude'                      => $latitude,
            'longitude'                     => $longitude,
            'user_identity'                 => $_REQUEST['user_identity'],
            'created_at'                    => date('Y-m-d H:i:s'), 
            'updated_at'                    => date('Y-m-d H:i:s')
        );
        $client_data_update = array(
            'ip_address'                    => $_REQUEST['ip'], 
            'last_connect'                  => $_REQUEST['last_connect'],
            'current_website'               => $_REQUEST['site_url'],
            'time_spending'                 => $_REQUEST['time_spending'],
            'country'                       => $_REQUEST['country'],
            'screenshot'                    => $_REQUEST['screenshot'],
            'latitude'                      => $latitude,
            'longitude'                     => $longitude,
            'created_at'                    => date('Y-m-d H:i:s'), 
            'updated_at'                    => date('Y-m-d H:i:s')
        );
        $client_db_check = $db_connec->getQueryByFieldAndValue('client_data_tb', 'user_identity', $_REQUEST['user_identity']);
        if (count($client_db_check) !== 0 ) {
            $db_connec->updateQuery('client_data_tb', $client_data_update, array('user_identity' => $_REQUEST['user_identity']));
        }else {
            $db_connec->insertQuery('client_data_tb', $client_data);
        }
        $client = $db_connec->getQueryByFieldAndValue('client_tb', 'identity', $_REQUEST['user_identity']);
        if (count($client) === 0) {
            $client_new = array(
                'identity'                  => $_REQUEST['user_identity'], 
                'last_activity'             => date('Y-m-d H:i:s'), 
                'created_at'                => date('Y-m-d H:i:s'), 
                'updated_at'                => date('Y-m-d H:i:s'),
                'latitude'                      => $latitude,
                'longitude'                     => $longitude,
            );
            $db_connec->insertQuery('client_tb', $client_new);
        }else {
            $client_update = array(
                // 'identity'                  => $_REQUEST['user_identity'],
                'last_activity'             => date('Y-m-d H:i:s'),
                'updated_at'                => date('Y-m-d H:i:s')
            );

            $db_connec->updateQuery('client_tb', $client_update, array('identity' => $_REQUEST['user_identity']));
        }
        json_response(200, 'success', '');
    }
    
?>