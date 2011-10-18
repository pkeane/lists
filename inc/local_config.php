<?php

$conf['db']['type'] = 'mysql';
$conf['db']['host'] = 'localhost';
$conf['db']['name'] = 'lists';
$conf['db']['user'] = 'lists_user';
$conf['db']['pass'] = 'lists_user';
$conf['db']['table_prefix'] = '';

$conf['app']['main_title'] = 'Lists';
$conf['app']['log_level'] = 3;
$conf['app']['init_global_data'] = false;
$conf['app']['user_class'] = 'Dase_User';

$conf['auth']['superuser']['pkeane'] = 'lists';
$conf['auth']['token'] = 'auth';
$conf['auth']['ppd_token'] = "ppd";
$conf['auth']['service_token'] = "service";
$conf['auth']['serviceuser']['test'] = 'ok';

