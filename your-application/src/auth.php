<?php

# load all composer packages automatically. root folder is one level up.
require_once __DIR__ . '/../vendor/autoload.php';

# load .env variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$fusionAuthClientId =     $_ENV['FUSIONAUTH_CLIENT_ID']     ?? getenv('FUSIONAUTH_CLIENT_ID');
$fusionAuthClientSecret = $_ENV['FUSIONAUTH_CLIENT_SECRET'] ?? getenv('FUSIONAUTH_CLIENT_SECRET');
$fusionAuthBaseUrl =      $_ENV['FUSIONAUTH_BASE_URL']      ?? getenv('FUSIONAUTH_BASE_URL');
$fusionAuthRedirectUrl =  $_ENV['FUSIONAUTH_REDIRECT_URL']  ?? getenv('FUSIONAUTH_REDIRECT_URL');

$provider = new \JerryHopper\OAuth2\Client\Provider\FusionAuth([
    'clientId'          => $fusionAuthClientId,
    'clientSecret'      => $fusionAuthClientSecret,
    'redirectUri'       => $fusionAuthRedirectUrl,
    'urlAuthorize'            => $fusionAuthBaseUrl . '/oauth2/authorize',
    'urlAccessToken'          => $fusionAuthBaseUrl . '/oauth2/token',
    'urlResourceOwnerDetails' => $fusionAuthBaseUrl . '/oauth2/userinfo',
]);

// send user to fusionauth login, if not coming from there
if (!isset($_GET['code'])) {
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;
}
elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Invalid CSRF state');
}
else {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);
    try {
        $user = $provider->getResourceOwner($token);
        printf('Hello %s!', $user->getNickname());
    }
    catch (Exception $e) {
        exit('Failed to get user details from FusionAuth');
    }
    echo $token->getToken();
}