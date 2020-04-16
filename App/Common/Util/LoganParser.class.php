<?php
/**
 * Created by PhpStorm.
 * User: Ydr
 * Date: 2019/11/12
 * Time: 19:34
 */

namespace Common\Util;

class LoganParser
{
    private $cipher = ''; //解密类型
    private $mode = ''; //解密模式
    private $key = ''; //AES key
    private $vi = ''; //AES vi

    public function __construct($key = '',$vi = ''){
        $this->cipher = MCRYPT_RIJNDAEL_128;
        $this->mode = MCRYPT_MODE_CBC;
        $this->key = $key;
        $this->vi = $vi;
    }


    /**
     * 读取二进制流文件，并生成文件
     * @param  string $is 文件目录
     * @param  string $os 文件目录
    **/
    public function parse($is = '',$os = '')
    {
        if(file_exists($is)){
            $fp = fopen($is,"rb");
            $buffer = 1024;
            $str = "";
            while(!feof($fp)){
                $str .= fread($fp,$buffer);
            }
            fclose($fp);
            $this->parseStream($str,$os);
        }
    }

    /**
     * 输入二进制流,解密生成文件
     * @param  string $is 输入流
     * @param  string $os 输出的文件
    **/
    public function parseStream($is,$os)
    {
        set_time_limit(0);
        $strLength = strlen($is);
        $start = 0;
        $limit = 1024;
        $bytes = [];
        for ($i=1; $i<=(int)($strLength/$limit); $i++){
            $bytes += str2Bytes(substr($is,$start,$limit));
            $limit += 1024;
        }
//        $bytes = str2Bytes($is);
        $content = $bytes;
        $contentLength = count($content);
        for($i = 0; $i < $contentLength; $i++){
            $start = $content[$i];
            //定义写入Mode
            $flag = FILE_APPEND;
            if($i==0){
                $flag = 0;
            }
            if($start == 1){
                $i++;
                $length = ($content[$i] & 0xFF) << 24 |
                    ($content[$i + 1 ] & 0xFF) << 16 |
                    ($content[$i + 2] & 0xFF) << 8 |
                    ($content[$i + 3] & 0xFF);
                $i += 3;
                if($length > 0){
                    $temp = $i + $length + 1;
                    if($contentLength - $i -1 == $length){ //异常
                        $type = 0;
                    }else if($contentLength - $i -1 > $length && 1 != $content[$temp]){
                        $type = 1;
                    }else if($contentLength - $i -1 > $length && 1 == $content[$temp]){ //异常
                        $type = 2;
                    }else{
                        $i -= 4;
                        continue;
                    }
                    $str = bytes2Str(array_slice($content,$i + 1,$length));
                    $decrypted = mcrypt_decrypt($this->cipher,$this->key,$str,$this->mode ,$this->vi);
                    $logsData = decode_Gzip($decrypted);
                    //写入文件
                    file_put_contents($os,$logsData,$flag);
                    $i += $length;
                    if($type == 1){
                        $i++;
                    }
                }
            }
        }
    }
}