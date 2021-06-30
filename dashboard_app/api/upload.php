<?php
require '../module/dbconnection.php';
require '../lib/uuid_gen.php';
require '../lib/simple_json_res.php';
if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_REQUEST['screenshot'])) {
    $file_name = UUID::v4() . '.png';
    $folder = "../upload/image/" . $file_name;
    // remove data:image/png;base64, in base64 data uri
    $img_data = substr($_REQUEST['screenshot'], strpos($_REQUEST['screenshot'], ',') + 1);
    // Move the uploaded image into the image folder
    file_put_contents($folder, base64_decode($img_data));
    $image = array('path' => '/upload/image/' . $file_name);
    // $img_id = $db_connec->insertQuery('upload_image', $image);
    // print_r($img_id);
    json_response(200, 'upload success', array(['img_path' => $image['path']])[0]);
}
?>