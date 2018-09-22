<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Gdata
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Zend_Gdata_HttpClient
 */
require_once 'Zend/Gdata/HttpClient.php';

/**
 * Zend_Version
 */
require_once 'Zend/Version.php';

/**
 * Class to facilitate Google's "Account Authentication
 * for Installed Applications" also known as "ClientLogin".
 * @see http://code.google.com/apis/accounts/AuthForInstalledApps.html
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Gdata
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_ClientLogin
{

    /**
     * The Google client login URI
     *
     */
    const CLIENTLOGIN_URI = 'https://www.google.com/accounts/ClientLogin';

    /**
     * The default 'source' parameter to send to Google
     *
     */
    const DEFAULT_SOURCE = 'Zend-ZendFramework';

    /**
     * Set Google authentication credentials.
     * Must be done before trying to do any Google Data operations that
     * require authentication.
     * For example, viewing private data, or posting or deleting entries.
     *
     * @param string $email
     * @param string $password
     * @param string $service
     * @param Zend_Gdata_HttpClient $client
     * @param string $source
     * @param string $loginToken The token identifier as provided by the server.
     * @param string $loginCaptcha The user's response to the CAPTCHA challenge.
     * @param string $accountType An optional string to identify whether the
     * account to be authenticated is a google or a hosted account. Defaults to
     * 'HOSTED_OR_GOOGLE'. See: http://code.google.com/apis/accounts/docs/AuthForInstalledApps.html#Request
     * @throws Zend_Gdata_App_AuthException
     * @throws Zend_Gdata_App_HttpException
     * @throws Zend_Gdata_App_CaptchaRequiredException
     * @return Zend_Gdata_HttpClient
     */
    public static function getHttpClient($account, $service = 'xapi',
        $client = null,
        $source = self::DEFAULT_SOURCE,
        $loginToken = null,
        $loginCaptcha = null,
        $loginUri = self::CLIENTLOGIN_URI,
        $accountType = 'HOSTED_OR_GOOGLE')
    {

        if ($client == null) {
            $client = new Zend_Gdata_HttpClient();
        }
        if (!$client instanceof Zend_Http_Client) {
            require_once 'Zend/Gdata/App/HttpException.php';
            throw new Zend_Gdata_App_HttpException(
                    'Client is not an instance of Zend_Http_Client.');
        }

        $client->setClientLoginToken($account);
        $useragent = $source . ' Zend_Framework_Gdata/' . Zend_Version::VERSION;
        $client->setConfig(array(
                'strictredirects' => true,
                'useragent' => $useragent
            )
        );
        return $client;
    }

}

