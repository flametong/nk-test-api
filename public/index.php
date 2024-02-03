<?php

require_once dirname(__DIR__) . '/config/init.php';
require_once ROOT . '/vendor/autoload.php';

use App\Storage\Db;
use Dotenv\Dotenv;

Db::getInstance();

(Dotenv::createImmutable(dirname(__DIR__)))->load();

require_once ROOT . '/config/init_sms_service.php';
require_once ROOT . '/routes/web.php';
