<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     https://opensource.org/licenses/MIT
 */
namespace Wizacha\Middleware;

use Aws\Credentials\Credentials;
use Aws\Signature\SignatureV4;
use PHPUnit\Framework\TestCase;

class AwsSignatureMiddlewareTest extends TestCase
{
    public function test invoke with host lowercase()
    {
        $tested = $this->generateTestMiddleware();

        $result = $tested([
            'headers' => [
                'host' => 'endpoint.eu-west-1.es.amazonaws.com:80',
            ],
            'http_method' => 'PUT',
            'uri' => '/twitter/tweet/1',
            'body' => '{"message" : "trying out Elasticsearch"}',
        ]);

        self::assertEquals([
            'headers' => [
                'host' => 'endpoint.eu-west-1.es.amazonaws.com:80',
                'X-Amz-Date' => [
                    0 => '20180104T094352Z',
                ],
                'Authorization' => [
                    0 => 'AWS4-HMAC-SHA256 Credential=id/20180104/eu-west-1/es/aws4_request, SignedHeaders=host;x-amz-date, Signature=f7497cc354b6efdb16119b8aed83938bfe92cc700b7a7452e8651e52be89ee34'
                ],
            ],
            'http_method' => 'PUT',
            'uri' => '/twitter/tweet/1',
            'body' => '{"message" : "trying out Elasticsearch"}',
        ], $result);
    }

    public function test invoke with host ucfirst()
    {
        $tested = $this->generateTestMiddleware();

        $result = $tested([
            'headers' => [
                'Host' => 'endpoint.eu-west-1.es.amazonaws.com:80',
            ],
            'http_method' => 'PUT',
            'uri' => '/twitter/tweet/1',
            'body' => '{"message" : "trying out Elasticsearch"}',
        ]);

        self::assertEquals([
            'headers' => [
                'Host' => 'endpoint.eu-west-1.es.amazonaws.com:80',
                'X-Amz-Date' => [
                    0 => '20180104T094352Z',
                ],
                'Authorization' => [
                    0 => 'AWS4-HMAC-SHA256 Credential=id/20180104/eu-west-1/es/aws4_request, SignedHeaders=host;x-amz-date, Signature=f7497cc354b6efdb16119b8aed83938bfe92cc700b7a7452e8651e52be89ee34'
                ],
            ],
            'http_method' => 'PUT',
            'uri' => '/twitter/tweet/1',
            'body' => '{"message" : "trying out Elasticsearch"}',
        ], $result);
    }

    private function generateTestMiddleware() : callable
    {
        // Mock time Aws\Signature to reproduce same result
        if (!function_exists('Aws\Signature\gmdate')) {
            eval("namespace Aws\Signature; function gmdate() { return '20180104T094352Z'; }");
        }

        // Create our middleware
        $middleware = new AwsSignatureMiddleware(
            new Credentials('id', 'secret'),
            new SignatureV4('es', 'eu-west-1')
        );

        // Use a fake handler which return its input
        $fakeHandler = function ($input) {
            return $input;
        };

        return $middleware($fakeHandler);
    }
}
