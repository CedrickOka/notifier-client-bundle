<?php

namespace Oka\Notifier\ClientBundle\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Psr7\Response;
use Oka\Notifier\ClientBundle\Notifier;
use Oka\Notifier\Message\Address;
use Oka\Notifier\Message\Notification;
use Oka\ServiceDiscoveryBundle\Catalog\Catalog;
use Oka\ServiceDiscoveryBundle\Catalog\Service;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 *
 * @author Cedrick Oka Baidai <cedric.baidai@veone.net>
 *
 */
class NotifierTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $catalogMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $httpClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $loggerMock;

    /**
     * @covers
     */
    public function setUp(): void
    {
        $this->catalogMock = $this->createMock(Catalog::class);
        $this->catalogMock
            ->method('getService')
            ->with('notifier')
            ->willReturn(new Service('localhost', 8080));

        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->httpClientMock = $this->createMock(Client::class);
        $this->httpClientMock
            ->method('__call')
            ->with('post', [
                '/v1/rest/notifications',
                [
                    RequestOptions::JSON => [
                        'notifications' => [
                            [
                                'channels' => ['sms'],
                                'sender' => ['value' => 'MTN DRIVE'],
                                'receiver' => ['value' => '00000000'],
                                'message' => 'Hello World!',
                                'attributes' => [
                                    'priority' => 0
                                ]
                            ]
                        ]
                    ]
                ]
            ])
            ->willReturn(new Response(204));
    }

    /**
     * @covers
     * @doesNotPerformAssertions
     */
    public function testThatCanSendNotification()
    {
        $notifier = new Notifier($this->catalogMock, 'notifier', $this->loggerMock);
        $reflProperty = new \ReflectionProperty(Notifier::class, 'httpClient');
        $reflProperty->setAccessible(true);
        $reflProperty->setValue($notifier, $this->httpClientMock);

        $notifier->send(new Notification(['sms'], new Address('MTN DRIVE'), new Address('00000000'), 'Hello World!', null, ['priority' => 0]), true);
    }
}
