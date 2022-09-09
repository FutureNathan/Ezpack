<?php

/*
 * Sample bootstrap file.
 */

#################################################################################################### ---
 
// The location of your project's vendor autoloader.

require __DIR__  . '/PayPal-PHP-SDK/autoload.php';
// require __DIR__ . '/common.php';

#################################################################################################### ---

use \PayPal\Auth\OAuthTokenCredential;
use \PayPal\Rest\ApiContext;

#################################################################################################### --- CALL getApiContext()

/** @var \Paypal\Rest\ApiContext $apiContext */
$apiContext = getApiContext($clientId, $clientSecret);

return $apiContext;

#################################################################################################### --- getApiContext FUNCTION

/**
 * Helper method for getting an APIContext for all calls
 * @param string $clientId Client ID
 * @param string $clientSecret Client Secret
 * @return PayPal\Rest\ApiContext
 */
 
function getApiContext($clientId, $clientSecret) {

  // ### Api context
  // Use an ApiContext object to authenticate
  // API calls. The clientId and clientSecret for the
  // OAuthTokenCredential class can be retrieved from
  // developer.paypal.com

  $apiContext = new ApiContext(
    new OAuthTokenCredential(
        CLIENT_ID,
        CLIENT_SECRET
    )
  );

  // Comment this line out and uncomment the PP_CONFIG_PATH
  // 'define' block if you want to use static file
  // based configuration

  $apiContext->setConfig(
    array(
      'mode' => 'sandbox',
      'log.LogEnabled' => true,
      'log.FileName' => '../PayPal.log',
      'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
      'cache.enabled' => true,
      //'cache.FileName' => '/PaypalCache' // for determining paypal cache directory
      // 'http.CURLOPT_CONNECTTIMEOUT' => 30
      // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
      //'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
    )
  );

  // Partner Attribution Id
  // Use this header if you are a PayPal partner. Specify a unique BN Code to receive revenue attribution.
  // To learn more or to request a BN Code, contact your Partner Manager or visit the PayPal Partner Portal
  // $apiContext->addRequestHeader('PayPal-Partner-Attribution-Id', '123123123');

  return $apiContext;
} 
