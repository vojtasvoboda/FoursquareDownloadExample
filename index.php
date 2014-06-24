<?php

require 'vendor/autoload.php';

// enable debugger
Tracy\Debugger::enable();

// nastavíme knihovnu pro stahování dat
$client = new \TheTwelve\Foursquare\HttpClient\CurlHttpClient('vendor/haxx-se/curl/cacert.pem');
$factory = new \TheTwelve\Foursquare\ApiGatewayFactory($client);
$factory->setClientCredentials(
    'CLIENT_ID',
    'CLIENT_SECRET'
);

// koncový bod pro venues/*
$gateway = $factory->getVenuesGateway();

// získání dat z venues/explore
$venues = $gateway->search(array(
    'll' => '50.071726,14.402497',
    'radius' => 250,
    'limit' => 5
));

Tracy\Debugger::dump($venues);
