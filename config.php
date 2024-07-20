<?php

use AmoCRM\Client\AmoCRMApiClient;

include_once __DIR__ . '/vendor/autoload.php';

$clientId = '0a80f143-fe82-4b48-9ee4-41b7cc187d62';
$clientSecret = 'a07KIwdGc5Mrq3VFs9vozu23Zvx2ubD14n8OHL6mZnzcnTLg43zULRueZeGMQBeO';
$redirectUri = 'https://kooleso.online/terminal';

$apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);

include_once __DIR__ . '/vendor/amocrm/amocrm-api-library/examples/token_actions.php';
include_once __DIR__ . '/vendor/amocrm/amocrm-api-library/examples/error_printer.php';