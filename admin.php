<?php

namespace think;

defined('IS_ADMIN') or define('IS_ADMIN',1);

require __DIR__ . '/thinkphp/base.php';

require __DIR__ . '/constant.php';

Container::get('app', [__DIR__ . '/application/'])->run()->send();