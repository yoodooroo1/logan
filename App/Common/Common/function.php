<?php
/**
 * Created by PhpStorm.
 * User: Ydr
 * Date: 2019/11/13
 * Time: 10:06
 */

/**
 * 日志记录
 * @param string $message
 * @param string $level
 * @param string $method
 */
function logWrite($message = '', $level = 'INFO', $method = 'record')
{
    \Think\Log::$method($message, $level);
}

/**
 * 错误记录
 * @param string $message
 * @param string $method
 */
function errorLogs($message = '',$method = 'write')
{
    \Think\Log::$method($message."\n", \Think\Log::INFO, "",LOG_PATH.'Error/'.date('y_m_d').'.log');
}

/**
 * 接收二进制流
 * @return string $ret
 *
 **/
 function receiveStream()
 {

    $streamData = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';

    if (empty($streamData)) {
        $streamData = file_get_contents('php://input');
    }

    if ($streamData != '') {
        $ret =  $streamData;
    } else {
        $ret = false;
    }

    return $ret;
}

/**
 * 接收二进制流文件并生成文件
 * @param string $receiveFile 文件目录
 * @return string $ret
 *
 **/
 function receiveStreamToFile($receiveFile = '')
 {
    $streamData = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
    if(empty($streamData)){
        $streamData = file_get_contents('php://input');
    }
    if($streamData != ''){
        $ret = file_put_contents($receiveFile, $streamData, true);
    }else{
        $ret = false;
    }
    return $ret;
}

/**
 * 将string转化为字节数组
 * @param string $str
 * @return array
 **/
 function str2Bytes($str = '')
 {
    $len = strlen($str);
    $bytes = array();
    for($i=0;$i<$len;$i++) {
        if(ord($str[$i]) >= 128){
            $byte = ord($str[$i]) - 256;
        }else{
            $byte = ord($str[$i]);
        }
        $bytes[] = $byte;
        flush();
    }
    return $bytes;
}

/**
 * 将字节数组转化为string类型的数据
 * @param array $bytes
 * @return string $str
 */
function bytes2Str($bytes = array())
{
    $str = '';
    foreach($bytes as $ch) {
        $str .= chr($ch);
        flush();
    }

    return $str;
}

/**
 * Gzip解压
 * @param string $data
 * @return string $unpacked
 */

 function decode_Gzip ($data)
 {
    $flags = ord(substr($data, 3, 1));
    $headerLen = 10;
    if ($flags & 4) {
        $extraLen = unpack('v' ,substr($data, 10, 2));
        $extraLen = $extraLen[1];
        $headerLen += 2 + $extraLen;
    }
    if ($flags & 8) // Filename
        $headerLen = strpos($data, chr(0), $headerLen) + 1;
    if ($flags & 16) // Comment
        $headerLen = strpos($data, chr(0), $headerLen) + 1;
    if ($flags & 2) // CRC at end of file
        $headerLen += 2;
    $unpacked = @gzinflate(substr($data, $headerLen));
    if ($unpacked === FALSE)
        $unpacked = $data;
    return $unpacked;
}