<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ApiClientException;

class ApiClient
{
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
        return [];
    }
}