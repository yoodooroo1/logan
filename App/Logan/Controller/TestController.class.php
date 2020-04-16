<?php
/**
 * Created by PhpStorm.
 * User: Ydr
 * Date: 2020/3/11
 * Time: 11:19
 */

namespace Logan\Controller;


use Common\Util\LoganParser;

class TestController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function test(){
       $user = ['6288593','6288594','6288598','6288573','6288992','6289244','6289812'];
       foreach ($user as $v){
           $this->receiveUser('cashier_overseas_android',$v);
       }
       echo 1;
    }
}