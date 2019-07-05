<?php

namespace think;

defined('IS_ADMIN') or define('IS_ADMIN',2);

require __DIR__ . '/thinkphp/base.php';

require __DIR__ . '/constant.php';

//Container::get('app', [__DIR__ . '/application/'])->run()->send();
Container::get('app')->bind('interface100')->run()->send();