<?php

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MixRepository
{

    public function __construct(
        private HttpClientInterface $githubContentClient,
        private CacheInterface $cache,
        private bool $isDebug
    ) {}

    public function findAll(): array {
        $url = '/SymfonyCasts/vinyl-mixes/main/mixes.json';
        return $this->cache->get('mixed.data.list', function(CacheItemInterface $cacheItem) use ($url) {
            $cacheItem->expiresAfter($this->isDebug ? 5 : 60);
            $response = $this->githubContentClient->request('GET', $url);
            return $response->toArray();
        });
    }

}