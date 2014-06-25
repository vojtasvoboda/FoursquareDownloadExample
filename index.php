<?php

// force using of UTF-8
header('Content-type: text/html; charset=utf-8');

// load all libraries installed from composer
require 'vendor/autoload.php';

// enable debugger
Tracy\Debugger::enable();

// set library for downloading data
$client = new \TheTwelve\Foursquare\HttpClient\CurlHttpClient('vendor/haxx-se/curl/cacert.pem');
$factory = new \TheTwelve\Foursquare\ApiGatewayFactory($client);
$factory->setClientCredentials(
    'CLIENT_ID',
    'CLIENT_SECRET'
);

// end-point for venues/*
$gateway = $factory->getVenuesGateway();

// TODO
// doplnit explore, nebo search, podle toho co není hotové
// dibi pro ukládání dat

// gets data from venues/explore
$venues = $gateway->search(array(
    'll' => '50.071726,14.402497',
    'radius' => 250,
    'limit' => 1
));

// print venues
foreach ($venues as $venue) {
    \Tracy\Debugger::dump($venue);
}
