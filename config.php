<?php

use AmoCRM\Client\AmoCRMApiClient;

include_once __DIR__ . '/vendor/autoload.php';

$clientId = 'a2e96c9a-3966-41f0-b135-0e58cb8b8cf5';
$clientSecret = 'MhTSENIVyDKhyWut3ARXAEpPLEAEpxjbyzXZF2Js34iri7Mjl3u9vLRnSCQqy1RQ';
$redirectUri = 'https://kooleso.online/terminal';

$apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);

include_once __DIR__ . '/vendor/amocrm/amocrm-api-library/examples/token_actions.php';
include_once __DIR__ . '/vendor/amocrm/amocrm-api-library/examples/error_printer.php';