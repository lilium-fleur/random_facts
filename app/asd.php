<?php


var_dump([
    'HTTP_PROXY' => getenv('HTTP_PROXY') ?: null,
    'http_proxy' => getenv('http_proxy') ?: null,
    'HTTPS_PROXY' => getenv('HTTPS_PROXY') ?: null,
    'https_proxy' => getenv('https_proxy') ?: null,
    'ALL_PROXY' => getenv('ALL_PROXY') ?: null,
]);
