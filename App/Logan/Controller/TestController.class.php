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

    public function decodeLogs(){
        $logan = new LoganParser(AES_KEY,AES_VI);
        $logan->parse(1,2);
    }
}