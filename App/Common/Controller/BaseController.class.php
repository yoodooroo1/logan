<?php
/**
 * Created by PhpStorm.
 * User: Ydr
 * Date: 2019/11/3
 * Time: 9:15
 */
namespace Common\Controller;
use Think\Controller;

class BaseController extends Controller\RestController
{

    //请求参数
    protected $req = NULL;

    public function __construct()
    {
        parent::__construct();
        header('Content-Type:text/html; charset=utf-8');
        $this->req = $this->getReqParam();
        logWrite("API-请求参数:" . json_encode($this->req, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 获取当前请求参数
     * @return array ['code'=>200, 'msg'=>'', 'data'=>null]
     */
    protected function getReqParam()
    {
        if (isset($this->req)) {
            return $this->req;
        }
        $get = I('get.');
        $post = I('post.');
        $req = array_merge($get, $post);
        $body = I('put.');
        return array_merge($req, $body);
    }

}