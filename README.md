# AwsEsConnection
Kind of connector to use AWS Elasticsearch Service with elastic/elasticsearch-php client

[![Build Status](https://travis-ci.org/wizacha/AwsSignatureMiddleware.svg?branch=master)](https://travis-ci.org/wizacha/AwsSignatureMiddleware)

## Installation
`composer require wizacha/aws-signature-middleware`

## Usage
Exemple with elasticsearch client

```php
<?php
$credentials = new \Aws\Credentials\Credentials('id', 'secret');
$signature = new \Aws\Signature\SignatureV4('es', 'eu-west-1');

$middleware = new \Wizacha\Middleware\AwsSignatureMiddleware($credentials, $signature);
$defaultHandler = \Elasticsearch\ClientBuilder::defaultHandler();
$awsHandler = $middleware($defaultHandler);

$clientBuilder =  \Elasticsearch\ClientBuilder::create();

$clientBuilder
    ->setHandler($awsHandler)
    ->setHosts(['endpoint.eu-west-1.es.amazonaws.com:80'])
;
$client = $clientBuilder->build();
```
