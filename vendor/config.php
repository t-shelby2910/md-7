<?php
require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$client = new Google_Client();

$client->setClientId('331503202638-i10076bvvb03k350l8ai5mgqengne25m.apps.googleusercontent.com');
$client->setClientSecret('QSjKPqffjYtufDbvKtF4NL87');
$client->setRedirectUri('https://sabiduria.in/authenticate_with_google.php');

$client->setAccessType('offline');
//$client->setPrompt('consent');
$client->setApprovalPrompt("force");

$client->setIncludeGrantedScopes(true);

$client->addScope('email');
$client->addScope('profile');
?>