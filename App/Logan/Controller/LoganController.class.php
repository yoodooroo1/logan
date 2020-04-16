<?php
/**
 * Created by PhpStorm.
 * User: Ydr
 * Date: 2019/11/13
 * Time: 9:51
 */
namespace Logan\Controller;
use Common\Util\LoganParser;

class LoganController extends BaseController
{
    private $id = '';
    private $appName = '';
    private $date = '';
    private $outputPath = '';

    public function __construct(){
        parent::__construct();
        $this->appName = $this->req['app'];
        $this->id = $this->req['id'];
        $this->date = $this->req['date'];
        if(ACTION_NAME == 'receiveLogs' || ACTION_NAME == 'checkReceive'){
            $this->outputPath = $this->deriveFilePath($this->appName,$this->id,$this->date);
        }
    }

    /**
     * 接收日志
     */
    public function receiveLogs()
    {
        $inputStream = receiveStream();
        if($inputStream){
            file_put_contents($this->outputPath,$inputStream);
            $this->delReceiveUser($this->appName,$this->id);
            echo 'success';
        }else{
            errorLogs($this->appName.','.$this->id. ', 输入内容为空');
            echo 'fail';
        }
    }

    /**
     * 接收日志
     */
    public function checkReceive()
    {
        if(empty($this->appName) || empty($this->id)){
            echo 0;
            exit();
        }
        $users = $this->getReceiveUsers();
        if(!empty($users)){
            $receiveUser = (string)$this->appName.$this->id;
            if(in_array($receiveUser,$users)){
               echo 1;
                exit();
            }
        }
        echo 0;
        exit();
    }

    /**
     * 接收解密日志
     * @return bool
     */
    public function decodeLogs()
    {
        $inputStream = receiveStream();
        if($inputStream){
            $logan = new LoganParser(AES_KEY,AES_VI);
            $logan->parse($inputStream,$this->outputPath);
            return true;
        }else{
            errorLogs($this->appName.','.$this->id. ', 输入内容为空');
            return false;
        }
    }

    /**
     * 新增接收用户
     */
    public function receivingUser()
    {
        if(IS_POST){
            if($this->appName && $this->id){
                $this->receiveUser($this->appName,$this->id);
                echo 'success';
                die();
            }
        }else{
         $this->display();
        }
    }
}