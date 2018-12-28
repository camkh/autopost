<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

//define('BITLY_USERNAME', 'camkh');
//define('BITLY_API_KEY', 'R_3a48ff6669fc49e287d13b41ec4d613f');
//define('BITLY_USERNAME', 'o_3eg0tkjvo6');
//define('BITLY_API_KEY', 'R_384e1933dab34cb2bf1b88c52a0b40fb');
//define('BITLY_USERNAME', 'o_19ln460ta4');
//define('BITLY_API_KEY', 'R_d25cf36a6098484cbdec62de87af48e9');
//define('BITLY_USERNAME', 'rythoenn999');
//define('BITLY_API_KEY', 'R_67b377aae1c041b991ba87236c423441');
define('BITLY_USERNAME', 'camkh3');
define('BITLY_API_KEY', 'R_f9773ee2731343d98458d6d98a7267e8');



define('TIME_ZONE', date_default_timezone_set('Asia/Phnom_Penh'));
/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */