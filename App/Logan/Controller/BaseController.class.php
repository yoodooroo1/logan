<?php
/**
 * Created by PhpStorm.
 * User: Ydr
 * Date: 2019/11/3
 * Time: 9:07
 */
namespace Logan\Controller;

use MongoDB\Driver\Server;

class BaseController extends \Common\Controller\BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 接收用户
     * @param string $appName
     * @param string $id
     * @return bool
     */
    protected function receiveUser($appName = '',$id = '')
    {
        if(!empty($appName) && !empty($id)){
            $arr = F('receivedUserArr');
            if(empty($arr)){
                $arr = [];
            }
            $value = (string)$appName.$id;
            if(!in_array($value,$arr)){
                $key = (string)$appName.$id;
                $arr[$key] = $value;
                F('receivedUserArr',$arr);
            }
        }
        return true;
    }

    /**
     *删除接收用户
     * @param string $appName
     * @param string $id
     * @return bool
     */
    protected function delReceiveUser($appName = '',$id = '')
    {
        if(!empty($appName) && !empty($id)){
            $arr = F('receivedUserArr');
            if(empty($arr)){
                $arr = [];
            }
            $key = (string)$appName.$id;
            if(!empty($arr[$key])){
                unset($arr[$key]);
                F('receivedUserArr',$arr);
            }
        }
        return true;
    }

    /**
     * 接收用户列表
     * @return array|mixed
     */
    protected function getReceiveUsers()
    {
        $arr = F('receivedUserArr');
        if(empty($arr)){
            $arr = array();
        }
        return $arr;
    }

    /**
     * 生成日志目录并获取文件路径 (APP为一级目录,id为二级目录,日期为文件名)
     * @param string $appName
     * @param string $id
     * @param string $date
     * @return string
     */
    protected function deriveFilePath($appName = '',$id = '',$date = '')
    {
        if(empty($appName)){
            $appName = 'none';
            errorLogs('APP名为空');
        }
        $path = 'Logs/'.$appName.'/';
        if(!empty($id)){
            $logPath = $path.'/'.$id.'/';
        }else{
            $logPath = $path.'/none/';
            errorLogs('ID为空');
        }
        if(empty($date)) $date = date('Y-m-d',$_SERVER["REQUEST_TIME"]);
        $outputPath =  $logPath.'/'.$date.'-'.$_SERVER["REQUEST_TIME"].'r';
        if(!is_dir($path)) mkdir($path);
        if(!is_dir($logPath)) mkdir($logPath);
        return $outputPath;
    }
}