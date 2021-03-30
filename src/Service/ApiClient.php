<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ApiClientException;
use Psr\Log\LoggerInterface;

class ApiClient
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $method
     * @param string $url
     * @param $data
     *
     * @throws ApiClientException
     *
     * @return array
     */
    public function send(string $method, string $url, $data): array
    {
        $this->logger->info('API :: Send ' . $method . ' request to ' . $url);

        return [];
    }
}