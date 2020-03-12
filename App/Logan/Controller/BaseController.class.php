<?php
/**
 * Created by PhpStorm.
 * User: Ydr
 * Date: 2019/11/3
 * Time: 9:07
 */
namespace Logan\Controller;

class BaseController extends \Common\Controller\BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 已接收日志记录
     * @param string $appName
     * @param string $id
     * @param string $date
     * @return bool
     */
    protected function addReceivedRecord($appName = '',$id = '',$date = ''){
        if(empty($date)){
            $date = date('Y-m-d',time());
        }
        if(!empty($appName) && !empty($id)){
            $arr = F('receivedArr'.$appName.$id);
            if(empty($arr)){
                $arr = [];
            }
            if(!in_array($date,$arr)){
                $arr[] = $date;
                F('receivedArr'.$appName.$id,$arr);
            }
        }
        return true;
    }

    /**
     * 获取已接收日志记录
     * @param string $appName
     * @param string $id
     * @return array|mixed
     */
    protected function getReceiveRecord($appName = '',$id = ''){
        $arr = F('receivedArr'.$appName.$id);
        if(empty($arr)){
            $arr = array();
        }
        return $arr;
    }
}