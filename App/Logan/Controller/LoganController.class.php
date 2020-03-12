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

    public function __construct()
    {
        parent::__construct();
        $this->appName = $this->req['app'];
        $this->id = $this->req['id'];
        $this->date = $this->req['date'];
        $this->outputPath = deriveFilePath($this->appName,$this->id,$this->date);
    }

    /**
     * 接收日志
     * @return bool
     */
    public function receiveLogs(){
        $inputStream = receiveStream();
        if($inputStream){
            file_put_contents($this->outputPath,$inputStream);
            $this->addReceivedRecord($this->appName,$this->id,$this->date);
            return true;
        }else{
            errorLogs('输入内容为空');
            return false;
        }
    }

    /**
     * 判断日志是否接收
     */
    public function isReceivedLogs(){
        echo 1;
        die();
        $query = $this->getReceiveRecord($this->appName,$this->id);
        if(!empty($query)){
            if(empty($this->date)){
                $this->date = date('Y-m-d',time());
            }
            if(in_array($this->date,$query)){
                echo 1;
                die();
            }
        }
        echo 0;
        die();
    }

    /**
     * 接收解密日志
     * @return bool
     */
    public function decodeLogs(){
        $inputStream = receiveStream();
        if($inputStream){
            $logan = new LoganParser(AES_KEY,AES_VI);
            $logan->parse($inputStream,$this->outputPath);
            $this->addReceivedRecord($this->appName,$this->id,$this->date);
            return true;
        }else{
            errorLogs('输入内容为空');
            return false;
        }
    }
}