<?php

require('./Cache.php');
require('./CacheService.php');

if (php_sapi_name() === "cli") {
    if ($argc < 5 || $argv[1] !== '--port' || $argv[3] !== '--origin') {
        echo "Usage: php proxy.php --port <number> --origin <url>\n";
        exit(1);
    }

    $port = $argv[2];
    $origin = $argv[4];

    $cache = new Cache(5);
    $cacheService = new CacheService($cache);

    try {
        $cacheService->initServer($port, $origin);
    } catch (\Exception $e) {
        echo "Failed to start the server: " . $e->getMessage();
        exit(1);
    }
}
