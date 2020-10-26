<?php

namespace App;

class SendgridWrapper
{
    private $apiKey = 'apiKey';
    private $client;

    public function __construct()
    {
        $this->client = new \SendGrid($this->apiKey);
    }

    public function sendMail($data = [])
    {
        if (empty($data)) {
            throw new \Exception('Data is required.');
        }

        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("nguyenvohoanghai@gmail.com", "Admin");
        $email->setSubject("Sending with SendGrid is Fun");
        $email->addContent('text/plain', 'ahihi');

        $listTo = [];
        $listSent = [];
        $marshaler = new \Aws\DynamoDb\Marshaler();
        foreach($data as $item) {
            $item = $marshaler->unmarshalItem($item);
            if (!in_array($item['email'], $listSent)) {
                $email->addTo($item['email']);
                $listSent[$item['email']] = $item['email'];
            }
        }
        unset($listSent);

        try {
            $response = $this->client->send($email);
            if ($response->statusCode() == '200') {
                return true;
            }
            // else {
            //     print $response->body();
            // }

            return false;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
