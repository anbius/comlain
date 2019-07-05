<?php

namespace think;

// 加载基础文件
require __DIR__ . '/thinkphp/base.php';

require __DIR__ . '/constant.php';

// 执行应用并响应
Container::get('app', [__DIR__ . '/application/'])->run()->send();