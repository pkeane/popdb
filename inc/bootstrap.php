<?php

ini_set('include_path',BASE_PATH.'/lib:'.BASE_PATH.'/models');

function popAutoload($class_name) {
	$class_name = str_replace('_','/',$class_name).'.php';
	@include ($class_name);
}

spl_autoload_register('popAutoload');

define('SQLITE_PATH',BASE_PATH.'/data/db.sqlite');
define('HANDLER_PATH',BASE_PATH.'/handlers');
define('TEMPLATE_PATH',BASE_PATH.'/templates');
define('TEMPLATE_COMPILE_PATH',BASE_PATH.'/templates_c');
define('DEFAULT_HANDLER','sample');
define('MAIN_TITLE','my app');
