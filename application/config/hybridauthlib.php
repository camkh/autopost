<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

$config =
	array(
		// set on "base_url" the relative url that point to HybridAuth Endpoint
		'base_url' => '/hauth/endpoint',

		"providers" => array (
			// openid providers
			"OpenID" => array (
				"enabled" => true
			),

			"Yahoo" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "dj0yJmk9dVg4VEd0QVVEWlRPJmQ9WVdrOU9FcG1NM1pVTkdrbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD1jMg--", "secret" => "2e7be2340936ea23899aea6cb461ab98b537a0f2" ),
			),

			"AOL"  => array (
				"enabled" => true
			),

			"Google" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "430296831941-pdaovacbng05iod639nleekbf5e5oj12.apps.googleusercontent.com", "secret" => "i8Y16D7NmP1Au54lkdnbyfFe" ),
                "scope"           => "https://www.googleapis.com/auth/userinfo.profile ". // optional
                               "https://www.googleapis.com/auth/userinfo.email ".// optional
                               "https://www.googleapis.com/auth/plus.login ".// optional
                               "https://picasaweb.google.com/data/ "  , // optional
                "access_type"     => "offline",   // optional
                "approval_prompt" => "force",     // optional
			),

			"Facebook" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "233050993510851", "secret" => "cb5fa7b926c85cc7606be76b9b4f1189", 'cookie' => true),
                "scope"   => "email, user_about_me, user_birthday, user_hometown, public_profile, publish_actions, user_groups, manage_pages", // optional
			),

			"Twitter" => array (
				"enabled" => true,
				"keys"    => array ( "key" => "C0J4QIoA3qKMqBDocEMSeHhs5", "secret" => "4oRYTCqABK7Mi3pHJUZzUn9CRJwoAVXlrzX8VrhnAWoXTtnKVn" )
			),

			// windows live
			"Live" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" )
			),

			"MySpace" => array (
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" )
			),

			"LinkedIn" => array (
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" )
			),

			"Foursquare" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" )
			),
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => (ENVIRONMENT == 'development'),

		"debug_file" => APPPATH.'/logs/hybridauth.log',
	);


/* End of file hybridauthlib.php */
/* Location: ./application/config/hybridauthlib.php */