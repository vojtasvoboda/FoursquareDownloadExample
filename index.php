<?php

// force using of UTF-8
header('Content-type: text/html; charset=utf-8');

// load all libraries installed from composer
require 'vendor/autoload.php';
require 'libs/functions.inc.php';

// enable debugger
Tracy\Debugger::enable();

// set library for downloading data
$caCertificate = \Kdyby\CurlCaBundle\CertificateHelper::getCaInfoFile();
$client = new \TheTwelve\Foursquare\HttpClient\CurlHttpClient($caCertificate);
$factory = new \TheTwelve\Foursquare\ApiGatewayFactory($client);
$factory->setClientCredentials(
    'CLIENT_ID',
    'CLIENT_SECRET'
);

// end-point for venues/*
$gateway = $factory->getVenuesGateway();

// gets data from venues/explore
$venues = $gateway->search(array(
    // 'll' => '50.071726,14.402497',
    'near' => 'Prague, Czech Republic',
    'radius' => 100000,
    'limit' => 5000,
    // 'intent' => 'global',
    // 'query' => 'sushi'
));
// \Tracy\Debugger::dump($venues);

// connect to database
$options = array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'foursquare'
);
$connection = new DibiConnection($options);

// allow database fields
$allowFields = array(
    'place_id', 'name', 'contact_phone', 'contact_formattedPhone', 'contact_twitter', 'location_address',
    'location_crossStreet', 'location_lat', 'location_lng', 'location_distance', 'location_postalCode', 'location_cc',
    'location_city', 'location_state', 'location_country', 'location_formattedAddress_0', 'location_formattedAddress_1',
    'categories_0_id', 'categories_0_name', 'verified', 'stats_checkinsCount', 'stats_usersCount', 'stats_tipCount',
    'url', 'specials_count', 'specials_items', 'hereNow_count', 'hereNow_summary', 'referralId'
);

// save venues
foreach ($venues as $venue) {
    // \Tracy\Debugger::dump($venue);
    $data = get_venue_data_flatten($venue, $allowFields);
    // if exists, skip
    $exists = $connection->query('SELECT place_id FROM places WHERE place_id = ?', $data['place_id'])->count();
    if ( !$exists ) {
        $connection->query('INSERT INTO places', $data);
        echo "Venue $data[place_id] created!<br>";
    } else {
        echo "Record $data[place_id] exists!<br>";
    }
}
