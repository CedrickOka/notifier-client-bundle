<?php

namespace Oka\Notifier\ClientBundle;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\RequestException;
use Oka\Notifier\Message\Notification;
use Oka\ServiceDiscoveryBundle\Catalog\Catalog;
use Psr\Log\LoggerInterface;

/**
 * @author Cedrick Oka Baidai <okacedrick@gmail.com>
 */
class Notifier
{
    private $catalog;
    private $serviceName;
    private $logger;

    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * @var array
     */
    private $notifications;

    public function __construct(Catalog $catalog, string $serviceName, LoggerInterface $logger = null)
    {
        $this->catalog = $catalog;
        $this->serviceName = $serviceName;
        $this->logger = $logger;
        $this->notifications = [];
    }

    public function send(Notification $notification, bool $immediately = false): void
    {
        if (false === $immediately) {
            $this->notifications[] = $notification;
            return;
        }

        $this->doSend($notification);
    }

    public function flush(): void
    {
        if (true === empty($this->notifications)) {
            return;
        }

        $this->doSend(...$this->notifications);
        $this->notifications = [];
    }

    protected function doSend(Notification ...$notifications): bool
    {
        /** @var \Oka\Notifier\Message\Notification $notification */
        foreach ($notifications as $key => $notification) {
            $notifications[$key] = $notification->toArray();
        }

        try {
            $response = $this->getHttpClient()->post('/v1/rest/notifications', [
                RequestOptions::JSON => [
                    'notifications' => $notifications
                ]
            ]);

            return 204 === $response->getStatusCode();
        } catch (RequestException $e) {
            $this->logger->error(sprintf(
                'Notifier Client: %s: %s (uncaught exception) at %s line %s',
                get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ), $notifications);
        }

        return false;
    }

    protected function getHttpClient(): Client
    {
        if (null === $this->httpClient) {
            $this->httpClient = new Client(['base_uri' => (string) $this->catalog->getService($this->serviceName)]);
        }

        return $this->httpClient;
    }
}
