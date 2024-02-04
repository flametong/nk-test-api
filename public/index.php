<?php

require_once dirname(__DIR__) . '/config/init.php';
require_once ROOT . '/vendor/autoload.php';

$config = require_once ROOT . '/config/init_db.php';

use Dotenv\Dotenv;

(Dotenv::createImmutable(dirname(__DIR__)))->load();

require_once ROOT . '/config/init_sms_service.php';
require_once ROOT . '/routes/web.php';
