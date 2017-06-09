<?php
/**
 * 应用入口
 */

//应用命名空间（请与应用所在目录名保持一致）
define('APP_NAMESPACE', 'App');

//应用路径
define('APP_PATH',      dirname(dirname(__FILE__)));

//是否显示错误信息
define('SHOW_ERROR',    true);

//是否启用布局功能
define('LAYOUT_ON',     true);

//引入 MiniFramework 就是这么简单
require '../../MiniFramework/Mini.php';