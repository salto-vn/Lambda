<?php

require 'vendor/autoload.php';

use App\DynamoDbWrapper;
use App\S3Wrapper;
use App\General;
use App\SendgridWrapper;

$db = new DynamoDbWrapper('Users');
$listUser = $db->getListWithScan([
    'created_at_lte' => strtotime('today midnight'),
    'sent' => 1
]);

if (!empty($listUser)) {
    //upload csv to S3
    $csv = General::createCsvString($listUser, ['email', 'last_login', 'created_at_lte']);
    
    if (file_exists($pathFile)) {
        $storage = new S3Wrapper();
        $result = $storage->uploadFile([
            'Bucket' => 'dummy2019',
            'Key'    => date('d/m/Y'),
            'Body' => $csv,
        ]);
    }

    //send mail
    (new SendgridWrapper())->sendMail($listUser);
}
