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

//로그인 관련 쿠키
define('LOGIN_COOKIE_KEY',    '5762344a7fa7fd5fd20fda5329d9401141682370');//sha1('vanetssasite!@');
define('LOGIN_COOKIE_NAME',   'van');
define('LOGIN_COOKIE_EXPIRE', 0);
define('LOGIN_COOKIE_DOMAIN', 'vanetssa.com');

if(ENVIRONMENT == 'production'){
	define('DOMAIN_URL', 'vanetssa.com');
}else{
	define('DOMAIN_URL', 'devphp.vanetssa.com');
}

//회원관련 코드값
define('USER_STATUS_NORMAL','AA');
define('USER_STATUS_BLOCK','BA');
define('USER_STATUS_DELETE','CA');

define('SNS_TYPE_FACEBOOK','FB');
define('SNS_TYPE_NAVER','NV');
define('SNS_TYPE_GOOGLE','GG');

/* End of file constants.php */
/* Location: ./application/config/constants.php */