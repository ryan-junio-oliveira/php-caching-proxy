<?php

class Cache
{
    private int $expirationMinutes;

    public function __construct(int $expirationMinutes)
    {
        $this->expirationMinutes = $expirationMinutes;
        $this->defineCacheConstants();
        $this->clearCache();
    }

    private function defineCacheConstants()
    {
        if (!defined('CACHE_DIR')) {
            define('CACHE_DIR', __DIR__ . '/cache/');
        }

        if (!defined('CACHE_TTL')) {
            define('CACHE_TTL', 60 * $this->expirationMinutes);
        }
    }

    public function clearCache(): bool
    {
        $files = glob(CACHE_DIR . '*');
        foreach ($files as $file) {
            unlink($file);
        }
        return true;
    }

    public function createCache($url, $response)
    {
        $cacheFile = CACHE_DIR . md5($url);
        $data = [
            'timestamp' => time(),
            'response' => $response
        ];
        file_put_contents($cacheFile, serialize($data));
    }

    public function getCachedResponse($url)
    {
        $cacheFile = CACHE_DIR . md5($url);
        if (file_exists($cacheFile)) {
            $data = unserialize(file_get_contents($cacheFile));
            if (time() - $data['timestamp'] < CACHE_TTL) {
                return $data['response'];
            }
        }
        return false;
    }
}