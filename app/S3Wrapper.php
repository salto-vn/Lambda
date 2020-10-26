<?php

namespace App;

class S3Wrapper extends AwsAbstract
{
    public $client;

    public function __construct()
    {
        parent::__construct([
            'version' => '2006-03-01',
        ]);

        $this->client = $this->sdk->createS3();
    }

    public function uploadFile($options = [])
    {
        if (empty($options)) {
            throw new \Exception('Options are required as array.');
        }
       
        return $this->client->putObject($options);
    }
}