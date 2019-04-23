<?php

namespace tests\functional;

use yii2rails\extension\telegram\helpers\MockResponseHelper;
use GuzzleHttp\Client;
use phpbundle\rest\domain\enums\HttpMethodEnum;
use yii2lab\test\Test\Unit;

class MessageTest extends Unit
{

    public function testSendMessage()
    {
        MockResponseHelper::clear();
        $this->sendRequest('привет');
        $this->assetMessage('привет');
    }

    protected function assetMessage($text)
    {
        $last = MockResponseHelper::first();
        $this->tester->assertEquals('sendMessage', $last['method']);
        $this->tester->assertEquals([
            'answerText' => $text,
        ], $last['params']);
    }

    protected function sendRequest($text)
    {
        $body = [
            "update_id" => 482389383,
            "message" => [
                "message_id" => 3004,
                "from" => [
                    "id" => 123456,
                    "is_bot" => false,
                    "first_name" => "testUser",
                    "username" => "testUser",
                    "language_code" => "ru",
                ],
                "chat" => [
                    "id" => 123456,
                    "first_name" => "testUser",
                    "username" => "testUser",
                    "type" => "private",
                ],
                "date" => 1555146733,
                "text" => $text,
            ],
        ];
        $envLocal = include(ROOT_DIR . DS . 'common/config/env-local.php');
        $client = new Client([
            'base_uri' => $envLocal['url']['test-telegram'],
            'json' => $body,
            //'debug' => true,
            'decode_content' => false,
        ]);
        $response = $client->request(HttpMethodEnum::POST);

        $this->tester->assertEquals(200, $response->getStatusCode());
        $this->tester->assertEquals('text/html; charset=UTF-8', $response->getHeaderLine('content-type'));

        return $response;
    }

}
