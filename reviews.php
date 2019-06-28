<?php

ini_set('display_errors', 1);
putenv('GOOGLE_APPLICATION_CREDENTIALS=villagedancetheatre-9663598abb13.json');

require_once('plugins/google/google-api-php-client-2.2.2/vendor/autoload.php');
require_once('plugins/google/mybusiness/MyBusiness.php');

$client = new Google_Client();
$client->setApplicationName('Village Dance Theatre Reviews');
$client->useApplicationDefaultCredentials();
$client->addScope('https://www.googleapis.com/auth/plus.business.manage');

$gmb_account ='115187020686795506577';
$gmb_location = '5465660576747196276';

$gmb = new Google_Service_MyBusiness($client);

$locationName = implode(array('accounts', $gmb_account, 'locations', $gmb_location));

$reviewData = $gmb->accounts_locations_reviews;


$reviews = array();

do {

	$listReviewsResponse = $reviewData->listAccountsLocationsReviews($locationName);

	$reviewsList = $listReviewsResponse->getReviews();

	foreach ($reviewsList as $index => $review) {

		$reviewItem = new stdClass();

		$reviewItem->date_created = $review->createTime;
		$reviewItem->date_updated = $review->updateTime;
		$reviewItem->rating = $review->starRating;
		$reviewItem->author = $review->reviewer->displayName;

		$reviews[] = $reviewItem;

	}

} while($listReviewsResponse->nextPageToken);

// $locs = $reviewData->listAccountsLocationsReviews($locationName);

echo '<pre>';
// echo var_dump(get_class_methods($locs));
echo var_dump($reviews);
echo '</pre>';
