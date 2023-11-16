<?php

require_once __DIR__ . '/../vendor/autoload.php';

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();

// $fusionAuthClientId = $_ENV['FUSIONAUTH_CLIENT_ID'] ?? getenv('FUSIONAUTH_CLIENT_ID');
// $fusionAuthClientSecret = $_ENV['FUSIONAUTH_CLIENT_SECRET'] ?? getenv('FUSIONAUTH_CLIENT_SECRET');
// $fusionAuthBaseUrl = $_ENV['FUSIONAUTH_BASE_URL'] ?? getenv('FUSIONAUTH_BASE_URL');
// $fusionAuthRedirectUrl = $_ENV['FUSIONAUTH_REDIRECT_URL'] ?? getenv('FUSIONAUTH_REDIRECT_URL');



$provider = new \JerryHopper\OAuth2\Client\Provider\FusionAuth([
    'clientId'          => '{client-id}',
    'clientSecret'      => '{client-secret}',
    'redirectUri'       => 'https://example.com/callback-url',
    'urlAuthorize'            => 'fusionauth:9011/oauth2/authorize',
    'urlAccessToken'          => 'fusionauth:9011/oauth2/token',
    'urlResourceOwnerDetails' => 'fusionauth:9011/oauth2/userinfo',
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getNickname());

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}