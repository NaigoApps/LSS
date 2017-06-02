<?php

/*
 * Makes a page secure.
 */
session_start();

require_once __DIR__.'/php/dao/UserDao.php';

require_once __DIR__.'/php/consts.php';
require_once __DIR__.'/google-api-php-client/src/Google/autoload.php';
//require_once dirname(__FILE__).'/GoogleClientApi/contrib/Google_AnalyticsService.php';

$client_id = '487942486199-ur74tmud23ud4fvdr7ao871ovomhg27l.apps.googleusercontent.com';
$client_secret = 'uClSFPHN-p_WgESXnnE0ryrw';

/**
 * Init current google client
 */
$client = new Google_Client();
$client->setAccessType('online'); // default: offline
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri('http://localhost/LSS/index.php');
//$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
$client->setScopes('email');

/*
 * User is requesting logout
 */
if (isset($_REQUEST['logout'])) {
    unset($_SESSION['access_token']);
    unset($_SESSION['user_data']);
    unset($_GET['code']);
    unset($user_data);
}else if (isset($_GET['code'])) {
    /*
     * We've got an authorization code: user approved login
     * Now we need to exchange it for an access token
     */
    try{
        $client->authenticate($_GET['code']);
        $_SESSION['access_token'] = $client->getAccessToken();
    }catch(Google_IO_Exception $e){
        print_msg($e->getMessage());
    }
    //$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    //header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    /*
     * User is logged in: 
     */
    $client->setAccessToken($_SESSION['access_token']);
} else {
    /*
     * User didn't login, so we must redirect him to Google login
     */
    
    if (isset($_GET['error'])) {
        /*
         * User didn't approve login.
         * Useless, but remind that could be handled
         */
    }
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
}

/*
 * If we are here user is logged in
 */
if ($client->getAccessToken()) {
    //print_msg('Checking access token');
    //['access_token'] = $client->getAccessToken();
    try {
        /*
         * Check if token is valid and extract data
         */
        $verified_token = $client->verifyIdToken();
        $token_data = $verified_token->getAttributes();

        $user_email = $token_data['payload']['email'];
        $USER_ID = $token_data['payload']['sub'];
        $_SESSION['user_data'] = (new UserDao())->findByEmail($user_email)->uniqueContent();
    } catch (Google_Auth_Exception $e) {
        /*
         * Token not valid: login again
         */
        unset($_SESSION['access_token']);
        $authUrl = $client->createAuthUrl();
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    } catch (Google_IO_Exception $e) {
        /*
         * Token not valid: login again
         */
        print_msg($e->getMessage());
    }
}

function print_msg($msg) {
    echo "<p class=\"text-success\">$msg</p>";
}

function filter($value){
    return $value;
}

?>