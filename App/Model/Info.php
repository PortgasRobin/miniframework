<?php
/**
 * 这是一个模型的案例
 * 由于 MiniFramework 全面启动了命名空间，在创建模型时，在文件顶部需要放置 namespace App\Model; 进行声明。
 */

namespace App\Model;

use Mini\Model;
//use Mini\Db;
//use Mini\Config;

class Info extends Model
{
    public function getInfo()
    {
        //如果你开启了数据库自动连接功能，就可以用下面这行代码自动加载数据库对象了
        //$db = $this->loadDb('default');
        
        //这是手工连接数据库的方法，需要解开顶部 use Mini\Db; 和 use Mini\Config; 两行代码的注释
        //$dbParams = Config::getInstance()->load('database:default');
        //$db = Db::factory('Mysql', $dbParams);
        
        return "Hello World!";
    }
}
