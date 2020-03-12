<?php
/**
 * Created by PhpStorm.
 * User: Ydr
 * Date: 2019/11/5
 * Time: 10:44
 */
// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',FALSE);

// 定义应用目录
define('BIND_MODULE', 'Logan');
define('APP_PATH','./App/');
defined('BIND_CONTROLLER') or define('BIND_CONTROLLER', 'Logan');
defined('BIND_ACTION') or define('BIND_ACTION', 'decodeLogs');

// 引入ThinkPHP入口文件
require './Core/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单