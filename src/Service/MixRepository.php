<?php

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Bridge\Twig\Command\DebugCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class MixRepository
{

    public function __construct(
        private HttpClientInterface $githubContentClient,
        private CacheInterface $cache,
        private bool $isDebug,
        #[Autowire(service: 'twig.command.debug')]
        private DebugCommand $debugCommand
    ) {}

    public function findAll(): array {
        /*$output = new BufferedOutput();
        $this->debugCommand->run(new ArrayInput([]), $output);
        dd($output);*/

        $url = '/SymfonyCasts/vinyl-mixes/main/mixes.json';
        return $this->cache->get('mixed.data.list', function(CacheItemInterface $cacheItem) use ($url) {
            $cacheItem->expiresAfter($this->isDebug ? 5 : 60);
            $response = $this->githubContentClient->request('GET', $url);
            return $response->toArray();
        });
    }

}