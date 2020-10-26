<?php

namespace App;

use \Aws\DynamoDb\Marshaler;

class DynamoDbWrapper extends AwsAbstract
{
    private $client;
    private $tableName = 'users';

    public function __construct($tableName)
    {
        parent::__construct();

        if (!empty($tableName)) {
            $this->tableName = $tableName;
        }

        $this->client = $this->sdk->createDynamoDb();
    }

    public function getListWithScan($condition)
    {
		try {
            $eav = [];
            $filter = '';

            if ($condition['created_at_lte']) {
                $eav[':created_at'] = $condition['created_at_lte'];
                $filter .= ' AND created_at <= :created_at';
            }

            if (isset($condition['sent'])) {
                $eav[':sent'] = (int) $condition['sent'];
                $filter .= ' AND sent = :sent';
            }
            
            $marshaler = new \Aws\DynamoDb\Marshaler();
            $params = [
                'TableName' => $this->tableName,
                'FilterExpression' => trim($filter, ' AND'),
                'ExpressionAttributeValues'=> $marshaler->marshalJson(json_encode($eav))
            ];
            var_dump($params);
            die;
            $result = $this->client->scan($params);
            var_dump($result['Items']);
            exit;
            return $result['Items'];
		} catch (DynamoDbException $e) {
            throw $e;
		}
    }
}
