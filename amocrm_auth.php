<?php

include_once __DIR__ . '/config.php';

session_start();

$apiClient->setAccountBaseDomain('autoschoolkoleso.amocrm.ru');

try {
    $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode('def50200443c00d19ef28b7a4d48e96e73855ebb3f82fe5ee555cf1a3deeb1b13aa85ccea8eb69a3aa89425e492f077b724d537e31873c5837b646d12b2fa5b1d3dd01af2c6a66d28cda94a85e63148a23c8b6dbc0b57faaea4b85c2cedb434f1f7ddb943500c07d0124a052becbcf11cbba13c2bd2a6ac5521857379166b59d98e0e16064b0f430b5dafb7a24fd79874ce41a103705a33241e934718ecb0dfd564de6072bdef6e81cda699b34ecf874756f26c189facef4795a0de9288b5cc1758d6c273393c8c3a1dcdbcfb86fa5864b87e47abfb3658cd79fdd0eb244f1dcad0a3c36d7f4991b87ffe3209f92443fc46d29379e09cdacfa229afcce32c94bb65d9d0f0e2aecc0540ebef560d9bc98497f0af8f9eb5cf03a0946f94a88526da261e90adf62f0ee5ae5efd471930e9b8c902e51a9984bd7752e3067ddf949f7d3331044aa8278bae7c9ba20ac558756d4fcdcff71bb41064b30bf6664c734f5c0e75f66db0ab409f9bc93fec35997fd261de777b7c1ce8cd5c937de4fb285fc853c21dc3a52a9698dfa0124cab3c9960929065b73d926b0b5bf731764a0a2d79aef821ada7a282a4d44407c462875211c6228127a417698467a5d59d44fdf70b7791a8f1724d2edc57dcd61795266e52d17adfaf9853a94c473d32a7ec34e446a464001191a3e112411443f23334cd8');
    var_dump($accessToken); 
    if (!$accessToken->hasExpired()) {
        saveToken([
            'accessToken' => $accessToken->getToken(),
            'refreshToken' => $accessToken->getRefreshToken(),
            'expires' => $accessToken->getExpires(),
            'baseDomain' => $apiClient->getAccountBaseDomain(),
        ]);
    }
} catch (Exception $e) {
    die((string)$e);
}

// YZKYBXOGGXLEMQRMI3VKZV7CVAHAKJT7
$ownerDetails = $apiClient->getOAuthClient()->getResourceOwner($accessToken);

var_dump('Hello, %s!', $ownerDetails->getName());