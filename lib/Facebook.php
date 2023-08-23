<?php

require  './vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__. '/../');
$dotenv->load();

use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\AdsInsights;
use FacebookAds\Object\Values\InsightsActionBreakdownsValues;

$app_id = _env('FACEBOOK_APP_ID');
$app_secret = _env('FACEBOOK_APP_SECRET');
$access_token = _env('FACEBOOK_ACCESS_TOKEN');
$account_id = _env('FACEBOOK_ACCOUNT_ID');
$campaign_id = _env('FACEBOOK_CAMPAIGN_ID');

// Initialize a new Session and instantiate an Api object
Api::init($app_id, $app_secret, $access_token);
// The Api object is now available through singleton
$api = Api::instance();

/* $fields = ['account_id', 'account_name', 'clicks'];
$params = ['date_preset'=>'maximum'];

$campaign = new Campaign($campaign_id);

$insights = $campaign->getInsights($fields, $params)->getResponse()->getContent();

print_r($insights);

exit; */

$fields = ['account_id', 'account_name', 'clicks'];
$params = ['date_preset'=>'maximum'];

$account = new AdAccount($account_id);
$campaigns = $account->getCampaigns(array(
  CampaignFields::NAME, CampaignFields::STATUS, CampaignFields::DAILY_BUDGET
), array('date_preset'=>'maximum'));

foreach($campaigns as $campaign) {
    $insight = $campaign->getInsights(array(
        'ad_name', 'reach', 'impressions', 'clicks' // Add more metrics as needed
    ), array('date_preset' => 'maximum'));
    print_r($insight->getResponse()->getContent());
}

exit;
$insights = [];
foreach ($campaigns as $c) {
    $campaign = new Campaign($c['id']);
    $insight = $campaign->getInsights($fields, $params)->getResponse()->getContent()['data'];
    $insights[] = $insight;
}

print_r($insights);

exit;
// Calculate ROAS for each campaign
foreach ($insights as $insight) {
    $campaignName = $insight[CampaignFields::NAME];
    $spend = $insight['spend'];
    $revenue = $insight['purchase'];
    $roas = ($revenue > 0) ? ($revenue / $spend) : 0;

    echo "Campaign: $campaignName\n";
    echo "ROAS: $roas\n\n";
}

exit;
foreach ($campaigns as $campaign) {
    if ($campaign['status'] == 'ACTIVE') {
        $c = new Campaign($campaign['id']);
        $active_campaigns[] = $c;

        // Retrieve insights for the current campaign
        $insightFields = ['ad_id']; // Modify as needed
        $insightParams = ['date_preset' => 'maximum']; // Modify as needed
        try {
            $insightsResponse = $c->getInsights($insightFields, $insightParams)->getResponse()->getContent();

            if (isset($insightsResponse['data']) && !empty($insightsResponse['data'])) {
                $insights[] = $insightsResponse['data'];
            } else {
                echo "No insights data for campaign ID: " . $campaign['id'] . PHP_EOL;
            }
        } catch (\Exception $e) {
            echo "Error retrieving insights: " . $e->getMessage() . PHP_EOL;
        }
    }
}


print_r($insights);
