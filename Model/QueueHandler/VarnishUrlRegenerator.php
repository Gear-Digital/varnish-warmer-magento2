<?php
/**
 * File: VarnishUrlRegenerator.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2019 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\VarnishWarmer\Model\QueueHandler;

use LizardMedia\VarnishWarmer\Api\QueueHandler\VarnishUrlRegeneratorInterface;
use React\HttpClient\Response;

/**
 * Class VarnishUrlRegenerator
 * @package LizardMedia\VarnishWarmer\Model\QueueHandler
 */
class VarnishUrlRegenerator extends AbstractQueueHandler implements VarnishUrlRegeneratorInterface
{
    /**
     * @var string
     */
    const PROCESS_TYPE = 'REGENERATE';

    /**
     * @param string $url
     * @return void
     */
    public function addUrlToRegenerate(string $url): void
    {
        $this->urls[] = $url;
        $this->total++;
    }

    /**
     * @return void
     */
    public function runRegenerationQueue(): void
    {
        while (!empty($this->urls)) {
            for($i = 0; $i < $this->getMaxNumberOfProcesses(); $i++) {
                if (!empty($this->urls)) {
                    $this->createRequest(array_pop($this->urls));
                }
            }
            $this->loop->run();
        }
    }

    /**
     * @return int
     */
    protected function getMaxNumberOfProcesses(): int
    {
        return $this->configProvider->getMaxConcurrentRegenerationProcesses();
    }

    /**
     * @return string
     */
    protected function getQueueProcessType(): string
    {
        return self::PROCESS_TYPE;
    }

    /**
     * @param string $url
     * @return void
     */
    private function createRequest(string $url): void
    {
        $client = $this->clientFactory->create($this->loop);
        $request = $client->request('GET', $url, $this->buildHeaders());
        $request->on('response', function (Response $response) use ($url) {
            $response->on(
                'end',
                function () use ($url){
                    $this->counter++;
                    $this->log($url);
                    $this->logProgress();
                }
            );
        });
        $request->end();
    }

    private function buildHeaders(): array
    {
        $headers = [];
        // Force host to use the store URL
        $parsedUrl = parse_url($this->storeUrl);
        if (isset($parsedUrl['host'])) {
            $headers['Host'] = $parsedUrl['host'];
        }
        if (isset($parsedUrl['scheme'])) {
            $headers['X-Forwarded-Proto'] = $parsedUrl['scheme'];
        }
        $varyString = $this->context->getVaryString();
        if ($varyString) {
            $headers['Cookie'] = 'X-Magento-Vary=' . $varyString;
        }
        return $headers;
    }
}
