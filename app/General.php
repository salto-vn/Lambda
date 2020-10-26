<?php

namespace App;

class General
{
    /**
     * Create csv data
     * @param string $tile
     * @param array $data
     * @return string
     */
    static public function createCsvString($data = [], $header = [], $glue = "\t")
	{
        if (empty($data)) {
            throw new \Exception('Data is required as a first argument!');
        }

		// Create title for csv
        $marshaler = new \Aws\DynamoDb\Marshaler();
        $csv = implode($glue, $header) . "\r\n";
		foreach ($data as $item) {
            $item = $marshaler->unmarshalItem($item);
            
            $csv .= implode($glue, [
                $item['email'],
                date('d/m/Y', @$item['last_login']),
                date('d/m/Y', @$item['created_at'])
            ]) . "\r\n";
        }

        return $csv;
	}
}