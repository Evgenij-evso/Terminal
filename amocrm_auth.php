<?php

include_once __DIR__ . '/config.php';

session_start();

$apiClient->setAccountBaseDomain('autoschoolkoleso.amocrm.ru');

try {
    $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode('def50200d453552937ac82df19dfc221c3ca9ec851599979bb2cbabb9f4a86a75ff958faaeaa1e0e3d28ece3e44b9eead4ffd4e237ae1d50d701f64d25285148786bec9b9de5be2026458cf7190e1b2b8145404d68ae892bc6abde0727e783a86849d437b0bedb6fbc7d4bdf01c752792429a4c0c069f3fa6f19ea7b952649b5a98b2a3e78aa7dbbba21b2d5355d5e86d26afa8e38e0a12d74b9c3c1bc3152e99dd637b861a38dccb9cdc4fcf987a69e5272d91e0fa0819038b7ce437bd4d1ac8a04fa00d166b4e4c4b0995a098062ceb676072c330af784f56d7be44952005d2540db73d4cade6a4bbfd7589645b5d77ca775161ad42623a0a4327a955233ad9113a5def33eaa2bcdb230e656ced57cf260388c8316363d3d12a148ce884eb72647cb9d574e26991677c8908756833b1bcd777e825450f3f4aadf124b6e9266ea14ae161713de7f4db608d7914d196c5983c91d5f1f57a762d8b4b64e1629d9acea9a280870c39e89f8230c77354a64f79e2921fb461d0c85d2fbc79380c370f4a994d76d4da7db342361ccbd09fefe5fe1d2aedcfea7fd00a0ed25cc49dd2e1c964113b0f9a846f8fadb94a8dae6dd81fe1d05ee152428f4a388782c6dccd58e75aac27c74119a7007bf8a77e760ed5977c966de4522cd637ba8e130eda6b3cb6422d69a20f027f03916713cd293a8');
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