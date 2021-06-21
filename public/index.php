<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| First we need to get an application instance. This creates an instance
| of the application / container and bootstraps the application so it
| is ready to receive HTTP / Console requests from the environment.
|
*/
// $allowedDomains = [
// 	'http://cs2.local',
// 	'http://cs2-local.mto.zing.vn',
// 	'https://cs2-local.mto.zing.vn',
// 	'https://laravel-vuejs-demo-local.com.vn'
// ];
// if (in_array($_SERVER['HTTP_ORIGIN'], $allowedDomains)) {
//     header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
// }
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Accept, Accept-Language, Authorization, Client-Id, Twitch-Api-Token, X-Forwarded-Proto, X-Requested-With, X-Csrf-Token, Content-Type, X-Device-Id');
$app = require __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$app->run();
