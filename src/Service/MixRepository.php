<?php

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MixRepository
{

    public function __construct(private HttpClientInterface $client, private CacheInterface $cache) {}

    public function findAll(): array {
        $url = 'https://raw.githubusercontent.com/SymfonyCasts/vinyl-mixes/main/mixes.json';
        return $this->cache->get('mixed.data.list', function(CacheItemInterface $cacheItem) use ($url) {
            $cacheItem->expiresAfter(20);
            $response = $this->client->request('GET', $url);
            return $response->toArray();
        });
    }

}