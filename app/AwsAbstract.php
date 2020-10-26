<?php
namespace App;

use Aws\Sdk;

abstract class AwsAbstract
{
    protected $sdk;
    protected $options = [
        'region'   => 'us-east-2',
        'version'  => 'latest'
    ];

    public function __construct($agrs = [])
    {
        $options = array_merge($this->options, $agrs);
        
        $this->sdk = new Sdk($options);
    }
}
