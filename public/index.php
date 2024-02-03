<?php

use App\Storage\Db;

require_once dirname(__DIR__) . '/config/init.php';
require_once ROOT . '/vendor/autoload.php';

Db::getInstance();

require_once ROOT . '/routes/web.php';
