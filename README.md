# AwsEsConnection
Kind of connector to use AWS Elasticsearch Service with elastic/elasticsearch-php client

## Installation
`composer require wizacha/awssignaturemiddleware`

## Usage
Exemple with elasticsearch client

```php
<?php
$credentials = new \Aws\Credentials\Credentials('id', 'secret');
$signature = new \Aws\Signature\SignatureV4('es', 'eu-west-1');

$middleware = new \Wizacha\Middleware\AwsSignatureMiddleware($credentials, $signature);
$defaultHandler = \Elasticsearch\ClientBuilder::defaultHandler();
$awsHandler = $middleware($defaultHandler)

$clientBuilder =  \Elasticsearch\ClientBuilder::create();

$clientBuilder
    ->setHandler($awsHandler)
    ->setHosts(['endpoint.eu-west-1.es.amazonaws.com:80'])
;
$client = $clientBuilder->build();
```