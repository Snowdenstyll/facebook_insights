<?php

require  './vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__. '/../');
$dotenv->load();

use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Object\AdsInsights;


$app_id = _env('FACEBOOK_APP_ID');
$app_secret = _env('FACEBOOK_APP_SECRET');
$access_token = _env('FACEBOOK_ACCESS_TOKEN');
$account_id = _env('FACEBOOK_ACCOUNT_ID');
$campaign_id = _env('FACEBOOK_CAMPAIGN_ID');

// Initialize a new Session and instantiate an Api object
Api::init($app_id, $app_secret, $access_token);
// The Api object is now available through singleton
$api = Api::instance();

$fields = ['account_id', 'campaign_id'];
$params = ['date_preset'=>'maximum'];

$campaign = new Campaign($campaign_id);

$insights = $campaign->getInsights($fields, $params)->getResponse()->getContent();

print_r($insights);
