<?php

class CacheService
{
    private $server;

    public function __construct(
        private Cache $cache
    ) {
        $this->verifyCacheExists();
    }

    public function cacheResponse(string $url, $response)
    {
        $this->cache->createCache($url, $response);
    }

    public function initServer($port, $origin)
    {
        $this->server = stream_socket_server("tcp://localhost:$port", $errno, $errstr);
        if (!$this->server) {
            throw new \RuntimeException("Error starting server: $errstr ($errno)\n");
        }

        $this->handleServer($origin);
    }

    public function verifyCacheExists()
    {
        if (!file_exists(CACHE_DIR)) {
            mkdir(CACHE_DIR, 0777, true);
        }
    }

    public function handleServer($origin)
    {
        while ($conn = stream_socket_accept($this->server)) {
            try {
                $request = fread($conn, 1024);
                preg_match("/GET (.*?) HTTP/", $request, $matches);

                if (!isset($matches[1])) {
                    fwrite($conn, "HTTP/1.1 400 Bad Request\r\n\r\nError: Invalid Request");
                    continue;
                }

                $url = $origin . $matches[1];
                $cachedResponse = $this->cache->getCachedResponse($url);

                if ($cachedResponse) {
                    echo "Cache HIT for $url\n";
                    fwrite($conn, "HTTP/1.1 200 OK\r\nX-Cache: HIT\r\n\r\n" . $cachedResponse);
                } else {
                    echo "Cache MISS for $url\n";
                    $response = @file_get_contents($url);

                    if ($response !== false) {
                        $this->cacheResponse($url, $response);
                        fwrite($conn, "HTTP/1.1 200 OK\r\nX-Cache: MISS\r\n\r\n" . $response);
                    } else {
                        fwrite($conn, "HTTP/1.1 500 Internal Server Error\r\n\r\nError forwarding request");
                    }
                }
            } finally {
                fclose($conn); // Garantir que a conexão seja fechada em qualquer caso
            }
        }
    }
}
