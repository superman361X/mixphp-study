<?php

namespace apps\api\controllers;

use mix\facades\Request;
use PayPal\Api\OpenIdSession;
use PayPal\Api\OpenIdTokeninfo;
use PayPal\Api\OpenIdUserinfo;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use Swoole\Mysql\Exception;


class PayController
{

    public function actionCreateUrl()
    {

        $clientId = 'AVAnHudP_Shyc3NkvjPxGePTza_r6JQT3VbOq8uZzFAocnCT4BiSJDJ-K2BuR3B8f7Glvp67vbnxDVyY';
        $clientSecret = 'EFYGGetGFUvTwjub4D3Klpa0c36fraewuPCHGBYiD0CgyE1Y7kZKibm-Es6d4jI_WI20fB15U7ZswqBK';

        $baseUrl = 'https://a.seeedstudio.com/oauth.html';
        $apiContext = $this->getApiContext($clientId, $clientSecret);

        $redirectUrl = OpenIdSession::getAuthorizationUrl(
            $baseUrl,
            array('openid', 'profile', 'address', 'email', 'phone',
                'https://uri.paypal.com/services/paypalattributes',
                'https://uri.paypal.com/services/expresscheckout',
                'https://uri.paypal.com/services/invoicing'),
            null,
            null,
            null,
            $apiContext
        );
        echo $redirectUrl;
    }


    private function getApiContext($clientId, $clientSecret)
    {

        // #### SDK configuration
        // Register the sdk_config.ini file in current directory
        // as the configuration source.
        /*
        if(!defined("PP_CONFIG_PATH")) {
            define("PP_CONFIG_PATH", __DIR__);
        }
        */


        // ### Api context
        // Use an ApiContext object to authenticate
        // API calls. The clientId and clientSecret for the
        // OAuthTokenCredential class can be retrieved from
        // developer.paypal.com

        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $clientId,
                $clientSecret
            )
        );

        // Comment this line out and uncomment the PP_CONFIG_PATH
        // 'define' block if you want to use static file
        // based configuration

        $apiContext->setConfig(
            array(
                'mode' => 'sandbox',
                'log.LogEnabled' => true,
                'log.FileName' => '../runtime/logs/PayPal.log',
                'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
                'cache.enabled' => true,
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


    public function actionUrlCallback()
    {
        $clientId = 'AVAnHudP_Shyc3NkvjPxGePTza_r6JQT3VbOq8uZzFAocnCT4BiSJDJ-K2BuR3B8f7Glvp67vbnxDVyY';
        $clientSecret = 'EFYGGetGFUvTwjub4D3Klpa0c36fraewuPCHGBYiD0CgyE1Y7kZKibm-Es6d4jI_WI20fB15U7ZswqBK';

        $apiContext = $this->getApiContext($clientId, $clientSecret);

        // ### User Consent Response
        // PayPal would redirect the user to the redirect_uri mentioned when creating the consent URL.
        // The user would then able to retrieve the access token by getting the code, which is returned as a GET parameter.
        if (Request::get('success') && Request::get('success') == 'true') {
            $code = Request::get('code');
            try {
                // Obtain Authorization Code from Code, Client ID and Client Secret
                $accessToken = OpenIdTokeninfo::createFromAuthorizationCode(array('code' => $code), null, null, $apiContext);
                return $accessToken->toArray();
                // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
                //ResultPrinter::printResult("Obtained Access Token", "Access Token", $accessToken->getAccessToken(), $_GET['code'], $accessToken);

            } catch (PayPalConnectionException $ex) {
                // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
                //ResultPrinter::printError("Obtained Access Token", "Access Token", null, $_GET['code'], $ex);
                //exit(1);
                dump($ex);
            }


        }
    }


    public function actionInfo()
    {

        $clientId = 'AVAnHudP_Shyc3NkvjPxGePTza_r6JQT3VbOq8uZzFAocnCT4BiSJDJ-K2BuR3B8f7Glvp67vbnxDVyY';
        $clientSecret = 'EFYGGetGFUvTwjub4D3Klpa0c36fraewuPCHGBYiD0CgyE1Y7kZKibm-Es6d4jI_WI20fB15U7ZswqBK';

        $apiContext = $this->getApiContext($clientId, $clientSecret);

        $refreshToken = Request::get('refresh_token');

        try {
            $tokenInfo = new OpenIdTokeninfo();
            $tokenInfo = $tokenInfo->createFromRefreshToken(array('refresh_token' => $refreshToken), $apiContext);
            $params = array('access_token' => $tokenInfo->getAccessToken());
            $userInfo = OpenIdUserinfo::getUserinfo($params, $apiContext);
        } catch (Exception $ex) {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            dump($ex);
            exit(1);
        }

        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        //ResultPrinter::printResult("User Information", "User Info", $userInfo->getUserId(), $params, $userInfo);
        //dump($userInfo->getUserId()) ;
        return $userInfo->toArray();

    }



    public function actionInfo2()
    {

        $clientId = 'AVAnHudP_Shyc3NkvjPxGePTza_r6JQT3VbOq8uZzFAocnCT4BiSJDJ-K2BuR3B8f7Glvp67vbnxDVyY';
        $clientSecret = 'EFYGGetGFUvTwjub4D3Klpa0c36fraewuPCHGBYiD0CgyE1Y7kZKibm-Es6d4jI_WI20fB15U7ZswqBK';

        $apiContext = $this->getApiContext($clientId, $clientSecret);

        $accessToken = Request::get('access_token');

        try {
            $params = array('access_token' => $accessToken);
            $userInfo = OpenIdUserinfo::getUserinfo($params, $apiContext);
        } catch (Exception $ex) {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            dump($ex);
            exit(1);
        }

        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        //ResultPrinter::printResult("User Information", "User Info", $userInfo->getUserId(), $params, $userInfo);
        //dump($userInfo->getUserId()) ;
        return $userInfo->toArray();

    }
}