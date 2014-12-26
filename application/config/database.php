<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'DBM';
$active_record = FALSE;

if(ENVIRONMENT == 'production') {
	// master
	$db['DBM']['hostname'] = 'localhost';	
	$db['DBM']['port'] = '3306';
	$db['DBM']['username'] = 'van';
	$db['DBM']['password'] = 'ejswus82';
	$db['DBM']['database'] = '';
	$db['DBM']['dbdriver'] = 'mysql';
	$db['DBM']['dbprefix'] = '';
	$db['DBM']['pconnect'] = TRUE;
	$db['DBM']['db_debug'] = TRUE;
	$db['DBM']['cache_on'] = FALSE;
	$db['DBM']['cachedir'] = '';
	$db['DBM']['char_set'] = 'utf8mb4';
	$db['DBM']['dbcollat'] = 'utf8mb4_unicode_ci';
	$db['DBM']['swap_pre'] = '';
	$db['DBM']['autoinit'] = TRUE;
	$db['DBM']['stricton'] = FALSE;

	// slave
	$db['DBS']['hostname'] = 'localhost';
	$db['DBS']['port'] = '3306';
	$db['DBS']['username'] = 'van';
	$db['DBS']['password'] = 'ejswus82';
	$db['DBS']['database'] = '';
	$db['DBS']['dbdriver'] = 'mysql';
	$db['DBS']['dbprefix'] = '';
	$db['DBS']['pconnect'] = TRUE;
	$db['DBS']['db_debug'] = TRUE;
	$db['DBS']['cache_on'] = FALSE;
	$db['DBS']['cachedir'] = '';
	$db['DBS']['char_set'] = 'utf8mb4';
	$db['DBS']['dbcollat'] = 'utf8mb4_unicode_ci';
	$db['DBS']['swap_pre'] = '';
	$db['DBS']['autoinit'] = TRUE;
	$db['DBS']['stricton'] = FALSE;
} else {
	// master
	$db['DBM']['hostname'] = 'localhost';	
	$db['DBM']['port'] = '3306';
	$db['DBM']['username'] = 'van';
	$db['DBM']['password'] = 'ejswus82';
	$db['DBM']['database'] = '';
	$db['DBM']['dbdriver'] = 'mysql';
	$db['DBM']['dbprefix'] = '';
	$db['DBM']['pconnect'] = TRUE;
	$db['DBM']['db_debug'] = TRUE;
	$db['DBM']['cache_on'] = FALSE;
	$db['DBM']['cachedir'] = '';
	$db['DBM']['char_set'] = 'utf8mb4';
	$db['DBM']['dbcollat'] = 'utf8mb4_unicode_ci';
	$db['DBM']['swap_pre'] = '';
	$db['DBM']['autoinit'] = TRUE;
	$db['DBM']['stricton'] = FALSE;

	// slave
	$db['DBS']['hostname'] = 'localhost';
	$db['DBS']['port'] = '3306';
	$db['DBS']['username'] = 'van';
	$db['DBS']['password'] = 'ejswus82';
	$db['DBS']['database'] = '';
	$db['DBS']['dbdriver'] = 'mysql';
	$db['DBS']['dbprefix'] = '';
	$db['DBS']['pconnect'] = TRUE;
	$db['DBS']['db_debug'] = TRUE;
	$db['DBS']['cache_on'] = FALSE;
	$db['DBS']['cachedir'] = '';
	$db['DBS']['char_set'] = 'utf8mb4';
	$db['DBS']['dbcollat'] = 'utf8mb4_unicode_ci';
	$db['DBS']['swap_pre'] = '';
	$db['DBS']['autoinit'] = TRUE;
	$db['DBS']['stricton'] = FALSE;
}

/* End of file database.php */
/* Location: ./application/config/database.php */