<?php
define('BASEDIR', __DIR__);
define('CLASSDIR', BASEDIR.'/class');

define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB', '');

spl_autoload_register(function ($class)
{
	if (file_exists(CLASSDIR."/$class.php"))
		include_once(CLASSDIR."/$class.php");
});