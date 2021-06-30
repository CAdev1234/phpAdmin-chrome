<?php
require '../lib/simple_json_res.php';
require '../module/dbconnection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_data = $db_connec->getAllQuery('client_data_tb');
    $clients = $db_connec->getAllQuery('client_tb');
    $clients_per_country = $db_connec->getQueryBySql('SELECT country, count(id) FROM client_data_tb GROUP BY country;');
    
    $data = array('client_data' => $client_data, 'clients' => $clients, 'current_timestamp' => date('Y-m-d H:i:s'), 'clients_per_country' => $clients_per_country);
    json_response(200, 'success', $data);
}
?>