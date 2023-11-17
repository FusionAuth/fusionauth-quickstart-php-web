<?php

# display errors in server console, but don't send to browser
// ini_set('display_errors', 0);
// ini_set('log_errors', '1');
// ini_set('error_log', 'php://stderr');

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
    'urlAccessToken'          => 'http://host.docker.internal:9011' . '/oauth2/token',
    'urlResourceOwnerDetails' => 'http://host.docker.internal:9011' . '/oauth2/userinfo',
]);

startSafeSession();
// redirect to account.php if already logged in
if (isset($_SESSION['id'])) {
    header('Location: account.php');
    exit;
}
// send user to fusionauth login, if not coming from there
if (!isset($_GET['code'])) {
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;
}
// check csrf
elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Invalid CSRF state');
}
// handle fusionauth callback
else {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);
    try {
        $user = $provider->getResourceOwner($token); // Properties: "applicationId"=>"e9fdb985-9173-4e01-9d73-ac2d60d1dc8e", "birthdate"=>"1985-11-23", "email"=>"richard@example.com", "email_verified"=>true, "family_name"=>"Hendricks", "given_name"=>"Richard", "roles"=>{}, "sub"=>"00000000-0000-0000-0000-111111111111", "tid"=>"d7d09513-a3f5-401c-9685-34ab6c552453"
        $userArray = $user->toArray();

        $email = $user->getEmail();
        $name = $userArray['given_name'];

        session_regenerate_id();
        $_SESSION['id'] = $user->getId();
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;
        header('Location: account.php');
    }
    catch (Exception $e) {
        exit('Failed to get user details from FusionAuth');
    }
}

function startSafeSession() {
    $cookieParams = [
        'lifetime' => 0, // 0 means "until the browser is closed"
        'path' => '/', // entire site can use this cookie
        // 'domain' => '', // Set your domain here
        'secure' => false, // true for HTTPS, false for HTTP
        'httponly' => true, // true to make the cookie accessible only through the HTTP protocol
        'samesite' => 'Strict' // None, Lax, or Strict
    ];
    session_set_cookie_params($cookieParams);
    session_start();
}

