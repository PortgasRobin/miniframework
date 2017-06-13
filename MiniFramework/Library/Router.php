<?php
// +------------------------------------------------------------
// | Mini Framework
// +------------------------------------------------------------
// | Source: https://github.com/jasonweicn/MiniFramework
// +------------------------------------------------------------
// | Author: Jason.wei <jasonwei06@hotmail.com>
// +------------------------------------------------------------

namespace Mini;

class Router
{    
    /**
     * Request实例
     * 
     * @var Request
     */
    private $_request;
    
    /**
     * 控制器
     * 
     * @var string
     */
    public $_controller;
    
    /**
     * 动作
     * 
     * @var string
     */
    public $_action;
    
    /**
     * 路由方式
     * 
     * @var string
     */
    protected $_routeType;
    
    /**
     * 路径数组
     * 
     * @var string
     */
    protected $_uriArray;
    
    /**
     * 命令行参数数组
     * 
     * @var array
     */
    protected $_cliParamsArray = array();
    
    
    
    /**
     * 构造
     * 
     */
    public function __construct()
    {
        $this->_request = Request::getInstance();
        
        if (true === $this->isCli()) {
            
            //CLI (/index.php Controller/Action param1=value1 param2=value2 ...)
            
            $this->_routeType = 'cli';
            
            if ($_SERVER['argc'] > 1) {
                
                if (preg_match("/^([a-zA-Z][a-zA-Z0-9]*)\/([a-zA-Z][a-zA-Z0-9]*)$/", $_SERVER['argv'][1], $m)) {
                    $controller = isset($m[1]) ? $m[1] : 'index';
                    $action = isset($m[2]) ? $m[2]: 'index';
                } else {
                    throw new Exceptions('Request params invalid.');
                }
                
            } else {
                $controller = $action = 'index';
            }
            
        } elseif (false === strpos($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])) {
            
            //Rewrite (/Controller/Action/param1/value1/param2/value2)
            
            $this->_routeType = 'rewrite';
            $this->_uriArray = $this->parseUrlToArray();
            
            $controller = (isset($this->_uriArray[1]) && !empty($this->_uriArray[1])) ? $this->_uriArray[1] : 'index';
            $action = (isset($this->_uriArray[2]) && !empty($this->_uriArray[2])) ? $this->_uriArray[2] : 'index';
        
        } else {
            
            //GET (/index.php?c=index&a=index)
            
            $this->_routeType = 'get';
            if (empty($_SERVER['QUERY_STRING'])) {
                $controller = $action = 'index';
            } else {
                $queryStringArray = $this->_request->getQueryStringArray();
                $controller = isset($queryStringArray['c']) ? $queryStringArray['c'] : 'index';
                $action = isset($queryStringArray['a']) ? $queryStringArray['a'] : 'index';
            }
            
        }
        
        if ($this->checkRoute($controller)) {
            $this->_request->setControllerName($controller);
        } else {
            throw new Exceptions('Controller name invalid.', 404);
        }
        
        if ($this->checkRoute($action)) {
            $this->_request->setActionName(strtolower($action));
        } else {
            throw new Exceptions('Action name invalid.', 404);
        }
    }
    
    /**
     * 存入路由方式
     */
    public function setRouteType($type)
    {
        $this->_routeType = $type;
    }
    
    /**
     * 读取路由方式
     * 
     * @return string
     */
    public function getRouteType()
    {
        return $this->_routeType;
    }
    
    /**
     * 解析Url为数组
     * 
     * @return array
     */
    private function parseUrlToArray()
    {
        $requestUri = '';
        
        if (empty($_SERVER['QUERY_STRING'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
        } else {
            $requestUri = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
        }
        
        $baseUrl = $this->_request->getBaseUrl();
        
        if ($requestUri != $baseUrl) {
            $requestUri = str_replace($baseUrl, '', $requestUri);
        }
        $uriArray = explode('/', $requestUri);
        
        return $uriArray;
    }
    
    /**
     * 检查路由参数合法性
     * 
     * @param mixed $value
     * @return int
     */
    protected function checkRoute($value)
    {
        return preg_match ("/^[a-zA-Z][a-zA-Z0-9]*$/", $value);
    }
    
    /**
     * 判断PHP是否处于CLI模式下运行
     * 
     * @return bool
     */
    public function isCli()
    {
        return preg_match("/cli/i", PHP_SAPI) ? true : false;
    }
}